=== Mindvalley Post & Get Variables ===
Contributors: mindvalley
Donate link: http://www.mindvalley.com/opensource
Tags: post, get, variables
Requires at least: 3.0.0
Tested up to: 3.1.3
Stable tag: 1.0.6

Lets you output a POST or GET variable in the page via shortcode.


== Description ==

Outputs $_POST or $_GET variable values using shortcodes.

Super duper uberly useful on tracking scripts on eCommerce thank you pages.

Example:

* $_POST['email'] > [post_var name="email"]
* $_GET['txn_id'] > [get_var name="txn_id"]

For Array values :

* $_POST['user']['email'] > [post_var] name="user[email]" [/post_var]
* $_GET['user']['first_name'] > [get_var] name="user[first_name]" [/get_var]
* $_GET['user']['address']['line1'] > [get_var] name="user[address][line1]" [/get_var]

Note: Remember to wrap the name attribute in double quotes (").


== Installation ==

1. Upload plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!


== Frequently Asked Questions ==

= Why I can't get some of the URL variables? =

WordPress has a set of reserved GET variables that is used for page querying. For example, 'name' or 'page_id' or 'id' can't be used without having some unexpected results.

= Array variables are not showing up. =

Verify that you wrap your the variable settings within a double quote ("), or else it won't work.


== Changelog ==

= 1.0 =
Initial Release.


= 1.0.2 =
Add support for array $_POST / $_GET variables


= 1.0.3 =
Additional checks to avoid unnecessary PHP warnings if the key provided appears to be an array.


= 1.0.4 =
Bug fix.


= 1.0.6 =
Support PHP auto replacements POST / GET variables with underscores

