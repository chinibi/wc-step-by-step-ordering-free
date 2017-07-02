(function($) {
$(document).ready(function() {
  $('.widget_shopping_cart').click(function(e) {
    if ( $(e.target).is('a:not(.remove), img') ) {
      e.preventDefault();
    }
  });
})
})(jQuery);
