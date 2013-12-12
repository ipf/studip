/*jslint browser: true, white: true, undef: true, nomen: true, eqeqeq: true, plusplus: true, bitwise: true, newcap: true, immed: true, indent: 4, onevar: false */
/*global window, $, jQuery, _ */

STUDIP.Messaging = {
    addToAdressees: function (username, name) {
            jQuery("form[name=upload_form]")
                .attr("action", STUDIP.URLHelper.getURL("?", {
                    "add_receiver[]": username,
                    "add_receiver_button_x": true
                }))
            [0].submit();
            return;
    }
};
