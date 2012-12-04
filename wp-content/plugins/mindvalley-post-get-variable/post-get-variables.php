<?php
/*
Plugin Name: Mindvalley Post & Get Variables
Description: Lets you output a POST or GET variable in the page via shortcode.
Author: Mindvalley
Version: 1.0.6
*/

/*
 * Example :
 * 
 * <script type="text/javascript">
 *      var pageTracker = _gat._getTracker("UA-XXXXX-1");
 *      pageTracker._trackPageview();
 *      pageTracker._addTrans(
 *          "[post_var name='txn_id']", // Order ID
 *          "[post_var name='aff']", // Affiliation
 *          "[post_var name='total']", // Total
 *          "[post_var name='tax']", // Tax
 *          "[post_var name='shipping']", // Shipping
 *          "[post_var name='city']", // City
 *          "[post_var name='state']", // State
 *          "[post_var name='country']" // Country
 *      );
 * </script>
 * 
 * For array values :
 * 
 * [post_var] name=search[user][email] [/post_var]
 * [get_var] name=search[user][first_name] [/get_var]
 * [get_var] name=search[user][last_name] [/get_var]
 * 
 */
Class MV_Post_Get_Variables {
    function __construct(){
        add_shortcode('post_var', array(&$this,'post_var'));
        add_shortcode('get_var', array(&$this,'get_var'));
    }
    
    function post_var($atts,$content){
        if(!is_array($atts)){
			$atts = array();
		}
		$atts = $this->convert_block_to_params($atts,$content);

		/** 
		 * 	PHP Auto Replacements 
		 * 	http://www.php.net/manual/en/language.variables.external.php#81080
		 * 	
		 * 	chr(32) ( ) (space)
		 * 	chr(46) (.) (dot)
		 * 	chr(91) ([) (open square bracket)
		 * 	chr(128) - chr(159) (various)
		 * 	
		 */

		$replacements = array();
		$replacements[] = chr(32);
		$replacements[] = chr(46);
		$replacements[] = chr(91);
		$replacements[] = chr(128);
		$replacements[] = chr(129);
		$replacements[] = chr(130);
		$replacements[] = chr(131);
		$replacements[] = chr(132);
		$replacements[] = chr(133);
		$replacements[] = chr(134);
		$replacements[] = chr(135);
		$replacements[] = chr(136);
		$replacements[] = chr(137);
		$replacements[] = chr(138);
		$replacements[] = chr(139);
		$replacements[] = chr(140);
		$replacements[] = chr(141);
		$replacements[] = chr(142);
		$replacements[] = chr(143);
		$replacements[] = chr(144);
		$replacements[] = chr(145);
		$replacements[] = chr(146);
		$replacements[] = chr(147);
		$replacements[] = chr(148);
		$replacements[] = chr(149);
		$replacements[] = chr(140);
		$replacements[] = chr(151);
		$replacements[] = chr(152);
		$replacements[] = chr(153);
		$replacements[] = chr(154);
		$replacements[] = chr(155);
		$replacements[] = chr(156);
		$replacements[] = chr(157);
		$replacements[] = chr(158);
		$replacements[] = chr(159);
	
		// Accomodate for PHP auto replace with underscore
		$key = str_replace($replacements,'_',urldecode($atts['name']));

		// Array Key
		if($pos = strpos($key,'[')){
			$basekey = substr($key,0,$pos);
			$remainder = substr($key,$pos);

			eval('$value = $_POST["' . $basekey . '"]' . $remainder . ';');
		}else{
			$value = $_POST[$key];
		}
		
        if(!empty($value) && is_string($value)){	// is_string() to avoid unnecessary warnings if key is an array
            return htmlentities($value);
        }
        return '';
    }
    
    function get_var($atts,$content){
		if(!is_array($atts)){
			$atts = array();
		}
		$atts = $this->convert_block_to_params($atts,$content);
		$key = $atts['name'];

		/** 
		 * 	PHP Auto Replacements 
		 * 	http://www.php.net/manual/en/language.variables.external.php#81080
		 * 	
		 * 	chr(32) ( ) (space)
		 * 	chr(46) (.) (dot)
		 * 	chr(91) ([) (open square bracket)
		 * 	chr(128) - chr(159) (various)
		 * 	
		 */

		$replacements = array();
		$replacements[] = chr(32);
		$replacements[] = chr(46);
		$replacements[] = chr(91);
		$replacements[] = chr(128);
		$replacements[] = chr(129);
		$replacements[] = chr(130);
		$replacements[] = chr(131);
		$replacements[] = chr(132);
		$replacements[] = chr(133);
		$replacements[] = chr(134);
		$replacements[] = chr(135);
		$replacements[] = chr(136);
		$replacements[] = chr(137);
		$replacements[] = chr(138);
		$replacements[] = chr(139);
		$replacements[] = chr(140);
		$replacements[] = chr(141);
		$replacements[] = chr(142);
		$replacements[] = chr(143);
		$replacements[] = chr(144);
		$replacements[] = chr(145);
		$replacements[] = chr(146);
		$replacements[] = chr(147);
		$replacements[] = chr(148);
		$replacements[] = chr(149);
		$replacements[] = chr(140);
		$replacements[] = chr(151);
		$replacements[] = chr(152);
		$replacements[] = chr(153);
		$replacements[] = chr(154);
		$replacements[] = chr(155);
		$replacements[] = chr(156);
		$replacements[] = chr(157);
		$replacements[] = chr(158);
		$replacements[] = chr(159);
	
		// Accomodate for PHP auto replace with underscore
		$key = str_replace($replacements,'_',urldecode($atts['name']));
				
		// Array Key
		if($pos = strpos($key,'[')){
			$basekey = substr($key,0,$pos);
			$remainder = substr($key,$pos);
			
			eval('$value = $_GET["' . $basekey . '"]' . $remainder . ';');
		}else{
			$value = $_GET[$key];
		}
		
        if(!empty($value) && is_string($value)){	// is_string() to avoid unnecessary warnings if key is an array
            return htmlentities($value);
        }
        return '';
    }

	function convert_block_to_params($params,$text){
		// Wordpress Funky Encoding
		$text = str_replace("&#8221;",'"',$text);
		$text = str_replace("&#8243;",'"',$text);
		$text = str_replace("–","-",$text);
		$text = str_replace('″','"',$text);
		$text = str_replace("’","'",$text);
		
		$values = array();
        $search = array(chr(145), chr(146), chr(147), chr(148), chr(151));
        $replace = array("'", "'", '"', '"', '-');
        $text = str_replace($search, $replace, $text);
        $text = str_replace('”','"',$text);
        $text = str_replace('\\"',"&quot;",$text);
        $text = str_replace("\\'","&#39;",$text);
        $text = str_replace('\\$','$',$text);
        $text = str_replace('\\{','{',$text);
        $text = str_replace('\\}','}',$text);
        $text = str_replace("<br />\n","\n ",$text);
        $text = str_replace("<p>","",$text);
        $text = str_replace("</p>","",$text);
		
		if (preg_match_all('/([^=\s]+)(\s|)=(\s|)("(?P<value1>[^"]+)"|\'(?P<value2>[^\']+)\'|(?P<value3>.+?)\b)/', $text, $matches, PREG_SET_ORDER))
            foreach ($matches as $match)
                $values[trim($match[1])] = trim(@$match['value1'] . @$match['value2'] . @$match['value3']);

		return array_merge_recursive($params,$values);
    }
}

new MV_Post_Get_Variables();