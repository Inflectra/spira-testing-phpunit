<?php
/**
 * Provides a facade for recording automated results against SpiraTest
 * 
 * @author		Inflectra Corporation
 * @version		2.3.0
 *
 */
 
 class SpiraListener_ImportExport
 {
  /* Class constants */
  
  //define the web-service namespace and URL suffix constants
	const WEB_SERVICE_NAMESPACE = "http://www.inflectra.com/SpiraTest/Services/v2.2/";
	const WEB_SERVICE_URL_SUFFIX = "/Services/v2_2/ImportExport.asmx";

  /* Class properties */
  
  /*
    The base url of the Spira web service
  */
  protected $baseUrl;
  public function getBaseUrl ()
  {
    return $this->baseUrl;
  }
  public function setBaseUrl ($value)
  {
    $this->baseUrl = $value;
  }

  /*
    The user name of the Spira account accessing the web service
  */
  protected $userName;
  public function getUserName ()
  {
    return $this->userName;
  }
  public function setUserName ($value)
  {
    $this->userName = $value;
  }

  /*
    The password of the Spira account accessing the web service
  */
  protected $password;
  public function getPassword ()
  {
    return $this->password;
  }
  public function setPassword ($value)
  {
    $this->password = $value;
  }
  
  /*
    The ID of the project we're returning results against
  */
  protected $projectId;
  public function getProjectId ()
  {
    return $this->projectId;
  }
  public function setProjectId ($value)
  {
    $this->projectId = $value;
  }
  
  /* Function that actually records the results in SpiraTest */
  public function recordAutomated($testCaseId, $releaseId, $testSetId, $startDate, $endDate, $executionStatusId, $testRunnerName, $testName, $assertCount, $message, $stackTrace)
  { 
    //Instantiante the SOAP client class
    $url = $this->baseUrl + SpiraListener_ImportExport::WEB_SERVICE_URL_SUFFIX;
    $soapClient = new SoapClient(null, array('location' => $url, 'uri' => SpiraListener_ImportExport::WEB_SERVICE_NAMESPACE));
    
    //Now call the test run method
    $params = array(
      $this->userName,
      $this->password,
      $this->projectId,
      -1,
      $testCaseId,
      $releaseId,
      $testSetId,
      $startDate,
      $endDate,
      $executionStatusId,
      $testRunnerName,
      $testName,
      $assertCount,
      $message,
      $stackTrace
      );
    $testRunId = $soapClient->__soapCall("TestRun_RecordAutomated2", $params);
    
    return $testRunId;
  }
 }