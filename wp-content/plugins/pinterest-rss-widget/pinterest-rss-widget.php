<?php
/*
Plugin Name: Pinterest RSS Widget
Plugin URI: http://www.bkmacdaddy.com/pinterest-rss-widget-a-wordpress-plugin-to-display-your-latest-pins/
Description: Display up to 25 of your latest Pinterest Pins in your sidebar. You are welcome to express your gratitude for this plugin by donating via <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SXTEL7YLUSFFC" target="_blank"><strong>PayPal</strong></a>
Author: bkmacdaddy designs
Version: 2.01
Author URI: http://bkmacdaddy.com/

/* License

    Pinterest RSS Widget
    Copyright (C) 2012 Brian McDaniel (brian at bkmacdaddy dot com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
*/

add_action('wp_enqueue_scripts', 'add_pinterest_rss_css');

function add_pinterest_rss_css() {

	$pinterest_rss_myStyleUrl = plugins_url('style.css', __FILE__); // Respects SSL, Style.css is relative to the current file
	$pinterest_rss_myStyleFile = WP_PLUGIN_DIR . '/pinterest-rss-widget/style.css';	
	$pinterest_rss_nailThumb = plugins_url('jquery.nailthumb.1.0.min.js', __FILE__);

	if ( file_exists($pinterest_rss_myStyleFile) ) {
		wp_register_style('pinterestRSScss', $pinterest_rss_myStyleUrl);
		wp_enqueue_style( 'pinterestRSScss');		
		wp_deregister_script( 'jquery' );
    	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
		wp_enqueue_script( 'jquery' );		
		wp_register_script( 'pinterestRSSjs', $pinterest_rss_nailThumb);    	
		wp_enqueue_script( 'pinterestRSSjs' );
	}
}

