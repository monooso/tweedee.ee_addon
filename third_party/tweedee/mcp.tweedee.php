<?php if ( ! defined('BASEPATH')) exit('Invalid file request.');

/**
 * Tweedee module control panel.
 *
 * @author			Stephen Lewis <stephen@experienceinternet.co.uk>
 * @copyright		Experience Internet
 * @package			Tweedee
 */

class Tweedee_mcp {

	/* --------------------------------------------------------------
	 * PRIVATE PROPERTIES
	 * ------------------------------------------------------------ */
	
	/**
	 * The "base" navigation URL to which method names are appended.
	 *
	 * @access	private
	 * @var		string
	 */
	private $_base_nav_url;

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

		$this->_base_nav_url = BASE .AMP .'C=addons_modules' .AMP .'M=show_module_cp' .AMP .'module=' .strtolower($this->_model->get_package_name()) .AMP .'method=';

		// Module navigation.
		$this->_ee->cp->set_right_nav(array(
			'nav_search_settings'	=> $this->_base_nav_url .'search_settings',
			'nav_search_results'	=> $this->_base_nav_url .'search_results'
		));

		// Base breadcrumb.
		$this->_ee->cp->set_breadcrumb($this->_base_nav_url .'index', $this->_ee->lang->line('tweedee_module_name'));
	}
	
	
	/**
	 * Module index page.
	 *
	 * @access	public
	 * @return	string
	 */
	public function index()
	{
		return $this->search_settings();
	}


	/**
	 * Module 'search results' page.
	 *
	 * @access	public
	 * @return	string
	 */
	public function search_results()
	{
		return '<p>Search results.</p>';
	}


	/**
	 * Module 'search settings' page.
	 *
	 * @access	public
	 * @return	string
	 */
	public function search_settings()
	{
		// Retrieve the package theme URL.
		$theme_url = $this->_model->get_package_theme_url();

		// Load our happy helpers.
		$this->_ee->load->helper('form');

		// Include the CSS and JS.
		$this->_ee->cp->load_package_css('cp');
		$this->_ee->cp->load_package_js('cp');

		// Set the page title.
		$this->_ee->cp->set_variable('cp_page_title', $this->_ee->lang->line('hd_search_settings'));

		// Assemble the view variables.
		$view_vars = array(
			'theme_url'		=> $theme_url
		);

		// Load and return the view.
		return $this->_ee->load->view('search', $view_vars, TRUE);
	}
	
}


/* End of file		: mcp.tweedee.php */
/* File location	: third_party/tweedee/mcp.tweedee.php */
