<?php
/**
 * Passes a list of tests to be executed to PHPUnit and adds the custom SpiraTest Listener
 * 
 * @author		Inflectra Corporation
 * @version		2.3.0
 *
 */
 
 require_once 'PHPUnit/Framework.php';
 require_once 'PHPUnit/TextUI/ResultPrinter.php';
 require_once './SimpleTest.php';
 require_once '../SpiraListener/Listener.php';
 
 // Create a test suite that contains the tests
 // from the ArrayTest class
  $suite = new PHPUnit_Framework_TestSuite('SimpleTest');
 
 //Create a new SpiraTest listener instance and specify the connection info
 $spiraListener = new SpiraListener_Listener;
 //$spiraListener->baseUrl = 'http://localhost/SpiraTest';
 //$spiraListener->userName = 'fredbloggs';
 //$spiraListener->password = 'fredbloggs';
 //$spiraListener->projectId = 1;
 //$spiraListener->releaseId = 1;
 //$spiraListener->testSetId = 1;
 
 // Create a test result and attach the SpiraTest listener
 // object as an observer to it (as well as the default console text listener)
 $result = new PHPUnit_Framework_TestResult;
 $textPrinter = new PHPUnit_TextUI_ResultPrinter;
 $result->addListener($textPrinter);
 $result->addListener($spiraListener);
 
 // Run the tests and print the results
 $result = $suite->run($result);
 //$textPrinter->printResult($result);

 ?>