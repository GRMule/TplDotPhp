<?php
    namespace grmule\tpldotphp;
    interface iTemplateUtilities {
        public function startTemplate($file);
        public function endTemplate();
        public function uid ($name);
    }
?>