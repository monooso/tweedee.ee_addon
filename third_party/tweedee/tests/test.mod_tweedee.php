<?php

/**
 * Tweedee module tests.
 *
 * @author			Stephen Lewis <stephen@experienceinternet.co.uk>
 * @copyright		Experience Internet
 * @package			Tweedee */

require_once PATH_THIRD .'tweedee/mcp.tweedee' .EXT;
require_once PATH_THIRD .'tweedee/tests/mocks/mock.tweedee_model' .EXT;

class Test_tweedee extends Testee_unit_test_case {
	
	/* --------------------------------------------------------------
	 * PRIVATE PROPERTIES
	 * ------------------------------------------------------------ */
	
	/**
	 * Model.
	 *
	 * @access	private
	 * @var		object
	 */
	private $_model;
	
	/**
	 * The test subject.
	 *
	 * @access	private
	 * @var		object
	 */
	private $_subject;
	
	
	
	/* --------------------------------------------------------------
	 * PUBLIC METHODS
	 * ------------------------------------------------------------ */
	
	/**
	 * Constructor.
	 *
	 * @access	public
	 * @return	void
	 */
	public function setUp()
	{
		parent::setUp();
		
		// Generate the mock model.
		Mock::generate('Mock_tweedee_model', get_class($this) .'_mock_model');
		$this->_model = $this->_get_mock('model');
		
		// The test subject.
		$this->_subject = new Tweedee();
	}
	
}


/* End of file		: test.mod_tweedee.php */
/* File location	: third_party/tweedee/tests/test.mod_tweedee.php */