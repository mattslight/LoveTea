<?php
/**
 * Copyright 2008 Chris Abernethy
 *
 * This file is part of UserVoice.
 * 
 * UserVoice is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * UserVoice is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with UserVoice.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

/**
 * This class is meant to be used as a base class for those subclasses that need
 * to function as simple data structures at their core. Data is stored in a
 * member variable and is accessed via accessor methods.
 */
class UserVoice_Structure
{

    /**
     * This member variable holds the data that is accessible via the get()
     * and set() accessor methods.
     *
     * @var array $_options
     */
    var $_data = array();

    /**
     * Update the data item identified by $name (creating it if needed) with
     * the value provided in $value.
     *
     * @param mixed $name
     * @param mixed $value
     */
    function set($name, $value) {
        $this->_data[$name] = $value;
    }

    /**
     * Make sure that requests for non-existant member variables does not
     * cause an E_NOTICE error to be generated.
     *
     * @param string $key The variable name.
     * @return null
     */
    function get($key)
    {
        if (array_key_exists($key, $this->_data)) {
            return $this->_data[$key];
        }
        $trace = debug_backtrace();
        trigger_error(sprintf(
            __('Undefined property via __get(): %1$s in file %2$s on line %3$d', 'user-voice')
          , $key, $trace[0]['file'], $trace[0]['line']
        ), E_USER_NOTICE);
        return null;
    }

}

/* EOF */