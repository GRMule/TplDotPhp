<?php
namespace GRMule\TplDptPhp;
trait MinumumUtilities {
    private $templateValues = array();
    private $activeTemplates = array();

    public function __call($name, $arguments) {
        return (
        is_array($arguments) === true  && is_string(reset($arguments)) == true ?
            implode(' ', $arguments) : (
        is_string($arguments) === true ?
            $arguments : ''
        )
        );
    }
    public function startTemplate($file) {
        $this->activeTemplates[] = $file;
    }
    public function endTemplate() {
        $template = array_pop($this->activeTemplates);
        if (array_key_exists($template, $this->templateValues) === true)
            unset($this->templateValues[$template]);
    }
    public function uid ($name) {
        $template = end($this->activeTemplates);
        $name = $this->toSlug($name);
        if (array_key_exists($template, $this->templateValues) === false)
            $this->templateValues[$template] = array();
        if (array_key_exists('uids', $this->templateValues[$template]) === false)
            $this->templateValues[$template]['uids'] = array();
        if (array_key_exists($name, $this->templateValues[$template]['uids']) === true)
            return $this->templateValues[$template]['uids'][$name];
        $this->templateValues[$template]['uids'][$name] = $name.\cmcCMS\randomString(5);
        return $this->templateValues[$template]['uids'][$name];
    }
}
?>