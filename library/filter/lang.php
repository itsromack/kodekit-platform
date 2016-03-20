<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Language filter
 *
 * Filters ISO codes like en-GB (lang-COUNTRY). Only checks the format, it doesn't care whether the language or country
 * actually exist
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
 */
class FilterLang extends FilterAbstract implements FilterTraversable
{
    /**
     * Validate a value
     *
     * @param   scalar  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        $value = trim($value);
        $pattern = '/^[a-z]{2}-[A-Z]{2}$/';
        return (empty($value))
                || (is_string($value) && preg_match($pattern, $value) == 1);
    }

    /**
     * Sanitize a value
     *
     * @param   scalar  $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        $value = trim($value);

        $parts  = explode('-', $value, 2);
        if(2 != count($parts)) {
            return null;
        }

        $parts[0]   = substr(preg_replace('/[^a-z]*/', '', $parts[0]), 0, 2);
        $parts[1]   = substr(preg_replace('/[^A-Z]*/', '', $parts[1]), 0, 2);
        $result = implode('-', $parts);

        // just making sure :-)
        if($this->_validate($result)) {
            return $result;
        }

        return null;
    }
}