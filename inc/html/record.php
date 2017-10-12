<?php

if (!defined('HTML_RECORD_TH_ATTR')) {
    define('HTML_RECORD_TH_ATTR', 'align="right" ');
}

require_once 'row.php';

class HtmlRecord extends HtmlRow{
	
	var $spRows = array(
		'__blank' => 'blank',
	);
	
	function blank($title = ''){
		return "<td class=\"blank\" colspan=\"2\">{$title}</td>\n";
	}
	
	function getHtml(){
		if(!is_array($this->header) && !$this->_buildDefaultHeader()){
			return '';
		}
		
		$html = '';
		$id = $this->htmlId ? ' id="' . $this->htmlId . '"' : '';
		$html .=  '<table border="1" cellspacing="0" cellpadding="4" class="form"' . $id . '>' . "\n";
		foreach($this->header as $key => $fvalue){
			if(key_exists($key, $this->spRows)){
				$html .= $this->{$this->spRows[$key]}($fvalue);
			}else{
				// th
				$thAttr = $this->thAttr($key);
				if(!$thAttr){
					$thAttr = HTML_RECORD_TH_ATTR;
				}
				$html .= "<tr>\n\t<th {$thAttr}>{$this->header[$key]}</th>\n";
				
				// td
				$tdAttr = $this->tdAttr($key, $this->data);
				$fvalue = $this->fieldValue($key, $this->data);
				$html .= "\t<td {$tdAttr}>{$fvalue}</td>\n</tr>\n";
			}
		}
		$html .= "</table>\n";
		
		return $html;
	}
	
	function dispaly(){
		echo $this->getHtml();
	}
	
}

?>