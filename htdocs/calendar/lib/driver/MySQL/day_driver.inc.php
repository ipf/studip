<?

function day_save (&$events_save, &$events_delete) {
	$db = new DB_Seminar();
	if (sizeof($this->events)) {
		$query = "REPLACE calendar_events (event_id,range_id,autor_id,uid,summary,description,"
		        . "start,end,class,categories,priority,location,ts,linterval,sinterval,wdays,"
						. "month,day,rtype,duration,expire,exceptions,mkdate,chdate) VALUES";
		
		$sep = FALSE;
		
		$chdate = time();
		if ($event->getMakeDate() == -1)
			$mkdate = $chdate;
		else
			$mkdate = $event->getMakeDate();
		
		foreach ($events_save as $event) {
			$properties = $event->getProperty();
			if ($sep1)
				$values .= ",";
			$values .= sprintf("('%s','%s','%s','%s','%s','%s',%s,%s,'%s','%s',%s,'%s',%s,%s,%s,
					'%s',%s,%s,'%s',%s,%s,'%s',%s,%s)",
					$event->getId(), $event->getUserId(), $event->getUserId(),
					$properties['UID'],
					$properties['SUMMARY'],
					$properties['DESCRIPTION'],
					$properties['DTSTART'],
					$properties['DTEND'],
					$properties['CLASS'],
					$properties['CATEGORIES'],
					$properties['PRIORITY'],
					$properties['LOCATION'],
					$properties['RRULE']['ts'],
					$properties['RRULE']['linterval'],
					$properties['RRULE']['sinterval'],
					$properties['RRULE']['wdays'],
					$properties['RRULE']['month'],
					$properties['RRULE']['day'],
					$properties['RRULE']['rtype'],
					$properties['RRULE']['duration'],
					$properties['RRULE']['expire'],
					$properties['EXCEPTIONS'],
					$mkdate, $chdate);
			$sep = TRUE;
		}
		
		if ($values) {
			$query .= $values;
			$db->query($query);
		}
		
	}
	if (sizeof($events_delete)) {
		$query = "DELETE FROM calendar_events WHERE autor_id = '{$user->id}' AND event_id IN (";
		$sep = FALSE;
		foreach ($events_delete as $event) {
			if ($sep)
				$values .= ",";
			$values .= "'" . $event->getId() . "'";
		}
		$query .= $values . ")";
		$db->query($query);
	}
}

