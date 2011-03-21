<?php if ( ! defined('BASEPATH')) exit('Direct script access is not permitted.');

/**
 * Tweedee module.
 *
 * @author			Stephen Lewis <stephen@experienceinternet.co.uk>
 * @copyright		Experience Internet
 * @package			Tweedee */

class Tweedee {
	
	/* --------------------------------------------------------------
	 * PUBLIC PROPERTIES
	 * ------------------------------------------------------------ */
	
	/**
	 * Return data.
	 *
	 * @access	public
	 * @var 	string
	 */
	public $return_data = '';
	
	
	/* --------------------------------------------------------------
	 * PRIVATE PROPERTIES
	 * ------------------------------------------------------------ */
	
	/**
	 * ExpressionEngine object reference.
	 *
	 * @access	private
	 * @var		object
	 */
	private $_ee;
	
	/**
	 * Model.
	 *
	 * @access	private
	 * @var		object
	 */
	private $_model;
	
	
	
	/* --------------------------------------------------------------
	 * PUBLIC METHODS
	 * ------------------------------------------------------------ */
	
	/**
	 * Constructor.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		$this->_ee =& get_instance();
		$this->_ee->load->model('tweedee_model');
		$this->_model = $this->_ee->tweedee_model;
	}
	
	
		
	
		/* --------------------------------------------------------------
	 * TEMPLATE TAG METHODS
	 * ------------------------------------------------------------ */
	
		/**
	 * 'tweets' template tag.
	 *
	 * @access	public
	 * @return	string
	 */
	public function tweets()
	{
		
	}
	
	
		}


/* End of file		: mod.tweedee.php */
/* File location	: third_party/tweedee/mod.tweedee.php */