function get_pins_feed_list($username, $boardname, $maxfeeds=25, $divname='standard', $printtext=NULL, $target='samewindow', $useenclosures='yes', $thumbwidth='150', $thumbheight='150', $showfollow='large') {
// This is the main function of the plugin. It is used by the widget and can also be called from anywhere in your theme. See the readme file for example.

		// Get Pinterest Feed(s)
		include_once(ABSPATH . WPINC . '/feed.php');
				if( empty($boardname) ){
					$pinsfeed = 'http://pinterest.com/'.$username.'/feed.rss';
				}
				else $pinsfeed = 'http://pinterest.com/'.$username.'/'.$boardname.'/rss';

                // Get a SimplePie feed object from the Pinterest feed source
                $rss = fetch_feed($pinsfeed);
				$rss->set_timeout(60);

                // Figure out how many total items there are.               
				$maxitems = $rss->get_item_quantity((int)$maxfeeds);

                // Build an array of all the items, starting with element 0 (first element).
                $rss_items = $rss->get_items(0,$maxitems);
				
                $content = '';
				$content .= '<ul class="pins-feed-list">';
				// Loop through each feed item and display each item as a hyperlink.
				  foreach ( $rss_items as $item ) : 
					$content .= '<li class="pins-feed-item" style="width:'. $thumbwidth .'px;">';
					$content .= '<div class="pins-feed-'.$divname.'">';
					$content .= '<a href="'.$item->get_permalink().'"';
					if ($target == 'newwindow') { $content .= 'target="_BLANK" '; };
					$content .= 'title="'.$item->get_title().' - Pinned on '.$item->get_date('M d, Y').'">'; 					
									
									if ($thumb = $item->get_item_tags(SIMPLEPIE_NAMESPACE_MEDIARSS, 'thumbnail') ) {
										$thumb = $thumb[0]['attribs']['']['url'];											
										$content .= '<img src="'.$thumb.'"'; 
										$content .= ' alt="'.$item->get_title().'"/>';
									 } else if ( $useenclosures == 'yes' && $enclosure = $item->get_enclosure() ) {
										$enclosure = $item->get_enclosures();
										$content .= '<img src="'.$enclosure[0]->get_link().'"'; 
										$content .= ' alt="'.$item->get_title().'"/>';
									}  else {
										preg_match('/src="([^"]*)"/', $item->get_content(), $matches);
										$src = $matches[1];
										
										if ($matches) {
										  $content .= '<img src="'.$src.'"'; 
										$content .= ' alt="'.$item->get_title().'"/>';
										} else {
										  $content .= "thumbnail not available";
										}
									} 
									if ($printtext) {
									  if ($printtext != 'no') {
										$content .= "<div class='imgtitle'>".$item->get_title()."</div>";
									  }
									}
								  $content .= '</a>';
							  $content .= '</div>';
					$content .= '</li>';
				  endforeach;
				  $content .= '<div class="pinsClear"></div>';
				$content .= '</ul>';
				$content .= '<script type="text/javascript">';
				$content .= 'jQuery(document).ready(function() {';
				$content .= "jQuery('.pins-feed-item img').nailthumb({width:".$thumbwidth.",height:".$thumbheight."})";
				$content .= '}); </script>'; 
					$pinterest_followButton = plugins_url('follow-on-pinterest-button.png', __FILE__);
					if ($showfollow == 'large') { 
						$content .= '<a href="http://pinterest.com/'. $username .'/" id="pins-feed-follow" target="_blank" class="followLarge" title="Follow Me on Pinterest">';
							$content .= '<img src="http://passets-cdn.pinterest.com/images/follow-on-pinterest-button.png" width="156" height="26" alt="Follow Me on Pinterest" border="0" />';
						$content .= '</a>';
					} elseif ($showfollow == 'medium') { 
						$content .= '<a href="http://pinterest.com/'. $username.'/" id="pins-feed-follow" target="_blank" class="followMed" title="Follow Me on Pinterest">';
							$content .= '<img src="http://passets-cdn.pinterest.com/images/pinterest-button.png" width="78" height="26" alt="Follow Me on Pinterest" border="0" />';
						$content .= '</a>';
					} elseif ($showfollow == 'small') { 
						$content .= '<a href="http://pinterest.com/'. $username .'/" id="pins-feed-follow" target="_blank" class="followSmall" title="Follow Me on Pinterest">';
							$content .= '<img src="http://passets-cdn.pinterest.com/images/big-p-button.png" width="61" height="61" alt="Follow Me on Pinterest" border="0" />';
						$content .= '</a>';
					} elseif ($showfollow == 'tiny') { 
						$content .= '<a href="http://pinterest.com/'. $username .'/" id="pins-feed-follow" target="_blank" class="followTiny" title="Follow Me on Pinterest">';
							$content .= '<img src="http://passets-cdn.pinterest.com/images/small-p-button.png" width="16" height="16" alt="Follow Me on Pinterest" border="0" />';
						$content .= '</a>';
					} elseif ($showfollow == 'none') {} 
					
					return $content;
}

function prw_shortcode( $atts )	{
 
	extract( shortcode_atts( array(
				'username' => '',
				'boardname' => '',
				'maxfeeds' => 25,
				'divname' => 'standard',
				'printtext' => NULL,
				'target' => 'samewindow',
				'useenclosures' => 'yes',
				'thumbwidth' => 150,
				'thumbheight' => 150,
				'showfollow' => 'large'
			), $atts 
		) 
	);
	// this will display the latest pins
	$prwsc = get_pins_feed_list($username, $boardname, $maxfeeds, $divname, $printtext, $target, $useenclosures, $thumbwidth, $thumbheight, $showfollow);
	return $prwsc;
 
}
add_shortcode('prw', 'prw_shortcode');

class Pinterest_RSS_Widget extends WP_Widget {
  function Pinterest_RSS_Widget() {
    $widget_ops = array('classname' => 'pinterest_rss_widget', 'description' => 'A widget to display latest Pinterest Pins via RSS feed' );
    $this->WP_Widget('pinterest_rss_widget', 'Pinterest RSS Widget', $widget_ops);
  }

  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;

