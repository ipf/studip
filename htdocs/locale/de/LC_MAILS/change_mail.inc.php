<?
		$subject="Account-�nderung Stud.IP-System";
		
		$mailbody="Dies ist eine Informationsmail des Stud.IP-Systems\n"
		."(Studienbegleitender Internetsupport von Pr�senzlehre)\n"
		."- " . $GLOBALS['UNI_NAME_CLEAN'] . " -\n\n"
		."Ihr Account wurde um " . $Zeit . " von einer Administratorin oder einem\n"
		."Administrator ver�ndert"
		.($this->user_data['auth_user_md5.locked']==1 ? " und gesperrt" : "")
		.".\nDie aktuellen Angaben lauten:\n\n"
		."Benutzername: " . $this->user_data['auth_user_md5.username'] . "\n"
		."Status: " . $this->user_data['auth_user_md5.perms'] . "\n"
		."Vorname: " . $this->user_data['auth_user_md5.Vorname'] . "\n"
		."Nachname: " . $this->user_data['auth_user_md5.Nachname'] . "\n"
		."E-Mail-Adresse: " . $this->user_data['auth_user_md5.Email'] . "\n\n"
		."Ihr Passwort hat sich nicht ver�ndert.\n\n"
		."Diese Mail wurde Ihnen zugesandt, um Sie �ber die �nderungen zu informieren.\n\n"
		."Wenn Sie Einw�nde gegen die �nderungen haben, wenden Sie sich bitte an\n"
		. $this->smtp->abuse . "\n"
		."Sie k�nnen einfach auf diese Mail antworten.\n\n"
		."Hier kommen Sie direkt ins System:\n"
		. $this->smtp->url . "\n\n";

?>
