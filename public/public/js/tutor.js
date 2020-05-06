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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/modules/commaSpace.js":
/*!***************************************************!*\
  !*** ./resources/assets/js/modules/commaSpace.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// Customer's input fix: adds whitespace after comma, if not present, in the node with .comma-space class.
document.querySelectorAll('.comma-space').forEach(function (n) {
  return n.innerHTML = n.innerHTML.replace(/,(?=[^\s])/g, ', ');
});

/***/ }),

/***/ "./resources/assets/js/modules/fading.js":
/*!***********************************************!*\
  !*** ./resources/assets/js/modules/fading.js ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return fading; });
/* harmony import */ var _initializable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./initializable */ "./resources/assets/js/modules/initializable.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _get(target, property, receiver) { if (typeof Reflect !== "undefined" && Reflect.get) { _get = Reflect.get; } else { _get = function _get(target, property, receiver) { var base = _superPropBase(target, property); if (!base) return; var desc = Object.getOwnPropertyDescriptor(base, property); if (desc.get) { return desc.get.call(receiver); } return desc.value; }; } return _get(target, property, receiver || target); }

function _superPropBase(object, property) { while (!Object.prototype.hasOwnProperty.call(object, property)) { object = _getPrototypeOf(object); if (object === null) break; } return object; }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }



var fading =
/*#__PURE__*/
function (_initializable) {
  _inherits(fading, _initializable);

  function fading(config) {
    _classCallCheck(this, fading);

    var defaultOpts = {
      duration: 200
    };
    config = Object.assign({}, defaultOpts, config);
    return _possibleConstructorReturn(this, _getPrototypeOf(fading).call(this, config));
  }

  _createClass(fading, [{
    key: "init",
    value: function init() {
      _get(_getPrototypeOf(fading.prototype), "init", this).call(this);
    }
  }, {
    key: "fade",
    value: function fade(fadeTarget, duration, op) {
      op = typeof op !== 'undefined' ? op : 0;
      duration = typeof duration !== 'undefined' ? duration : this._duration;
      fadeTarget.style.transitionProperty = 'opacity';
      fadeTarget.style.transitionDuration = duration / 1000 + 's';

      if (0 === parseFloat(op)) {
        setTimeout(function () {
          return fadeTarget.style.display = null;
        }, duration);
      } else if (0 < parseFloat(op)) {
        if (fadeTarget.hasAttribute('hidden')) fadeTarget.removeAttribute('hidden');
        if (!fadeTarget.style.display || fadeTarget.style.display === 'none') fadeTarget.style.display = 'block';
      }

      setTimeout(function () {
        return fadeTarget.style.opacity = op;
      }, 100);
    }
  }, {
    key: "in",
    value: function _in(fadeTarget, duration) {
      this.fade(fadeTarget, duration, 1);
    }
  }, {
    key: "out",
    value: function out(fadeTarget, duration) {
      this.fade(fadeTarget, duration, 0);
    }
  }]);

  return fading;
}(_initializable__WEBPACK_IMPORTED_MODULE_0__["default"]);


window.fadeInOut = new fading({});

/***/ }),

/***/ "./resources/assets/js/modules/initializable.js":
/*!******************************************************!*\
  !*** ./resources/assets/js/modules/initializable.js ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return initializable; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**
 * Base class. Just extended with overridden config and init method.
 */
var initializable =
/*#__PURE__*/
function () {
  function initializable(config) {
    _classCallCheck(this, initializable);

    var defaultOpts = {
      callback: this.init
    };
    config = Object.assign({}, defaultOpts, config);

    for (var prop in config) {
      if (config.hasOwnProperty(prop)) this['_' + prop] = config[prop];
    }

    this._callback();
  }

  _createClass(initializable, [{
    key: "init",
    value: function init() {}
  }]);

  return initializable;
}();



/***/ }),

/***/ "./resources/assets/js/modules/slideOutMenu.js":
/*!*****************************************************!*\
  !*** ./resources/assets/js/modules/slideOutMenu.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return slideOutMenu; });
/* harmony import */ var _initializable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./initializable */ "./resources/assets/js/modules/initializable.js");
/* harmony import */ var _modules_fading__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../modules/fading */ "./resources/assets/js/modules/fading.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _get(target, property, receiver) { if (typeof Reflect !== "undefined" && Reflect.get) { _get = Reflect.get; } else { _get = function _get(target, property, receiver) { var base = _superPropBase(target, property); if (!base) return; var desc = Object.getOwnPropertyDescriptor(base, property); if (desc.get) { return desc.get.call(receiver); } return desc.value; }; } return _get(target, property, receiver || target); }

function _superPropBase(object, property) { while (!Object.prototype.hasOwnProperty.call(object, property)) { object = _getPrototypeOf(object); if (object === null) break; } return object; }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }




