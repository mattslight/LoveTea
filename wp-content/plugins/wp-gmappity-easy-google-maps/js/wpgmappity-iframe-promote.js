function wpgmappity_set_promotion_event(map, data) {
  jQuery("#wpgmappity_promote").change(wpgmappity_promotion_callback(map, data));
}

function wpgmappity_promotion_callback(map, data) {
  return function() {

      /*
       * Set checkmark checked event
       */
      if ( jQuery("#wpgmappity_promote").is(':checked') )
      {
	data.promote = '1';
	var text = '<a href="http://www.wordpresspluginfu.com/wpgmappity/" target="_blank">';
	text += 'Google Maps for WordPress by WPGmappity</a>';
	jQuery("#wpgmappity_promote_text").html(text);
      }
      /*
       * Set checkmark unchecked event
       */
      else
      {
        data.promote = '0';
	jQuery("#wpgmappity_promote_text").html('');
      }
  };
}