<?php
# Lifter002: TODO

/*
* DataFieldEntry.class.php - <short-description>
*
* Copyright (C) 2005 - Martin Gieseking  <mgieseki@uos.de>
* Copyright (C) 2007 - Marcus Lunzenauer <mlunzena@uos.de>
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License as
* published by the Free Software Foundation; either version 2 of
* the License, or (at your option) any later version.
*/


require_once 'lib/functions.php';
require_once 'config/config.inc.php';
require_once 'lib/classes/DataFieldStructure.class.php';
require_once 'lib/classes/SeminarCategories.class.php';

abstract class DataFieldEntry {
	public $value;
	public $structure;
	public $rangeID;
	
	
	function __construct($structure = null, $rangeID = '', $value = null) {
		$this->structure = $structure;
		$this->rangeID   = $rangeID;
		$this->value     = $value;
	}
	
	
	// @static
	public static function getDataFieldEntries ($rangeID, $object_type = '', $object_class_hint = '') {
		if (!$rangeID) return false; // we necessarily need a range ID
		
		if (is_array($rangeID)) {    // rangeID may be an array ("classic" rangeID and second rangeID used for user roles)
			$secRangeID = $rangeID[1];
			$rangeID = $rangeID[0];    // to keep compatible with following code
			if ('usersemdata' !== $object_type && 'roleinstdata' !== $object_type) {
				$object_type = 'userinstrole';
			}
			$clause1 = "AND sec_range_id='$secRangeID'";
		}
		
		if (!$object_type) $object_type = get_object_type($rangeID);
		
		if ($object_type) {
			switch ($object_type) {
				case 'sem':
					if($object_class_hint){
						$object_class = SeminarCategories::GetByTypeId($object_class_hint); 	
					} else {
						$object_class = SeminarCategories::GetBySeminarId($rangeID); 	
					}
					$clause2 = "object_class=".(int)$object_class." OR object_class IS NULL";
					
				break;
					
				case 'inst':
				case 'fak':
					if($object_class_hint){
						$object_class = $object_class_hint; 	
					} else {
						$query = "SELECT type FROM Institute WHERE Institut_id = '$rangeID'";
						$object_class = DBManager::get()->query($query)->fetchColumn();
					}
					$object_type = "inst";
					$clause2 = "object_class=".(int)$object_class." OR object_class IS NULL";
				break;
				
				case 'roleinstdata': //hmm tja, vermutlich so
					$clause2 = '1';
				break;
				
				case 'user':
				case 'userinstrole':
				case 'usersemdata':
					$object_class = DataFieldStructure::permMask($GLOBALS['perm']->get_perm($rangeID));
					$clause2 = "((object_class & ". (int)$object_class .") OR object_class IS NULL)";
				break;
			}
			
			$query  = "SELECT a.*, content ";
			$query .= "FROM datafields a LEFT JOIN datafields_entries b ON (a.datafield_id=b.datafield_id AND range_id = '$rangeID' $clause1) ";
			$query .= "WHERE object_type ='$object_type' AND ($clause2) ORDER BY object_class, priority";
			$rs = DBManager::get()->query($query);
			$entries = array();
			while ($data = $rs->fetch(PDO::FETCH_ASSOC)) {
				$struct = new DataFieldStructure($data);
				$entries[$data['datafield_id']]= DataFieldEntry::createDataFieldEntry($struct, $rangeID, $data['content']);
			}
		}
		return $entries;
	}
	
	
	// @static
	//hmm wird das irgendwo gebraucht (und wenn ja wozu)?
	/*
	public static function getDataFieldEntriesBySecondRangeID ($secRangeID) {
		$db = new DB_Seminar;
		$query  = "SELECT *, a.datafield_id AS id ";
		$query .= "FROM datafields a JOIN datafields_entries b ON a.datafield_id=b.datafield_id ";
		$query .= "AND sec_range_id = '$secRangeID'";
		$db->query($query);
		while ($db->next_record()) {
			$data = array('datafield_id' => $db->f('id'), 'name' => $db->f('name'), 'type' => $db->f('type'),
			'typeparam' => $db->f('typeparam'), 'object_type' => $db->f('object_type'), 'object_class' => $db->f('object_class'),
			'edit_perms' => $db->f('edit_perms'), 'priority' => $db->f('priority'), 'view_perms' => $db->f('view_perms'));
			$struct = new DataFieldStructure($data);
			$entry = DataFieldEntry::createDataFieldEntry($struct, array($db->f('range_id'), $secRangeID), $db->f('content'));
			$entries[$db->f("id")] = $entry;
		}
		return $entries;
	}
	*/
	
	
	function store () {
		if (is_array($this->rangeID)) {
			list($rangeID, $secRangeID) = $this->rangeID;
		} else {
			$rangeID = $this->rangeID;
			$secRangeID = "";
		}
		$query = "INSERT INTO datafields_entries (content, datafield_id, range_id, sec_range_id, mkdate, chdate)
					 VALUES (?,?,?,?,UNIX_TIMESTAMP(), UNIX_TIMESTAMP()) 
					 ON DUPLICATE KEY UPDATE content=?, chdate=UNIX_TIMESTAMP()";
		$st = DBManager::get()->prepare($query);
		$ret = $st->execute(array($this->getValue(),$this->structure->getID(),$rangeID,$secRangeID,$this->getValue()));
		return $ret;
	}
	
	
	// @static
	public static function removeAll ($rangeID) {
		if ($rangeID) {
			$query = "DELETE FROM datafields_entries WHERE range_id = '$rangeID'";
			$ret = DBManager::get()->exec($query);
			return $ret;
		}
	}
	
