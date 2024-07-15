/**
 * Document ready function to initialize the media field
 * @param {jQuery} $ - The `$` parameter in the `getGalleryMedia` function is typically used as a shorthand for
 * the jQuery library. It allows you to use jQuery methods and functions within the function.
 */
jQuery(document).ready(function ($) {
  wp_media($);
  console.log('media_fields_running');
});
/**
 * The function `wp_media` checks if the `wp.media` object is defined and then performs operations
 * related to getting, adding, and removing media.
 * @param {jQuery} $ - The `$` symbol is commonly used as a shorthand for the jQuery library in JavaScript code.
 * It is often used as a parameter name to represent the jQuery object within a function.
 * @returns {boolean} The function `wp_media` is returning `false`.
 */
function wp_media($) {
  if (typeof wp.media !== 'undefined') {
    getMedia($);
    addMedia($);
    removeMedia($);
  }
  return false
}

/**
 * The function `getMedia` in JavaScript opens a media editor with custom settings.
 * @param {jQuery} $ - The `$` parameter in the `getMedia` function is typically used as a shorthand for the
 * jQuery library. It allows you to use jQuery methods and functions within the function.
 */
function getMedia($) {
  let _custom_media = true;
  let _orig_send_attachment = wp.media.editor.send.attachment;
  openMedia($, _custom_media, _orig_send_attachment);
}

/**
 * The function `openMedia` is designed to handle the opening of media attachments in WordPress when a
 * specific button with class `open-media` is clicked.
 * @param {jQuery} $ - The `$` parameter in the `openMedia` function is typically used as a shorthand for the
 * jQuery library. It allows you to use jQuery methods and functions within the function without having
 * to repeatedly type out "jQuery".
 * @param {boolean} _custom_media - _custom_media is a boolean variable that is used to determine if the media
 * being opened is a custom media. In the provided function, it is set to true before calling the
 * openMediaAttachment function. This variable can be used to customize the behavior of the media
 * opening process based on whether it is a custom
 * @param {function()|object} _orig_send_attachment - The `_orig_send_attachment` parameter in the `openMedia` function is
 * a reference to the original `send.attachment` function from the `wp.media.editor` object. This
 * reference is stored in the variable `send_attachment_bkp` before the function is modified within the
 * `click` event handler.
 * @returns The function `openMedia` is returning nothing explicitly, as it ends with `return;` without
 * any value being returned.
 */
function openMedia($, _custom_media, _orig_send_attachment) {
  $('.open-media').click(function (e) {
    let send_attachment_bkp = wp.media.editor.send.attachment;
    let button = $(this);
    let id = button.data('target');
    let preview = $('#image-preview-' + id);
    let _input = $('input#' + id);
    let _return = _input.data('return');
    _custom_media = true;
    openMediaAttachment(_input, _return, _custom_media, preview, $);
    wp.media.editor.open(button);
    return;
  });
}

/**
 * The function `openMediaAttachment` handles media attachments in WordPress, allowing for custom media
 * handling and displaying a preview image.
 * @param {element|object} _input - The `_input` parameter is typically a jQuery object representing an input field
 * where the selected media attachment's value will be stored.
 * @param {string} _return - The `_return` parameter is used to specify the target element where the selected
 * media attachment value will be returned or set.
 * @param {boolean} _custom_media - _custom_media is a boolean parameter that indicates whether the media
 * attachment is custom or not. If it is set to true, the function will handle the custom media
 * attachment logic, otherwise it will fall back to the default behavior.
 * @param {element|object} preview - The `preview` parameter in the `openMediaAttachment` function is likely a jQuery
 * object representing an image element where the selected media attachment will be displayed for
 * preview.
 * @param {jQuery} $ - The `$` parameter in the `openMediaAttachment` function is likely a reference to the
 * jQuery library. It is commonly used in WordPress development to manipulate the DOM and handle events
 * in a more convenient way.
 * @returns In the provided code snippet, the function `openMediaAttachment` is returning either the
 * value `_val` when `_custom_media` is true, or it is returning the result of calling
 * `_orig_send_attachment` with the arguments `props` and `attachment` when `_custom_media` is false.
 */
function openMediaAttachment(_input, _return, _custom_media, preview, $) {
  wp.media.editor.send.attachment = function (props, attachment) {
    if (_custom_media) {
      let _val = returnValue(_return, attachment);
      _input.val(_val);
      preview.attr('src', attachment.url);
      showRemoveBtn($, attachment.url);
    } else {
      return _orig_send_attachment.apply(this, [props, attachment]);
    };
  }
}

/**
 * The function `returnValue` returns either the URL or ID of an attachment based on the input
 * parameter `_return`.
 * @param {string} _return - The `_return` parameter is used to determine whether to return the URL or the ID of
 * an attachment.
 * @param {object} attachment - Attachment is an object that likely contains information about a file or
 * resource, such as its ID and URL.
 * @returns {string} either the URL of the attachment if the `_return` parameter is set to 'url', or the ID of
 * the attachment if `_return` is not 'url'.
 */
function returnValue(_return, attachment) {
  if (_return == 'url') {
    return attachment.url;
  } 
  return attachment.id;
}

/**
 * The function `addMedia` is designed to handle a click event on elements with the class `add_media`
 * and set a variable `_custom_media` to false.
 * @param {jQuery} $ - In the provided code snippet, the parameter `$` is likely being used as an alias for the
 * jQuery library. This is a common practice in jQuery code to reference the jQuery object using a
 * shorthand notation.
 */
function addMedia($) {
  $('.add_media').on('click', function () {
    _custom_media = false;
  });
}
/**
 * The function `removeMedia` is used to remove media files and hide the corresponding element when a
 * specific button with class `remove-media` is clicked.
 * @param {jQuery} $ - In the provided code snippet, the parameter `$` is likely referring to the jQuery
 * library. In jQuery, the `$` symbol is commonly used as a shorthand alias for the jQuery object,
 * which allows you to easily select and manipulate DOM elements using jQuery methods.
 */

function removeMedia($) {
  $('.remove-media').on('click', function () {
    let target = $(this).data('target');
    $('input#' + target).val('');
    $('#image-preview-' + target).attr('src', '');
    $(this).hide();
  });
}

/**
 * The function `showRemoveBtn` toggles the visibility of elements with the class `remove-media` based
 * on the presence of a URL parameter.
 * @param {jQuery} $ - The parameter `$` is typically used as a shorthand for the jQuery library in JavaScript.
 * It allows you to easily select and manipulate elements on a webpage. In the provided function
 * `showRemoveBtn`, the `$` parameter is likely being used to access jQuery methods to show or hide
 * elements with the class `.
 * @param {string} url - The `url` parameter in the `showRemoveBtn` function is a string that represents the
 * URL of a media file. If a URL is provided, the function will show a remove button with the class
 * `remove-media`. If no URL is provided (empty string or not provided), the remove button
 */
function showRemoveBtn($, url="") {
  if (url) {
    $('.remove-media').show();
  } else {
    $('.remove-media').hide();
  }
}