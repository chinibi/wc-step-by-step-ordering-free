(function($) {

$(document).ready(function() {

  var $varForm = $('form.variations_form');
  var $varFormInputs = $('form.variations_form :input');

  $varFormInputs.change(function() {

    var formData = $(this.form).serializeArray();

    var variationID = null;
    for (var i = formData.length - 1; i>=0; i--) {
      if ( formData[i].name === 'variation_id' && formData[i].value ) {
        variationID = parseInt(formData[i].value);
        break;
      }
    }

    var variationImage = null;
    if (variationID) {
      var productVariations = $(this.form).data('product_variations');
      for (var i = 0; i < productVariations.length; i++) {
        if (productVariations[i].id === variationID || productVariations[i].variation_id === variationID) {
          variationImage = productVariations[i].image;
          break;
        }
      }
    }

    if (variationImage) {
      var $image = $('.mfp-content .modal-image img');
      $image.attr({
        width:  variationImage.src_w  ? variationImage.src_w  : $image.attr('width'),
        height: variationImage.src_h  ? variationImage.src_h  : $image.attr('height'),
        src:    variationImage.src    ? variationImage.src    : $image.attr('src'),
        sizes:  variationImage.sizes  ? variationImage.sizes  : $image.attr('sizes'),
        srcset: variationImage.srcset ? variationImage.srcset : null,
        alt:    variationImage.alt    ? variationImage.alt    : $image.attr('alt')
      });
    }

  });

});

})(jQuery);
