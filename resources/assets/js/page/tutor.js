require('../modules/commaSpace');
require('../modules/tabsNavigation');
import slideOutMenu from "../modules/slideOutMenu";

$('.slider-container').flexslider({
    selector: ".slider > blockquote",
    animation: "fade",
    controlNav: true,
    slideshow: false,
    smoothHeight: true
});
$('.flex-prev').html('<i class="far fa-angle-left"></i>');
$('.flex-next').html('<i class="far fa-angle-right"></i>');