<?

// wikiMarkups are used by the wikiDirective function
// after all other conversions,
// wikiMarkup patterns are replaced
// args to wikiMarkup are passed to preg_replace
//

wikiMarkup('/\\(:stepform:\\)/e',"wiki_stepform('step')");
wikiMarkup('/\\(:steplist\\s*(.*?):\\)/e',"wiki_steplist('step',array('q'=>'$1'))");

$step_templates['step']=array(
	// common prefix to alle newly created pages
	// must be a WikiWord and should be unique to
	// avoid conflicts with other templates
	"prefix"=>"StEP", 
	// Some Text to display as form heading
	"formheading"=>"<h2>"._("Neuer StEP-Eintrag")."</h2><p>"._("Name des Autoren und Erstellungszeit werden automatisch hinzugef�gt.")."</p>",
	// body of form for new entries, is embedded in <form>..</form>
	// environment. Make sure that field names match variable names
	// in template (see below)
	"formbody"=>"<table>
<tr><td>Zusammenfassung:</td>
<td><input size=60 name=\"step_zusammenfassung\"></td></tr>
<tr><td>Zust�ndig:</td>
<td><input size=60 name=\"step_zustaendig\"></td></tr>
<tr><td>Komplexit�t:</td>
<td><select name=\"step_komplexitaet\"><option>gering</option><option>mittel</option><option>hoch</option></select></td></tr>
<tr><td>Beschreibung:</td>
<td><textarea name=\"step_beschreibung\" cols=60 rows=10></textarea></td></tr>
<tr><td>&nbsp;</td><td><input type=image ".makeButton("eintragen","src")." border=0></td></tr>
</table>",
	// template is evaluated alter to form default text
	// important: make sure that variables evaluate at the right time
	// you may use predefined: 
	// - $author for author name
	// - $create_time for time at creation
	"template"=>'!!!!$pagename
Zusammenfassung: $step_zusammenfassung
Autor: $author
Zust�ndig: $step_zustaendig
Komplexit�t: $step_komplexitaet
Erstellt: $create_time
Status: neu
Beschreibung: 

$step_beschreibung', 
	// list of fields to parse for list view, matching is case-insensitive
	// order must be same as indicated by listheader
	// first field (name) will be added
	"listview"=>array('erstellt','autor','zust�ndig','komplexit�t','status','zusammenfassung'),
	// standard order of fields for sort function
  	"stdorder"=>'-erstellt,status,autor,zust�ndig,zusammenfassung',
	// header for list tables, first column always is the pages name
	// order defines order criterion for sort action
	"listheader"=>array(array("order"=>"-name","heading"=>"StEP#"),
		array("order"=>"erstellt", "heading"=>"Erstellt"),
		array("order"=>"autor", "heading"=>"Autor"),
		array("order"=>"zust�ndig", "heading"=>"Zust�ndig"),
		array("order"=>"komplexit�t", "heading"=>"Komplex."),
		array("order"=>"status", "heading"=>"Status"),
		array("order"=>"zusammenfassung", "heading"=>"Zusammenfassung"))
);

// ---------- end of config ---------------------------------------

if ($_REQUEST['step_action']=='new_step') {
	// add new StEP-page to wiki pages
	wiki_newstep($_REQUEST['step_template']);
}

// create StEP form
//
function wiki_stepform($template_name) {
	global $PHP_SELF, $keyword;
	global $step_templates;
	$template=$step_templates[$template_name];
	if (!is_array($template)) { echo "<h1>Error: unknown template $template_name"; die(); }

	$form=$template['formheading'];
	$form.="<form action=\"$PHP_SELF\" method=post>\n
		<input type=\"hidden\" name=\"step_action\" value=\"new_step\">
		<input type=\"hidden\" name=\"step_template\" value=\"$template_name\">
		<input type=\"hidden\" name=\"keyword\" value=\"$keyword\">";
	$form.=$template['formbody'];
	$form.="</form>";
	return $form;
}

// get list of StEP entries
//
function wiki_get_steppagelist($template) {
	global $SessSemName;
	$list=array();
	$db=new DB_Seminar();
	$query="SELECT DISTINCT keyword FROM wiki WHERE range_id='$SessSemName[1]' AND keyword LIKE '".$template['prefix']."%'";
	$db->query($query);
	while ($db->next_record()) {
		$list[]=$db->f('keyword');
	}
	return $list;
}
 
