(function($) {
  $(document).ready(function() {

    var group = $('.sortable').sortable({
      group: 'sortable',
      nested: true,
      onDrop: function($item, container, _super) {
        var data = $('#sbs-order').sortable('serialize').get();
        var jsonString = JSON.stringify(data);

        $('input#step_order').val(jsonString);
        _super($item, container);
      }
    });

  });
})(jQuery);
