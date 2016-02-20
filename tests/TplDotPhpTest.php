<?php

use GRMule\TplDptPhp;

class TplDotPhpTest extends PHPUnit_Framework_TestCase {

    public function testEngineNoFindTemplateWithNoInitPaths() {
        $nacho = new PhpEngine([]);
        $this->assertFalse($nacho->exists('poo'));
    }

}