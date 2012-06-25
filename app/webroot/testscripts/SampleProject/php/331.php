<?php
set_include_path(get_include_path() . PATH_SEPARATOR . "drivers/php");
require_once 'Testing/Selenium.php';
require_once 'Testing/BRUnit.php';

class Example extends BRUnit {
    function testMyTestCase() {
        $this->selenium->open("/?utm_source=bromine&utm_medium=testscripts&utm_content=brunit&utm_campaign=php&utm_term=brunit");

        $this->verifyTrue($this->selenium->isElementPresent("//div[@id='column2']/h1"));
        $this->customCommand('waiting','$this->customCommand can be used to write these messages, just use \'waiting\' as status if you don\'t it to influence the status of the test','','');
        $this->customCommand('waiting','function $this->waiting() will ignore the command just before it self. This is useful when working this ajax or just waiting for an element to be present','','');
        $this->verifyTrue($this->selenium->isElementPresent('pagesssss'));
        $this->waiting();
        $this->verifyTrue(1==1, 'passed');

        $this->verifyTrue(true,'passed');

        $this->verifyFalse(1==2, 'passed');
        $this->verifyFalse(false, 'passed');

        $this->verifyEquals(1, 1, 'passed');
        $this->verifyEquals('ss', 'ss', 'passed');
        $this->verifyEquals(array('ss', false, 1, 'hh'), array('ss', false, 1, 'hh'), 'passed');
        $this->verifyEquals(true, true, 'passed');
        $this->verifyEquals(3.31231231, 3.31231231, 'passed');

        $this->verifyNotEquals(1, 2,'passed');
        $this->verifyNotEquals('ss', 'sss','passed');
        $this->verifyNotEquals(array('ss', false, 1), array('sss', false, 1),'passed');
        $this->verifyNotEquals(true, false, 'passed');
        $this->verifyNotEquals(3.31231231, 3.312312319,'passed');

        $this->verifyNotEquals(1, '1', 'passed');
        $this->verifyNotEquals(1, true, 'passed');
        $this->verifyNotEquals('1', true,'passed');
        $this->verifyNotEquals(array(), 'array()', 'passed');
        $this->verifyNotEquals(3.000001, 3,'passed');

        $this->assertTrue(true,'An assert will break test if it fails');
        $this->assertEquals(3, 3,'An assert will break test if it fails');

        $this->verifyNotEquals(3.000001, 3, 'passed');
        $url = $this->selenium->getLocation();
        $this->verifyNotEquals('url='.$url, "qwertyuiopåäsdfghjklæø'<zxcvbnm,.-\\<>;,:.-_*'^^~¨´|``´´?+1234567890=}][{=)(/&%¤#\"!§½@£$", "another description");

    }
}
startTest("Example" , $argv);
?>