<?php if ( ! defined('EXT')) exit('Invalid file request.');

/**
 * Tweedee model.
 *
 * @author			Stephen Lewis (http://github.com/experience/)
 * @copyright		Experience Internet
 * @package			Tweedee
 * @version 		0.2.0
 */

require_once PATH_THIRD .'tweedee/classes/tweedee_criterion' .EXT;

class Tweedee_model extends CI_Model {
	
    private $_base_search_url;
	private $_ee;
	private $_module_base_url;
	private $_package_name;
	private $_package_theme_url;
	private $_package_version;
	private $_site_id;
	
	
	/* --------------------------------------------------------------
	 * PUBLIC METHODS
	 * ------------------------------------------------------------ */

	/**
	 * Constructor.
	 *
	 * @access	public
	 * @param 	string		$package_name		The package name. Used during testing.
	 * @param	string		$package_version	The package version. Used during testing.
     * @param   string      $base_search_url    The base search URL. Used during testing.
	 * @return	void
	 */
	public function __construct($package_name = '', $package_version = '', $base_search_url = '')
	{
		parent::__construct();

		$this->_ee 				=& get_instance();
        $this->_base_search_url = $base_search_url ? $base_search_url : 'http://search.twitter.com/search.json?q=';
		$this->_package_name	= $package_name ? $package_name : 'tweedee';
		$this->_package_title	= 'Tweedee';
		$this->_package_version	= $package_version ? $package_version : '0.2.0';
	}


    /**
     * Builds the Twitter search URL.
     *
     * @access  public
     * @param   array       $criteria       An array of Tweedee_criterion objects.
     * @return  string
     */
    public function build_search_url(Array $criteria = array())
    {
        // Get out early.
        if ( ! $criteria)
        {
            return '';
        }

        $url_criteria = array();
        foreach ($criteria AS $criterion)
        {
            // This really should never happen.
            if ( ! $criterion instanceof Tweedee_criterion)
            {
                throw new Exception($this->_ee->lang->line('exception__invalid_search_criterion_type'));
            }

            // This is similarly exceptional.
            if ( ! $criterion->to_search_string())
            {
                throw new Exception($this->_ee->lang->line('exception__empty_search_criterion_string'));
            }

            $url_criteria[] = $criterion->to_search_string();
        }

        return $this->_base_search_url .implode('&', $url_criteria);
    }

    /**
     * Returns the 'base' query string for all module URLs.
     *
     * @access  public
     * @return  string
     */
    public function get_module_base_querystring()
    {
        return 'C=addons_modules' .AMP .'M=show_module_cp' .AMP .'module=' .$this->get_package_name();
    }


	/**
	 * Returns the package name.
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_package_name()
	{
		return $this->_package_name;
	}
	
	
	/**
	 * Returns the URL of the themes folder.
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_package_theme_url()
	{
		if ( ! $this->_package_theme_url)
		{
			$theme_url = $this->_ee->config->item('theme_folder_url');
			$theme_url = substr($theme_url, -1) == '/'
				? $theme_url .'third_party/'
				: $theme_url .'/third_party/';
			
			$this->_package_theme_url = $theme_url .strtolower($this->get_package_name()) .'/';
		}
		
		return $this->_package_theme_url;
	}
	
	
    /**
     * Retrieves the search criteria from the POST data.
     *
     * @access  public
     * @return  array
     */
    public function get_search_criteria_from_post_data()
    {
        $input_criteria = $this->_ee->input->post('search_criteria', array());
        $return_criteria = array();

        foreach ($input_criteria AS $input_criterion)
        {
            if ( ! is_array($input_criterion)
                OR ! array_key_exists('criterion_type', $input_criterion)
                OR ! array_key_exists('criterion_value', $input_criterion)
                OR ! $input_criterion['criterion_value']
                OR ! Tweedee_criterion::is_valid_criterion_type($input_criterion['criterion_type']))
            {
                continue;
            }

            $return_criteria[] = new Tweedee_criterion($input_criterion);
        }

        return $return_criteria;
    }


	/**
	 * Returns the package version.
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_package_version()
	{
		return $this->_package_version;
	}
	
	
	/**
	 * Returns the site ID.
	 *
	 * @access	public
	 * @return	string
	 */
	public function get_site_id()
	{
		if ( ! $this->_site_id)
		{
			$this->_site_id = $this->_ee->config->item('site_id');
		}
		
		return $this->_site_id;
	}


