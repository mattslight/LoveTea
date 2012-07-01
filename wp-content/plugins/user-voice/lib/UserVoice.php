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
 * UserVoice makes it easy to integrate the UserVoice customer feedback system into WordPress without having to directly edit template code.
 */
class UserVoice
{

    /**
     * An instance of the options structure containing all options for this
     * plugin.
     *
     * @var UserVoice_Structure_Options
     */
    var $_options = null;

    /**************************************************************************/
    /*                         Singleton Functionality                        */
    /**************************************************************************/

    /**
     * Retrieve the instance of this class, creating it if necessary.
     *
     * @return UserVoice
     */
    function instance()
    {
        static $instance = null;
        if (null == $instance) {
            $c = __CLASS__;
            $instance = new $c;
        }
        return $instance;
    }

    /**
     * The constructor initializes the options object for this plugin.
     */
    function UserVoice()
    {
        $this->_options = new UserVoice_Structure_Options('user-voice_options');
    }

    /**************************************************************************/
    /*                     Plugin Environment Management                      */
    /**************************************************************************/

    /**
     * This initialization method instantiates an instance of the plugin and
     * performs the initialization sequence. This method is meant to be called
     * statically from the plugin bootstrap file.
     *
     * Example Usage:
     * <pre>
     * UserVoice::run(__FILE__)
     * </pre>
     *
     * @param string $plugin_file The full path to the plugin bootstrap file.
     */
    function run($plugin_file)
    {
        $plugin = UserVoice::instance();

        // Activation and deactivation hooks have special registration
        // functions that handle sanitization of the given filename. It
        // is recommended that these be used rather than directly adding
        // an action callback for 'activate_<filename>'.

        register_activation_hook  ($plugin_file, array(&$plugin, 'hookActivation'));
        register_deactivation_hook($plugin_file, array(&$plugin, 'hookDeactivation'));

        // Set up action callbacks.
        add_action('do_meta_boxes'             , array(&$plugin, 'registerMetaBoxes'), 10, 3);
        add_action('wp_insert_post'            , array(&$plugin, 'updatePost'));
        add_action('wp_footer'                 , array(&$plugin, 'footerAction'));
    }

    /**
     * This is the plugin activation hook callback. It performs setup actions
     * for the plugin and should be smart enough to know when the plugin has
     * already been installed and is simply being re-activated.
     */
    function hookActivation()
    {
        // If 'version' is not yet set in the options array, this is a first
        // time install scenario. Perform the initial database and options
        // setup.
        if (null === $this->getOption('version')) {
            $this->_install();
            return;
        }

        // If the plugin version stored in the options structure is older than
        // the current plugin version, initiate the upgrade sequence.
        if (version_compare($this->getOption('version'), '1.3', '<')) {
            $this->_upgrade();
            return;
        }
    }

    /**
     * This is the plugin deactivation hook callback, it performs teardown
     * actions for the plugin.
     */
    function hookDeactivation()
    {
    }

    /**
     * This method is called when the plugin needs to be installed for the first
     * time.
     */
    function _install()
    {
        global $wpdb;

        // Create fields in the posts table to hold per-post plugin options.
        $wpdb->query(sprintf("
            ALTER TABLE %s
               ADD COLUMN `%s` varchar(128) NOT NULL DEFAULT ''
             , ADD COLUMN `%s` varchar(128) NOT NULL DEFAULT 'general'
             , ADD COLUMN `%s` tinyint(1) unsigned NOT NULL DEFAULT 0
             , ADD COLUMN `%s` enum('left','right') NOT NULL DEFAULT 'right'
             , ADD COLUMN `%s` char(6) NOT NULL DEFAULT '00BCBA'
          ", $wpdb->posts
           , $wpdb->escape('user-voice_username')
           , $wpdb->escape('user-voice_slug')
           , $wpdb->escape('user-voice_active')
           , $wpdb->escape('user-voice_alignment')
           , $wpdb->escape('user-voice_color')
        ));

        // Set the default options.
        $this->setOption('version', '1.3');
        $this->_options->save();
    }

    /**
     * Remove all traces of this plugin from the WordPress database. This
     * includes removing custom fields from the wp_posts table as well as any
     * options in the wp_options table. This method should <em>only</em> be
     * called if the plugin is also going to be deactivated.
     */
    function _uninstall()
    {
        global $wpdb;

        // Remove the per-post plugin option fields from the wp_posts table.
        $wpdb->query(sprintf("
            ALTER TABLE %s
              DROP `%s`
            , DROP `%s`
            , DROP `%s`
            , DROP `%s`
            , DROP `%s`
        " , $wpdb->posts
          , $wpdb->escape('user-voice_username')
          , $wpdb->escape('user-voice_slug')
          , $wpdb->escape('user-voice_active')
          , $wpdb->escape('user-voice_alignment')
          , $wpdb->escape('user-voice_color')
        ));

        // Remove all plugin options from the wp_options table.
        $this->_options->delete();
    }

