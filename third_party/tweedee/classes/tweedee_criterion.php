<?php

/**
 * Tweedee Criterion data class.
 *
 * @author      Stephen Lewis (http://github.com/experience)
 * @copyright   Experience Internet
 * @package     Tweedee
 */

class Tweedee_criterion {

    private $_criterion_type;
    private $_criterion_value;

    // Criterion types.
    const TYPE_AND          = 'ands';
    const TYPE_FROM         = 'from';
    const TYPE_HASHTAG      = 'hashtag';
    const TYPE_NOT          = 'nots';
    const TYPE_OR           = 'ors';
    const TYPE_PHRASE       = 'phrase';
    const TYPE_REFERENCING  = 'referencing';
    const TYPE_TO           = 'to';


    /* --------------------------------------------------------------
     * CLASS METHODS
     * ------------------------------------------------------------ */
    
    /**
     * Returns an array of valid criterion types.
     *
     * @access  public
     * @return  array
     */
    public static function get_all_criterion_types()
    {
        return array(
            self::TYPE_AND, self::TYPE_FROM, self::TYPE_HASHTAG, self::TYPE_NOT,
            self::TYPE_OR, self::TYPE_PHRASE, self::TYPE_REFERENCING, self::TYPE_TO
        ); 
    }

    
    /**
     * Determines whether the supplied string is a valid criterion type.
     *
     * @access  public
     * @param   string        $criterion_type        The criterion type to validate.
     * @return  bool
     */
    public static function is_valid_criterion_type($criterion_type)
    {
        return in_array($criterion_type, self::get_all_criterion_types());
    }


    /* --------------------------------------------------------------
     * PUBLIC METHODS
     * ------------------------------------------------------------ */
    
    /**
     * Constructor.
     *
     * @access  public
     * @param   array        $props         Instance properties.
     * @return  void
     */
    public function __construct(Array $props = array())
    {
        $this->reset();

		foreach ($props AS $key => $val)
		{
			$method_name = 'set_' .$key;

			if (method_exists($this, $method_name))
			{
				$this->$method_name($val);
			}
		}
    }


    /**
     * Returns the criterion type.
     *
     * @access  public
     * @return  string
     */
    public function get_criterion_type()
    {
        return $this->_criterion_type;
    }


    /**
     * Returns the criterion value.
     *
     * @access  public
     * @return  string
     */
    public function get_criterion_value()
    {
        return $this->_criterion_value;
    }


    /**
     * Resets the instance properties.
     *
     * @access  public
     * @return  Tweedee_criterion
     */
    public function reset()
    {
        $this->_criterion_type  = '';
        $this->_criterion_value = '';
        return $this;
    }
    
    
    /**
     * Sets the criterion type.
     *
     * @access  public
     * @param   string        $criterion_type        The criterion type.
     * @return  string
     */
    public function set_criterion_type($criterion_type)
    {
        if (self::is_valid_criterion_type($criterion_type))
        {
            $this->_criterion_type = $criterion_type;
        }
        return $this->get_criterion_type();
    }
    
    
    /**
     * Sets the criterion value.
     *
     * @access  public
     * @param   string        $criterion_value        The criterion value.
     * @return  string
     */
    public function set_criterion_value($criterion_value)
    {
        if ( ! is_string($criterion_value))
        {
            return $this->get_criterion_value();
        }

        $criterion_value = str_replace(array('@', '#'), '', trim($criterion_value));

        if (in_array($this->get_criterion_type(), array(self::TYPE_FROM, self::TYPE_TO)))
        {
            // There can only be one 'FROM' or 'TO' name.
            $criterion_value = explode(' ', $criterion_value);
            $criterion_value = $criterion_value[0];
        }

        $this->_criterion_value = $criterion_value;
        return $this->get_criterion_value();
    }
    
    
    /**
     * Converts the instance to an associative array.
     *
     * @access  public
     * @return  array
     */
    public function to_array()
    {
        return array(
            'criterion_type'    => $this->get_criterion_type(),
            'criterion_value'   => $this->get_criterion_value()
        );
    }


    /**
     * Converts the instance to a 'search string', suitable for use in a Twitter search query string.
     *
     * @access  public
     * @return  string
     */
    public function to_search_string()
    {
        $delimiter  = '+';
        $prefix     = '';
        $suffix     = '';
        $value      = urlencode($this->get_criterion_value());

        switch ($this->get_criterion_type())
        {
            case self::TYPE_FROM:
                $prefix = urlencode('from:');
                break;

            case self::TYPE_HASHTAG:
                $hash       = urlencode('#');
                $delimiter  = '+' .$hash;
                $prefix     = $hash;
                break;

            case self::TYPE_NOT:
                $delimiter  = '+-';
                $prefix     = '-';
                break;

            case self::TYPE_OR:
                $delimiter = '+OR+';
                break;

            case self::TYPE_PHRASE:
                $prefix = '"';
                $suffix = '"';
                break;

            case self::TYPE_REFERENCING:
                $at         = urlencode('@');
                $delimiter  = '+' .$at;
                $prefix     = $at;
                break;

            case self::TYPE_TO:
                $prefix = urlencode('to:');
                break;

            default:
                break;
        }

        return $prefix .str_replace('+', $delimiter, $value) .$suffix;
    }

}


/* End of file      : tweedee_criterion.php */
/* File location    : third_party/tweedee/classes/tweedee_criterion.php */