    $title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
    $user_name = empty($instance['user_name']) ? '&nbsp;' : $instance['user_name'];
    $board_name = empty($instance['board_name']) ? '' : $instance['board_name'];
    $maxnumber = empty($instance['maxnumber']) ? '&nbsp;' : $instance['maxnumber'];
    $thumb_height = empty($instance['thumb_height']) ? '&nbsp;' : $instance['thumb_height'];
    $thumb_width = empty($instance['thumb_width']) ? '&nbsp;' : $instance['thumb_width'];
    $target = empty($instance['target']) ? '&nbsp;' : $instance['target'];
    $displaytitle = empty($instance['displaytitle']) ? '&nbsp' : $instance['displaytitle'];
    $useenclosures = empty($instance['useenclosures']) ? '&nbsp;' : $instance['useenclosures'];
    $showfollow = empty($instance['showfollow']) ? '&nbsp;' : $instance['showfollow'];
 
    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    if ( empty( $board_name ) ) { $board_name = ''; };

    if ( empty( $target ) ) { $target = 'samewindow'; };

    if ( empty( $displaytitle ) ) { $displaytitle = 'no'; };

    if ( empty( $useenclosures ) ) { $useenclosures = 'yes'; };

    if ( empty( $thumb_width ) ) { $thumb_width = '150'; };

    if ( empty( $thumb_height ) ) { $thumb_height = '150'; };

    if ( empty( $showfollow ) ) { $showfollow = 'none'; };

    if ( !empty( $user_name ) ) {

      echo get_pins_feed_list($user_name, $board_name, $maxnumber, 'small', $displaytitle, $target, $useenclosures, $thumb_width, $thumb_height, $showfollow); ?>

                <div style="clear:both;"></div>

                <?php }

    echo $after_widget;
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['user_name'] = strip_tags($new_instance['user_name']);
    $instance['board_name'] = strip_tags($new_instance['board_name']);
    $instance['maxnumber'] = strip_tags($new_instance['maxnumber']);
    $instance['thumb_height'] = strip_tags($new_instance['thumb_height']);
    $instance['thumb_width'] = strip_tags($new_instance['thumb_width']);
    $instance['target'] = strip_tags($new_instance['target']);
    $instance['displaytitle'] = strip_tags($new_instance['displaytitle']);
    $instance['useenclosures'] = strip_tags($new_instance['useenclosures']);
    $instance['showfollow'] = strip_tags($new_instance['showfollow']);
 
    return $instance;
  }
 
  function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'user_name' => '', 'board_name' => '', 'maxnumber' => '', 'thumb_height' => '', 'thumb_width' => '', 'target' => '', 'displaytitle' => '', 'useenclosures' => '', 'showfollow' => '') );
    $title = strip_tags($instance['title']);
    $user_name = strip_tags($instance['user_name']);
    $board_name = strip_tags($instance['board_name']);
    $maxnumber = strip_tags($instance['maxnumber']);
    $thumb_height = strip_tags($instance['thumb_height']);
    $thumb_width = strip_tags($instance['thumb_width']);
    $target = strip_tags($instance['target']);
    $displaytitle = strip_tags($instance['displaytitle']);
    $useenclosures = strip_tags($instance['useenclosures']);
    $showfollow = strip_tags($instance['showfollow']);