    /**
     * This method is called when the internal plugin state needs to be
     * upgraded.
     */
    function _upgrade()
    {
        // Upgrade Example
        //$old_version = $this->getOption('version');
        //if (version_compare($old_version, '3.5', '<')) {
        //    // Do upgrades for version 3.5
        //    $this->setOption('version', '3.5');
        //}
        $this->setOption('version', '1.3');
        $this->_options->save();
    }

    /**************************************************************************/
    /*                          Action Hook Callbacks                         */
    /**************************************************************************/

    /**
     * Render the meta-boxes for this plugin in the advanced section of both
     * the post and page editing screens.
     *
     * @param string $page The type of page being loaded (page, post, link or comment)
     * @param string $context The context of the meta box (normal, advanced)
     * @param StdClass $object The object representing the page type
     */
    function registerMetaBoxes($page, $context, $object)
    {
        if (in_array($page, array('page', 'post'))) {
            add_meta_box(
                attribute_escape('user-voice') // id attribute
              , wp_specialchars('UserVoice')   // metabox title
              , array(&$this, 'renderMetaBox')     // callback function
              , $page                              // page type
            );
        }
    }

    /**
     * This action hook callback is called after a post or page is created or
     * updated.
     *
     * @param integer $post_id
     */
    function updatePost($post_id)
    {
        global $wpdb;

        // Don't update the wp_posts fields if this is a quick-edit.
        if (@$_POST['action'] == 'inline-save') {
            return;
        }

        $wpdb->query(sprintf("
            UPDATE %s
            SET `%s` = '%s'
              , `%s` = '%s'
              , `%s` = '%s'
              , `%s` = '%s'
              , `%s` = '%s'
            WHERE ID = %d
        ", $wpdb->posts
         , $wpdb->escape('user-voice_username'), $wpdb->escape(@$_POST['user-voice_username'])
         , $wpdb->escape('user-voice_slug'), $wpdb->escape(@$_POST['user-voice_slug'])
         , $wpdb->escape('user-voice_active'), $wpdb->escape(@$_POST['user-voice_active'])
         , $wpdb->escape('user-voice_alignment'), $wpdb->escape(@$_POST['user-voice_alignment'])
         , $wpdb->escape('user-voice_color'), $wpdb->escape(@$_POST['user-voice_color'])
         , $post_id
        ));

    }

    /**
     * This action hook callback is called in the footer of a template.
     */
    function footerAction() {
        if(is_single()||is_page()) {
            global $post;
            if($post->{'user-voice_active'}) {
                $view = new UserVoice_Structure_View('uservoice.phtml');
                $view->set('username' , $post->{'user-voice_username'});
                $view->set('slug'     , $post->{'user-voice_slug'});
                $view->set('alignment', $post->{'user-voice_alignment'});
                $view->set('color'    , $post->{'user-voice_color'});
                $view->render();
            }
        }
    }

    /**************************************************************************/
    /*                           Indirect Callbacks                           */
    /**************************************************************************/

    /**
     * Render the metabox content for this plugin.
     *
     * @param StdClass $object The object representing the page type
     * @param array $box An array containing the id, title and callback used when
     *                   registering the meta box being displayed.
     */
    function renderMetaBox($object, $box)
    {
        $view = new UserVoice_Structure_View('metabox.phtml');
        $view->set('plugin_label', 'user-voice');
        $view->set('username' , $object->{'user-voice_username'});
        $view->set('slug'     , $object->{'user-voice_slug'});
        $view->set('active'   , $object->{'user-voice_active'});
        $view->set('alignment', $object->{'user-voice_alignment'});
        $view->set('color'    , $object->{'user-voice_color'});
        $view->render();
    }

    /**************************************************************************/
    /*                            Utility Methods                             */
    /**************************************************************************/

    /**
     * This accessor grants read access to the internal options object so that
     * the isPrivate method can check option values when it is called as a
     * static method.
     *
     * @param string $option_name
     * @return Mixed
     */
    function getOption($option_name)
    {
        return $this->_options->get($option_name);
    }

    /**
     * This accessor grants write access to the internal options object so that
     * option values can be changed.
     *
     * @param string $option_name
     * @param mixed $option_value
     */
    function setOption($option_name, $option_value)
    {
        $this->_options->set($option_name, $option_value);
    }

};

/* EOF */