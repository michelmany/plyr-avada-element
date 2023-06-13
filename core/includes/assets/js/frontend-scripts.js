/*------------------------- 
Frontend related javascript
-------------------------*/

(function( $ ) {

	"use strict";

    $(document).ready( function() {
        $.ajax({
            type : "post",
            dataType : "json",
            url : plyrae.ajaxurl,
            data : {
                action: "my_demo_ajax_call", 
                demo_data : 'test_data', 
                ajax_nonce_parameter: plyrae.security_nonce
            },
            success: function(response) {
                console.log( response );
            }
        });
    });

})( jQuery );
