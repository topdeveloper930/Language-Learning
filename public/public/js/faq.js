var $ = jQuery;

(function($) {
	$(document).ready(function() {
		/* - ACCORDION
		---------------------------------------------- */
		$('.accordion').each(function(){
			if($(this).hasClass('active')){
				$(this).find('.accordion-title').append('<i class="far fa-angle-up"></i>');
			}
			else {
				$(this).find('.accordion-title').append('<i class="far fa-angle-down"></i>');
			}
		})

		$('.accordion-title').on('click',function(e){
			e.preventDefault();

			// auto close accordions
			//$('.accordion').removeClass('active');

			// check if clicked accordion is active
			if($(this).parent('.accordion').hasClass('active')){
				var isActive = true;
			}
			$(this).parent('.accordion').removeClass('active');
			$('.accordion-title').find('i').removeClass('fa-angle-up').addClass('fa-angle-down');

			if(isActive == true){
				// add active class to the one clicked
				$(this).parent('.accordion').removeClass('active');
				$(this).find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
			} else {
				// add active class to the one clicked
				$(this).parent('.accordion').addClass('active');
				$(this).find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
			}
			//find('.accordion-content').slideDown();
		});
		$('.accordion a.active').parents('.accordion').find('.accordion-title').trigger('click'); // opens the menu if it has an active child

	});
}(jQuery));