	// @static
	public static function getSupportedTypes () {
		return array("bool", "textline", "textarea", "selectbox", "date", "time", "email", "phone", "radio", "combo");
	}
	
	// "statische" Methode: liefert neues Datenfeldobjekt zu gegebenem Typ
	// @static
	public static function createDataFieldEntry ($structure, $rangeID='', $value='') {
		if (!is_object($structure)) return false;
		$type = $structure->getType();
		if(in_array($type, DataFieldEntry::getSupportedTypes())){
			$entry_class = 'DataField' . ucfirst($type) . 'Entry';
			return new $entry_class($structure, $rangeID, $value);
		} else {
			return false;
		}
	}
	
	function getType () {
		$class = strtolower(get_class($this));
		return substr($class, 9, strpos($class, 'entry')-9);
	}
	
	function getDisplayValue ($entities = true) {
		if ($entities) return htmlReady($this->getValue());
		return $this->getValue();
	}
	
	function getValue ()           {return $this->value;}
	function getName ()            {return $this->structure->getName();}
	function getId ()            {return $this->structure->getID();}
	function getHTML ($name)       {return '';}
	function setValue ($v)         {$this->value = $v;}
	function setValueFromSubmit ($submitted_value) { $this->setValue(remove_magic_quotes($submitted_value));}
	function setRangeID ($v)       {$this->rangeID = $v;}
	function setSecondRangeID ($v) {$this->rangeID = array(is_array($this->rangeID) ? $this->rangeID[0] : $this->rangeID, $v);}
	function isValid ()            {return true;}
	function numberOfHTMLFields () {return 1;}

}


class DataFieldBoolEntry extends DataFieldEntry {
	
	function getHTML ($name) {
		$field_name = $name .'['.$this->structure->getID().']';
		if ($this->getValue()) $checked = 'checked';
		return "<input type=\"hidden\" name=\"$field_name\" value=\"0\">
		<input type=\"checkbox\" name=\"$field_name\" value=\"1\" $checked>";
	}
	
	function getDisplayValue ($entities = true) {
		return $this->getValue() ? _('Ja') : _('Nein');
	}
	
	function setValueFromSubmit($submitted_value){
		$this->setValue((int)$submitted_value);
	}
}


class DataFieldTextlineEntry extends DataFieldEntry {
	function getHTML ($name) {
		$field_name = $name .'['.$this->structure->getID().']';
		$valattr = 'value="' . $this->getDisplayValue() . '"';
		return "<input type=\"text\" name=\"$field_name\" $valattr>";
	}
}


class DataFieldTextareaEntry extends DataFieldEntry {
	function getHTML ($name) {
		$field_name = $name .'['.$this->structure->getID().']';
		return sprintf('<textarea name="%s" rows="6" cols="58">%s</textarea>', $field_name, $this->getDisplayValue());
	}
}


class DataFieldEmailEntry extends DataFieldEntry {
	
	function getHTML ($name) {
		$field_name = $name .'['.$this->structure->getID().']';
		return sprintf('<input name="%s" value="%s" size="30">', $field_name, $this->getDisplayValue());
	}
	
	function isValid () {
		if ($this->value) return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($this->value));
		return true;
	}
}


