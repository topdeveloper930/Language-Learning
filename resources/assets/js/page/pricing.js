(function($) {
    $(document).ready(function() {
        /* - ACCORDION
        ---------------------------------------------- */
        $('.accordion').each(function() {
            if ($(this).hasClass('active')) {
                $(this).find('.accordion-title').append('<i class="far fa-angle-up"></i>');
            } else {
                $(this).find('.accordion-title').append('<i class="far fa-angle-down"></i>');
            }
        })

        $('.accordion-title').on('click', function(e) {
            e.preventDefault();

            // auto close accordions
            //$('.accordion').removeClass('active');

            // check if clicked accordion is active
            if ($(this).parent('.accordion').hasClass('active')) {
                var isActive = true;
            }
            $(this).parent('.accordion').removeClass('active');
            $('.accordion-title').find('i').removeClass('fa-angle-up').addClass('fa-angle-down');

            if (isActive == true) {
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


        if ($('input.pricing-slider').length > 0) {

            // selectable boxes
            $('.pricing-selections a').on('click', function(e) {
                e.preventDefault();
                $(this).closest('.pricing-selections').find('a').removeClass('selected');
                $(this).addClass('selected');

                $('.pricing-slider input').val(0.5).change();
                $('html, body').animate({
                    scrollTop: $("#pricing").offset().top
                }, 200);
            })

            // slider + calculations
            $('input.pricing-slider').rangeslider({
                polyfill: false,
                onInit: function(position, value) {
                    $('.pricing-figure').html(value);
                },
                onSlide: function(position, value) {
                    priceCalculation(value);
                },
                onSlideEnd: function(position, value) {}
            });

            function pluralize(count, noun, suffix = 's') {
                if (count !== 1) return noun + suffix;
                else return noun;
            }

            function priceCalculation(hours) {
                var program = $('.pricing-selections a.selected').attr('data-program');
                var cost = 20; // default per hour cost
                var discount = 0;

                // option for different prices for different programs
                if (program == 'Standard') {
                    var cost = 16.99;
                } else if (program == 'Exam') {
                    var cost = 25.99;
                } else if (program == 'Professional') {
                    var cost = 25.99;
                }

                // calculate hourly discount from percentages
                if (hours >= 100) {
                    discount = cost - (cost * 0.5); //50%
                } else if (hours >= 90) {
                    discount = cost - (cost * 0.55); //45%
                } else if (hours >= 80) {
                    discount = cost - (cost * 0.60); //40%
                } else if (hours >= 70) {
                    discount = cost - (cost * 0.65); //35%
                } else if (hours >= 60) {
                    discount = cost - (cost * 0.7); //30%
                } else if (hours >= 50) {
                    discount = cost - (cost * 0.75); //25%
                } else if (hours >= 40) {
                    discount = cost - (cost * 0.8); //20%
                } else if (hours >= 30) {
                    discount = cost - (cost * 0.85); //15%
                } else if (hours >= 20) {
                    discount = cost - (cost * 0.9); //10%
                } else if (hours >= 10) {
                    discount = cost - (cost * 0.95); //5%
                } else {
                    discount = 0;
                }

                // output the costs
                var total_cost = hours * (cost - discount);
                var total_hourly_cost = total_cost / hours;

                if (discount > 0) {
                    $('.pricing-calculations .savings').html('(Saving a total of <strong>$' + (discount * hours).toFixed(2) + '</strong>)');
                } else {
                    $('.pricing-calculations .savings').html('(<strong>Purchase more hours for up to 50% off</strong>)');
                }

                $('.pricing-figure span').html(total_cost.toFixed(2));
                $('.pricing-calculations .pricing-hourly span').html(total_hourly_cost.toFixed(2));
                $('.range-labels label').html(hours + '' + pluralize(hours, ' Hour') + ' (' + program + ') Spanish');
            }
            priceCalculation(1); // set default before slider is used
        }

    });
}(jQuery));