	/**
	 * Installs the module.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function install_module()
	{
		$this->install_module_register();
		$this->install_module_search_criteria_table();
		
		return TRUE;
	}
	
	
	/**
	 * Register the module in the database.
	 *
	 * @access	public
	 * @return	void
	 */
	public function install_module_register()
	{
		$this->_ee->db->insert('modules', array(
			'has_cp_backend'		=> 'y',
			'has_publish_fields'	=> 'n',
			'module_name'			=> $this->get_package_name(),
			'module_version'		=> $this->get_package_version()
		));
	}


	/**
	 * Creates the module settings table.
	 *
	 * @access	public
	 * @return	void
	 */
	public function install_module_search_criteria_table()
	{
		$this->_ee->load->dbforge();

		// Table columns.
		$columns = array(
			'criterion_id' => array(
				'auto_increment'	=> TRUE,
				'constraint'		=> 10,
				'type'				=> 'INT',
				'unsigned'			=> TRUE
			),
			'site_id' => array(
				'constraint'		=> 5,
				'type'				=> 'INT',
				'unsigned'			=> TRUE
			),
			'criterion_type' => array(
				'constraint'		=> 32,
				'type'				=> 'VARCHAR'
			),
			'criterion_value' => array(
				'constraint'		=> 255,
				'type'				=> 'VARCHAR'
			)
		);

		$this->_ee->dbforge->add_field($columns);
		$this->_ee->dbforge->add_key('criterion_id', TRUE);
		$this->_ee->dbforge->create_table('tweedee_search_criteria', TRUE);
	}


	/**
	 * Loads the search criteria.
	 *
	 * @access	public
	 * @return	array
	 */
	public function load_search_criteria()
	{
		$db_result = $this->_ee->db->select('criterion_id, criterion_type, criterion_value')
			->get_where('tweedee_search_criteria', array('site_id' => $this->get_site_id()));

		$criteria = array();

		if ( ! $db_result->num_rows())
		{
			return $criteria;
		}

		foreach ($db_result->result_array() AS $db_row)
		{
            $criteria[] = new Tweedee_criterion($db_row);
		}

		return $criteria;
	}


	/**
	 * Saves the search criteria submitted by the user.
	 *
	 * @access	public
     * @param   array       $criteria       An array of Tweedee_criterion objects.
	 * @return	bool
	 */
	public function save_search_criteria(Array $criteria = array())
	{
        // Validate the search criteria.
        foreach ($criteria AS $criterion)
        {
            if ( ! $criterion instanceof Tweedee_criterion
                OR ! $criterion->get_criterion_type()
                OR ! $criterion->get_criterion_value())
            {
                return FALSE;
            }
        }

		$site_id = $this->get_site_id();
        $this->_ee->db->delete('tweedee_search_criteria', array('site_id' => $site_id));
        $base_insert_data = array('site_id' => $site_id);

        foreach ($criteria AS $criterion)
        {
            $insert_data = array_merge($base_insert_data, $criterion->to_array());
            $this->_ee->db->insert('tweedee_search_criteria', $insert_data);
        }

		return TRUE;
	}
	
	
	/**
	 * Uninstalls the module.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function uninstall_module()
	{
		$this->_ee->load->dbforge();

		// Retrieve the module information.
		$db_module = $this->_ee->db
			->select('module_id')
			->get_where('modules', array('module_name' => $this->get_package_name()), 1);
		
		if ($db_module->num_rows() !== 1)
		{
			return FALSE;
		}
		
		// Delete module from the module_member_groups table.
		$this->_ee->db->delete('module_member_groups', array('module_id' => $db_module->row()->module_id));
		
		// Delete the module from the modules table.
		$this->_ee->db->delete('modules', array('module_name' => $this->get_package_name()));

		// Drop the 'search criteria' table.
		$this->_ee->dbforge->drop_table('tweedee_search_criteria');
		
		return TRUE;
	}
	
	
	/**
	 * Updates the module.
	 *
	 * @access	public
	 * @param 	string		$installed_version		The installed version.
	 * @param 	string		$package_version		The package version.
	 * @return	bool
	 */
	public function update_module($installed_version = '', $package_version = '')
	{
		if (version_compare($installed_version, $package_version, '>='))
		{
			return FALSE;
		}
		
		return TRUE;
	}
	
}

/* End of file		: tweedee_model.php */
/* File location	: third_party/tweedee/models/tweedee_model.php */
