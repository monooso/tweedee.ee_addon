<?php if ( ! defined('BASEPATH')) exit('Invalid file request.');

/**
 * Tweedee module update.
 *
 * @author			Stephen Lewis <stephen@experienceinternet.co.uk>
 * @copyright		Experience Internet
 * @package			Tweedee */

class Tweedee_upd {
	
	/* --------------------------------------------------------------
	 * PRIVATE PROPERTIES
	 * ------------------------------------------------------------ */

	/**
	 * Version.
	 *
	 * @access	public
	 * @var		string
	 */
	public $version;
	
	
	
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

		// We need to explicitly set the package path.
		$this->_ee->load->add_package_path(PATH_THIRD .'tweedee/');
		$this->_ee->load->model('tweedee_model');
		$this->_model = $this->_ee->tweedee_model;
		
		// Set the version.
		$this->version = $this->_model->get_package_version();
	}
	
	
	/**
	 * Installs the module.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function install()
	{
		return $this->_model->install_module();
	}


	/**
	 * Uninstalls the module.
	 *
	 * @access	public
	 * @return	bool
	 */
	public function uninstall()
	{
		return $this->_model->uninstall_module();
	}


	/**
	 * Updates the module.
	 *
	 * @access	public
	 * @param	string		$installed_version		The installed version.
	 * @return	bool
	 */
	public function update($installed_version = '')
	{
		return $this->_model->update_module($installed_version, $this->version);
	}
	
}


/* End of file		: upd.tweedee.php */
/* File location	: third_party/tweedee/upd.tweedee.php */
