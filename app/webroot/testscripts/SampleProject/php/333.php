<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "drivers/php");
require_once 'Testing/Selenium.php';
require_once 'Testing/BRUnit.php';

class Example extends BRUnit
{
  function testMyTestCase()
  {
    $this->selenium->open("/?utm_source=bromine&utm_medium=testscripts&utm_content=screencast&utm_campaign=php&utm_term=screencasts");
    $this->selenium->click("link=Features");
    $this->selenium->waitForPageToLoad("30000");
    $this->verifyEquals("Bromine Screencast: Integration with Sauce Labs & Hudson CI", $this->selenium->getText("//div[@id='column2']/h1[2]"));
    $this->verifyTrue($this->selenium->isElementPresent("//div[@id='column2']/a[1]/img"));
    $this->verifyTrue($this->selenium->isElementPresent("//div[@id='column2']/h1[3]"));
    $this->verifyTrue($this->selenium->isElementPresent("//div[@id='column2']/a[2]/img"));
    echo "If you output anything in your testscript. It will apear as a notice here...";
  }
}
startTest("Example" , $argv);
?>