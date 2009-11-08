<?php
/**
 * Listens during PHPUnit test executions and reports the results back to SpiraTest/Team
 * 
 * @author		Inflectra Corporation
 * @version		2.3.0
 *
 */
 
 require_once 'PHPUnit/Framework.php';
 require_once 'ImportExport.php';
 
 class SpiraListener_Listener implements PHPUnit_Framework_TestListener
 {
  //General constants
  const DEFAULT_TEST_RUNNER_NAME = "PHPUnit";
  
  //SpiraTest execution status constants
  const EXECUTION_STATUS_ID_PASSED = 2;
  const EXECUTION_STATUS_ID_FAILED = 1;
  const EXECUTION_STATUS_ID_NOT_RUN = 3;
  const EXECUTION_STATUS_ID_CAUTION = 6;
  const EXECUTION_STATUS_ID_BLOCKED = 5;
  
  /* Class properties */
  
  /*
    The name of the test runner to report back to SpiraTest.
    If you are running a Selenium-RC web test, you might want to 
    set it to Selenium to distinguish from a true PHPUnit test
  */
  protected $testRunnerName = SpiraListener_Listener::DEFAULT_TEST_RUNNER_NAME;
  public function getTestRunnerName ()
  {
    return $this->testRunnerName;
  }
  public function setTestRunnerName ($value)
  {
    $this->testRunnerName = $value;
  }
  
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
  
    /*
    The ID of the release we're returning results against (optional)
  */
  protected $releaseId = -1;
  public function getReleaseId ()
  {
    return $this->releaseId;
  }
  public function setReleaseId ($value)
  {
    $this->releaseId = $value;
  }
  
    /*
    The ID of the test set we're returning results against (optional)
  */
  protected $testSetId = -1;
  public function getTestSetId ()
  {
    return $this->testSetId;
  }
  public function setTestSetId ($value)
  {
    $this->testSetId = $value;
  }

    /**
     * An error occurred.
     *
     * @param  PHPUnit_Framework_Test $test
     * @param  Exception              $e
     * @param  float                  $time
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
      //Not implemented, we just use endTest and check the status
    }

    /**
     * A failure occurred.
     *
     * @param  PHPUnit_Framework_Test                 $test
     * @param  PHPUnit_Framework_AssertionFailedError $e
     * @param  float                                  $time
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
      //Not implemented, we just use endTest and check the status
    }

    /**
     * Incomplete test.
     *
     * @param  PHPUnit_Framework_Test $test
     * @param  Exception              $e
     * @param  float                  $time
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
      //Not implemented, we just use endTest and check the status
    }

    /**
     * Skipped test.
     *
     * @param  PHPUnit_Framework_Test $test
     * @param  Exception              $e
     * @param  float                  $time
     * @since  Method available since Release 3.0.0
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
      //Not implemented, we just use endTest and check the status
    }

    /**
     * A test suite started.
     *
     * @param  PHPUnit_Framework_TestSuite $suite
     * @since  Method available since Release 2.2.0
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
      //Do nothing
    }

    /**
     * A test suite ended.
     *
     * @param  PHPUnit_Framework_TestSuite $suite
     * @since  Method available since Release 2.2.0
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
      //Let the user know that we've finished the whole suite
      printf ("\nTest Suite '%s' sent to SpiraTest\n", $suite->getName());
    }

    /**
     * A test started.
     *
     * @param  PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
      //Do nothing
    }

    /**
     * A test ended.
     *
     * @param  PHPUnit_Framework_Test $test
     * @param  float                  $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
      //Get the full test name (includes the spira id appended)
      $testNameAndId = $test->getName();
      $testComponents = split("__", $testNameAndId);
      if (count($testComponents >= 2))
      {
        //extract the test case id from the name (separated by two underscores)
        $testName = $testComponents[0];
        $testCaseId = (integer)$testComponents[1];
        
        //Now convert the execution status into the values expected by SpiraTest
        $executionStatusId = SpiraListener_Listener::EXECUTION_STATUS_ID_NOT_RUN;
        $assertCount = 0;
        $message = "test1";
        $stackTrace = "test2";
        $startDate = $time;
        $endDate = $time;
        
        //If the test was in the warning situation, report as Blocked
        if ($test instanceof PHPUnit_Framework_Warning)
        {
          $executionStatusId = EXECUTION_STATUS_ID_BLOCKED;
        }
        else
        {
           if ($test->getStatus() == PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED)
           {
            $executionStatusId = SpiraListener_Listener::EXECUTION_STATUS_ID_BLOCKED;
           }
           if ($test->getStatus() == PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE)
           {
            $executionStatusId = SpiraListener_Listener::EXECUTION_STATUS_ID_CAUTION;
           }
           if ($test->getStatus() == PHPUnit_Runner_BaseTestRunner::STATUS_PASSED)
           {
              $executionStatusId = SpiraListener_Listener::EXECUTION_STATUS_ID_PASSED;
           }
           if ($test->getStatus() == PHPUnit_Runner_BaseTestRunner::STATUS_FAILURE || $test->getStatus() == PHPUnit_Runner_BaseTestRunner::STATUS_ERROR)
           {
              $executionStatusId = SpiraListener_Listener::EXECUTION_STATUS_ID_FAILED;
           }
        }        
        
        //Send the results to SpiraTest
        $importExport = new SpiraListener_ImportExport();
        $importExport->setBaseUrl($this->baseUrl);
        $importExport->setUserName($this->userName);
        $importExport->setPassword($this->password);
        $importExport->setProjectId($this->projectId);
        $testRunId = $importExport->recordAutomated(
            $testCaseId, $this->releaseId, $this->testSetId, $startDate, $endDate, $executionStatusId,
            $this->testRunnerName, $testName, $assertCount, $message, $stackTrace);
            
        //Display the message letting the user know that the results were sent
        printf ("\nTest Case '%s' (TC000%d) sent to SpiraTest with status %d - Test Run (TR000%d).\n", $testName, $testCaseId, $executionStatusId, $testRunId);
      }
    /*
        if (!$test instanceof PHPUnit_Framework_Warning) {
            if ($test->getStatus() == PHPUnit_Runner_BaseTestRunner::STATUS_PASSED) {
                $ifStatus   = array('assigned', 'new', 'reopened');
                $newStatus  = 'closed';
                $message    = 'Automatically closed by PHPUnit (test passed).';
                $resolution = 'fixed';
                $cumulative = TRUE;
            }

            else if ($test->getStatus() == PHPUnit_Runner_BaseTestRunner::STATUS_FAILURE) {
                $ifStatus   = array('closed');
                $newStatus  = 'reopened';
                $message    = 'Automatically reopened by PHPUnit (test failed).';
                $resolution = '';
                $cumulative = FALSE;
            }

            else {
                return;
            }

            $name    = $test->getName();
            $tickets = PHPUnit_Util_Test::getTickets(get_class($test), $name);

            foreach ($tickets as $ticket) {
                // Remove this test from the totals (if it passed).
                if ($test->getStatus() == PHPUnit_Runner_BaseTestRunner::STATUS_PASSED) {
                    unset($this->ticketCounts[$ticket][$name]);
                }

                // Only close tickets if ALL referenced cases pass
                // but reopen tickets if a single test fails.
                if ($cumulative) {
                    // Determine number of to-pass tests:
                    if (count($this->ticketCounts[$ticket]) > 0) {
                        // There exist remaining test cases with this reference.
                        $adjustTicket = FALSE;
                    } else {
                        // No remaining tickets, go ahead and adjust.
                        $adjustTicket = TRUE;
                    }
                } else {
                    $adjustTicket = TRUE;
                }

                if ($adjustTicket && in_array($ticketInfo[3]['status'], $ifStatus)) {
                    $this->updateTicket($ticket, $newStatus, $message, $resolution);
                }
            }
        }*/
    }
 }
 ?>