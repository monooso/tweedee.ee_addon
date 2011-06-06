<?php if ( ! defined('BASEPATH')) exit('Invalid file request.');

/**
 * Tweedee module control panel.
 *
 * @author			Stephen Lewis <stephen@experienceinternet.co.uk>
 * @copyright		Experience Internet
 * @package			Tweedee
 */

class Tweedee_mcp {

    private $_base_qs;
    private $_base_url;
	private $_ee;
	private $_model;
    private $_theme_url;
	
	
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

        // Basic stuff required by every view.
        $this->_base_qs     = $this->_model->get_module_base_querystring();
        $this->_base_url    = BASE .AMP .$this->_base_qs;
        $this->_theme_url   = $this->_model->get_package_theme_url();

        $this->_ee->load->helper('form');
        $this->_ee->load->library('table');

		$this->_ee->cp->set_breadcrumb($this->_base_url, $this->_ee->lang->line('tweedee_module_name'));
		$this->_ee->cp->add_to_foot('<script type="text/javascript" src="' .$this->_theme_url .'js/libs/jquery.roland.js"></script>');
		$this->_ee->cp->add_to_foot('<script type="text/javascript" src="' .$this->_theme_url .'js/cp.js"></script>');
		$this->_ee->javascript->compile();

		$this->_ee->cp->add_to_head('<link rel="stylesheet" type="text/css" href="' .$this->_theme_url .'css/cp.css" />');

		$nav_array = array(
			'nav_search_criteria'	=> $this->_base_url .AMP .'method=search_criteria',
			'nav_search_results'	=> $this->_base_url .AMP .'method=search_results'
		);

		$this->_ee->cp->set_right_nav($nav_array);
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
		if ($this->_model->save_search_criteria($this->_model->get_search_criteria_from_post_data()))
		{
			$this->_ee->session->set_flashdata('message_success', $this->_ee->lang->line('msg_search_criteria_saved'));
			$this->_ee->functions->redirect($this->_base_url .AMP .'method=search_results');
		}
		else
		{
			$this->_ee->session->set_flashdata('message_failure', $this->_ee->lang->line('msg_search_criteria_not_saved'));
			$this->_ee->functions->redirect($this->_base_url .AMP .'method=search_criteria');
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
		$view_vars = array(
            'cp_page_title'     => $this->_ee->lang->line('hd_search_results'),
			'theme_url'			=> $this->_theme_url
		);

		return $this->_ee->load->view('search_results', $view_vars, TRUE);
	}


	/**
	 * Module 'search criteria' page.
	 *
	 * @access	public
	 * @return	string
	 */
	public function search_criteria()
	{
        $type_ids = Tweedee_criterion::get_all_criterion_types();
        $criterion_types = array('' => $this->_ee->lang->line('lbl_select_criterion_type'));

        foreach ($type_ids AS $type_id)
        {
            $criterion_types[$type_id] = $this->_ee->lang->line('lbl_criterion_' .$type_id);
        }

		$view_vars = array(
            'cp_page_title'     => $this->_ee->lang->line('hd_search_criteria'),
			'criterion_types'	=> $criterion_types,
            'form_action'       => $this->_base_qs .AMP .'method=save_search_criteria',
			'search_criteria'	=> $this->_model->load_search_criteria(),
			'theme_url'			=> $this->_theme_url
		);

		return $this->_ee->load->view('search_criteria', $view_vars, TRUE);
	}
	
}


/* End of file		: mcp.tweedee.php */
/* File location	: third_party/tweedee/mcp.tweedee.php */