var slideOutMenu =
/*#__PURE__*/
function (_initializable) {
  _inherits(slideOutMenu, _initializable);

  function slideOutMenu(config) {
    var _this;

    _classCallCheck(this, slideOutMenu);

    var defaultOpts = {
      fade: new _modules_fading__WEBPACK_IMPORTED_MODULE_1__["default"]({}),
      menuSelector: ".hidden-menu",
      linkSelector: ".hidden-menu",
      overlaySelector: ".hidden-menu-overlay",
      classActive: "open"
    };
    config = Object.assign({}, defaultOpts, config);
    _this = _possibleConstructorReturn(this, _getPrototypeOf(slideOutMenu).call(this, config));
    _this._overlay = document.querySelector(_this._overlaySelector);
    return _this;
  }

  _createClass(slideOutMenu, [{
    key: "init",
    value: function init() {
      'use strict';

      _get(_getPrototypeOf(slideOutMenu.prototype), "init", this).call(this);

      var self = this,
          section = document.querySelectorAll(this._sectionSelector),
          sections = {};
      document.addEventListener('click', function (e) {
        return self.eventHandler(e);
      }, false);
    }
  }, {
    key: "eventHandler",
    value: function eventHandler(e) {
      if (this.overlay && e.target === this.overlay) {
        this.closeMenu();
        return;
      }

      var href = this.getHrefAttr(e.target);

      if (false !== href) {
        e.preventDefault();
        this.openMenu(href);
      }
    }
  }, {
    key: "closeMenu",
    value: function closeMenu() {
      document.querySelector(this._menuSelector + '.' + this._classActive).classList.remove(this._classActive);

      this._fade.out(this.overlay);
    }
  }, {
    key: "openMenu",
    value: function openMenu(target) {
      document.querySelector('.hidden-menu' + target).classList.add(this._classActive);

      this._fade["in"](this.overlay);
    }
  }, {
    key: "getHrefAttr",
    value: function getHrefAttr(a) {
      if ('A' !== a.nodeName || !a.getAttribute("href") || a.getAttribute("href").charAt(0) !== "#" || a.getAttribute("href").length < 2) return false;
      var href = a.getAttribute("href");
      return document.querySelector(this._menuSelector + href) ? href : false;
    }
  }, {
    key: "overlay",
    get: function get() {
      return this._overlay;
    },
    set: function set(ol) {
      this._overlay = ol;
    }
  }]);

  return slideOutMenu;
}(_initializable__WEBPACK_IMPORTED_MODULE_0__["default"]);


new slideOutMenu();

/***/ }),

/***/ "./resources/assets/js/modules/tabsNavigation.js":
/*!*******************************************************!*\
  !*** ./resources/assets/js/modules/tabsNavigation.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

document.addEventListener("DOMContentLoaded", function () {
  var tabLinks = document.querySelectorAll('.tab-navigation a[href*="#"]'),
      hide = function hide(id) {
    return document.getElementById(id).setAttribute('hidden', 1);
  },
      show = function show(id) {
    return document.getElementById(id).removeAttribute('hidden');
  },
      clickHandler = function clickHandler(e) {
    var anchor = e.currentTarget;
    e.preventDefault();
    anchor.closest('.tab-navigation').querySelectorAll('a').forEach(function (l) {
      if (l === anchor) {
        l.classList.add('active');
        show(l.getAttribute('href').substr(1));
      } else {
        l.classList.remove('active');
        hide(l.getAttribute('href').substr(1));
      }
    });
  };

  if (!tabLinks.length) return;
  tabLinks.forEach(function (link) {
    link.addEventListener('click', clickHandler, false);
    if (!link.classList.contains('active')) hide(link.getAttribute('href').substr(1));
  });
});

/***/ }),

/***/ "./resources/assets/js/page/tutor.js":
/*!*******************************************!*\
  !*** ./resources/assets/js/page/tutor.js ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_slideOutMenu__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../modules/slideOutMenu */ "./resources/assets/js/modules/slideOutMenu.js");
__webpack_require__(/*! ../modules/commaSpace */ "./resources/assets/js/modules/commaSpace.js");

__webpack_require__(/*! ../modules/tabsNavigation */ "./resources/assets/js/modules/tabsNavigation.js");


$('.slider-container').flexslider({
  selector: ".slider > blockquote",
  animation: "fade",
  controlNav: true,
  slideshow: false,
  smoothHeight: true
});
$('.flex-prev').html('<i class="far fa-angle-left"></i>');
$('.flex-next').html('<i class="far fa-angle-right"></i>');

/***/ }),

/***/ 0:
/*!*************************************************!*\
  !*** multi ./resources/assets/js/page/tutor.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\OSPanel\domains\localhost\laravel\resources\assets\js\page\tutor.js */"./resources/assets/js/page/tutor.js");


/***/ })

/******/ });