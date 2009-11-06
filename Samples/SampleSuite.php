<?php
/**
 * Passes a list of tests to be executed to PHPUnit and adds the custom SpiraTest Listener
 * 
 * @author		Inflectra Corporation
 * @version		2.2.0
 *
 */
 
 require_once 'PHPUnit/Framework.php';
 require_once 'PHPUnit/TextUI/ResultPrinter.php';
 require_once './SimpleTest.php';
 //require_once 'SimpleTestListener.php';
 
 // Create a test suite that contains the tests
 // from the ArrayTest class
  $suite = new PHPUnit_Framework_TestSuite('SimpleTest');
 
 // Create a test result and attach a SimpleTestListener
 // object as an observer to it.
 $result = new PHPUnit_Framework_TestResult;
 $textPrinter = new PHPUnit_TextUI_ResultPrinter;
 $result->addListener($textPrinter);
 //$result->addListener(new SimpleTestListener);
 
 // Run the tests and print the results
 $result = $suite->run($result);
 $textPrinter->printResult($result);

 ?>