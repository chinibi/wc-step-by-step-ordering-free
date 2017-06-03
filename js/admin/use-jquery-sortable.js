(function($) {
  $(document).ready(function() {

    var group = $('.sortable').sortable({
      group: 'sortable',
      nested: true,
      isValidTarget: function($item, container) {

        if ( !sbsLicenseValid && $(container.el).is('.step-sortable') && $('#sbs-order').children().not('.placeholder, .dragged').length >= 2  ) {
          return false;
        }

        if ( !sbsLicenseValid && $(container.el).is('.package-sortable') && $('#sbs-order').children().not('.placeholder, .dragged').length >= 1  ) {
          return false;
        }

        if ( $item.attr('parent-id') === '0' && ($(container.el).is('#sbs-order') || $(container.el).is('#sbs-pool')) ) {
          return true;
        }

        if ( $item.attr('parent-id') === $(container.el).parent().attr('data-catid') ) {
          return true;
        }

        return false;

      },
      onDrop: function($item, container, _super) {
        var data = $('#sbs-order').sortable('serialize').get();
        var jsonString = JSON.stringify(data);

        $('input#step_order').val(jsonString);
        _super($item, container);
      }
    });

    if ( !sbsLicenseValid ) {
      $('.onf-sortable').sortable('disable');
    }

  });
})(jQuery);
