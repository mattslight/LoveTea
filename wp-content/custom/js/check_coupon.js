jQuery(function () {
    jQuery("#coupon-code").blur(function () {
        var coupon = jQuery("#coupon-code").val();
        if (coupon) {
            alert("Coupon Used");
            jQuery.ajax({
                success: function (data) {
                    if (data) {
                        alert("DATA RECEIVED");
                    }
                },
                data: 'coupon=' + coupon,
                type: 'POST',
                dataType: 'json',
                url: '/wp-content/custom/process-coupon.php'
            });
        }
        return false;
    });
});