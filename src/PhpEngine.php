<?php
namespace GRMule\TplDptPhp;
class PhpEngine implements iTplDotPhp {
		private $utility = null;
        private $paths = array();
		public $log = array();
        private $errorHtmlClass = 'template-error';
        private $returnErrors = false;
        private $throwExceptions = false;
        private $sharedData = array();

		public function __construct (
			array $paths,
			TemplateUtilities $utility = null,
            $returnErrors = false,
            $throwExceptions = false
		){
			$this->utility = is_object($utility) === true ? $utility : new EmptyTemplateUtilities();
			$this->paths = $paths;
            $this->returnErrors = $returnErrors;
            $this->throwExceptions = $throwExceptions;
		}

        public function setSharedData($data) {
            $this->sharedData = $data;
        }
        public function getSharedData() {
            return $this->sharedData;
        }
        public function clearSharedData() {
            $this->sharedData = array();
        }

		public function exists($template, $extraPaths = array()) {
			return $this->getTemplatePath($template, $extraPaths) === false ? false : true;
		}

		public function template($template, $data = null, $extraPaths=array()) {
            if (!$this->exists($template) === false) {
                return $this->handleError('RenderEngine: template "' . $template . '" not found');
            }

			if (
				is_array($data) === false &&
				!($data instanceOf \Traversable)
			) {
				$data = array();
			}

            $templatePath = $this->getTemplatePath($template, $extraPaths);

			ob_start();
			$engine = $e = $this;
			$utility = $u = $this->utility;
			$utility->startTemplate($templatePath);

			include($templatePath);

			$html = ob_get_contents();
			ob_end_clean();

			$utility->endTemplate();

			return $html;
		}

		public function utility($method, $args=null) {
			if (is_array($args) === false)
				$args = array($args);
			if (method_exists($this->utility, $method)) {
				return call_user_func_array(array($this->utility, $method), $args);
			}
			return null;
		}

        private function getTemplatePath($template, $extraPaths = array()) {
            $pathList = $this->paths;
            if (count($extraPaths) > 0) {
                $pathList = array_merge($pathList, $extraPaths);
            }
            foreach ($pathList as $path) {
                $tryPath = realpath($path.DIRECTORY_SEPARATOR.$template);
                if (file_exists($tryPath) === true) {
                    return $tryPath;
                }
            }
            return false;
        }

        private function log($msg) {
            $this->log[] = $msg;
        }
        private function handleError($message) {
            if ($this->throwExceptions === true) {
                throw new \Exception($message);
            }
            $this->log($message);
            if ($this->returnErrors === true) {
                return '<p class="'.$this->errorHtmlClass.'-wrapper"><span class="'.$this->errorHtmlClass.'">'.$message.'</span></p>';
            }
            return '';

        }
	}
?>
