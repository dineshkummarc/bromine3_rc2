<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "drivers/php");
require_once 'Testing/Selenium.php';
require_once 'Testing/BRUnit.php';

class Example extends BRUnit
{
  function testMyTestCase()
  {
    $this->selenium->open("/?utm_source=bromine&utm_medium=testscripts&utm_content=featurelist&utm_campaign=php&utm_term=feature_list");
    $this->selenium->click("link=Features");
    $this->selenium->waitForPageToLoad("30000");
    $this->verifyEquals("Bromine an opensource QA tool | Features", $this->selenium->getTitle());
    $this->verifyTrue($this->selenium->isElementPresent("//div[@id='column2']/h1[1]"));
    $this->verifyTrue($this->selenium->isTextPresent("Integrates with Selenium"));
    $this->verifyTrue($this->selenium->isElementPresent("link=Bromine forum"));
  }
}
startTest("Example" , $argv);
?>