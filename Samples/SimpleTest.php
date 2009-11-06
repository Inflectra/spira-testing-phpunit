<?php
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Some simple tests using the ability to return results back to SpiraTest
 * 
 * @author		Inflectra Corporation
 * @version		2.2.0
 *
 */
 
/*@SpiraTestConfiguration(
	url="http://sandman/SpiraTest",
	login="fredbloggs",
	password="fredbloggs",
	projectId=1,
	releaseId=1,
	testSetId=1
)*/
class SimpleTest extends PHPUnit_Framework_TestCase
{
	protected $fValue1;
	protected $fValue2;

	/**
	 * Sets up the unit test
	 */
	protected function setUp()
	{
		$this->fValue1= 2;
		$this->fValue2= 3;
	}

	/**
	 * Tests the addition of the two values
	 */
	/*@Test
	@SpiraTestCase(testCaseId=5)*/
	public function testAdd()
	{
		$result = $this->fValue1 + $this->fValue2;

		// forced failure result == 5
		$this->assertTrue ($result == 6);
	}

	/**
	 * Tests division by zero
	 */
   /*
	@Test
	@SpiraTestCase(testCaseId=5)*/
	public function testDivideByZero()
	{
		$zero = 0;
		$result = 8 / $zero;
		$result++; // avoid warning for not using result
	}

	/**
	 * Tests two equal values
	 */
   /*
	@Test
	@SpiraTestCase(testCaseId=6)*/
	public function testEquals()
	{
		$this->assertEquals(12, 12);
		$this->assertEquals(12.0, 12.0);
    $num1 = 12;
    $num2 = 12;
		$this->assertEquals($num1, $num2);

		$this->assertEquals("Size", 12, 13);
		$this->assertEquals("Capacity", 12.0, 11.99, 0.0);
	}

	/**
	 * Tests success
	 */
   /*
	@Test
	@SpiraTestCase(testCaseId=6)*/
	public function testSuccess()
	{
		//Successful test
		$this->assertEquals(12, 12);
	}

	/**
	 * Entry point for command line execution
	 * 
	 * @param args	The command line arguments
	 */
	/*public static void main (String[] args)
	{
		//Instantiate the JUnit core
		JUnitCore core = new JUnitCore();

		//Add the custom SpiraTest listener
		core.addListener(new SpiraTestListener());

		//Finally run the test fixture
		core.run (SimpleTest.class);
	}*/

	/**
	 * Entry point for JUnit 4.x runners
	 * 
	 * @return		Handle to the test framework
	 */
   /*
	public static junit.framework.Test suite() 
	{
		return new JUnit4TestAdapter(SimpleTest.class);
	}*/
}
?>
