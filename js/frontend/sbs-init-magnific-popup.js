(function($) {

$(document).ready(function() {
  console.log('use-magnific-popup.js loaded');
  // prevent thumbnails on shop pages from opening links; they will instead
  // open a modal
  $('.woocommerce-LoopProduct-link').click(function(e) {
    e.preventDefault();
  });

  $('.open-popup-link').magnificPopup({
    type: 'inline',
    midClick: true
  });

});

})(jQuery);