?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <br /><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
								    
      <p><label for="<?php echo $this->get_field_id('user_name__title'); ?>">Pinterest Username: <br /><input class="widefat" id="<?php echo $this->get_field_id('user_name'); ?>" name="<?php echo $this->get_field_name('user_name'); ?>" type="text" value="<?php echo attribute_escape($user_name); ?>" /></label></p>
      
      <p><label for="<?php echo $this->get_field_id('user_name__board'); ?>">Username Board: (Optional) <br /><input class="widefat" id="<?php echo $this->get_field_id('board_name'); ?>" name="<?php echo $this->get_field_name('board_name'); ?>" type="text" value="<?php echo attribute_escape($board_name); ?>" /></label></p>
		     
      <p><label for="<?php echo $this->get_field_id('maxnumber'); ?>">Max number of pins to display: <br /><input class="widefat" id="<?php echo $this->get_field_id('maxnumber'); ?>" name="<?php echo $this->get_field_name('maxnumber'); ?>" type="text" value="<?php echo attribute_escape($maxnumber); ?>" /></label></p>
      
      <p><label for="<?php echo $this->get_field_id('thumb_height'); ?>">Thumbnail Height in pixels<br /><em>(defaults to 150):</em><br /><input class="widefat" id="<?php echo $this->get_field_id('thumb_height'); ?>" name="<?php echo $this->get_field_name('thumb_height'); ?>" type="text" value="<?php echo attribute_escape($thumb_height); ?>" /></label></p>
      
      <p><label for="<?php echo $this->get_field_id('thumb_width'); ?>">Thumbnail Width in pixels<br /><em>(defaults to 150):</em><br /><input class="widefat" id="<?php echo $this->get_field_id('thumb_width'); ?>" name="<?php echo $this->get_field_name('thumb_width'); ?>" type="text" value="<?php echo attribute_escape($thumb_width); ?>" /></label></p>

      <p><label for="<?php echo $this->get_field_id('displaytitle'); ?>">Display title below pins? &nbsp;<select id="<?php echo $this->get_field_id('displaytitle'); ?>" name="<?php echo $this->get_field_name('displaytitle'); ?>">
        <?php 
  	  echo '<option ';
          if ( $instance['displaytitle'] == 'yes' ) { echo 'selected '; }
          echo 'value="yes">';
	  echo 'Yes</option>';
  	  echo '<option ';
          if ( $instance['displaytitle'] == 'no' ) { echo 'selected '; }
          echo 'value="no">';
	  echo 'No</option>'; ?>
      </select></label></p>
      
      <p><label for="<?php echo $this->get_field_id('target'); ?>">Where to open the links: <br /><select id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>">
        <?php 
  	  echo '<option ';
          if ( $instance['target'] == 'samewindow' ) { echo 'selected '; }
          echo 'value="samewindow">';
	  echo 'Same Window</option>';
  	  echo '<option ';
          if ( $instance['target'] == 'newwindow' ) { echo 'selected '; }
          echo 'value="newwindow">';
	  echo 'New Window</option>'; ?>
      </select></label></p>      
      
      <p><label for="<?php echo $this->get_field_id('showfollow'); ?>">Show "Follow Me On Pinterest" button? <br /><select id="<?php echo $this->get_field_id('showfollow'); ?>" name="<?php echo $this->get_field_name('showfollow'); ?>">
        <?php 
			echo '<option ';
				if ( $instance['showfollow'] == 'large' ) { echo 'selected '; }
					echo 'value="large">';
					echo 'Large (156x26) </option>';
			echo '<option ';
				if ( $instance['showfollow'] == 'medium' ) { echo 'selected '; }
					echo 'value="medium">';
					echo 'Medium (78x26) </option>'; 
			echo '<option ';
				if ( $instance['showfollow'] == 'small' ) { echo 'selected '; }
					echo 'value="small">';
					echo 'Small (61x61) </option>';
			echo '<option ';
				if ( $instance['showfollow'] == 'tiny' ) { echo 'selected '; }
					echo 'value="tiny">';
					echo 'Tiny (16x16) </option>';
			echo '<option ';
				if ( $instance['showfollow'] == 'none' ) { echo 'selected '; }
					echo 'value="none">';
					echo 'None </option>';
		?>
      </select></label></p>

<?php
																			}
}

// register_widget('Pinterest_RSS_Widget');
add_action( 'widgets_init', create_function('', 'return register_widget("Pinterest_RSS_Widget");') );

add_filter( 'wp_feed_cache_transient_lifetime', create_function('$a', 'return 600;') );

?>