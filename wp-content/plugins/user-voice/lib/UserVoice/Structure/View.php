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
 * A basic view rendering class.
 */
class UserVoice_Structure_View extends UserVoice_Structure
{

    /**
     * Script file name to execute
     * 
     * @var string $_file
     */
    var $_file = null;

    /**
     * Initialize the view by resolving the relative path to the view script.
     *
     * @param string $file Path to the view script, relative to the 'views'
     *                     directory.
     */
    function UserVoice_Structure_View($file)
    {
        // Find base directory for our views. This is expected to be called
        // 'views', and to be located immediately beneath the plugin directory.
        $view_base = explode('/', plugin_basename(__FILE__));
        $view_base = WP_PLUGIN_DIR . "/{$view_base[0]}/views";

        // Sanitize the given view path by converting windows directory
        // separators as well as any double slashes.
        $file = str_replace('\\','/',$file);
        $file = preg_replace('|/+|','/', $file);

        // Store the full path to the view script.
        $this->_file = $view_base . '/' . $file;
    }
    
    /**
     * Render the view directly to standard out.
     */
    function render()
    {
        if (!is_readable($this->_file)) {
            $trace = debug_backtrace();
            trigger_error(sprintf(
                __('Cannot find view script %1$s in file %2$s on line %3$d', 'user-voice')
              , $this->_file, $trace[0]['file'], $trace[0]['line']
            ), E_USER_ERROR);
        }
        include $this->_file;
    }

}

/* EOF */