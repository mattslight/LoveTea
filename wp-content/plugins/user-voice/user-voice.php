<?php
/**
 * Plugin Name: UserVoice
 * Plugin URI:  http://www.chrisabernethy.com/wordpress-plugins/user-voice/
 * Description: UserVoice makes it easy to integrate the UserVoice customer feedback system into WordPress without having to directly edit template code.
 * Version:     1.3
 * Author:      Chris Abernethy
 * Author URI:  http://www.chrisabernethy.com/
 * 
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

// Include all class files up-front so that we don't have to worry about the
// include path or any globals containing the plugin base path.

require_once 'lib/UserVoice/Structure.php';
require_once 'lib/UserVoice/Structure/Options.php';
require_once 'lib/UserVoice/Structure/View.php';
require_once 'lib/UserVoice.php';

// Run the plugin.
UserVoice::run(__FILE__);

/* EOF */