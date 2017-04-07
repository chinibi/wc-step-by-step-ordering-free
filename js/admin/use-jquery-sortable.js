(function($) {
  $(document).ready(function() {

    $('.sortable').sortable({
      items: '.sortable-item:not(.sortable-fixed)',
      placeholder: 'ui-sortable-placeholder',
      cursor: 'move',
      start: function(e, ui) {
         ui.placeholder.height(ui.item.height());
      },
      update: function(e, ui) {
        var order = $('#sbs-order').sortable('toArray', {attribute: 'data-catid'});
        order = order.filter(function(each) {
          return !isNaN(each);
        });
        $('#step_order').val(order.join(','));
      }
    });

    $('#sbs-order').sortable('option', 'connectWith', '#sbs-pool');
    $('#sbs-pool').sortable('option', 'connectWith', '#sbs-order');

    $('.sortable').disableSelection();

  });
})(jQuery);
