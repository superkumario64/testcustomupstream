/**
 * Override jQuery Form's ajaxSubmit method in order to prevent it setting
 * iframeSrc to javascript:false for https requests, because that no longer
 * works with Chromium-based browsers.
 * - https://www.drupal.org/project/drupal/issues/3138421
 *
 * Aim to be compatible with jQuery Form v4.2.2
 * - https://github.com/jquery-form/form/blob/v4.2.2/src/jquery.form.js#L121
 */

(function (jQuery) {

  // Exclude Internet Explorer / Trident-based browsers.
  var ua = navigator.userAgent;
  if (ua.indexOf("MSIE ") > -1 || ua.indexOf("Trident/") > -1) {
    return;
  }

  var fnOriginalAjaxSubmit = jQuery.fn.ajaxSubmit;
  jQuery.fn.extend({
    ajaxSubmit: function (options, data, dataType, onSuccess) {

      if (typeof options === 'function') {
        options = { success: options };

      } else if (typeof options === 'string' || (options === false && arguments.length > 0)) {
        options = {
          'url': options,
          'data': data,
          'dataType': dataType
        };

        if (typeof onSuccess === 'function') {
          options.success = onSuccess;
        }

      } else if (typeof options === 'undefined') {
        options = {};
      }

      options = jQuery.extend(true, {
        iframeSrc: 'about:blank'
      }, options);

      return fnOriginalAjaxSubmit.call(this, options, data, dataType, onSuccess);
    }
  });

})(jQuery);
