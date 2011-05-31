<?php

/**
 * Tweedee Criterion tests.
 *
 * @author      Stephen Lewis (http://github.com/experience)
 * @copyright   Experience Internet
 * @package     Tweedee
 */

require_once PATH_THIRD .'tweedee/classes/tweedee_criterion' .EXT;

class Test_tweedee_criterion extends Testee_unit_test_case {

    private $_props;
    private $_subject;


    /* --------------------------------------------------------------
     * PUBLIC METHODS
     * ------------------------------------------------------------ */
    
    /**
     * "Set up" method, called before each test.
     *
     * @access  public
     * @return  void
     */
    public function setUp()
    {
        parent::setUp();

        $this->_props = array(
            'criterion_id'      => 10,
            'criterion_type'    => Tweedee_criterion::TYPE_HASHTAG,
            'criterion_value'   => 'eecms'
        );

        $this->_subject = new Tweedee_criterion($this->_props);
    }


    public function test__set_criterion_id__convert_to_integer()
    {
        $this->assertIdentical(99, $this->_subject->set_criterion_id('99'));
    }


    public function test__set_criterion_id__invalid_values()
    {
        $this->assertIdentical($this->_props['criterion_id'], $this->_subject->set_criterion_id(0));
        $this->assertIdentical($this->_props['criterion_id'], $this->_subject->set_criterion_id('NAN'));
        $this->assertIdentical($this->_props['criterion_id'], $this->_subject->set_criterion_id(new StdClass()));
        $this->assertIdentical($this->_props['criterion_id'], $this->_subject->set_criterion_id(FALSE));
    }


    public function test__set_criterion_type__valid_types()
    {
        $this->assertIdentical(Tweedee_criterion::TYPE_AND, $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_AND));
        $this->assertIdentical(Tweedee_criterion::TYPE_FROM, $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_FROM));
        $this->assertIdentical(Tweedee_criterion::TYPE_HASHTAG, $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_HASHTAG));
        $this->assertIdentical(Tweedee_criterion::TYPE_NOT, $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_NOT));
        $this->assertIdentical(Tweedee_criterion::TYPE_OR, $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_OR));
        $this->assertIdentical(Tweedee_criterion::TYPE_PHRASE, $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_PHRASE));
        $this->assertIdentical(Tweedee_criterion::TYPE_REFERENCING, $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_REFERENCING));
        $this->assertIdentical(Tweedee_criterion::TYPE_TO, $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_TO));
    }


    public function test__set_criterion_type__invalid_type()
    {
        $this->assertIdentical($this->_props['criterion_type'], $this->_subject->set_criterion_type('unknown'));
        $this->assertIdentical($this->_props['criterion_type'], $this->_subject->set_criterion_type(FALSE));
        $this->assertIdentical($this->_props['criterion_type'], $this->_subject->set_criterion_type(100));
        $this->assertIdentical($this->_props['criterion_type'], $this->_subject->set_criterion_type(array()));
        $this->assertIdentical($this->_props['criterion_type'], $this->_subject->set_criterion_type(new StdClass()));
    }


    public function test__set_criterion_value__invalid_values()
    {
        $this->assertIdentical($this->_props['criterion_value'], $this->_subject->set_criterion_value(FALSE));
        $this->assertIdentical($this->_props['criterion_value'], $this->_subject->set_criterion_value(100));
        $this->assertIdentical($this->_props['criterion_value'], $this->_subject->set_criterion_value(array()));
        $this->assertIdentical($this->_props['criterion_value'], $this->_subject->set_criterion_value(new StdClass()));
    }


    public function test__set_criterion_value__clean_ats_and_hashes()
    {
        $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_AND);     // No special characterists.
        $this->assertIdentical('the cat sat on the mat', $this->_subject->set_criterion_value('the @cat sat on the #mat'));
    }


    public function test__set_criterion_value__clean_errant_spaces()
    {
        $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_AND);
        $this->assertIdentical('the cat sat on the mat', $this->_subject->set_criterion_value(' the cat sat on the mat     '));
    }


    public function test__set_criterion_value__clean_from_multiple_values()
    {
        $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_FROM);
        $this->assertIdentical('mrw', $this->_subject->set_criterion_value('mrw has a handbag dog'));
    }


    public function test__set_criterion_value__clean_to_multiple_values()
    {
        $this->_subject->set_criterion_type(Tweedee_criterion::TYPE_TO);
        $this->assertIdentical('mrw', $this->_subject->set_criterion_value('mrw yaykyle'));
    }


    public function test__to_array__success()
    {
        $this->assertIdentical($this->_props, $this->_subject->to_array());
    }


    public function test__to_search_string__and()
    {
        $value = 'the cat sat on the mat';
        $props = array('criterion_type' => Tweedee_criterion::TYPE_AND, 'criterion_value' => $value);
        $subject = new Tweedee_criterion($props);

        $expected_result = str_replace(' ', '+', $value);
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }


    public function test__to_search_string__from()
    {
        $props = array('criterion_type' => Tweedee_criterion::TYPE_FROM, 'criterion_value' => 'mrw yaykyle');
        $subject = new Tweedee_criterion($props);

        $expected_result = urlencode('from:mrw');
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }


    public function test__to_search_string__hashtag_single()
    {
        $props = array('criterion_type' => Tweedee_criterion::TYPE_HASHTAG, 'criterion_value' => ' #soup ');
        $subject = new Tweedee_criterion($props);

        $expected_result = urlencode('#soup');;
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }


    public function test__to_search_string__hashtag_multiple()
    {
        $props = array('criterion_type' => Tweedee_criterion::TYPE_HASHTAG, 'criterion_value' => '#soup nazi');
        $subject = new Tweedee_criterion($props);

        $expected_result = urlencode('#soup') .'+' .urlencode('#nazi');
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }


    public function test__to_search_string__not()
    {
        $value = 'no soup for you';
        $props = array('criterion_type' => Tweedee_criterion::TYPE_NOT, 'criterion_value' => $value);
        $subject = new Tweedee_criterion($props);

        $expected_result = '-' .str_replace(' ', '+-', $value);
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }


    public function test__to_search_string__or()
    {
        $value = 'no soup for you';
        $props = array('criterion_type' => Tweedee_criterion::TYPE_OR, 'criterion_value' => $value);
        $subject = new Tweedee_criterion($props);

        $expected_result = str_replace(' ', '+OR+', $value);
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }


    public function test__to_search_string__phrase()
    {
        $value = 'no soup for you';
        $props = array('criterion_type' => Tweedee_criterion::TYPE_PHRASE, 'criterion_value' => $value);
        $subject = new Tweedee_criterion($props);

        $expected_result = '"' .urlencode($value) .'"';
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }


    public function test__to_search_string__referencing()
    {
        $value = ' mrw yaykyle rands ';
        $props = array('criterion_type' => Tweedee_criterion::TYPE_REFERENCING, 'criterion_value' => $value);
        $subject = new Tweedee_criterion($props);

        $at = urlencode('@');
        $expected_result = $at .str_replace(' ', "+{$at}", trim($value));
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }


    public function test__to_search_string__to()
    {
        $props = array('criterion_type' => Tweedee_criterion::TYPE_TO, 'criterion_value' => 'mrw yaykyle');
        $subject = new Tweedee_criterion($props);

        $expected_result = urlencode('to:mrw');
        $this->assertIdentical($expected_result, $subject->to_search_string());
    }

}

/* End of file      : test.tweedee_criterion.php */
/* File location    : third_party/tweedee/tests/test.tweedee_criterion.php */
