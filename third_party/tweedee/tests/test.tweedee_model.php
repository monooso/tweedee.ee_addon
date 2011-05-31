<?php

/**
 * Tweedee model tests.
 *
 * @author			Stephen Lewis <stephen@experienceinternet.co.uk>
 * @copyright		Experience Internet
 * @package			Tweedee
 */

require_once PATH_THIRD .'tweedee/models/tweedee_model' .EXT;

class Test_tweedee_model extends Testee_unit_test_case {
	
	private $_package_name;
	private $_package_version;
	private $_site_id;
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
		
		// Dummy package name and version.
		$this->_package_name 	= 'Example_package';
		$this->_package_version	= '1.0.0';
		
		// Dummy site ID value.
		$this->_site_id = 10;
		$this->_ee->config->setReturnValue('item', $this->_site_id, array('site_id'));
		
		// The test subject.
		$this->_subject = new Tweedee_model($this->_package_name, $this->_package_version);
	}
	
	
	public function test__constructor__package_name_and_version()
	{
		// Dummy values.
		$package_name 		= 'Example_package';
		$package_version	= '1.0.0';

		// Tests.
		$subject = new Tweedee_model($package_name, $package_version);
		$this->assertIdentical($package_name, $subject->get_package_name());
		$this->assertIdentical($package_version, $subject->get_package_version());
	}
	
	
	public function test__get_site_id__success()
	{
		// Expectations.
		$this->_ee->config->expectOnce('item', array('site_id'));
		
		// Tests.
		$this->assertIdentical(intval($this->_site_id), $this->_subject->get_site_id());
	}
	
	
	public function test__install_module_register__success()
	{
		// Dummy values.
		$query_data = array(
			'has_cp_backend'		=> 'y',
			'has_publish_fields'	=> 'n',
			'module_name'			=> $this->_package_name,
			'module_version'		=> $this->_package_version
		);
		
		// Expectations.
		$this->_ee->db->expectOnce('insert', array('modules', $query_data));
		
		// Tests.
		$this->_subject->install_module_register();
	}


	public function test__install_module_search_criteria_table__success()
	{
		$dbforge = $this->_get_mock('dbforge');

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

		$this->_ee->dbforge =& $dbforge;

		$dbforge->expectOnce('add_field', array($columns));
		$dbforge->expectOnce('add_key', array('criterion_id', TRUE));
		$dbforge->expectOnce('create_table', array('tweedee_search_criteria', TRUE));
	
		// Run the tests.
		$this->_subject->install_module_search_criteria_table();
	}


	public function test__load_search_criteria__success()
	{
		$site_id_string = strval($this->_site_id);

		$result = $this->_get_mock('db_query');
		$rows	= array(
			array(
				'criterion_id'		=> '10',
				'site_id'			=> $site_id_string,
				'criterion_type'	=> 'to',
				'criterion_value'	=> 'mrw'
			),
			array(
				'criterion_id'		=> '20',
				'site_id'			=> $site_id_string,
				'criterion_type'	=> 'from',
				'criterion_value'	=> 'monooso'
			),
			array(
				'criterion_id'		=> '20',
				'site_id'			=> $site_id_string,
				'criterion_type'	=> 'hashtag',
				'criterion_value'	=> 'oyvey'
			)
		);

		$this->_ee->db->expectOnce('select', array('criterion_id, site_id, criterion_type, criterion_value'));
		$this->_ee->db->expectOnce('get_where', array('tweedee_search_criteria', array('site_id' => $this->_site_id)));

		$this->_ee->db->setReturnReference('get_where', $result);
		$result->setReturnValue('num_rows', count($rows));
		$result->setReturnValue('result_array', $rows);

		$return = array();

		foreach ($rows AS $row)
		{
			$return[] = array(
				'criterion_id'		=> intval($row['criterion_id']),
				'site_id'			=> intval($row['site_id']),
				'criterion_type'	=> $row['criterion_type'],
				'criterion_value'	=> $row['criterion_value']
			);
		}

		// Run the tests.
		$this->assertIdentical($return, $this->_subject->load_search_criteria());
	}


	public function test__load_search_criteria__no_saved_criteria()
	{
		$result = $this->_get_mock('db_query');

		$result->expectOnce('num_rows');
		$result->expectNever('result_array');

		$this->_ee->db->setReturnReference('get_where', $result);
		$result->setReturnValue('num_rows', 0);
	
		// Run the tests.
		$this->assertIdentical(array(), $this->_subject->load_search_criteria());
	}


	public function test__save_search_criteria__success()
	{
		$search_criteria = array(
			array('type' => 'from', 'value' => 'monooso'),
			array('type' => 'to', 'value' => 'mrw'),
			array('type' => 'phrase', 'value' => 'oy'),
			array('type' => 'hashtag', 'value' => ''),
			array('type' => '', 'value' => 'value')
		);

		// Retrieve the POST data.
		$this->_ee->input->expectOnce('post', array('search_criteria', TRUE));
		$this->_ee->input->setReturnValue('post', $search_criteria, array('search_criteria', TRUE));

		// Delete the existing search criteria.
		$this->_ee->db->expectOnce('delete', array('tweedee_search_criteria', array('site_id' => $this->_site_id)));

		// Loop through the new search criteria.
		$count = 0;

		foreach ($search_criteria AS $criterion)
		{
			if ($criterion['type'] == '' OR $criterion['value'] == '')
			{
				continue;
			}

			$insert_data = array(
				'criterion_type'	=> $criterion['type'],
				'criterion_value'	=> $criterion['value'],
				'site_id'			=> $this->_site_id
			);

			$this->_ee->db->expectAt($count, 'insert', array('tweedee_search_criteria', $insert_data));
			$count++;
		}
	
		$this->_ee->db->expectCallCount('insert', $count);

		// Run the tests.
		$this->assertIdentical(TRUE, $this->_subject->save_search_criteria());
	}


	public function test__save_search_criteria__missing_criterion_type()
	{
		$search_criteria = array(array('value' => 'example'));

		// Retrieve the POST data.
		$this->_ee->input->setReturnValue('post', $search_criteria, array('search_criteria', TRUE));

		// This should never happen.
		$this->_ee->db->expectNever('delete');
		$this->_ee->db->expectNever('insert');
	
		// Run the tests.
		$this->assertIdentical(FALSE, $this->_subject->save_search_criteria());
	}
	
		
	
	public function test__uninstall_module__success()
	{
		// Dummy values.
		$db_module_result 			= $this->_get_mock('db_query');
		$db_module_row 				= new StdClass();
		$db_module_row->module_id	= '10';
		
		// Expectations.
		$this->_ee->db->expectOnce('select', array('module_id'));
		$this->_ee->db->expectOnce('get_where', array('modules', array('module_name' => $this->_package_name), 1));
		
		$this->_ee->db->expectCallCount('delete', 2);
		$this->_ee->db->expectAt(0, 'delete', array('module_member_groups', array('module_id' => $db_module_row->module_id)));
		$this->_ee->db->expectAt(1, 'delete', array('modules', array('module_name' => $this->_package_name)));

		$dbforge			= $this->_get_mock('dbforge');
		$this->_ee->dbforge	=& $dbforge;

		$dbforge->expectOnce('drop_table', array('tweedee_search_criteria'));
				
		// Return values.
		$this->_ee->db->setReturnReference('get_where', $db_module_result);
		$db_module_result->setReturnValue('num_rows', 1);
		$db_module_result->setReturnValue('row', $db_module_row);
		
		// Tests.
		$this->assertIdentical(TRUE, $this->_subject->uninstall_module());
	}
	
	
	public function test__uninstall_module__module_not_found()
	{
		// Dummy values.
		$db_module_result = $this->_get_mock('db_query');
		
		// Expectations.
		$this->_ee->db->expectOnce('select');
		$this->_ee->db->expectOnce('get_where');
		$this->_ee->db->expectNever('delete');
		
		// Return values.
		$this->_ee->db->setReturnReference('get_where', $db_module_result);
		$db_module_result->setReturnValue('num_rows', 0);
		
		// Tests.
		$this->assertIdentical(FALSE, $this->_subject->uninstall_module());
	}
	
	
	public function test__update_module__no_update_required()
	{
		// Dummy values.
		$installed_version	= '1.0.0';
		$package_version	= '1.0.0';

		// Tests.
		$this->assertIdentical(FALSE, $this->_subject->update_module($installed_version, $package_version));
	}
	
	
	
	public function test__update_module__update_required()
	{
		// Dummy values.
		$installed_version	= '0.9.0';
		$package_version	= '1.0.0';

		// Tests.
		$this->assertIdentical(TRUE, $this->_subject->update_module($installed_version, $package_version));
	}
	
	
	public function test__update_module__no_installed_version()
	{
		// Dummy values.
		$installed_version	= '';
		$package_version	= '1.0.0';

		// Tests.
		$this->assertIdentical(TRUE, $this->_subject->update_module($installed_version, $package_version));
	}
	
	
	public function test__update_module__no_package_version()
	{
		// Dummy values.
		$installed_version	= '1.0.0';
		$package_version	= '';

		// Tests.
		$this->assertIdentical(FALSE, $this->_subject->update_module($installed_version, $package_version));
	}
	
	
}


/* End of file		: test.tweedee_model.php */
/* File location	: third_party/tweedee/tests/test.tweedee_model.php */
