<?php
    namespace grmule\tpldotphp;
	class TemplateUtilities implements iTemplateUtilities {
        use MinumumUtilities;

		public function heading($headingText = '', $headingTag = 'h1') {
			if (strlen(trim($headingText)) < 1) {
				return '';
			}
			return $this->wrapTextInTag($headingText, $headingTag);
		}
		public function paragraph($text = '', $paragraphTag = 'p') {
			if (strlen(trim($text)) < 1) {
				return '';
			}
			return $this->wrapTextInTag($text, $paragraphTag);
		}
		public function wrapTextInTag($text, $tag) {
			return '<'.$tag.'>'.$text.'</'.$tag.'>';
		}
		public function date($date, $describe=false) {
			if (is_numeric($date) === false)
				$dateTime = strtotime($date);
			if ($describe === true)
				return date($this->getSetting('display.dateDescribeFormat'), $date);
			return date($this->getSetting('display.dateFormat'), $date);
		}
		public function time($time) {
			if (is_numeric($time) === false)
				$dateTime = strtotime($time);
			return date($this->getSetting('displaytimeFormat'), $time);
		}
		public function dateTime($dateTime, $describe=false) {
			if (is_numeric($dateTime) === false)
				$dateTime = strtotime($dateTime);
			if ($describe === true)
				return date($this->getSetting('display.dateTimeDescribeFormat'), $dateTime);
			return date($this->getSetting('display.dateTimeFormat'), $dateTime);
		}

		public function resource($resourceRelative) {
			if (strpos($resourceRelative, '//') !== false)
				return $resourceRelative;
			return $this->getSetting('paths.baseUrls').$resourceRelative;
		}
		public function image($resourceRelative,  array $props=array()){
			return $this->img($resourceRelative, $props);
		}
		public function img($resourceRelative,  array $props=array()) {
			if (strpos($resourceRelative, '//') === false)
				$url = $this->resource($resourceRelative);
			else
				$url = $resourceRelative;
			$img = '<img src="'.$url.'"';
			$img = $this->propsToTag($props, $img);
			$img .= ' onerror="this.src=\''.$this->resource('images/default.png').'\';" ';
			$img .= ' data-orginal-media="'.$url.'" ';
			$img .= ' />';
			return $img;
		}
		private function propsToTag($props, $tagPartial) {
			foreach ($props as $propName=>$propVal) {
				$tagPartial.= ' '.$propName.'="'.$propVal.'"';
			}
			return $tagPartial;
		}
		public function tagProperties ($props) {
			$proplist = array();
			foreach ($props as $propName=>$propVal) {
				if (is_array($propVal))
					$propVal = json_encode($propVal);
				$proplist[] = $propName.'="'.htmlspecialchars($propVal).'"';
			}
			return implode(' ', $proplist);
		}
		public function cash($cash) {
			return money_format('%.2n', $cash);
		}
	}
?>