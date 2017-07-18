(function($) {

$(document).ready(function() {

  $('.open-popup-link').click(function() {

		if (window.innerWidth > 768) {
			$('.open-popup-link').click(function() {
				setTimeout(function() {
					$('.mfp-content .modal-image').zoom();
				}, 50);
			});
		}

  });

});

})(jQuery);
