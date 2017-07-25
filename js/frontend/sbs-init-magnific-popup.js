(function($) {

$(document).ready(function() {
  // prevent thumbnails on shop pages from opening links; they will instead
  // open a modal
  $('.woocommerce-LoopProduct-link, .woocommerce-LoopProduct-link img, .woocommerce-LoopProduct-link .woocommerce-loop-product__title, .woocommerce-LoopProduct-link .price').on('contextmenu', function(e) {
    e.preventDefault();
  });

  $('.open-popup-link').magnificPopup({
    type: 'inline',
    midClick: true
  });

});

})(jQuery);
