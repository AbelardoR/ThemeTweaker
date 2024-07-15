
/**
 * Document ready function to initialize the media gallery fields
 * @param {jQuery} $ - The `$` parameter in the `getGalleryMedia` function is typically used as a shorthand for
 * the jQuery library. It allows you to use jQuery methods and functions within the function.
 */
jQuery(document).ready(function ($) {

    // Call the wp_gallery_media function to initialize the media gallery
    wp_gallery_media($);
    console.log('media_gallery_fields_running');
});

/**
 * The function `isMulti` returns true if the input value is "true" or greater than 0, otherwise it
 * returns false.
 * @param  {string|number} value - The function `isMulti` checks if the `value` is equal to the string "true" or if it
 * is greater than 0. If either condition is met, the function returns `true`, otherwise it returns
 * `false`.
 * @returns The function `isMulti` returns `true` if the `value` is equal to the string "true" or if it
 * is greater than 0. Otherwise, it returns `false`.
 */
function isMulti(value) {
    // If the value is "true" or greater than 0, return true
    if (value == "true" || value > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * The function `wp_gallery_media` checks if `wp.media` is defined and performs operations related to
 * gallery media if it is.
 * @param {jQuery} $ - The `$` symbol is commonly used as a shorthand for the jQuery library in JavaScript code.
 * It is often used as a parameter name in functions to reference the jQuery object. In this context,
 * it seems like the function `wp_gallery_media` is expecting the jQuery object to be passed as a
 * parameter so
 * @returns The function `wp_gallery_media` is returning `false`.
 */
function wp_gallery_media($) {
    // Check if wp.media is defined
    if (typeof wp.media !== 'undefined') {
        // Get the gallery media
        getGalleryMedia($);
        // Add remove gallery media item
        addRemoveGalleryMediaItem($);       
        // Remove gallery media item
        removeGalleryMediaItem($);
    }
    return false
}


/**
 * The function `getGalleryMedia` in JavaScript handles uploading and displaying gallery media in
 * WordPress.
 * @param {jQuery} $ - The `$` symbol is commonly used as a shorthand in JavaScript to represent the jQuery
 * library. It is often used as a parameter name in functions to indicate that jQuery is being passed
 * in.
 */
function getGalleryMedia($) {
    // Uploading files
    let file_frame;
    let wp_media_post_id = wp.media.model.settings.post.id; // Store the old ID
    let set_to_post_id = ''; // This variable is used to store the post ID

    // Open the gallery media
    openGalleryMedia($, file_frame, wp_media_post_id, set_to_post_id);
    // Restore the main ID when the add media button is pressed
    addGalleryMedia($, wp_media_post_id)
}

/**
 * The function `openGalleryMedia` opens a media gallery for selecting images and setting
 * them to a specified post ID.
 * 
 * @param {jQuery} $ - The `$` parameter is typically used as a shorthand for the jQuery library in JavaScript
 * code. It allows you to access jQuery functions and methods using the `$` symbol. In this context, it
 * is being passed into the `openGalleryMedia` function to use jQuery functionalities within the
 * function.
 * @param {object|any} file_frame- The `file_frame` parameter is used to store the reference to the media frame
 * that is created for selecting/uploading images in WordPress. It helps in managing the state of the
 * media frame and reusing it if it already exists to avoid creating multiple instances.
 * @param {number|string} wp_media_post_id - The `wp_media_post_id` parameter in the `openGalleryMedia` function is
 * used to store the ID of the WordPress post where the media will be attached or associated with. This
 * ID is used to set the post ID for the media uploader so that the media is uploaded to the correct
 * post.
 * @param {number|string} @param set_to_post_id - The `set_to_post_id` parameter in the `openGalleryMedia` function is used to
 * store the ID of the post to which the media will be attached or associated with. This ID is then
 * used when initializing the media uploader to ensure that the uploaded media is linked to the correct
 * post.
 * @returns {void} The `openGalleryMedia` function returns nothing explicitly. It is an event handler function
 * that sets up a click event listener on elements with the class `upload_image_button` to open a media
 * frame for selecting images. The function does not have a return statement that explicitly returns a
 * value.
 */
function openGalleryMedia($, file_frame, wp_media_post_id, set_to_post_id) {
    // On click of the upload image button
    $('.upload_image_button').on('click', function (event) {
        let multi = isMulti($(this).attr('data-multi'));
        let previewer = $(this).attr('data-prev');
        set_to_post_id = $(this).data('target');

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (file_frame) {
            // Set the post ID to what we want
            file_frame.uploader.uploader.param('post_id', set_to_post_id);

            // Set the wp.media post ID so the uploader grabs the ID
            wp.media.model.settings.post.id = set_to_post_id;

            // Open frame
            file_frame.open();
            return;
        } else {
            // Set the wp.media post ID so the uploader grabs the ID we want when initialised
            wp.media.model.settings.post.id;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select a image to upload',
            button: {
                text: 'Use this image',
            },
            multiple: multi // Set to true to allow multiple files to be selected
        });
        // When an image is selected, run a callback.
        onSelectedGalleryMedia($, file_frame, wp_media_post_id, set_to_post_id, previewer);

        // Finally, open the modal
        file_frame.open();
        return;
    });

}

/**
 * The function `onSelectedGalleryMedia` handles the selection of media items in a WordPress gallery,
 * allowing for single or multiple image selection and updating the preview accordingly.
 * 
 * @param {jQuery} $ - In the provided function `onSelectedGalleryMedia`, the parameter `$` is likely being used
 * as a reference to the jQuery object. This is a common practice in JavaScript to alias the jQuery
 * object as `$` for brevity and convenience. It allows you to use the familiar `$` shorthand for
 * jQuery
 * @param {object|any} file_frame - The `file_frame` parameter in the `onSelectedGalleryMedia` function is a
 * reference to the WordPress media uploader frame. It is used to handle the selection of media items
 * (images in this case) from the media library. The function sets up an event listener for when media
 * items are selected using
 * @param {number|string} wp_media_post_id - The `wp_media_post_id` parameter in the `onSelectedGalleryMedia` function
 * is used to store the main post ID. This ID is later restored after processing the selected media
 * items from the gallery.
 * @param {number|string} @param set_to_post_id - The `set_to_post_id` parameter in the `onSelectedGalleryMedia` function is
 * used to specify the ID of the input field where the selected image(s) ID should be set. This ID is
 * used to target the specific input field in the DOM and update its value with the ID of the
 * @param {string} previewer - The `previewer` parameter in the `onSelectedGalleryMedia` function is used to
 * specify the ID of the element where the selected images will be previewed before adding them to the
 * gallery.
 */
function onSelectedGalleryMedia($, file_frame, wp_media_post_id, set_to_post_id, previewer) {
    file_frame.on('select', function () {

        // We set multiple to false so only get one image from the uploader
        if (!file_frame.state().attributes.multiple) {
            attachment = file_frame.state().get('selection').first().toJSON();
            // Do something with attachment.id and/or attachment.url here
            // AddPrevImgInput(attachment.id, attachment.url);
            $("#" + set_to_post_id).val(attachment.id);
        } else {
            let newIput = [];
            let newImg = '';
            let _previewer = $("#" + previewer);
            attachment = file_frame.state().get('selection').toJSON();
            attachment.forEach((Img, index) => {
                newImg = '<div class="img-box" id="img-box-'+index+'" data-img="'+Img.id+'">';
                newImg+= '<span class="remove-img">X</span>';
                newImg+= '<img id="image-preview-'+Img.id+'" src="'+Img.url+'" >';
                newImg+= '</div>';
                if (_previewer.find("#image-preview-" + Img.id).length > 0) {
                    alert(Img.title + ': image allready exists on this gallery please select another');
                } else {
                    _previewer.append(newImg);
                    newIput[index] = Img.id;
                }
            });
            removeGalleryMediaItem($);
            onSetGalleryMedia($, set_to_post_id, newIput);
        }
        // Restore the main post ID
        wp.media.model.settings.post.id = wp_media_post_id;
    });
}

/**
 * The function `onSetGalleryMedia` updates a hidden input field with a comma-separated list of values
 * by adding new values and removing zeros.
 * @param {jQuery} $ - The `$` parameter in the `onSetGalleryMedia` function is typically used as an alias for
 * the jQuery library. It allows you to use jQuery methods and functionalities within the function.
 * @param {number|string} set_to_post_id - The `set_to_post_id` parameter in the `onSetGalleryMedia` function is the ID
 * of the input element where the gallery media values will be set.
 * @param {Array<number>} newIput - The `newIput` parameter in the `onSetGalleryMedia` function appears to represent an
 * array of new values that need to be added to the existing values in the input field.
 * @returns {string} The function `onSetGalleryMedia` is returning the updated value of the input field with the
 * ID `set_to_post_id` after adding the new input `newIput` to the existing comma-separated list of
 * values in the input field.
 */
function onSetGalleryMedia($, set_to_post_id, newIput) {
    $("#" + set_to_post_id).val(function () {
        let currentValue = this.value;
        let valToArray = currentValue.split(",").map(Number);
        
        valToArray = valToArray.concat(newIput);
        valToArray = valToArray.filter(item => item !== 0)
        
        currentValue = valToArray.join();
        return currentValue;
    });
}

/**
 * The function `addGalleryMedia` sets the post ID for WordPress media settings when a media button is
 * clicked.
 * @param {jQuery} $ - The `$` parameter is typically used as a shorthand for the jQuery library in JavaScript.
 * It is commonly used to select and manipulate elements in the DOM.
 * @param {number|string} wp_media_post_id - The `wp_media_post_id` parameter in the `addGalleryMedia` function is the
 * ID of the post to which the media will be attached when the user clicks on the "Add Media" button in
 * the gallery.
 */
function addGalleryMedia($, wp_media_post_id) {
    $('a.add_media').on('click', function () {
        wp.media.model.settings.post.id = wp_media_post_id;
    });
}

/**
 * The function adds a remove button to each image preview in a gallery.
 * @param {jQuery} $ - In the provided JavaScript function, the parameter `$` is likely being used as a
 * shorthand or alias for the jQuery library. This is a common practice in jQuery code where the `$`
 * symbol is used as a reference to the jQuery object to make the code more concise and readable.
 */
function addRemoveGalleryMediaItem($) {
    $(".image-preview-wrapper .img-box").prepend('<span class="remove-img">X</span>');
}

/**
 * The function `removeGalleryMediaItem` removes a media item from a gallery and updates the
 * corresponding input field.
 * @param {jQuery} $ - The `$` parameter in the `removeGalleryMediaItem` function is typically used as an alias
 * for the jQuery library. It allows you to use jQuery methods and functions within the scope of the
 * function without having to repeatedly type out "jQuery" or "$" each time. This is a common practice
 * in
 * @returns The function `removeGalleryMediaItem` is not returning anything explicitly. It is a
 * function that removes a media item from a gallery based on the user's interaction with a remove
 * button.
 */
function removeGalleryMediaItem($) {
    $(".remove-img").click(function () {
        let to_remove = $(this).parent(".img-box").attr("data-img");
        let get_input = $(this).closest(".image-preview-wrapper").data('target');
        let currentValue = $("input#" + get_input).val();

        if (currentValue !== "") {
            let valToArray = currentValue.split(",").map(Number);
            valToArray = valToArray.filter(function(item) {
                return item !== parseInt(to_remove);
            });
            currentValue = valToArray.join();
        }
        
        $("#" + get_input).val(currentValue);
        $(this).parent(".img-box").remove();

    });
}