class DataFieldSelectboxEntry extends DataFieldEntry {
	function __construct ($struct, $range_id, $value) {
		parent::__construct($struct, $range_id, $value);
		list($values, $is_assoc) = $this->getParams();
		$this->is_assoc_param = $is_assoc;
		$this->type_param = $values;
		reset($values);
		if(is_null($this->getValue())){
			if(!$is_assoc){
				$this->setValue(current($values));  // first selectbox entry is default
			} else {
				$this->setValue((string)key($values));
			}
		}
		
	}
	
	function getHTML ($name) {
		$field_name = $name .'['.$this->structure->getID().']';
		$ret = "<select name=\"$field_name\">";
		foreach ($this->type_param as $pkey => $pval) {
			$value = $this->is_assoc_param ? (string)$pkey : $pval;
			$sel = $value == $this->getValue() ? 'selected' : '';
			$ret .= sprintf('<option value="%s" %s>%s</option>',
			htmlReady($value),
			$sel,
			htmlReady($pval));
		}
		return $ret . "</select>";
	}
	
	function getParams(){
		$ret = array();
		$i = 0;
		$is_assoc = false;
		foreach(array_map('trim',explode("\n", $this->structure->getTypeParam())) as $p){
			if(strpos($p,'=>') !== false){
				$is_assoc = true;
				list($key, $value) = array_map('trim',explode('=>', $p));
				$ret[$key] = $value;
			} else {
				$ret[$i] = $p;
			}
			++$i;
		}
		return array($ret, $is_assoc);
	}
	
	function getDisplayValue($entities = true){
		$value = $this->is_assoc_param ? $this->type_param[$this->getValue()] : $this->getValue();
		return $entities ? htmlReady($value) : $value;
	}
}


class DataFieldRadioEntry extends DataFieldSelectboxEntry {
	
	function __construct ($struct, $range_id, $value) {
		parent::__construct($struct, $range_id, $value);
	}
	
	function getHTML ($name) {
		$field_name = $name .'['.$this->structure->getID().']';
		$ret = '';
		foreach ($this->type_param as $pkey => $pval) {
			$value = $this->is_assoc_param ? (string)$pkey : $pval;
			$ret .= sprintf('<input type="radio" value="%s" name="%s"%s> %s',
			htmlReady($value), $field_name,
			$value == $this->getValue()
                        ? ' checked="checked"' : '',
			htmlReady($pval));
		}
		return $ret;
	}
}


class DataFieldComboEntry extends DataFieldEntry {
	function __construct ($struct, $range_id, $value) {
		parent::__construct($struct, $range_id, $value);
		if(is_null($this->getValue())){
			$values = explode("\n", $this->structure->getTypeParam());
			$this->setValue(trim($values[0]));  // first selectbox entry is default
		}
	}
	
	function numberOfHTMLFields () {
		return 2;
	}
	
	function setValueFromSubmit ($value) {
		parent::setValueFromSubmit($value[$value['combo']]);
	}
	
	function getHTML ($name) {
		$field_name = $name .'['.$this->structure->getID().']';
		$values = array_map('trim', explode("\n", $this->structure->getTypeParam()));
		$id = $this->structure->getID();
		$ret = sprintf('<input type="radio" value="select" id="combo_%s_select" name="%s"%s>',
		$id, $field_name .'[combo]',
		($select = in_array($this->value, $values))
		? ' checked="checked"' : '');
		$ret .= sprintf('<select onFocus="$(\'combo_%s_select\').checked = \'checked\';" name="%s">', $id, $field_name .'[select]');
		foreach ($values as $val) {
			$val = trim(htmlentities($val, ENT_QUOTES));
			$sel = $val == $this->getValue() ? 'selected' : '';
			$ret .= "<option value=\"$val\" $sel>$val</option>";
		}
		$ret .= "</select>&nbsp;";
		
		$ret .= sprintf('<input type="radio" value="text" id="combo_%s_text" name="%s"%s>',
		$id, $field_name .'[combo]',
		$select ? '' : ' checked="checked"');
		
		if ($this->value && !$select) $valattr = 'value="' . $this->getDisplayValue() . '"';
		$ret .= sprintf('<input name="%s" onFocus="$(\'combo_%s_text\').checked = \'checked\';" %s>', $field_name .'[text]', $id, $valattr);
		return $ret;
	}
}


class DataFieldPhoneEntry extends DataFieldEntry {
	function numberOfHTMLFields () {return 3;}
	
	function setValueFromSubmit ($value) {
		if (is_array($value)){
			parent::setValueFromSubmit(str_replace(' ', '', implode("\n", array_slice($value, 0, 3))));
		}
	}
	
