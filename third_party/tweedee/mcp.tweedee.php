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

		$this->_base_nav_url = $this->_model->get_module_base_url();

		// Module navigation.
		$this->_ee->cp->set_right_nav(array(
			'nav_search_criteria'	=> $this->_base_nav_url .'search_criteria',
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
		return $this->search_criteria();
	}


	/**
	 * Saves the submitted search criteria.
	 *
	 * @access	public
	 * @return	void
	 */
	public function save_search_criteria()
	{
		if ($this->_model->save_search_criteria())
		{
			$this->_ee->session->set_flashdata('message_success', $this->_ee->lang->line('msg_search_criteria_saved'));
			$this->_ee->functions->redirect($this->_base_nav_url .'search_results');
		}
		else
		{
			$this->_ee->session->set_flashdata('message_failure', $this->_ee->lang->line('msg_search_criteria_not_saved'));
			$this->_ee->functions->redirect($this->_base_nav_url .'search_criteria');
		}
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
	 * Module 'search criteria' page.
	 *
	 * @access	public
	 * @return	string
	 */
	public function search_criteria()
	{
		// Retrieve the package theme URL.
		$theme_url = $this->_model->get_package_theme_url();

		// Load our happy helpers.
		$this->_ee->load->helper('form');

		// Include the CSS and JS.
		$this->_ee->cp->load_package_css('cp');
		$this->_ee->cp->load_package_js('cp');

		// Set the page title.
		$this->_ee->cp->set_variable('cp_page_title', $this->_ee->lang->line('hd_search_criteria'));

		// Assemble the view variables.
		$view_vars = array(
			'form_action'	=> substr($this->_base_nav_url, strlen(BASE .AMP)) .'save_search_criteria',
			'theme_url'		=> $theme_url
		);

		// Load and return the view.
		return $this->_ee->load->view('search', $view_vars, TRUE);
	}
	
}


/* End of file		: mcp.tweedee.php */
/* File location	: third_party/tweedee/mcp.tweedee.php */
