<?php

/**
 * Tweedee module control panel tests.
 *
 * @author			Stephen Lewis <stephen@experienceinternet.co.uk>
 * @copyright		Experience Internet
 * @package			Tweedee
 */

require_once PATH_THIRD .'tweedee/mcp.tweedee' .EXT;
require_once PATH_THIRD .'tweedee/tests/mocks/mock.tweedee_model' .EXT;

class Test_tweedee_cp extends Testee_unit_test_case {
	
    private $_base_qs;
	private $_model;
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
		$this->_model				= $this->_get_mock('model');
		$this->_ee->tweedee_model	=& $this->_model;

		// Called from the constructor.
		$this->_base_qs = 'base';
        $this->_base_url = BASE .AMP .$this->_base_qs;
		$this->_model->setReturnValue('get_module_base_querystring', $this->_base_qs);

		$this->_subject = new Tweedee_mcp();
	}


	public function test__save_search_criteria__success()
	{
        $criteria = 'Search criteria';
        $this->_model->expectOnce('get_search_criteria_from_post_data');
        $this->_model->setReturnValue('get_search_criteria_from_post_data', $criteria);

		$this->_model->expectOnce('save_search_criteria', array($criteria));
		$this->_model->setReturnValue('save_search_criteria', TRUE);

		$message = 'saved';
		$this->_ee->lang->setReturnValue('line', $message, array('msg_search_criteria_saved'));
		$this->_ee->session->expectOnce('set_flashdata', array('message_success', $message));
		$this->_ee->functions->expectOnce('redirect', array($this->_base_url .AMP .'method=search_results'));
	
		$this->_subject->save_search_criteria();
	}
	

	public function test__save_search_criteria__failure()
	{
        $criteria = 'Search criteria';
        $this->_model->expectOnce('get_search_criteria_from_post_data');
        $this->_model->setReturnValue('get_search_criteria_from_post_data', $criteria);

		$this->_model->expectOnce('save_search_criteria', array($criteria));
		$this->_model->setReturnValue('save_search_criteria', FALSE);

		$message = 'not saved';
		$this->_ee->lang->setReturnValue('line', $message, array('msg_search_criteria_not_saved'));
		$this->_ee->session->expectOnce('set_flashdata', array('message_failure', $message));
		$this->_ee->functions->expectOnce('redirect', array($this->_base_url .AMP .'method=search_criteria'));
	
		$this->_subject->save_search_criteria();
	}
	
}


/* End of file		: test.mcp_tweedee.php */
/* File location	: third_party/tweedee/tests/test.mcp_tweedee.php */
