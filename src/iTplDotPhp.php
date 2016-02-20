<?php
	namespace GRMule\TplDptPhp;
	interface iTplDotPhp {
		public function exists($template, $extraPath = array());
		public function template($template, $data = null, $extraPaths=array());
		public function utility($tool, $args=null);
	}
?>