// create new StEP page
// data is passed from form defined in wiki_stepform()
//
function wiki_newstep($template_name) {
	global $SessSemName, $auth;
	global $keyword, $view, $wiki_plugin_messages;
	global $step_templates;
	$template=$step_templates[$template_name];
	foreach ($_REQUEST as $rkey=>$rvalue) { // locally set request-vars
		eval("\$".$rkey."='".$rvalue."';"); // that might be used in template
	}
	$list=wiki_get_steppagelist($template);
	foreach ($list as $l) {
		$issue=max(@$issue, substr($l,strlen($template['prefix'])));
	}
	$pagename=sprintf("%s%05d",$template['prefix'],@$issue+1);
	$create_time=date('Y-m-d H:i',time());
	$author=get_fullname(NULL,'no_title_short');
// print "<p>template ist: <pre>"; print_r($template); print "</pre>";
// print "<p>evaling: <pre>"."\$text=".$template['template'].";"."</pre>";
	eval("\$text=\"".$template['template']."\";");
// print "<p>Generierter Text:<br>$text"; // debug
	$db=new DB_Seminar();
	$userid=$auth->auth['uid'];
	$query="INSERT INTO wiki SET range_id='$SessSemName[1]', keyword='$pagename', body='".addslashes($text)."', user_id='$userid', chdate='".time()."', version='1'";
	$db->query($query);
	$wiki_plugin_messages[]="msg�"._("Ein neuer Eintrag wurde angelegt. Sie k�nnen ihn nun weiter bearbeiten oder <a href=\"$PHP_SELF?keyword=$keyword\">zur�ck zur Ausgangsseite</a> gehen.");
	$view='show';
	$keyword=$pagename;
	return;
}

// wiki_steplist creates a table of StEP issues according to various
// criteria.  
function wiki_steplist($template_name,$opt) {
	global $PHP_SELF, $SessSemName;
	global $keyword, $show_wiki_comments, $step_templates;
	$template=$step_templates[$template_name];
	$opt = array_merge($opt,@$_REQUEST);
	$steplist = wiki_get_steppagelist($template);
	$out[] = "<table border='1' cellspacing='0' cellpadding='3'></tr>";
	foreach ($template['listheader'] as $h) {
		$out[]="<th><a href='$PHP_SELF?keyword=$keyword&order=".urlencode($h[order])."'>$h[heading]</a></th>";
	}
	$out[]="</tr>\n";
	$terms = preg_split('/((?<!\\S)[-+]?[\'"].*?[\'"](?!\\S)|\\S+)/',
		$opt['q'],-1,PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
	foreach($terms as $t) {
		if (trim($t)=='') continue;
		if (preg_match('/([^\'":=]*)[:=]([\'"]?)(.*?)\\2$/',$t,$match))
			$opt[strtolower($match[1])] = $match[3]; 
	}
	$n=0; $slist=array();
	foreach($steplist as $s) {
		$page = getLatestVersion($s,$SessSemName[1]);
		preg_match_all("/(^|\n)([A-Za-z][^:]*):([^\n]*)/",$page['body'],$match);
		$fields = array();
		for($i=0;$i<count($match[2]);$i++) 
			$fields[strtolower($match[2][$i])] = htmlentities($match[3][$i],ENT_QUOTES);
		foreach(explode(',',$template['stdorder']) as $h) {
			if (!@$opt[$h]) continue;
			foreach(preg_split('/[ ,]/',$opt[$h]) as $t) {
				if (substr($t,0,1)!='-' && substr($t,0,1)!='!') {
					if (strpos(strtolower(@$fields[$h]),strtolower($t))===false) 
						continue 3;
				} else if (strpos(strtolower(@$fields[$h]), strtolower(substr($t,1)))!==false) 
					continue 3;
			}
		}
		$slist[$n] = $fields;
		$slist[$n]['name'] = $s;
		$n++;
	}
	$cmp = CreateOrderFunction(@$opt['order'].",".$template['stdorder']);
	usort($slist,$cmp);
	foreach($slist as $s) {
		$out[] = "<tr><td><font size=-1><a href='$PHP_SELF?keyword=$s[name]'>$s[name]</a></font></td>";
		foreach($template['listview'] as $h) 
			$out[] = @"<td><font size=-1>".wikiLinks(wikiReady(decodeHTML($s[$h]),TRUE,FALSE,$show_wiki_comments), $keyword)."&nbsp;</font></td>";
			$out[] = "</tr>";
	}
	$out[] = "</table>";
	return implode('',$out);
}

// This function creates specialized ordering functions needed to
// (more efficiently) perform sorts on arbitrary sets of criteria.
function CreateOrderFunction($order) { 
  $code = '';
  foreach(preg_split('/[\\s,|]+/',strtolower($order),-1,PREG_SPLIT_NO_EMPTY) 
      as $o) {
    if (substr($o,0,1)=='-') { $r='-'; $o=substr($o,1); }
    else $r='';
    if (preg_match('/\\W/',$o)) continue;
    $code .= "\$c=strcasecmp(@\$x['$o'],@\$y['$o']); if (\$c!=0) return $r\$c;\n";
  }
  $code .= "return 0;\n";
  return create_function('$x,$y',$code);
}


?>
