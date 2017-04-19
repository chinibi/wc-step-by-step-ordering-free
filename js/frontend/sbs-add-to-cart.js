(function($) {

$(document).ready(function() {

  $(document.body).on('adding_to_cart', function(event, $button, data) {

    data.action = 'sbs_adding_to_cart';

    $.post(sbsAjaxUrl, data, function(response) {

      if (!response) {
        return;
      }

      console.log(response);

      accounting.settings = {
        currency: {
          symbol: response.format.currency,
          format: "%s%v",
          decimal: response.format.decimal_separator,
          thousand: response.format.thousand_separator,
          precision: response.format.decimal_places
        },
        number: {
          thousand: response.format.thousand_separator,
          precision: response.format.decimal_places
        }
      };

      var category = response.category;
      var priceToAdd = response.total_price;
      var taxToAdd = response.tax;

      var targetCalcElement = '.sbs-widget-sidebar-total-column[data-cat="' + category + '"] .woocomerce-Price-numeric';
      var taxElement = '.sbs-widget-sidebar-total-column[data-cat="Sales Tax"] .woocomerce-Price-numeric';
      var grandTotalElement = '.sbs-widget-sidebar-total-column[data-cat="GRAND TOTAL"] .woocomerce-Price-numeric';

      var oldCategoryTotal = accounting.unformat( $(targetCalcElement).html() );
      var newCategoryTotal = accounting.formatMoney( oldCategoryTotal + priceToAdd );

      var oldTax = accounting.unformat( $(taxElement).html() );
      var newTax = accounting.formatMoney( oldTax + taxToAdd );

      var oldGrandTotal = accounting.unformat( $(grandTotalElement).html() );
      var newGrandTotal = accounting.formatMoney( oldGrandTotal + priceToAdd + taxToAdd );

      $(targetCalcElement).html(newCategoryTotal);
      $(taxElement).html(newTax);
      // $(grandTotalElement).html(newGrandTotal);

    });

  });

  $(document.body).on('added_to_cart', function() {

    var data = {};
    data.action = 'sbs_query_grand_total';

    $.post(sbsAjaxUrl, data, function(response) {

      if (!response) {
        return;
      }

      console.log(response);

      accounting.settings = {
        currency: {
          symbol: response.format.currency,
          format: "%s%v",
          decimal: response.format.decimal_separator,
          thousand: response.format.thousand_separator,
          precision: response.format.decimal_places
        },
        number: {
          thousand: response.format.thousand_separator,
          precision: response.format.decimal_places
        }
      };

      var subTotalElement = '.sbs-widget-sidebar-total-column[data-cat="SUBTOTAL"] .woocomerce-Price-numeric'
      var grandTotalElement = '.sbs-widget-sidebar-total-column[data-cat="GRAND TOTAL"] .woocomerce-Price-numeric';

      $(subTotalElement).html( accounting.formatMoney( response.sub_total ) );
      $(grandTotalElement).html( accounting.formatMoney( response.grand_total ) );

    });

  });

});

})(jQuery);
