(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     */
    jQuery(document).ready(function($) {
        $(document).on('click', '#pct_views_image_button', function(e) {
            e.preventDefault();

            // Create a new media frame.
            var frame = wp.media({
                title: 'Select or Upload an Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            // When an image is selected, run this function.
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#pct_views_image').val(attachment.url);
                $('#pct_views_image_preview').attr('src', attachment.url);
            });

            // Open the media frame.
            frame.open();
        });
    });



})(jQuery);