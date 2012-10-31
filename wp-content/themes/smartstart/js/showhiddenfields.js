/*! jQuery script to hide certain form fields */

$(document).ready(function() {

	//Hide the fields initially
	$("#recipient-name").hide();
	$("#gift-message").hide();

	//Show the text field only when the third option is chosen
	$("select[name=gift]").change(function() {
		if ($("select[name=gift]").val() == "It's for somebody else") {
			$("#recipient-name").show();
			$("#gift-message").show();
		}
		else {
			$("#recipient-name").hide();
			$("#gift-message").hide();
		}
	});
});