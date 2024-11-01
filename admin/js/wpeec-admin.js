jQuery(function ($) {
  $(document).on('click','#wpeec-event-form-mode-modal',function (e) {
    $('.wpeec-button-text-wrapper').slideDown();
    $('.wpeec-frame-height-wrapper').slideUp();
  });

  $(document).on('click','#wpeec-event-form-mode-embed',function (e) {
    $('.wpeec-button-text-wrapper').slideUp();
    $('.wpeec-frame-height-wrapper').slideDown();
  });

  $(document).ready(function() {
    if ($('#wpeec-event-form-mode-modal').is(':checked')) {
      $('.wpeec-button-text-wrapper').show();
      $('.wpeec-frame-height-wrapper').hide();
    } else {
      $('.wpeec-button-text-wrapper').hide();
      $('.wpeec-frame-height-wrapper').show();
    }
  });
});