(function($) {
  $(document).ready(function() {

    function serializeData() {
      var data = $('#sbs-order').sbssortable('serialize').get();
      var jsonString = JSON.stringify(data);
      $('input#step_order').val(jsonString);
    }

    var group = $('.sortable').sbssortable({
      group: 'sortable',
      nested: true,
      isValidTarget: function($item, container) {

        if ( $(container.el).is('.step-sortable') && $('#sbs-order').children().not('.placeholder, .dragged').length >= 2  ) {
          return false;
        }

        if ( $(container.el).is('.package-sortable') && $('#sbs-order').children().not('.placeholder, .dragged').length >= 1  ) {
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
        serializeData();
        _super($item, container);
      }
    });

    /**
     * Buttons for moving sortables, in case drag and drop does not work.
     */
    $('.sbs-sortable-item-move-up').on('click touchend', function() {
      $item = $(this).parent().parent();
      $before = $item.prev();
      if ($before) {
        $item.insertBefore($before);
        serializeData();
      }
    });

    $('.sbs-sortable-item-move-down').on('click touchend', function() {
      $item = $(this).parent().parent();
      $next = $item.next();
      if ($next) {
        $item.insertAfter($next);
        serializeData();
      }
    });

    $('.sbs-sortable-item-add').on('click touchend', function() {
      $item = $(this).parent().parent();

      if ( $('.step-sortable').length && $('#sbs-order').children().length >= 2  ) {
        return false;
      }

      if ( $('.package-sortable').length && $('#sbs-order').children().length >= 1  ) {
        return false;
      }

      $item.detach().appendTo('#sbs-order');
      serializeData();
    });

    $('.sbs-sortable-item-remove').on('click touchend', function() {
      $item = $(this).parent().parent();
      $item.detach().appendTo('#sbs-pool');
      serializeData();
    });

  });
})(jQuery);