function day_restore (&$this) {
	
	$db = new DB_Seminar;
	// die Abfrage grenzt das Trefferset weitgehend ein
/*	$query = sprintf("SELECT termin_id,content,date,end_time,date_typ,expire,repeat,color,priority,raum"
	       . " FROM termine WHERE range_id='%s' AND autor_id='%s' AND ((date BETWEEN %s AND %s OR "
				 . "end_time BETWEEN %s AND %s) OR (%s BETWEEN date AND end_time) OR (date <= %s AND expire > %s AND"
				 . " repeat REGEXP '(.+,,,.*%s.*,,,DAYLY)|(.+,.+,,,,,DAYLY)|"
				 . "(.+,.+,,.*%s.*,,,WEEKLY)|(.+,.+,,,,%s,MONTHLY)|"
				 . "(.+,.+,.+,%s,,,MONTHLY)|(.+,1,,,%s,%s,YEARLY)|"
				 . "(.+,1,.+,%s,%s,,YEARLY)|(^.*,[^#]+$)'))"
				 . " ORDER BY date ASC"
				 , $this->user_id, $this->user_id, $this->getStart(), $this->getEnd(), $this->getStart()
				 , $this->getEnd(), $this->getStart(), $this->getEnd(), $this->getStart(), $this->dow, $this->dow
				 , $this->dom, $this->dow, $this->mon, $this->dom, $this->dow, $this->mon);
*/	
	$query = sprintf("SELECT * FROM calendar_events WHERE range_id='%s' AND((start BETWEEN %s AND %s "
					. "OR end BETWEEN %s AND %s) OR (%s BETWEEN start AND end) OR (start <= %s AND expire > %s "
					. "AND (rtype = 'DAILY' OR (rtype = 'WEEKLY' AND wdays LIKE '%%%s%%') OR (rtype = 'MONTHLY' "
					. "AND (wdays LIKE '%%%s%%' OR day = %s)) OR (rtype = 'YEARLY' AND (month = %s AND (day = %s "
					. "OR wdays LIKE '%%%s%%'))) OR duration > 1)))",
					$this->getUserId(), $this->getStart(), $this->getEnd(), $this->getStart(), $this->getEnd(),
					$this->getStart(), $this->getEnd(), $this->getStart(), $this->dow, $this->dow, $this->dom,
					$this->mon, $this->dom, $this->dow);
	
	$db->query($query);
	
	while ($db->next_record()) {
		$rep = array(
				"ts"        => $db->f("ts"),
				"linterval" => $db->f("linterval"),
				"sinterval" => $db->f("sinterval"),
				"wdays"     => $db->f("wdays"),
				"month"     => $db->f("month"),
				"day"       => $db->f("day"),
				"rtype"     => $db->f("rtype"),
				"duration"  => $db->f("duration"));
		
		// der "Ursprungstermin"
		if ($db->f("start") >= $this->getStart() && $db->f("end") <= $this->getEnd()) {
			createEvent($this, $db, 0);
		}
		elseif ($db->f("start") >= $this->getStart() && $db->f("start") <= $this->getEnd()) {
			createEvent($this, $db, 1);
		}
		elseif ($db->f("start") < $this->getStart() && $db->f("end") > $this->getEnd()) {
			createEvent($this, $db, 2);
		}
		elseif ($db->f("end") >= $this->getStart() && $db->f("end") <= $this->getEnd()) {
			createEvent($this, $db, 3);
		}
		else {
			
			switch ($rep["rtype"]) {
				case "DAILY":
					
		/*			// t�glich wiederholte Termine sind eh drin
					if($rep["linterval"] == 1){
						createEvent($this, $db, 0);
						break;
					}*/
					
					$pos = (($this->ts - $rep["ts"]) / 86400) % $rep["linterval"];
					
					if ($pos == 0) {
						if ($rep["duration"] > 1)
							createEvent($this, $db, 1);
						else
							createEvent($this, $db, 0);
						break;
					}
					
					if ($pos < $rep["duration"]) {
						if (($pos == $rep["duration"] - 1) || ($rep["duration"] - $rep["linterval"] - 1 == $pos))
							createEvent($this, $db, 3);
						else
							createEvent($this, $db, 2);
					}
					break;
					
				case "WEEKLY":
					if ($rep["duration"] == 1) {
						// berechne den Montag in dieser Woche...
						$adate = $this->ts - ($this->dow - 1) * 86400;
						if(ceil(($adate - $rep["ts"]) / 604800) % $rep["linterval"] == 0){
							createEvent($this, $db, 0);
							break;
						}
					}
					else {
						$adate = $this->ts - ($this->dow - 1) * 86400;
						if ($adate + 1 > $rep["ts"] - ($this->dow - 1) * 86400) {
							for ($i = 0;$i < strlen($rep["wdays"]);$i++) {
								$pos = (($adate - $rep["ts"]) / 86400 - $rep["wdays"][$i] + $this->dow) % ($rep["linterval"] * 7);
								if ($pos == 0) {
									createEvent($this, $db, 1);
									break;
								}
								if ($pos < $rep["duration"]) {
									if($pos == $rep["duration"] - 1)
										createEvent($this, $db, 3);
									else
										createEvent($this, $db, 2);
							//		break 2;
								}
							}
						}
					}
					break;
				case "MONTHLY":
					if ($rep["duration"] == 1) {
						// liegt dieser Tag nach der ersten Wiederholung und geh�rt der Monat zur Wiederholungsreihe?
						if ($rep["ts"] < $this->ts + 1 && abs(date("n", $rep["ts"]) - $this->mon) % $rep["linterval"] == 0) {
							// es ist ein Termin am X. Tag des Monats, den hat die Datenbankabfrage schon richtig erkannt
							if ($rep["sinterval"] == "") {
								createEvent($this, $db, 0);
								break;
							}
							// Termine an einem bestimmten Wochentag in der X. Woche
							if (ceil($this->dom / 7) == $rep["sinterval"]) {
								createEvent($this, $db, 0);
								break;
							}
							if ($rep["sinterval"] == 5 && (($this->dom / 7) > 3))
								createEvent($this, $db, 0);
						}
					}
					else {
						$amonth = ($rep["linterval"] - ((($this->year - date("Y",$rep["ts"])) * 12) - (date("n",$rep["ts"]))) % $rep["linterval"]) % $rep["linterval"];
						if ($rep["day"]) {
							$lwst = mktime(12, 0, 0, $amonth, $rep["day"], $this->year, 0);
							$hgst = $lwst + ($rep["duration"] - 1) * 86400;
							if ($this->ts == $lwst) {
								createEvent($this, $db, 1);
								break;
							}
					
							if ($this->ts > $lwst && $this->ts < $hgst) {
								createEvent($this, $db, 2);
								break;
							}
					
							if ($this->ts == $hgst) {
								createEvent($this, $db, 3);
								break;
							}
							
							$lwst = mktime(12, 0, 0, $amonth - $rep["linterval"], $rep["day"], $this->year, 0);
							$hgst = $lwst + $rep["duration"] * 86400;
							
							if ($this->ts == $lwst) {
								createEvent($this, $db, 1);
								break;
							}
					
							if ($this->ts > $lwst && $this->ts < $hgst) {
								createEvent($this, $db, 2);
								break;
							}
					
							if ($this->ts == $hgst) {
								createEvent($this, $db, 3);
								break;
							}
							
						}
						if ($rep["sinterval"]) {
						
							if ($rep["sinterval"] == 5)
								$cor = 0;
							else
								$cor = 1;
							
							$lwst = mktime(12, 0 , 0, $amonth, 1, $this->year, 0) + ($rep["sinterval"] - $cor) * 604800;
							$aday = strftime("%u", $lwst);
							$lwst -= ($aday - $rep["wdays"]) * 86400;
							if ($rep["sinterval"] == 5) {
								if(date("j", $lwst) < 10)
									$lwst -= 604800;
								if (date("n", $lwst) == date("n", $lwst + 604800))
									$lwst += 604800;
							}
							else {
								if($aday > $rep["wdays"])
									$lwst += 604800;
							}
							
							$hgst = $lwst + ($rep["duration"] - 1) * 86400;
							
							if ($this->ts == $lwst) {
								createEvent($this, $db, 1);
								break;
							}
							
							if ($this->ts > $lwst && $this->ts < $hgst) {
								createEvent($this, $db, 2);
								break;
							}
							
							if ($this->ts == $hgst) {
								createEvent($this, $db, 3);
								break;
							}
							
							$lwst = mktime(12, 0, 0, $amonth - $rep["linterval"], 1, $this->year, 0) + ($rep["sinterval"] - $cor) * 604800;;
							$aday = strftime("%u", $lwst);
							$lwst -= ($aday - $rep["wdays"]) * 86400;
							if ($rep["sinterval"] == 5) {
								if (date("j", $lwst) < 10)
									$lwst -= 604800;
								if (date("n", $lwst) == date("n", $lwst + 604800))
									$lwst += 604800;
							}
							else {
								if ($aday > $rep["wdays"])
									$lwst += 604800;
							}
							
							$hgst = $lwst + $rep["duration"] * 86400;
							$lwst += 86400;
							
							if ($this->ts == $lwst) {
								createEvent($this, $db, 1);
								break;
							}
							
							if ($this->ts > $lwst && $this->ts < $hgst) {
								createEvent($this, $db, 2);
								break;
							}
							
							if($this->ts == $hgst){
								createEvent($this, $db, 3);
								break;
							}
						}
						
					}
						
					break;
				case "YEARLY":
				
					if ($rep["duration"] == 1) {
						if ($rep["ts"] > $this->getStart() && $rep["ts"] < $this->getEnd()) {
							createEvent($this, $db, 0);
							break;
						}
							
						// liegt der Wiederholungstermin �berhaupt in diesem Jahr?
						if ($this->year == date("Y", $rep["ts"]) || ($this->year - date("Y", $rep["ts"])) % $rep["linterval"] == 0) {
							// siehe "MONTHLY"
							if ($rep["sinterval"] == "") {
								createEvent($this, $db, 0);
								break;
							}
							if (ceil($this->dom / 7) == $rep["sinterval"]) {
								createEvent($this, $db, 0);
								break;
							}
							if ($rep["sinterval"] == 5 && (($this->dom / 7) > 3)) {
								createEvent($this, $db, 0);
								break;
							}
						}
					}
					else {
					
						// der erste Wiederholungstermin
						$lwst = $rep["ts"];
						$hgst = $rep["ts"] + $rep["duration"] * 86400;
						if ($lwst == $this->ts) {
							createEvent($this, $db, 1);
							break;
						}
						
						if ($this->ts > $lwst && $this->ts < $hgst) {
							createEvent($this, $db, 2);
							break;
						}
					
						if ($this->ts == $hgst) {
							createEvent($this, $db, 3);
							break;
						}
						
						if ($rep["day"]) {
							$lwst = mktime(12,0,0,$rep["month"],$rep["day"],$this->year,0);
							$hgst = $lwst + ($rep["duration"] - 1) * 86400;
							if ($this->ts == $lwst) {
								createEvent($this, $db, 1);
								break;
							}
					
							if ($this->ts > $lwst && $this->ts < $hgst) {
								createEvent($this, $db, 2);
								break;
							}
					
							if ($this->ts == $hgst) {
								createEvent($this, $db, 3);
								break;
							}
							
							$lwst = mktime(12, 0, 0, $rep["month"], $rep["day"] - 1, $this->year - 1, 0);
							$hgst = $lwst + $rep["duration"] * 86400;
							
							if ($this->ts == $lwst) {
								createEvent($this, $db, 1);
								break;
							}
					
							if ($this->ts > $lwst && $this->ts < $hgst) {
								createEvent($this, $db, 2);
								break;
							}
					
							if ($this->ts == $hgst) {
								createEvent($this, $db, 3);
								break;
							}
							
						}
						
						if ($rep["sinterval"]) {
							$lwst = mktime(12, 0, 0, $rep["month"], 1, $this->year, 0) + ($rep["sinterval"] - $cor) * 604800;
							$aday = strftime("%u",$lwst);
							$lwst -= ($aday - $rep["wdays"]) * 86400;
							if ($rep["sinterval"] == 5) {
								if (date("j",$lwst) < 10)
									$lwst -= 604800;
								if (date("n", $lwst) == date("n", $lwst + 604800))
									$lwst += 604800;
							}
							else
								if ($aday > $rep["wdays"])
									$lwst += 604800;
					
							$hgst = $lwst + ($rep["duration"] - 1) * 86400;
					
							if ($this->ts == $lwst) {
								createEvent($this, $db, 1);
								break;
							}
							
							if ($this->ts > $lwst && $this->ts < $hgst) {
								createEvent($this, $db, 2);
								break;
							}
							
							if ($this->ts == $hgst) {
								createEvent($this, $db, 3);
								break;
							}
							
							$lwst = mktime(12, 0, 0, $rep["$month"], 1, $this->year - 1, 0) + ($rep["sinterval"] - $cor) * 604800;
							$aday = strftime("%u", $lwst);
							$lwst -= ($aday - $rep["wdays"]) * 86400;
							if ($rep["sinterval"] == 5) {
								if (date("j", $lwst) < 10)
									$lwst -= 604800;
								if (date("n", $lwst) == date("n", $lwst + 604800))
									$lwst += 604800;
							}
							else {
								if ($aday > $rep["wdays"])
									$lwst += 604800;
							}
							
							$hgst = $lwst + $rep["duration"] * 86400;
							$lwst += 86400;
							
							if ($this->ts == $lwst) {
								createEvent($this, $db, 1);
								break;
							}
							
							if ($this->ts > $lwst && $this->ts < $hgst) {
								createEvent($this, $db, 2);
								break;
							}
							
							if ($this->ts == $hgst) {
								createEvent($this, $db, 3);
								break;
							}
							
						}
					}
			}
		}
		
	/*	if($is_in_day==TRUE){		
			switch($time_range){
				case 0: // Einzeltermin
					$start = mktime(date("G",$db->f("date")),date("i",$db->f("date")),0,$this->mon,$this->dom,$this->year);
					$end = mktime(date("G",$db->f("end_time")),date("i",$db->f("end_time")),0,$this->mon,$this->dom,$this->year);
					break;
				case 1: // Start
					$start = mktime(date("G",$db->f("date")),date("i",$db->f("date")),0,$this->mon,$this->dom,$this->year);
					$end = $this->getEnd();
					break;
				case 2: // Mitte
					$start = $this->getStart();
					$end = $this->getEnd();
					break;
				case 3: // Ende
					$start = $this->getStart();
					$end = mktime(date("G",$db->f("end_time")),date("i",$db->f("end_time")),0,$this->mon,$this->dom,$this->year);
			}
			$termin = new CalendarEvent($start, $end, $db->f("content"), $db->f("repeat"), $db->f("expire"),
			                     $db->f("color"), $db->f("priority"), $db->f("raum"), $db->f("termin_id"), $db->f("date_typ"));
			if($time_range == 2)
				$termin->setDayEvent(TRUE);
			$termin->chng_flag = FALSE;
			$this->app[] = $termin;
		}*/
	}
}
	
	function createEvent (&$this, &$db, $time_range) {
		switch ($time_range) {
			case 0: // Einzeltermin
				$start = mktime(date("G", $db->f("start")), date("i",$db->f("start")), 0, $this->mon, $this->dom, $this->year);
				$end = mktime(date("G", $db->f("end")), date("i", $db->f("end")), 0, $this->mon, $this->dom, $this->year);
				break;
			case 1: // Start
				$start = mktime(date("G", $db->f("start")), date("i", $db->f("start")), 0, $this->mon, $this->dom, $this->year);
				$end = $this->getEnd();
				break;
			case 2: // Mitte
				$start = $this->getStart();
				$end = $this->getEnd();
				break;
			case 3: // Ende
				$start = $this->getStart();
				$end = mktime(date("G", $db->f("end")), date("i", $db->f("end")), 0, $this->mon, $this->dom, $this->year);
		}
		$termin =& new CalendarEvent(array(
				"DTSTART"       => $start,
				"DTEND"         => $end,
				"SUMMARY"       => $db->f("summary"),
				"DESCRIPTION"   => $db->f("description"),
				"PRIORITY"      => $db->f("prority"),
				"LOCATION"      => $db->f("location"),
				"CATEGORIES"    => $db->f("categories"),
				"UID"           => $db->f("uid"),
				"RRULE"         => array(
						"ts"        => $db->f("ts"),
						"linterval" => $db->f("linterval"),
						"sinterval" => $db->f("sinterval"),
						"wdays"     => $db->f("wdays"),
						"month"     => $db->f("month"),
						"day"       => $db->f("day"),
						"rtype"     => $db->f("rtype"),
						"duration"  => $db->f("duration"),
						"expire"    => $db->f("expire"))),
				$db->f("event_id"), $db->f("mkdate"), $db->f("chdate"));
		
		if ($time_range == 2)
			$termin->setDayEvent(TRUE);
		$this->events[] = $termin;
	}
	

?>
