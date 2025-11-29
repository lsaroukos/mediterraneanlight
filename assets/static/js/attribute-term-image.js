jQuery(function($){

    function openMediaPicker(button) {
        const frame = wp.media({
            title: 'Select or Upload Image',
            button: { text: 'Use Image' },
            multiple: false
        });

        frame.on('select', function(){
            const attachment = frame.state().get('selection').first().toJSON();

            // The input (hidden) is right before the preview container
            const container = button.closest('.form-field, td');
            container.find('#term_image').val(attachment.id);
            container.find('#term_image_preview')
                .html('<img src="' + attachment.sizes.thumbnail.url + '" style="max-width:80px;">');

            container.find('.remove_term_image_button').show();
        });

        frame.open();
    }

    $(document).on('click', '.upload_term_image_button', function(e){
        e.preventDefault();
        openMediaPicker($(this));
    });

    $(document).on('click', '.remove_term_image_button', function(e){
        e.preventDefault();

        const container = $(this).closest('.form-field, td');
        container.find('#term_image').val('');
        container.find('#term_image_preview').html('');
        $(this).hide();
    });

});
