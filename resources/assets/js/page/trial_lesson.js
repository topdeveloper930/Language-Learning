var $ = jQuery;

$(document).ready(function() {
			/* - TOASTER FUNCTIONALITY
	---------------------------------------------- */
	function toaster(message, type){
		if($('.toaster').length == 0){
			$('body').append('<div class="toaster"></div>');
		}
		var toast = $('<div class="toast"></div>');
		toast.html(message).appendTo('.toaster')
		if(type == 'success') toast.addClass('success');
		else if(type == 'notify') toast.addClass('notify');
		else if(type == 'caution') toast.addClass('caution');
		toast.show(200).css("display","inline-block");
		toast.animate({'bottom':0},200);
		toast.delay(4000);
		toast.fadeOut(200);
	}
	var toast_cooking = false;
	//toaster('Nice, another success message!','success');
	$(document).on('click','.make-toast',function(e){
		e.preventDefault();
		var type = "default";
		if($(this).hasClass('success')) type = 'success';
		else if($(this).hasClass('notify')) type = 'notify';
		else if($(this).hasClass('caution')) type = 'caution';
		toaster($(this).attr('data-toast'),type);
	});
	$(document).on('click','.toast',function(){
		$(this).stop(true,false).animate({'opacity':0},200,function(){$(this).slideUp(200)});
	});
	
	if($('.terms-of-service-required').length > 0){
		$('.button.facebook, .button.google, .button.primary, .steps a').on('click',function(e){
			if($('.terms-of-service-required').prop('checked') == false){
				e.preventDefault();
				toaster('You must agree to the terms of service','caution');
			}
		});
	}

		    /* - MODAL BOXES
	    ---------------------------------------------- */
		// if there's modal boxes, add the overlay to the dom
	    if($('.modal-box').length > 0){
		    $('body').append('<span class="modal-box-overlay"></span>')
	    }
		closeModal = function(){
			$('.modal-box').removeClass('open');
			$('.modal-box-overlay').fadeOut(200);
		}
		openModal = function(target){
			//$('.menu.' + direction).toggleClass('open');
			$('.modal-box'+target).toggleClass('open');
			$('.modal-box-overlay').fadeIn(200);
		}
		// tap to close modal
		$(document).on('click touchstart','.modal-box-overlay, .modal-close',function(e){
			e.preventDefault();
			closeModal();
		});
		// modal triggers
		$(document).on('click','a[href*="#"]',function(e){
			var target = $(this).attr('href');
			if(target != '#'){
				if($('.modal-box'+target).length > 0){
					e.preventDefault();
					openModal(target);
				}
			}
		});

		/* - RANGE SLIDERS
		---------------------------------------------- */
		$('input[type="range"]').rangeslider({
		    polyfill : false,
		    onInit : function() {
				//this.output = $( '<div class="range-output" />' ).insertAfter( this.$range ).html( this.$element.val() );
				//console.log(this.$element);
				//$(this.$element).closest('div').find('.range-value').css('background','red');
		    },
		    onSlide : function( position, value ) {
				$(this.$element).closest('div').find('.range-value').html(value);
				//$(this).closest('.range-value').html(value);
				//$('.range-value').html(value);
				//this.output.html(this.$element.val())
		        //$('.').html(value);
		    },
			onSlideEnd: function(position, value) {
			}
		});
		
		/* - SCHEDULE CLASS CALENDAR
		---------------------------------------------- */
		if($("#datepicker").length > 0){
			$("#datepicker").datepicker({ minDate: 1, maxDate: "+1M" });
			$('.calendar-times button').on('click',function(){
				$(this).parent('.calendar-times').find('.selected').removeClass('selected');
				$(this).addClass('selected');
			});
		}


		/* - TRIAL CLASS CALENDAR
		---------------------------------------------- */
		if($("#datepicker-trial-class").length > 0){
			$("#datepicker-trial-class").datepicker({ minDate: 1, maxDate: "+1M" });

			$('.trial-class-availibility .save-time').on('click',function(){
				var date = $('#datepicker-trial-class').datepicker("getDate");

				date = date.getMonth() + '/' + date.getDate() + '/' + date.getFullYear();

				var time = $('.timepicker').val();

				$('.available-datetimes').append('<a href="#" class="badge">' + date + ' (' + time + ')' + ' <i class="far fa-times"></i></a>');
				closeModal();
			})
			$(document).on('click','.available-datetimes a','',function(e){
				e.preventDefault();
				$(this).remove();
			});
		}

		/* - SHOW DATE TIME MODAL ON INPUT FOCUS
		---------------------------------------------- */
		$('input.date-time-picker').focus(function(){
			openModal('#date-time-picker');
		})

		

})
