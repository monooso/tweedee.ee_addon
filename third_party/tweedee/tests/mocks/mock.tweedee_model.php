<?php

/**
 * Mock Tweedee model.
 *
 * @see				http://simpletest.org/en/mock_objects_documentation.html
 * @author			Stephen Lewis (http://github.com/experience/)
 * @copyright		Experience Internet
 * @package			Tweedee
 */

class Mock_tweedee_model {

	public function get_module_base_querystring() {}
	public function get_package_name() {}
	public function get_package_theme_url() {}
	public function get_package_version() {}
    public function get_search_criteria_from_post_data() {}
	public function get_site_id() {}
	public function install_module() {}
	public function install_module_register() {}
	public function install_module_search_criteria_table() {}
	public function load_search_criteria() {}
	public function save_search_criteria() {}
	public function uninstall_module() {}
	public function update_module($installed_version = '', $package_version = '') {}

}

/* End of file		: mock.tweedee_model.php */
/* File location	: third_party/tweedee/tests/mocks/mock.tweedee_model.php */
