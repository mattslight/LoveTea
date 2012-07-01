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
class UserVoice_Structure_Options extends UserVoice_Structure
{

    /**
     * The lookup key used to locate the options record for this plugin in the
     * wp_options table. This corresponds to the option_name field.
     *
     * @var string $_option_key
     */
    var $_option_key = null;

    /**
     * Initialize the options structure with options fetched by looking up
     * the record in the wp_options table with an option_name field matching
     * the given $option_key.
     *
     * @param unknown_type $option_key
     */
    function UserVoice_Structure_Options($option_key)
    {
        $options = get_option($option_key);
        if (false === $options) {
            $options = array();
        }
        $this->_data       = $options;
        $this->_option_key = $option_key;
    }

    /**
     * Save the internal options data to the wp_options table using the stored
     * $option_key value as the key.
     */
    function save()
    {
        update_option($this->_option_key, $this->_data);
    }

    /**
     * Delete the internal options data from the wp_options table. This method
     * is intended to be used as part of the uninstall process.
     */
    function delete()
    {
        delete_option($this->_option_key);
    }

}

/* EOF */