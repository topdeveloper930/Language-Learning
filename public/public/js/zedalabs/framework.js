/* - SYNTAX HIGHLIGHTING
---------------------------------------------- */
hljs.initHighlightingOnLoad();


// -- put jquery inside ready funciton below -- //


var $ = jQuery;

(function($) {
	$(document).ready(function() {


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


		/* - SHOW DATE TIME MODAL ON INPUT FOCUS
		---------------------------------------------- */
		$('input.date-time-picker').focus(function(){
			openModal('#date-time-picker');
		})


		/* - ADD CLASS TO FILLED INPUTS ON MODAL PAGE
		---------------------------------------------- */
		$('.modal-page input, .modal-page select').blur(function(){
			console.log('hi');
			if($(this).val() != ''){
				$(this).addClass('filled');
			} else {
				$(this).removeClass('filled');
			}
		})


		/* - TABLE HEADING GENERATION FOR MOBILE
		---------------------------------------------- */
		$('table').each(function(){
			var count = 1;
			$(this).find('thead th').each(function(){
				if($(this).html() != ''){
					var title = $(this).clone().children().remove().end().text() + ': ';
					$(this).closest('table').find('tbody td:nth-of-type(' + count + ')').attr('data-th',title);
				}
				count++;
			});
		});


		/* - FILTERS
		---------------------------------------------- */
		$(document).on('click','.filter-navigation a',function(e){
			e.preventDefault();

			$(this).parent('.filter-navigation').find('a').removeClass('active');
			$(this).addClass('active');
			
			var target = $(this).attr('href');
			if(target == '#'){
				$('.filter-content >').show();
			} else {
				$('.filter-content >').hide();
				$('.filter-content >[data-filter="' + target.substring(1, target.length) + '"]').show();
			}
		});


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



		/* - HIDDEN FORM FIELD HELPER FUNCTIONALITY
		---------------------------------------------- */
		$('input,textarea,select').focus(function(){
			var helptext = $(this).attr('data-hidden-help');
			if (typeof helptext !== typeof undefined && helptext !== false){
				$(this).after('<span class="field-help-label">' + helptext + '</span>');
				$(this).siblings('.field-help-label').slideDown(100);
				$(this).removeAttr('data-hidden-help');
			}
		})

		/* - GENERATE REQUIRED LABELS FOR REQUIRED FORM ELEMENTS
		---------------------------------------------- */
		$('form.required-labels input,textarea,select').filter('[required]:visible').each(function(){
			$(this).parent('.form-group').prepend('<span class="field-required-label">Required</span>');
		});


		/* - INPUT LENGTH CALCULATOR AND DISPLAY
		---------------------------------------------- */
		$('input, textarea').each(function(){
			var maxlength = $(this).attr('maxlength');
			if (typeof maxlength !== typeof undefined && maxlength !== false) {
				$(this).wrap('<div class="maxlength-wrap"></div>)');
				//$(this).find('.maxlength-wrap').append('<span class="maxlength-count">0/' + maxlength + '</span>');
				$('<span class="maxlength-count"><span class="count">0</span>/' + maxlength + '</span>').insertAfter($(this));
				if($(this).is('textarea')){
					$(this).parent('.maxlength-wrap').find('.maxlength-count').addClass('textarea');
				}
				else if($(this).is('input')){
					$(this).parent('.maxlength-wrap').find('.maxlength-count').addClass('input');
				}
			}
		});
		function inputCalcs(element){
			var maxlength_count = element.parent('.maxlength-wrap').find('.maxlength-count');
			var curlength = element.val().length;
			var maxlength = element.attr('maxlength')
			maxlength_count.html(curlength + '/' + maxlength);
			if(curlength == maxlength){
				maxlength_count.addClass('limit-reached');
			} else {
				maxlength_count.removeClass('limit-reached');
			}
		}
		// calculate when typing
		$(document).on('keyup','textarea, input',function() {
			inputCalcs($(this));
		});
		// calculate on load
		$('textarea, input').each(function(){
			inputCalcs($(this));
		});


		/* - TABS
		---------------------------------------------- */
		if($('.tab-navigation').length > 0){
			$('.tab-navigation a[href*="#"]').each(function(){
				var targets = $(this).attr('href');
				$(targets).hide();
			});
			$('.tab-navigation a.active').each(function(){
				var target = $(this).attr('href');
				$(target).show();
			});
		}
		$('.tab-navigation a[href*="#"]').on('click',function(e){
			$(this).parent('.tab-navigation').find('a').removeClass('active');
			$(this).addClass('active')
			var target = $(this).attr('href');
			if($(target).length > 0){
				$(this).parent('.tab-navigation').find('a[href*="#"]').each(function(){
					var targets = $(this).attr('href');
					$(targets).hide();
				});
				e.preventDefault();
				$(target).show();
			}
		});



		/* - COPY TO CLIPBOARD
		---------------------------------------------- */
		function copyToClipboard(element) {
			var $temp = $("<input>");
			$("body").append($temp);
			$temp.val($(element).text()).select();
			document.execCommand("copy");
			$temp.remove();
			toaster('Copied to your clipboard!','success');
		}
		/*
		$('.copy-this:hover').css('cursor');
		$(document).on('click','.copy-this',function(e){
			e.preventDefault();
			copyToClipboard($(this));
		});
		*/



		/* - COLOR SWATCHES
		---------------------------------------------- */
		$('.color-swatch').each(function(){
			$(this).prepend('<a href="javascript:void(0);" class="copy-link">Copy</a>');
		});
		$(document).on('click','.color-swatch',function(){
			copyToClipboard($(this).find('.swatch-value'));
		});


		/* - VISUALIZE FLEXBOX GRID
		---------------------------------------------- */
		$('.flexbox-example .columns .column').each(function(){
			$(this).wrapInner('<div class="example-box"></div>');
		});


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


		/* - SHORTEN TEXT SNIPPETS
		---------------------------------------------- */
		$('.shorten').each(function(){
			var strlength = $(this).attr('data-length');
			if (typeof strlength === typeof undefined || strlength === false){
				strlength = 150; // default
			}
			if($(this).text().length > strlength){
				$(this).html('<span class="short">' + $(this).text().substring(0,strlength) + '</span><span class="long">' + $(this).text().substring(strlength) + '</span>');
				$(this).append('<span class="ellipsis">... </span> <a href="javascript:void(0);" class="short-long">More</a>');
				$(this).find('.long').hide();
			}
		});
		$(document).on('click','.shorten a.short-long',function(){
			var target = $(this).closest('.shorten');
			if(target.find('.long').is(':visible')){
				target.find('.long').hide();
				target.find('.ellipsis').show();
				$(this).text('More');
			} else {
				target.find('.long').show();
				target.find('.ellipsis').hide();
				$(this).text('Less');
			}
		});


		/* - SHOW / HIDE UTILITY
		---------------------------------------------- */
/*
		if($('.show-hide').length > 0){
			$('.show-hide a[href*="#"]').each(function(){
				var target = $(this).attr('href');
				if($(this).hasClass('show')){

				} else {
					$(target).show();
				}

			});
			$('.show-hide').on('click',function(e){
				e.preventDefault();
				var target = $(this).attr('href');
				if($(target).is(':visible')){
					$(target).hide();
				} else {
					$(target).show();
				}
			});
		}
*/


		/* - LAZY LOADING
		---------------------------------------------- */
		$('.lazy').lazy({
			'threshold':200,
			'effect': 'fadeIn',
			'effectTime':100
		});


		/* - AUTO QUICK NAV
		---------------------------------------------- */
		function convertToSlug(Text){
		    return Text
		        .toLowerCase()
		        .replace(/ /g,'-')
		        .replace(/[^\w-]+/g,'')
		        ;
		}
		var quickNav = $(".post-quick-nav");
		var link_count = 0;
		if(quickNav.length > 0){
			var nav = '';
				nav += '';
				nav += '<ul>';
			$('span.section-label').each(function(){
				link_count++;
				var slug = convertToSlug($(this).text());
				$(this).attr('id',slug);
				if($(this).is('h3')){
					nav += '<li class="sub">';
				} else {
					nav += '<li>';
				}
				//if($(this).is('h2') || $(this).is('h3')){
					nav += '<a href="#' + slug + '">' + $(this).text() + '</a>';
				//}
	/*
					$('.content h3').each(function(){
						var slug = convertToSlug($(this).text());
						$(this).attr('id',slug);
						nav += '<ul>';
						nav += '<li><a href="#' + slug + '">' + $(this).text() + '</a></li>';
						nav += '</ul>';
					});
	*/
				nav += '</li>';
			});
			nav += '</ul>';
			nav += '';
			quickNav.append(nav);
			quickNav.show();
		}


		/* - VIDEO POPUP
		---------------------------------------------- */
		$(document).on('click','*[data-video-id]',function(e){
			var attr = $(this).attr('data-video-id');
			if (typeof attr !== typeof undefined && attr !== false){
				e.preventDefault();
				openPopup('#video');
				$('.popup-box#video iframe').attr('src','https://www.youtube.com/embed/' + attr + '?rel=0&amp;showinfo=0&autoplay=1');
				$(window).trigger('resize'); // video resizing
			}
		});


		/* - ANNOUNCEMENT BAR
		---------------------------------------------- */
		$(document).on('click','.announcement .announcement-close',function(e){
			e.preventDefault();
			$(this).closest('.announcement').slideUp(100);
			Cookies.set('announcement', '1', {expires: 3600 });
		});
		if (typeof Cookies.get('announcement') == 'undefined') {
			$('.announcement').css('display','flex');
		}
		if($('.announcement:visible').length > 0){
			// sticky announcemeent
			var announcement = $('.announcement.sticky:visible');

			$(window).scroll(function(){
				var scrollPos = $(document).scrollTop();
				if(scrollPos > 0){
					announcement.addClass('fixed');
					$('.navbar').css('margin-top',announcement.innerHeight());
				} else {
					announcement.removeClass('fixed');
					$('.navbar').css('margin-top',0);
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



	    /* - SLIDE OUT MENUS
	    ---------------------------------------------- */
		closeMenu = function(){
			$('.hidden-menu').removeClass('open');
			$('.hidden-menu-overlay').fadeOut(200);
		}
		openMenu = function(target){
			//$('.menu.' + direction).toggleClass('open');
			$('.hidden-menu'+target).toggleClass('open');
			$('.hidden-menu-overlay').fadeIn(200);
		}
		// tap to close menu
		$(document).on('click touchstart ','.hidden-menu-overlay',function(){
			if($('.hidden-menu').hasClass('open')){
				closeMenu();
			}
		});

		// menu triggers
		$(document).on('click','a[href*="#"]',function(e){
			var target = $(this).attr('href');
			if(target != '#'){
				if($('.hidden-menu'+target).length > 0){
					e.preventDefault();
					openMenu(target);
				}
			}
		});


		/* - SCROLLSPY
		---------------------------------------------- */

		var lastId,
		scrollSpyMenu = $("nav.scrollspy");
		scrollSpyMenuHeight = scrollSpyMenu.outerHeight();
		scrollSpyMenuItems = scrollSpyMenu.find('a');
		scrollItems = scrollSpyMenuItems.map(function(){
			var item = $($(this).attr('href'));
			if (item.length) {
				return item;
			}
		});
		// Bind to scroll
		$(window).scroll(function(){
			var fromTop = $(this).scrollTop() + ($(window).height() / 2); // menu item will activate at half of the window height
			var cur = scrollItems.map(function(){
				if ($(this).offset().top < fromTop)
					return this;
			});
			cur = cur[cur.length-1];
			var id = cur && cur.length ? cur[0].id : "";
			if (lastId !== id) {
				lastId = id;

				scrollSpyMenuItems.removeClass('active').filter('[href="#' + id + '"]').addClass("active");
			}
		}).scroll();


	});
}(jQuery));
