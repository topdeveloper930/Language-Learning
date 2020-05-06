/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/page/trial_lesson.js":
/*!**************************************************!*\
  !*** ./resources/assets/js/page/trial_lesson.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var $ = jQuery;
$(document).ready(function () {
  /* - TOASTER FUNCTIONALITY
  ---------------------------------------------- */
  function toaster(message, type) {
    if ($('.toaster').length == 0) {
      $('body').append('<div class="toaster"></div>');
    }

    var toast = $('<div class="toast"></div>');
    toast.html(message).appendTo('.toaster');
    if (type == 'success') toast.addClass('success');else if (type == 'notify') toast.addClass('notify');else if (type == 'caution') toast.addClass('caution');
    toast.show(200).css("display", "inline-block");
    toast.animate({
      'bottom': 0
    }, 200);
    toast.delay(4000);
    toast.fadeOut(200);
  }

  var toast_cooking = false; //toaster('Nice, another success message!','success');

  $(document).on('click', '.make-toast', function (e) {
    e.preventDefault();
    var type = "default";
    if ($(this).hasClass('success')) type = 'success';else if ($(this).hasClass('notify')) type = 'notify';else if ($(this).hasClass('caution')) type = 'caution';
    toaster($(this).attr('data-toast'), type);
  });
  $(document).on('click', '.toast', function () {
    $(this).stop(true, false).animate({
      'opacity': 0
    }, 200, function () {
      $(this).slideUp(200);
    });
  });

  if ($('.terms-of-service-required').length > 0) {
    $('.button.facebook, .button.google, .button.primary, .steps a').on('click', function (e) {
      if ($('.terms-of-service-required').prop('checked') == false) {
        e.preventDefault();
        toaster('You must agree to the terms of service', 'caution');
      }
    });
  }
  /* - MODAL BOXES
  ---------------------------------------------- */
  // if there's modal boxes, add the overlay to the dom


  if ($('.modal-box').length > 0) {
    $('body').append('<span class="modal-box-overlay"></span>');
  }

  closeModal = function closeModal() {
    $('.modal-box').removeClass('open');
    $('.modal-box-overlay').fadeOut(200);
  };

  openModal = function openModal(target) {
    //$('.menu.' + direction).toggleClass('open');
    $('.modal-box' + target).toggleClass('open');
    $('.modal-box-overlay').fadeIn(200);
  }; // tap to close modal


  $(document).on('click touchstart', '.modal-box-overlay, .modal-close', function (e) {
    e.preventDefault();
    closeModal();
  }); // modal triggers

  $(document).on('click', 'a[href*="#"]', function (e) {
    var target = $(this).attr('href');

    if (target != '#') {
      if ($('.modal-box' + target).length > 0) {
        e.preventDefault();
        openModal(target);
      }
    }
  });
  /* - RANGE SLIDERS
  ---------------------------------------------- */

  $('input[type="range"]').rangeslider({
    polyfill: false,
    onInit: function onInit() {//this.output = $( '<div class="range-output" />' ).insertAfter( this.$range ).html( this.$element.val() );
      //console.log(this.$element);
      //$(this.$element).closest('div').find('.range-value').css('background','red');
    },
    onSlide: function onSlide(position, value) {
      $(this.$element).closest('div').find('.range-value').html(value); //$(this).closest('.range-value').html(value);
      //$('.range-value').html(value);
      //this.output.html(this.$element.val())
      //$('.').html(value);
    },
    onSlideEnd: function onSlideEnd(position, value) {}
  });
  /* - SCHEDULE CLASS CALENDAR
  ---------------------------------------------- */

  if ($("#datepicker").length > 0) {
    $("#datepicker").datepicker({
      minDate: 1,
      maxDate: "+1M"
    });
    $('.calendar-times button').on('click', function () {
      $(this).parent('.calendar-times').find('.selected').removeClass('selected');
      $(this).addClass('selected');
    });
  }
  /* - TRIAL CLASS CALENDAR
  ---------------------------------------------- */


  if ($("#datepicker-trial-class").length > 0) {
    $("#datepicker-trial-class").datepicker({
      minDate: 1,
      maxDate: "+1M"
    });
    $('.trial-class-availibility .save-time').on('click', function () {
      var date = $('#datepicker-trial-class').datepicker("getDate");
      date = date.getMonth() + '/' + date.getDate() + '/' + date.getFullYear();
      var time = $('.timepicker').val();
      $('.available-datetimes').append('<a href="#" class="badge">' + date + ' (' + time + ')' + ' <i class="far fa-times"></i></a>');
      closeModal();
    });
    $(document).on('click', '.available-datetimes a', '', function (e) {
      e.preventDefault();
      $(this).remove();
    });
  }
  /* - SHOW DATE TIME MODAL ON INPUT FOCUS
  ---------------------------------------------- */


  $('input.date-time-picker').focus(function () {
    openModal('#date-time-picker');
  });
});

/***/ }),

/***/ 5:
/*!********************************************************!*\
  !*** multi ./resources/assets/js/page/trial_lesson.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp5.6\htdocs\framework\resources\assets\js\page\trial_lesson.js */"./resources/assets/js/page/trial_lesson.js");


/***/ })

/******/ });