	function getDisplayValue ($entities = true) {
		list($country, $area, $phone) = explode("\n", $this->value);
		if ($country!='' || $area!='' || $phone!='')
		{
			if ($country)
			$country = "+$country";
			if ($area) {
				$area = "(0)$area";
				if ($phone)
				$area .= '/';
			}
			return "$country $area$phone";
		} else
		{
			return '';
		}
	}
	
	function getHTML ($name) {
		$name = $name .'['.$this->structure->getID().'][]';
		$parts = explode("\n", $this->value);
		for ($i=3-count($parts); $i > 0; $i--) array_unshift($parts, '');
		$size   = array(3, 6, 10);
		$title  = array(_('Landesvorwahl ohne f&uuml;hrende Nullen'), _('Ortsvorwahl ohne f&uuml;hrende Null'), _('Rufnummer'));
		$prefix = array('+', '(0)', '&nbsp;/&nbsp;');
		$ret = '';
		foreach ($parts as $i => $part) {
			//      $part = preg_replace('/^0+(.*)$/', '\1', $part);
			$ret .= sprintf('%s<input name="%s" maxlength="%d" size="%d" value="%s" title="%s">',
			$prefix[$i], $name, $size[$i], $size[$i]-1, htmlentities($part, ENT_QUOTES), $title[$i]);
		}
		$ret .= '<font size="-1">';
		$ret .= '&nbsp;'._('z.B.:').' +<span style="{border-style:inset; border-width:2px;}">&nbsp;49&nbsp;</span>';
		$ret .= '&nbsp;(0)<span style="{border-style:inset; border-width:2px;}">&nbsp;541&nbsp;</span>';
		$ret .= '&nbsp;/&nbsp;<span style="{border-style:inset; border-width:2px;}">&nbsp;969-0000&nbsp;</span>';
		$ret .= '</font>';
		return $ret;
	}
	
	function isValid () {
		if (trim($this->value) == '') return true;
		return preg_match('/^[1-9][0-9]*\n[1-9][0-9]+\n[1-9][0-9]+(-[0-9]+)?$/', $this->value);
	}
}


class DataFieldDateEntry extends DataFieldEntry {
	function numberOfHTMLFields () {return 3;}
	
	function setValueFromSubmit ($value) {
			if (is_array($value) && $value[0] != '' && $value[1] != '' && $value[2] != '') {
			parent::setValueFromSubmit("$value[2]-$value[1]-$value[0]");
		}
	}
	
	function getDisplayValue ($entries = true) {
		if (preg_match('/(\d+)-(\d+)-(\d+)/', $this->value, $m))
		return "$m[3].$m[2].$m[1]";
		return '';
	}
	
	function getHTML ($name) {
		$field_name = $name .'['.$this->structure->getID().'][]';
		$parts = split('-', $this->value);
		$ret = sprintf('<input name="%s" maxlength="2" size="1" value="%s" title="Tag">', $field_name, $parts[2]);
		$ret .= ".&nbsp;";
		$months = array('', 'Januar', 'Februar', 'M�rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'Novemember', 'Dezember');
		$ret .= "<select name=\"$field_name\" title=\"Monat\">";
		foreach ($months as $i=>$m)
		$ret .= sprintf('<option %s value="%s">%s</option>', ($parts[1] == $i ? 'selected' : ''), $i, $m);
		$ret .= "</select>&nbsp;";
		$ret .= sprintf('<input name="%s" maxlength="4" size="3" value="%s" title="Jahr">',  $field_name, $parts[0]);
		return $ret;
	}
	
	function isValid () {
		if (trim($this->value) == '')
		return true;
		$parts = split("-", $this->value);
		$valid = preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $this->value);
		return trim($this->value) != '' && $valid && checkdate($parts[1], $parts[2], $parts[0]);
	}
}


class DataFieldTimeEntry extends DataFieldEntry {
	function numberOfHTMLFields () {return 2;}
	
	function setValueFromSubmit ($value) {
		if (is_array($value)) {
			parent::setValueFromSubmit("$value[0]:$value[1]");
		}
	}
	
	function getHTML ($name) {
		$name = $name .'['.$this->structure->getID().'][]';
		$parts = split(':', $this->value);
		$ret = sprintf('<input name="%s" maxlength="2" size="1" value="%s" title="Stunden">:', $name, $parts[0]);
		$ret .= sprintf('<input name="%s" maxlength="2" size="1" value="%s" title="Minuten">', $name, $parts[1]);
		return $ret;
	}
	
	function isValid () {
		$parts = split(':', $this->value);
		return $parts[0] >= 0 && $parts[0] <= 24 && $parts[1] >= 0 && $parts[1] <= 59;
	}
}

?>