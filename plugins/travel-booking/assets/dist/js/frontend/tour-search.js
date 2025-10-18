/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/frontend/tour-search/date-time.js":
/*!*****************************************************!*\
  !*** ./assets/js/frontend/tour-search/date-time.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const $ = jQuery;
const tourDateTime = () => {
  const daterange = $('.tour-search-field.date input');
  const removeDate = document.querySelector('#remove-date-range');
  const selectionWrapper = document.querySelector('.tour-search-field.selection');
  if (!daterange) {
    return;
  }
  const value = daterange.val();
  daterange.daterangepicker({
    opens: 'center',
    locale: {
      // autoUpdateInput: false,
      format: 'DD/MM/YYYY'
      // cancelLabel: 'Clear'
    }
  }, function (start, end, label) {});
  if (!value) {
    daterange.val('');
  }
  if (removeDate) {
    removeDate.addEventListener('click', function (event) {
      daterange.val('');
      if (selectionWrapper) {
        const listNode = selectionWrapper.querySelector('.list');
        const fieldNode = listNode.querySelector(`li[data-field="date"]`);
        fieldNode.remove();
      }
    });
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (tourDateTime);

/***/ }),

/***/ "./assets/js/frontend/tour-search/destination.js":
/*!*******************************************************!*\
  !*** ./assets/js/frontend/tour-search/destination.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var tom_select__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tom-select */ "./node_modules/tom-select/dist/js/tom-select.complete.js");
/* harmony import */ var tom_select__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(tom_select__WEBPACK_IMPORTED_MODULE_0__);

const tourDestination = () => {
  const destinationWrapper = document.querySelector('.tour-search-field-inner.pa_destination');
  const selectionWrapper = document.querySelector('.tour-search-field.selection');
  if (!destinationWrapper) {
    return;
  } else {
    const destinationSelect = destinationWrapper.querySelector('select.pa_destination');
    const destinationRemove = destinationWrapper.querySelector('.remove-attr');
    destinationRemove.addEventListener('click', function (event) {
      destinationSelect.selectedIndex = 0;
      if (destinationRemove) {
        const listNode = selectionWrapper.querySelector('.list');
        const fieldNode = listNode.querySelector(`li[data-field="destination"]`);
        fieldNode.remove();
      }
    });
  }
  const mothWrapper = document.querySelector('.tour-search-field-inner.pa_month');
  if (!mothWrapper) {
    return;
  } else {
    const mothSelect = mothWrapper.querySelector('select.pa_month');
    const mothRemove = mothWrapper.querySelector('.remove-attr');
    mothRemove.addEventListener('click', function (event) {
      mothSelect.selectedIndex = 0;
      if (destinationRemove) {
        const listNode = selectionWrapper.querySelector('.list');
        const fieldNode = listNode.querySelector(`li[data-field="month"]`);
        fieldNode.remove();
      }
    });
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (tourDestination);

/***/ }),

/***/ "./assets/js/frontend/tour-search/fields.js":
/*!**************************************************!*\
  !*** ./assets/js/frontend/tour-search/fields.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const productFilterNode = document.querySelector('.travel-product-filter ');
const travelProductFilterField = () => {
  if (!productFilterNode) {
    return;
  }
  toggleAllFields();
  toggleField();
};
const toggleAllFields = () => {
  const toggleBtn = document.querySelector('.filter-button-toggle-wp');
  const el = document.querySelector('.travel-product-filter');
  const heading1 = document.querySelectorAll('.item-filter-heading');
  const filterTitleToggles1 = productFilterNode.querySelectorAll('.filter-title-toggle');
  const close_form = document.querySelector('.close-filter-product');
  if (!toggleBtn) {
    return;
  }
  toggleBtn.addEventListener('click', function () {
    productFilterNode.classList.toggle('disabled');
    document.body.classList.add('overlay-filter');
  });
  if (toggleBtn.classList.contains('button-hidden-desktop')) {
    el.classList.remove("show-filter-toggle");
  }
  if (window.innerWidth < 768 && toggleBtn.classList.contains('button-hidden-desktop')) {
    el.classList.add("show-filter-toggle");
    filterTitleToggles1.forEach(filterTitleToggle1 => {
      if (window.innerWidth < 768) {
        filterTitleToggle1.classList.remove("filter-item-dropdown");
        filterTitleToggle1.classList.add("open");
      }
    });
  }
  window.onresize = function (event) {
    if (window.innerWidth < 768 && toggleBtn.classList.contains('button-hidden-desktop')) {
      el.classList.add("show-filter-toggle");
      filterTitleToggles1.forEach(filterTitleToggle1 => {
        filterTitleToggle1.classList.remove("filter-item-dropdown");
        filterTitleToggle1.classList.add("open");
      });
    } else {
      if (toggleBtn.classList.contains('button-hidden-desktop')) {
        el.classList.remove("show-filter-toggle");
      }
      filterTitleToggles1.forEach(filterTitleToggle1 => {
        const toggletypes = productFilterNode.querySelectorAll(".toggle-type-dropdown");
        toggletypes.forEach(toggletype => {
          toggletype.classList.add("filter-item-dropdown");
        });
        filterTitleToggle1.classList.remove("open");
      });
    }
  };
  document.addEventListener('click', function (event) {
    if (event.target != toggleBtn && !event.target.closest('#search_tour_form')) {
      productFilterNode.classList.remove('disabled');
      document.body.classList.remove('overlay-filter');
    }
  });
  close_form.addEventListener('click', function (event) {
    productFilterNode.classList.remove('disabled');
    document.body.classList.remove('overlay-filter');
  });
};
const toggleField = () => {
  const filterTitleToggles = productFilterNode.querySelectorAll('.filter-title-toggle');
  [...filterTitleToggles].map(filterTitleToggle => {
    const heading = filterTitleToggle.querySelector('.item-filter-heading');
    heading.addEventListener('click', function () {
      if (window.innerWidth > 767 && filterTitleToggle.classList.contains('toggle-type-dropdown') || window.innerWidth < 767 && !filterTitleToggle.classList.contains('has-btn-filter-show_mobile')) {
        if (filterTitleToggle.classList.contains("open")) {
          filterTitleToggle.classList.remove("open");
        } else {
          const toggleTitlesWithIsOpen = productFilterNode.querySelectorAll(".open");
          toggleTitlesWithIsOpen.forEach(toggleTitleWithIsOpen => {
            toggleTitleWithIsOpen.classList.remove("open");
          });
          filterTitleToggle.classList.toggle('open');
        }
      } else {
        filterTitleToggle.classList.toggle('open');
      }
    });
    document.addEventListener('click', function (event) {
      if (!event.target.classList.contains('item-filter-heading') && filterTitleToggle.classList.contains('toggle-type-dropdown')) {
        if (filterTitleToggle.classList.contains("open") && filterTitleToggle.classList.contains('toggle-type-dropdown')) {
          filterTitleToggle.classList.remove('open');
        }
      }
    });
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (travelProductFilterField);

/***/ }),

/***/ "./assets/js/frontend/tour-search/handle-search.js":
/*!*********************************************************!*\
  !*** ./assets/js/frontend/tour-search/handle-search.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_0__);

const tourHandleSearch = () => {};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (tourHandleSearch);

/***/ }),

/***/ "./assets/js/frontend/tour-search/price.js":
/*!*************************************************!*\
  !*** ./assets/js/frontend/tour-search/price.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   renderPriceFormat: () => (/* binding */ renderPriceFormat)
/* harmony export */ });
/* harmony import */ var nouislider__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! nouislider */ "./node_modules/nouislider/dist/nouislider.mjs");
/* provided dependency */ var noUiSlider = __webpack_require__(/*! nouislider */ "./node_modules/nouislider/dist/nouislider.mjs");


// const numberDecimals = TOUR_SEARCH_OBJ.decimals || 0;
// const thousandsSeparator = TOUR_SEARCH_OBJ.thousand_separator || '';
const numberDecimals = 0;
const thousandsSeparator = '';
const tourPrice = () => {
  let minPrice, maxPrice, step;
  const priceWrapper = document.querySelector('.tour-search-field.price');
  if (!priceWrapper) {
    return;
  }
  minPrice = priceWrapper.getAttribute('data-min');
  maxPrice = priceWrapper.getAttribute('data-max');
  if (minPrice === '' || maxPrice === '') {
    return;
  }
  const minPriceNode = priceWrapper.querySelector('#min-price');
  const maxPriceNode = priceWrapper.querySelector('#max-price');
  const priceSliderNode = priceWrapper.querySelector('#tour-price-range');
  const start = minPriceNode.value || minPrice;
  const end = maxPriceNode.value || maxPrice;
  noUiSlider.create(priceSliderNode, {
    start: [parseInt(start), parseInt(end)],
    connect: true,
    tooltips: false,
    range: {
      min: parseInt(minPrice),
      max: parseInt(maxPrice)
    }
    // direction: 'lt',
  });
  priceSliderNode.noUiSlider.on('update', function (values, handle, unencoded) {
    minPriceNode.value = parseInt(values[0]);
    maxPriceNode.value = parseInt(values[1]);
    // if (['left', 'left-space'].includes(TOUR_SEARCH_OBJ.price_format)) {
    //     priceWrapper.querySelector('.min bdi').lastChild.data = renderPriceNumber(values[0]);
    //     priceWrapper.querySelector('.max bdi').lastChild.data = renderPriceNumber(values[1]);
    // } else {
    priceWrapper.querySelector('.tour-min-price').firstChild.data = renderPriceNumber(values[0]);
    priceWrapper.querySelector('.tour-max-price').firstChild.data = renderPriceNumber(values[1]);
    // }
  });

  //Reset
  const resetBtn = priceWrapper.querySelector('.reset');
  if (resetBtn) {
    resetBtn.addEventListener('click', function (event) {
      event.preventDefault();
      priceSliderNode.noUiSlider.updateOptions({
        start: [parseInt(start), parseInt(end)]
      });
    });
  }
};
const renderPriceNumber = price => {
  price = (price / 1).toFixed(numberDecimals);
  price = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);

  // if (TOUR_SEARCH_OBJ.price_format === 'left-space') {
  //     price = ' ' + price;
  // } else if (TOUR_SEARCH_OBJ.price_format === 'right-space') {
  price = price + ' ';
  // }

  return price;
};
const renderPriceFormat = price => {
  price = renderPriceNumber(price);

  // if (['left', 'left-space'].includes(TOUR_SEARCH_OBJ.price_format)) {
  //     price = TOUR_SEARCH_OBJ.currency_symbol + price;
  // } else {
  // price = price + TOUR_SEARCH_OBJ.currency_symbol;
  // }

  return price;
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (tourPrice);

/***/ }),

/***/ "./assets/js/frontend/tour-search/selection.js":
/*!*****************************************************!*\
  !*** ./assets/js/frontend/tour-search/selection.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _price__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./price */ "./assets/js/frontend/tour-search/price.js");

let listNode, clearBtn;
const $ = jQuery;
const destinationSelect = document.querySelector('.tour-search-field.destination select.pa_destination');
const monthSelect = document.querySelector('.tour-search-field.destination select.pa_month');
const dateNode = document.querySelector('.tour-search-field.date input');
const durationNodes = document.querySelectorAll('.tour-search-field .duration-item input');
const ratingNodes = document.querySelectorAll('.tour-search-field.rating  input[name="tour_rating"]');
const priceSliderNode = document.querySelector('.tour-search-field.price #tour-price-range');
const tourtypeNodes = document.querySelectorAll('.tour-search-field.tour-type  input');
const priceWrapper = document.querySelector('.tour-search-field.price');
const resetAllBtn = document.querySelector('.button-reset-searh-tour');
const tourSelection = () => {
  const selectionWrapper = document.querySelector('.tour-search-field.selection');
  if (!selectionWrapper) {
    return;
  }
  listNode = selectionWrapper.querySelector('.list');
  clearBtn = selectionWrapper.querySelector('button.clear');
  updateDestination();
  updateMonth();
  updateDate();
  updateDuration();
  updateRating();
  updatePrice();
  updateTourtype();
  //RemoveSelection
  removeSelection();
  removeAll();
};
const changeSelectedField = (field, value, text) => {
  let fieldNode;
  fieldNode = listNode.querySelector(`li[data-field="${field}"]`);
  if (field === 'duration' || field === 'tourtype') {
    fieldNode = listNode.querySelector(`li[data-field="${field}"][data-value="${value}"]`);
  }
  if (fieldNode) {
    if (field === 'duration' || field === 'tourtype') {
      fieldNode.remove();
    } else {
      if (value) {
        fieldNode.setAttribute('data-value', value);
        fieldNode.querySelector('.title').innerHTML = text;
      } else {
        fieldNode.remove();
      }
    }
  } else {
    const item = `<li class="list-item" data-field = "${field}" data-value="${value}">
            <span class="title">${text}</span>
            <svg class="remove" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M12.5 3.5L3.5 12.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12.5 12.5L3.5 3.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
           </svg>
        </li>`;
    listNode.insertAdjacentHTML('beforeend', item);
  }
};
const updateDestination = () => {
  if (!destinationSelect) {
    return;
  }
  // if (destinationSelect.value ) { 
  //     changeSelectedField('destination', destinationSelect.value, destinationSelect.options[destinationSelect.selectedIndex].text);
  // }

  destinationSelect.addEventListener('change', function () {
    const value = this.value;
    if (value != '') {
      changeSelectedField('destination', value, destinationSelect.options[destinationSelect.selectedIndex].text);
    }
  });
};
const updateMonth = () => {
  if (!monthSelect) {
    return;
  }
  // if (monthSelect.value) {
  //     changeSelectedField('month', monthSelect.value, monthSelect.options[monthSelect.selectedIndex].text);
  // }
  monthSelect.addEventListener('change', function () {
    const value = this.value;
    if (value != '') {
      changeSelectedField('month', value, monthSelect.options[monthSelect.selectedIndex].text);
    }
  });
};
const updateDate = () => {
  if (!dateNode) {
    return;
  }
  if (dateNode.value) {
    const date = dateNode.value.split('-');
    const startDateHTML = date[0];
    const endDateHTML = date[1];
    changeSelectedField('date', startDateHTML + '-' + endDateHTML, startDateHTML + '-' + endDateHTML);
  }
  $('.tour-search-field.date input').on('apply.daterangepicker', function (ev, picker) {
    const startDate = picker.startDate;
    const endDate = picker.endDate;
    const startDateHTML = startDate.format('DD/MM/YYYY');
    const endDateHTML = endDate.format('DD/MM/YYYY');
    changeSelectedField('date', startDateHTML + '-' + endDateHTML, startDateHTML + '-' + endDateHTML);
  });
};
const updateDuration = () => {
  if (!durationNodes) {
    return;
  }
  [...durationNodes].map(durationNode => {
    if (durationNode.checked) {
      const label = durationNode.closest('li').querySelector('label').innerHTML;
      changeSelectedField('duration', durationNode.value, travel_booking_search.duration + label);
    }
    durationNode.addEventListener('change', function () {
      const label = this.closest('li').querySelector('label').innerHTML;
      changeSelectedField('duration', this.value, travel_booking_search.duration + label);
    });
  });
};
const updateRating = () => {
  if (!ratingNodes) {
    return;
  }
  [...ratingNodes].map(ratingNode => {
    if (ratingNode.checked) {
      const value = ratingNode.value;
      const label = value + ' star';
      changeSelectedField('rating', value, label);
    }
    ratingNode.addEventListener('change', function () {
      const value = this.value;
      const label = value + ' star';
      changeSelectedField('rating', value, label);
    });
  });
};
const updateTourtype = () => {
  if (!tourtypeNodes) {
    return;
  }
  [...tourtypeNodes].map(tourtypeNode => {
    if (tourtypeNode.checked) {
      const label = tourtypeNode.closest('li').querySelector('label').innerHTML;
      changeSelectedField('tourtype', tourtypeNode.value, label);
    }
    tourtypeNode.addEventListener('change', function () {
      const label = this.closest('li').querySelector('label').innerHTML;
      changeSelectedField('tourtype', this.value, label);
    });
  });
};
const updatePrice = () => {
  const minPriceNode = document.querySelector('.tour-search-field.price').getAttribute('data-min');
  const maxPriceNode = document.querySelector('.tour-search-field.price').getAttribute('data-max');
  priceSliderNode.noUiSlider.on('update', function (values, handle, unencoded) {
    const minPrice = parseInt(values[0]);
    const maxPrice = parseInt(values[1]);
    if (minPrice != minPriceNode || maxPrice != maxPriceNode) {
      changeSelectedField('price', minPrice + '-' + maxPrice, travel_booking_search.price + (0,_price__WEBPACK_IMPORTED_MODULE_0__.renderPriceFormat)(minPrice) + '-' + (0,_price__WEBPACK_IMPORTED_MODULE_0__.renderPriceFormat)(maxPrice));
    }
  });
};
const removeSelection = () => {
  document.addEventListener('click', function (event) {
    const target = event.target;

    // if (!target.classList.contains('remove')) {
    //     return;
    // }

    const selectionWrapper = target.closest('.tour-search-field.selection');
    if (!selectionWrapper) {
      return;
    }
    const listItem = target.closest('.list-item');
    const field = listItem.getAttribute('data-field');
    switch (field) {
      case 'destination':
        resetDestination();
        break;
      case 'month':
        resetMonth();
        break;
      case 'date':
        resetDate();
        break;
      case 'duration':
        resetDuration(listItem.getAttribute('data-value'));
        break;
      case 'rating':
        resetRating();
        break;
      case 'price':
        resetPrice();
        break;
      case 'tourtype':
        resetTourtype(listItem.getAttribute('data-value'));
      default:
        break;
    }
    if (listItem) {
      listItem.remove();
    }
  });
};
const removeAll = () => {
  clearBtn.addEventListener('click', function () {
    resetDestination();
    resetMonth();
    resetDate();
    resetDuration('all');
    resetRating();
    resetPrice();
    resetTourtype('all');
    listNode.innerHTML = '';
  });
};
const resetDestination = () => {
  if (!destinationSelect) {
    return;
  }
  destinationSelect.selectedIndex = 0;
};
const resetMonth = () => {
  if (!monthSelect) {
    return;
  }
  monthSelect.selectedIndex = 0;
};
const resetDate = () => {
  if (!dateNode) {
    return;
  }
  dateNode.value = '';
};
const resetDuration = (value = 'all') => {
  if (value === 'all') {
    [...durationNodes].map(durationNode => {
      durationNode.checked = false;
    });
  } else {
    const input = document.querySelector(`.tour-search-field .duration-item input[value="${value}"]`);
    input.checked = false;
  }
};
const resetRating = () => {
  [...ratingNodes].map(ratingNode => {
    ratingNode.checked = false;
  });
};
const resetTourtype = (value = 'all') => {
  if (value === 'all') {
    [...tourtypeNodes].map(tourtypeNode => {
      tourtypeNode.checked = false;
    });
  } else {
    const input = document.querySelector(`.tour-search-field.tour-type li input[value="${value}"]`);
    input.checked = false;
  }
};
const resetPrice = () => {
  priceSliderNode.noUiSlider.updateOptions({
    start: [parseInt(priceWrapper.getAttribute('data-min')), parseInt(priceWrapper.getAttribute('data-max'))]
  });
};
const resetAll = () => {
  if (!resetAllBtn) {
    return;
  }
  resetAllBtn.addEventListener('click', function (e) {
    e.preventDefault();
    resetDestination();
    resetMonth();
    resetDate();
    resetDuration('all');
    resetRating();
    resetPrice();
    resetTourtype('all');
    listNode.innerHTML = '';
  });
};
const tourSelectionInit = () => {
  tourSelection();
  resetAll();
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (tourSelectionInit);

/***/ }),

/***/ "./node_modules/nouislider/dist/nouislider.mjs":
/*!*****************************************************!*\
  !*** ./node_modules/nouislider/dist/nouislider.mjs ***!
  \*****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   PipsMode: () => (/* binding */ PipsMode),
/* harmony export */   PipsType: () => (/* binding */ PipsType),
/* harmony export */   create: () => (/* binding */ initialize),
/* harmony export */   cssClasses: () => (/* binding */ cssClasses),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });

var PipsMode;
(function (PipsMode) {
    PipsMode["Range"] = "range";
    PipsMode["Steps"] = "steps";
    PipsMode["Positions"] = "positions";
    PipsMode["Count"] = "count";
    PipsMode["Values"] = "values";
})(PipsMode || (PipsMode = {}));
var PipsType;
(function (PipsType) {
    PipsType[PipsType["None"] = -1] = "None";
    PipsType[PipsType["NoValue"] = 0] = "NoValue";
    PipsType[PipsType["LargeValue"] = 1] = "LargeValue";
    PipsType[PipsType["SmallValue"] = 2] = "SmallValue";
})(PipsType || (PipsType = {}));
//region Helper Methods
function isValidFormatter(entry) {
    return isValidPartialFormatter(entry) && typeof entry.from === "function";
}
function isValidPartialFormatter(entry) {
    // partial formatters only need a to function and not a from function
    return typeof entry === "object" && typeof entry.to === "function";
}
function removeElement(el) {
    el.parentElement.removeChild(el);
}
function isSet(value) {
    return value !== null && value !== undefined;
}
// Bindable version
function preventDefault(e) {
    e.preventDefault();
}
// Removes duplicates from an array.
function unique(array) {
    return array.filter(function (a) {
        return !this[a] ? (this[a] = true) : false;
    }, {});
}
// Round a value to the closest 'to'.
function closest(value, to) {
    return Math.round(value / to) * to;
}
// Current position of an element relative to the document.
function offset(elem, orientation) {
    var rect = elem.getBoundingClientRect();
    var doc = elem.ownerDocument;
    var docElem = doc.documentElement;
    var pageOffset = getPageOffset(doc);
    // getBoundingClientRect contains left scroll in Chrome on Android.
    // I haven't found a feature detection that proves this. Worst case
    // scenario on mis-match: the 'tap' feature on horizontal sliders breaks.
    if (/webkit.*Chrome.*Mobile/i.test(navigator.userAgent)) {
        pageOffset.x = 0;
    }
    return orientation ? rect.top + pageOffset.y - docElem.clientTop : rect.left + pageOffset.x - docElem.clientLeft;
}
// Checks whether a value is numerical.
function isNumeric(a) {
    return typeof a === "number" && !isNaN(a) && isFinite(a);
}
// Sets a class and removes it after [duration] ms.
function addClassFor(element, className, duration) {
    if (duration > 0) {
        addClass(element, className);
        setTimeout(function () {
            removeClass(element, className);
        }, duration);
    }
}
// Limits a value to 0 - 100
function limit(a) {
    return Math.max(Math.min(a, 100), 0);
}
// Wraps a variable as an array, if it isn't one yet.
// Note that an input array is returned by reference!
function asArray(a) {
    return Array.isArray(a) ? a : [a];
}
// Counts decimals
function countDecimals(numStr) {
    numStr = String(numStr);
    var pieces = numStr.split(".");
    return pieces.length > 1 ? pieces[1].length : 0;
}
// http://youmightnotneedjquery.com/#add_class
function addClass(el, className) {
    if (el.classList && !/\s/.test(className)) {
        el.classList.add(className);
    }
    else {
        el.className += " " + className;
    }
}
// http://youmightnotneedjquery.com/#remove_class
function removeClass(el, className) {
    if (el.classList && !/\s/.test(className)) {
        el.classList.remove(className);
    }
    else {
        el.className = el.className.replace(new RegExp("(^|\\b)" + className.split(" ").join("|") + "(\\b|$)", "gi"), " ");
    }
}
// https://plainjs.com/javascript/attributes/adding-removing-and-testing-for-classes-9/
function hasClass(el, className) {
    return el.classList ? el.classList.contains(className) : new RegExp("\\b" + className + "\\b").test(el.className);
}
// https://developer.mozilla.org/en-US/docs/Web/API/Window/scrollY#Notes
function getPageOffset(doc) {
    var supportPageOffset = window.pageXOffset !== undefined;
    var isCSS1Compat = (doc.compatMode || "") === "CSS1Compat";
    var x = supportPageOffset
        ? window.pageXOffset
        : isCSS1Compat
            ? doc.documentElement.scrollLeft
            : doc.body.scrollLeft;
    var y = supportPageOffset
        ? window.pageYOffset
        : isCSS1Compat
            ? doc.documentElement.scrollTop
            : doc.body.scrollTop;
    return {
        x: x,
        y: y,
    };
}
// we provide a function to compute constants instead
// of accessing window.* as soon as the module needs it
// so that we do not compute anything if not needed
function getActions() {
    // Determine the events to bind. IE11 implements pointerEvents without
    // a prefix, which breaks compatibility with the IE10 implementation.
    return window.navigator.pointerEnabled
        ? {
            start: "pointerdown",
            move: "pointermove",
            end: "pointerup",
        }
        : window.navigator.msPointerEnabled
            ? {
                start: "MSPointerDown",
                move: "MSPointerMove",
                end: "MSPointerUp",
            }
            : {
                start: "mousedown touchstart",
                move: "mousemove touchmove",
                end: "mouseup touchend",
            };
}
// https://github.com/WICG/EventListenerOptions/blob/gh-pages/explainer.md
// Issue #785
function getSupportsPassive() {
    var supportsPassive = false;
    /* eslint-disable */
    try {
        var opts = Object.defineProperty({}, "passive", {
            get: function () {
                supportsPassive = true;
            },
        });
        // @ts-ignore
        window.addEventListener("test", null, opts);
    }
    catch (e) { }
    /* eslint-enable */
    return supportsPassive;
}
function getSupportsTouchActionNone() {
    return window.CSS && CSS.supports && CSS.supports("touch-action", "none");
}
//endregion
//region Range Calculation
// Determine the size of a sub-range in relation to a full range.
function subRangeRatio(pa, pb) {
    return 100 / (pb - pa);
}
// (percentage) How many percent is this value of this range?
function fromPercentage(range, value, startRange) {
    return (value * 100) / (range[startRange + 1] - range[startRange]);
}
// (percentage) Where is this value on this range?
function toPercentage(range, value) {
    return fromPercentage(range, range[0] < 0 ? value + Math.abs(range[0]) : value - range[0], 0);
}
// (value) How much is this percentage on this range?
function isPercentage(range, value) {
    return (value * (range[1] - range[0])) / 100 + range[0];
}
function getJ(value, arr) {
    var j = 1;
    while (value >= arr[j]) {
        j += 1;
    }
    return j;
}
// (percentage) Input a value, find where, on a scale of 0-100, it applies.
function toStepping(xVal, xPct, value) {
    if (value >= xVal.slice(-1)[0]) {
        return 100;
    }
    var j = getJ(value, xVal);
    var va = xVal[j - 1];
    var vb = xVal[j];
    var pa = xPct[j - 1];
    var pb = xPct[j];
    return pa + toPercentage([va, vb], value) / subRangeRatio(pa, pb);
}
// (value) Input a percentage, find where it is on the specified range.
function fromStepping(xVal, xPct, value) {
    // There is no range group that fits 100
    if (value >= 100) {
        return xVal.slice(-1)[0];
    }
    var j = getJ(value, xPct);
    var va = xVal[j - 1];
    var vb = xVal[j];
    var pa = xPct[j - 1];
    var pb = xPct[j];
    return isPercentage([va, vb], (value - pa) * subRangeRatio(pa, pb));
}
// (percentage) Get the step that applies at a certain value.
function getStep(xPct, xSteps, snap, value) {
    if (value === 100) {
        return value;
    }
    var j = getJ(value, xPct);
    var a = xPct[j - 1];
    var b = xPct[j];
    // If 'snap' is set, steps are used as fixed points on the slider.
    if (snap) {
        // Find the closest position, a or b.
        if (value - a > (b - a) / 2) {
            return b;
        }
        return a;
    }
    if (!xSteps[j - 1]) {
        return value;
    }
    return xPct[j - 1] + closest(value - xPct[j - 1], xSteps[j - 1]);
}
//endregion
//region Spectrum
var Spectrum = /** @class */ (function () {
    function Spectrum(entry, snap, singleStep) {
        this.xPct = [];
        this.xVal = [];
        this.xSteps = [];
        this.xNumSteps = [];
        this.xHighestCompleteStep = [];
        this.xSteps = [singleStep || false];
        this.xNumSteps = [false];
        this.snap = snap;
        var index;
        var ordered = [];
        // Map the object keys to an array.
        Object.keys(entry).forEach(function (index) {
            ordered.push([asArray(entry[index]), index]);
        });
        // Sort all entries by value (numeric sort).
        ordered.sort(function (a, b) {
            return a[0][0] - b[0][0];
        });
        // Convert all entries to subranges.
        for (index = 0; index < ordered.length; index++) {
            this.handleEntryPoint(ordered[index][1], ordered[index][0]);
        }
        // Store the actual step values.
        // xSteps is sorted in the same order as xPct and xVal.
        this.xNumSteps = this.xSteps.slice(0);
        // Convert all numeric steps to the percentage of the subrange they represent.
        for (index = 0; index < this.xNumSteps.length; index++) {
            this.handleStepPoint(index, this.xNumSteps[index]);
        }
    }
    Spectrum.prototype.getDistance = function (value) {
        var distances = [];
        for (var index = 0; index < this.xNumSteps.length - 1; index++) {
            distances[index] = fromPercentage(this.xVal, value, index);
        }
        return distances;
    };
    // Calculate the percentual distance over the whole scale of ranges.
    // direction: 0 = backwards / 1 = forwards
    Spectrum.prototype.getAbsoluteDistance = function (value, distances, direction) {
        var xPct_index = 0;
        // Calculate range where to start calculation
        if (value < this.xPct[this.xPct.length - 1]) {
            while (value > this.xPct[xPct_index + 1]) {
                xPct_index++;
            }
        }
        else if (value === this.xPct[this.xPct.length - 1]) {
            xPct_index = this.xPct.length - 2;
        }
        // If looking backwards and the value is exactly at a range separator then look one range further
        if (!direction && value === this.xPct[xPct_index + 1]) {
            xPct_index++;
        }
        if (distances === null) {
            distances = [];
        }
        var start_factor;
        var rest_factor = 1;
        var rest_rel_distance = distances[xPct_index];
        var range_pct = 0;
        var rel_range_distance = 0;
        var abs_distance_counter = 0;
        var range_counter = 0;
        // Calculate what part of the start range the value is
        if (direction) {
            start_factor = (value - this.xPct[xPct_index]) / (this.xPct[xPct_index + 1] - this.xPct[xPct_index]);
        }
        else {
            start_factor = (this.xPct[xPct_index + 1] - value) / (this.xPct[xPct_index + 1] - this.xPct[xPct_index]);
        }
        // Do until the complete distance across ranges is calculated
        while (rest_rel_distance > 0) {
            // Calculate the percentage of total range
            range_pct = this.xPct[xPct_index + 1 + range_counter] - this.xPct[xPct_index + range_counter];
            // Detect if the margin, padding or limit is larger then the current range and calculate
            if (distances[xPct_index + range_counter] * rest_factor + 100 - start_factor * 100 > 100) {
                // If larger then take the percentual distance of the whole range
                rel_range_distance = range_pct * start_factor;
                // Rest factor of relative percentual distance still to be calculated
                rest_factor = (rest_rel_distance - 100 * start_factor) / distances[xPct_index + range_counter];
                // Set start factor to 1 as for next range it does not apply.
                start_factor = 1;
            }
            else {
                // If smaller or equal then take the percentual distance of the calculate percentual part of that range
                rel_range_distance = ((distances[xPct_index + range_counter] * range_pct) / 100) * rest_factor;
                // No rest left as the rest fits in current range
                rest_factor = 0;
            }
            if (direction) {
                abs_distance_counter = abs_distance_counter - rel_range_distance;
                // Limit range to first range when distance becomes outside of minimum range
                if (this.xPct.length + range_counter >= 1) {
                    range_counter--;
                }
            }
            else {
                abs_distance_counter = abs_distance_counter + rel_range_distance;
                // Limit range to last range when distance becomes outside of maximum range
                if (this.xPct.length - range_counter >= 1) {
                    range_counter++;
                }
            }
            // Rest of relative percentual distance still to be calculated
            rest_rel_distance = distances[xPct_index + range_counter] * rest_factor;
        }
        return value + abs_distance_counter;
    };
    Spectrum.prototype.toStepping = function (value) {
        value = toStepping(this.xVal, this.xPct, value);
        return value;
    };
    Spectrum.prototype.fromStepping = function (value) {
        return fromStepping(this.xVal, this.xPct, value);
    };
    Spectrum.prototype.getStep = function (value) {
        value = getStep(this.xPct, this.xSteps, this.snap, value);
        return value;
    };
    Spectrum.prototype.getDefaultStep = function (value, isDown, size) {
        var j = getJ(value, this.xPct);
        // When at the top or stepping down, look at the previous sub-range
        if (value === 100 || (isDown && value === this.xPct[j - 1])) {
            j = Math.max(j - 1, 1);
        }
        return (this.xVal[j] - this.xVal[j - 1]) / size;
    };
    Spectrum.prototype.getNearbySteps = function (value) {
        var j = getJ(value, this.xPct);
        return {
            stepBefore: {
                startValue: this.xVal[j - 2],
                step: this.xNumSteps[j - 2],
                highestStep: this.xHighestCompleteStep[j - 2],
            },
            thisStep: {
                startValue: this.xVal[j - 1],
                step: this.xNumSteps[j - 1],
                highestStep: this.xHighestCompleteStep[j - 1],
            },
            stepAfter: {
                startValue: this.xVal[j],
                step: this.xNumSteps[j],
                highestStep: this.xHighestCompleteStep[j],
            },
        };
    };
    Spectrum.prototype.countStepDecimals = function () {
        var stepDecimals = this.xNumSteps.map(countDecimals);
        return Math.max.apply(null, stepDecimals);
    };
    Spectrum.prototype.hasNoSize = function () {
        return this.xVal[0] === this.xVal[this.xVal.length - 1];
    };
    // Outside testing
    Spectrum.prototype.convert = function (value) {
        return this.getStep(this.toStepping(value));
    };
    Spectrum.prototype.handleEntryPoint = function (index, value) {
        var percentage;
        // Covert min/max syntax to 0 and 100.
        if (index === "min") {
            percentage = 0;
        }
        else if (index === "max") {
            percentage = 100;
        }
        else {
            percentage = parseFloat(index);
        }
        // Check for correct input.
        if (!isNumeric(percentage) || !isNumeric(value[0])) {
            throw new Error("noUiSlider: 'range' value isn't numeric.");
        }
        // Store values.
        this.xPct.push(percentage);
        this.xVal.push(value[0]);
        var value1 = Number(value[1]);
        // NaN will evaluate to false too, but to keep
        // logging clear, set step explicitly. Make sure
        // not to override the 'step' setting with false.
        if (!percentage) {
            if (!isNaN(value1)) {
                this.xSteps[0] = value1;
            }
        }
        else {
            this.xSteps.push(isNaN(value1) ? false : value1);
        }
        this.xHighestCompleteStep.push(0);
    };
    Spectrum.prototype.handleStepPoint = function (i, n) {
        // Ignore 'false' stepping.
        if (!n) {
            return;
        }
        // Step over zero-length ranges (#948);
        if (this.xVal[i] === this.xVal[i + 1]) {
            this.xSteps[i] = this.xHighestCompleteStep[i] = this.xVal[i];
            return;
        }
        // Factor to range ratio
        this.xSteps[i] =
            fromPercentage([this.xVal[i], this.xVal[i + 1]], n, 0) / subRangeRatio(this.xPct[i], this.xPct[i + 1]);
        var totalSteps = (this.xVal[i + 1] - this.xVal[i]) / this.xNumSteps[i];
        var highestStep = Math.ceil(Number(totalSteps.toFixed(3)) - 1);
        var step = this.xVal[i] + this.xNumSteps[i] * highestStep;
        this.xHighestCompleteStep[i] = step;
    };
    return Spectrum;
}());
//endregion
//region Options
/*	Every input option is tested and parsed. This will prevent
    endless validation in internal methods. These tests are
    structured with an item for every option available. An
    option can be marked as required by setting the 'r' flag.
    The testing function is provided with three arguments:
        - The provided value for the option;
        - A reference to the options object;
        - The name for the option;

    The testing function returns false when an error is detected,
    or true when everything is OK. It can also modify the option
    object, to make sure all values can be correctly looped elsewhere. */
//region Defaults
var defaultFormatter = {
    to: function (value) {
        return value === undefined ? "" : value.toFixed(2);
    },
    from: Number,
};
var cssClasses = {
    target: "target",
    base: "base",
    origin: "origin",
    handle: "handle",
    handleLower: "handle-lower",
    handleUpper: "handle-upper",
    touchArea: "touch-area",
    horizontal: "horizontal",
    vertical: "vertical",
    background: "background",
    connect: "connect",
    connects: "connects",
    ltr: "ltr",
    rtl: "rtl",
    textDirectionLtr: "txt-dir-ltr",
    textDirectionRtl: "txt-dir-rtl",
    draggable: "draggable",
    drag: "state-drag",
    tap: "state-tap",
    active: "active",
    tooltip: "tooltip",
    pips: "pips",
    pipsHorizontal: "pips-horizontal",
    pipsVertical: "pips-vertical",
    marker: "marker",
    markerHorizontal: "marker-horizontal",
    markerVertical: "marker-vertical",
    markerNormal: "marker-normal",
    markerLarge: "marker-large",
    markerSub: "marker-sub",
    value: "value",
    valueHorizontal: "value-horizontal",
    valueVertical: "value-vertical",
    valueNormal: "value-normal",
    valueLarge: "value-large",
    valueSub: "value-sub",
};
// Namespaces of internal event listeners
var INTERNAL_EVENT_NS = {
    tooltips: ".__tooltips",
    aria: ".__aria",
};
//endregion
function testStep(parsed, entry) {
    if (!isNumeric(entry)) {
        throw new Error("noUiSlider: 'step' is not numeric.");
    }
    // The step option can still be used to set stepping
    // for linear sliders. Overwritten if set in 'range'.
    parsed.singleStep = entry;
}
function testKeyboardPageMultiplier(parsed, entry) {
    if (!isNumeric(entry)) {
        throw new Error("noUiSlider: 'keyboardPageMultiplier' is not numeric.");
    }
    parsed.keyboardPageMultiplier = entry;
}
function testKeyboardMultiplier(parsed, entry) {
    if (!isNumeric(entry)) {
        throw new Error("noUiSlider: 'keyboardMultiplier' is not numeric.");
    }
    parsed.keyboardMultiplier = entry;
}
function testKeyboardDefaultStep(parsed, entry) {
    if (!isNumeric(entry)) {
        throw new Error("noUiSlider: 'keyboardDefaultStep' is not numeric.");
    }
    parsed.keyboardDefaultStep = entry;
}
function testRange(parsed, entry) {
    // Filter incorrect input.
    if (typeof entry !== "object" || Array.isArray(entry)) {
        throw new Error("noUiSlider: 'range' is not an object.");
    }
    // Catch missing start or end.
    if (entry.min === undefined || entry.max === undefined) {
        throw new Error("noUiSlider: Missing 'min' or 'max' in 'range'.");
    }
    parsed.spectrum = new Spectrum(entry, parsed.snap || false, parsed.singleStep);
}
function testStart(parsed, entry) {
    entry = asArray(entry);
    // Validate input. Values aren't tested, as the public .val method
    // will always provide a valid location.
    if (!Array.isArray(entry) || !entry.length) {
        throw new Error("noUiSlider: 'start' option is incorrect.");
    }
    // Store the number of handles.
    parsed.handles = entry.length;
    // When the slider is initialized, the .val method will
    // be called with the start options.
    parsed.start = entry;
}
function testSnap(parsed, entry) {
    if (typeof entry !== "boolean") {
        throw new Error("noUiSlider: 'snap' option must be a boolean.");
    }
    // Enforce 100% stepping within subranges.
    parsed.snap = entry;
}
function testAnimate(parsed, entry) {
    if (typeof entry !== "boolean") {
        throw new Error("noUiSlider: 'animate' option must be a boolean.");
    }
    // Enforce 100% stepping within subranges.
    parsed.animate = entry;
}
function testAnimationDuration(parsed, entry) {
    if (typeof entry !== "number") {
        throw new Error("noUiSlider: 'animationDuration' option must be a number.");
    }
    parsed.animationDuration = entry;
}
function testConnect(parsed, entry) {
    var connect = [false];
    var i;
    // Map legacy options
    if (entry === "lower") {
        entry = [true, false];
    }
    else if (entry === "upper") {
        entry = [false, true];
    }
    // Handle boolean options
    if (entry === true || entry === false) {
        for (i = 1; i < parsed.handles; i++) {
            connect.push(entry);
        }
        connect.push(false);
    }
    // Reject invalid input
    else if (!Array.isArray(entry) || !entry.length || entry.length !== parsed.handles + 1) {
        throw new Error("noUiSlider: 'connect' option doesn't match handle count.");
    }
    else {
        connect = entry;
    }
    parsed.connect = connect;
}
function testOrientation(parsed, entry) {
    // Set orientation to an a numerical value for easy
    // array selection.
    switch (entry) {
        case "horizontal":
            parsed.ort = 0;
            break;
        case "vertical":
            parsed.ort = 1;
            break;
        default:
            throw new Error("noUiSlider: 'orientation' option is invalid.");
    }
}
function testMargin(parsed, entry) {
    if (!isNumeric(entry)) {
        throw new Error("noUiSlider: 'margin' option must be numeric.");
    }
    // Issue #582
    if (entry === 0) {
        return;
    }
    parsed.margin = parsed.spectrum.getDistance(entry);
}
function testLimit(parsed, entry) {
    if (!isNumeric(entry)) {
        throw new Error("noUiSlider: 'limit' option must be numeric.");
    }
    parsed.limit = parsed.spectrum.getDistance(entry);
    if (!parsed.limit || parsed.handles < 2) {
        throw new Error("noUiSlider: 'limit' option is only supported on linear sliders with 2 or more handles.");
    }
}
function testPadding(parsed, entry) {
    var index;
    if (!isNumeric(entry) && !Array.isArray(entry)) {
        throw new Error("noUiSlider: 'padding' option must be numeric or array of exactly 2 numbers.");
    }
    if (Array.isArray(entry) && !(entry.length === 2 || isNumeric(entry[0]) || isNumeric(entry[1]))) {
        throw new Error("noUiSlider: 'padding' option must be numeric or array of exactly 2 numbers.");
    }
    if (entry === 0) {
        return;
    }
    if (!Array.isArray(entry)) {
        entry = [entry, entry];
    }
    // 'getDistance' returns false for invalid values.
    parsed.padding = [parsed.spectrum.getDistance(entry[0]), parsed.spectrum.getDistance(entry[1])];
    for (index = 0; index < parsed.spectrum.xNumSteps.length - 1; index++) {
        // last "range" can't contain step size as it is purely an endpoint.
        if (parsed.padding[0][index] < 0 || parsed.padding[1][index] < 0) {
            throw new Error("noUiSlider: 'padding' option must be a positive number(s).");
        }
    }
    var totalPadding = entry[0] + entry[1];
    var firstValue = parsed.spectrum.xVal[0];
    var lastValue = parsed.spectrum.xVal[parsed.spectrum.xVal.length - 1];
    if (totalPadding / (lastValue - firstValue) > 1) {
        throw new Error("noUiSlider: 'padding' option must not exceed 100% of the range.");
    }
}
function testDirection(parsed, entry) {
    // Set direction as a numerical value for easy parsing.
    // Invert connection for RTL sliders, so that the proper
    // handles get the connect/background classes.
    switch (entry) {
        case "ltr":
            parsed.dir = 0;
            break;
        case "rtl":
            parsed.dir = 1;
            break;
        default:
            throw new Error("noUiSlider: 'direction' option was not recognized.");
    }
}
function testBehaviour(parsed, entry) {
    // Make sure the input is a string.
    if (typeof entry !== "string") {
        throw new Error("noUiSlider: 'behaviour' must be a string containing options.");
    }
    // Check if the string contains any keywords.
    // None are required.
    var tap = entry.indexOf("tap") >= 0;
    var drag = entry.indexOf("drag") >= 0;
    var fixed = entry.indexOf("fixed") >= 0;
    var snap = entry.indexOf("snap") >= 0;
    var hover = entry.indexOf("hover") >= 0;
    var unconstrained = entry.indexOf("unconstrained") >= 0;
    var invertConnects = entry.indexOf("invert-connects") >= 0;
    var dragAll = entry.indexOf("drag-all") >= 0;
    var smoothSteps = entry.indexOf("smooth-steps") >= 0;
    if (fixed) {
        if (parsed.handles !== 2) {
            throw new Error("noUiSlider: 'fixed' behaviour must be used with 2 handles");
        }
        // Use margin to enforce fixed state
        testMargin(parsed, parsed.start[1] - parsed.start[0]);
    }
    if (invertConnects && parsed.handles !== 2) {
        throw new Error("noUiSlider: 'invert-connects' behaviour must be used with 2 handles");
    }
    if (unconstrained && (parsed.margin || parsed.limit)) {
        throw new Error("noUiSlider: 'unconstrained' behaviour cannot be used with margin or limit");
    }
    parsed.events = {
        tap: tap || snap,
        drag: drag,
        dragAll: dragAll,
        smoothSteps: smoothSteps,
        fixed: fixed,
        snap: snap,
        hover: hover,
        unconstrained: unconstrained,
        invertConnects: invertConnects,
    };
}
function testTooltips(parsed, entry) {
    if (entry === false) {
        return;
    }
    if (entry === true || isValidPartialFormatter(entry)) {
        parsed.tooltips = [];
        for (var i = 0; i < parsed.handles; i++) {
            parsed.tooltips.push(entry);
        }
    }
    else {
        entry = asArray(entry);
        if (entry.length !== parsed.handles) {
            throw new Error("noUiSlider: must pass a formatter for all handles.");
        }
        entry.forEach(function (formatter) {
            if (typeof formatter !== "boolean" && !isValidPartialFormatter(formatter)) {
                throw new Error("noUiSlider: 'tooltips' must be passed a formatter or 'false'.");
            }
        });
        parsed.tooltips = entry;
    }
}
function testHandleAttributes(parsed, entry) {
    if (entry.length !== parsed.handles) {
        throw new Error("noUiSlider: must pass a attributes for all handles.");
    }
    parsed.handleAttributes = entry;
}
function testAriaFormat(parsed, entry) {
    if (!isValidPartialFormatter(entry)) {
        throw new Error("noUiSlider: 'ariaFormat' requires 'to' method.");
    }
    parsed.ariaFormat = entry;
}
function testFormat(parsed, entry) {
    if (!isValidFormatter(entry)) {
        throw new Error("noUiSlider: 'format' requires 'to' and 'from' methods.");
    }
    parsed.format = entry;
}
function testKeyboardSupport(parsed, entry) {
    if (typeof entry !== "boolean") {
        throw new Error("noUiSlider: 'keyboardSupport' option must be a boolean.");
    }
    parsed.keyboardSupport = entry;
}
function testDocumentElement(parsed, entry) {
    // This is an advanced option. Passed values are used without validation.
    parsed.documentElement = entry;
}
function testCssPrefix(parsed, entry) {
    if (typeof entry !== "string" && entry !== false) {
        throw new Error("noUiSlider: 'cssPrefix' must be a string or `false`.");
    }
    parsed.cssPrefix = entry;
}
function testCssClasses(parsed, entry) {
    if (typeof entry !== "object") {
        throw new Error("noUiSlider: 'cssClasses' must be an object.");
    }
    if (typeof parsed.cssPrefix === "string") {
        parsed.cssClasses = {};
        Object.keys(entry).forEach(function (key) {
            parsed.cssClasses[key] = parsed.cssPrefix + entry[key];
        });
    }
    else {
        parsed.cssClasses = entry;
    }
}
// Test all developer settings and parse to assumption-safe values.
function testOptions(options) {
    // To prove a fix for #537, freeze options here.
    // If the object is modified, an error will be thrown.
    // Object.freeze(options);
    var parsed = {
        margin: null,
        limit: null,
        padding: null,
        animate: true,
        animationDuration: 300,
        ariaFormat: defaultFormatter,
        format: defaultFormatter,
    };
    // Tests are executed in the order they are presented here.
    var tests = {
        step: { r: false, t: testStep },
        keyboardPageMultiplier: { r: false, t: testKeyboardPageMultiplier },
        keyboardMultiplier: { r: false, t: testKeyboardMultiplier },
        keyboardDefaultStep: { r: false, t: testKeyboardDefaultStep },
        start: { r: true, t: testStart },
        connect: { r: true, t: testConnect },
        direction: { r: true, t: testDirection },
        snap: { r: false, t: testSnap },
        animate: { r: false, t: testAnimate },
        animationDuration: { r: false, t: testAnimationDuration },
        range: { r: true, t: testRange },
        orientation: { r: false, t: testOrientation },
        margin: { r: false, t: testMargin },
        limit: { r: false, t: testLimit },
        padding: { r: false, t: testPadding },
        behaviour: { r: true, t: testBehaviour },
        ariaFormat: { r: false, t: testAriaFormat },
        format: { r: false, t: testFormat },
        tooltips: { r: false, t: testTooltips },
        keyboardSupport: { r: true, t: testKeyboardSupport },
        documentElement: { r: false, t: testDocumentElement },
        cssPrefix: { r: true, t: testCssPrefix },
        cssClasses: { r: true, t: testCssClasses },
        handleAttributes: { r: false, t: testHandleAttributes },
    };
    var defaults = {
        connect: false,
        direction: "ltr",
        behaviour: "tap",
        orientation: "horizontal",
        keyboardSupport: true,
        cssPrefix: "noUi-",
        cssClasses: cssClasses,
        keyboardPageMultiplier: 5,
        keyboardMultiplier: 1,
        keyboardDefaultStep: 10,
    };
    // AriaFormat defaults to regular format, if any.
    if (options.format && !options.ariaFormat) {
        options.ariaFormat = options.format;
    }
    // Run all options through a testing mechanism to ensure correct
    // input. It should be noted that options might get modified to
    // be handled properly. E.g. wrapping integers in arrays.
    Object.keys(tests).forEach(function (name) {
        // If the option isn't set, but it is required, throw an error.
        if (!isSet(options[name]) && defaults[name] === undefined) {
            if (tests[name].r) {
                throw new Error("noUiSlider: '" + name + "' is required.");
            }
            return;
        }
        tests[name].t(parsed, !isSet(options[name]) ? defaults[name] : options[name]);
    });
    // Forward pips options
    parsed.pips = options.pips;
    // All recent browsers accept unprefixed transform.
    // We need -ms- for IE9 and -webkit- for older Android;
    // Assume use of -webkit- if unprefixed and -ms- are not supported.
    // https://caniuse.com/#feat=transforms2d
    var d = document.createElement("div");
    var msPrefix = d.style.msTransform !== undefined;
    var noPrefix = d.style.transform !== undefined;
    parsed.transformRule = noPrefix ? "transform" : msPrefix ? "msTransform" : "webkitTransform";
    // Pips don't move, so we can place them using left/top.
    var styles = [
        ["left", "top"],
        ["right", "bottom"],
    ];
    parsed.style = styles[parsed.dir][parsed.ort];
    return parsed;
}
//endregion
function scope(target, options, originalOptions) {
    var actions = getActions();
    var supportsTouchActionNone = getSupportsTouchActionNone();
    var supportsPassive = supportsTouchActionNone && getSupportsPassive();
    // All variables local to 'scope' are prefixed with 'scope_'
    // Slider DOM Nodes
    var scope_Target = target;
    var scope_Base;
    var scope_ConnectBase;
    var scope_Handles;
    var scope_Connects;
    var scope_Pips;
    var scope_Tooltips;
    // Slider state values
    var scope_Spectrum = options.spectrum;
    var scope_Values = [];
    var scope_Locations = [];
    var scope_HandleNumbers = [];
    var scope_ActiveHandlesCount = 0;
    var scope_Events = {};
    var scope_ConnectsInverted = false;
    // Document Nodes
    var scope_Document = target.ownerDocument;
    var scope_DocumentElement = options.documentElement || scope_Document.documentElement;
    var scope_Body = scope_Document.body;
    // For horizontal sliders in standard ltr documents,
    // make .noUi-origin overflow to the left so the document doesn't scroll.
    var scope_DirOffset = scope_Document.dir === "rtl" || options.ort === 1 ? 0 : 100;
    // Creates a node, adds it to target, returns the new node.
    function addNodeTo(addTarget, className) {
        var div = scope_Document.createElement("div");
        if (className) {
            addClass(div, className);
        }
        addTarget.appendChild(div);
        return div;
    }
    // Append a origin to the base
    function addOrigin(base, handleNumber) {
        var origin = addNodeTo(base, options.cssClasses.origin);
        var handle = addNodeTo(origin, options.cssClasses.handle);
        addNodeTo(handle, options.cssClasses.touchArea);
        handle.setAttribute("data-handle", String(handleNumber));
        if (options.keyboardSupport) {
            // https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/tabindex
            // 0 = focusable and reachable
            handle.setAttribute("tabindex", "0");
            handle.addEventListener("keydown", function (event) {
                return eventKeydown(event, handleNumber);
            });
        }
        if (options.handleAttributes !== undefined) {
            var attributes_1 = options.handleAttributes[handleNumber];
            Object.keys(attributes_1).forEach(function (attribute) {
                handle.setAttribute(attribute, attributes_1[attribute]);
            });
        }
        handle.setAttribute("role", "slider");
        handle.setAttribute("aria-orientation", options.ort ? "vertical" : "horizontal");
        if (handleNumber === 0) {
            addClass(handle, options.cssClasses.handleLower);
        }
        else if (handleNumber === options.handles - 1) {
            addClass(handle, options.cssClasses.handleUpper);
        }
        origin.handle = handle;
        return origin;
    }
    // Insert nodes for connect elements
    function addConnect(base, add) {
        if (!add) {
            return false;
        }
        return addNodeTo(base, options.cssClasses.connect);
    }
    // Add handles to the slider base.
    function addElements(connectOptions, base) {
        scope_ConnectBase = addNodeTo(base, options.cssClasses.connects);
        scope_Handles = [];
        scope_Connects = [];
        scope_Connects.push(addConnect(scope_ConnectBase, connectOptions[0]));
        // [::::O====O====O====]
        // connectOptions = [0, 1, 1, 1]
        for (var i = 0; i < options.handles; i++) {
            // Keep a list of all added handles.
            scope_Handles.push(addOrigin(base, i));
            scope_HandleNumbers[i] = i;
            scope_Connects.push(addConnect(scope_ConnectBase, connectOptions[i + 1]));
        }
    }
    // Initialize a single slider.
    function addSlider(addTarget) {
        // Apply classes and data to the target.
        addClass(addTarget, options.cssClasses.target);
        if (options.dir === 0) {
            addClass(addTarget, options.cssClasses.ltr);
        }
        else {
            addClass(addTarget, options.cssClasses.rtl);
        }
        if (options.ort === 0) {
            addClass(addTarget, options.cssClasses.horizontal);
        }
        else {
            addClass(addTarget, options.cssClasses.vertical);
        }
        var textDirection = getComputedStyle(addTarget).direction;
        if (textDirection === "rtl") {
            addClass(addTarget, options.cssClasses.textDirectionRtl);
        }
        else {
            addClass(addTarget, options.cssClasses.textDirectionLtr);
        }
        return addNodeTo(addTarget, options.cssClasses.base);
    }
    function addTooltip(handle, handleNumber) {
        if (!options.tooltips || !options.tooltips[handleNumber]) {
            return false;
        }
        return addNodeTo(handle.firstChild, options.cssClasses.tooltip);
    }
    function isSliderDisabled() {
        return scope_Target.hasAttribute("disabled");
    }
    // Disable the slider dragging if any handle is disabled
    function isHandleDisabled(handleNumber) {
        var handleOrigin = scope_Handles[handleNumber];
        return handleOrigin.hasAttribute("disabled");
    }
    function disable(handleNumber) {
        if (handleNumber !== null && handleNumber !== undefined) {
            scope_Handles[handleNumber].setAttribute("disabled", "");
            scope_Handles[handleNumber].handle.removeAttribute("tabindex");
        }
        else {
            scope_Target.setAttribute("disabled", "");
            scope_Handles.forEach(function (handle) {
                handle.handle.removeAttribute("tabindex");
            });
        }
    }
    function enable(handleNumber) {
        if (handleNumber !== null && handleNumber !== undefined) {
            scope_Handles[handleNumber].removeAttribute("disabled");
            scope_Handles[handleNumber].handle.setAttribute("tabindex", "0");
        }
        else {
            scope_Target.removeAttribute("disabled");
            scope_Handles.forEach(function (handle) {
                handle.removeAttribute("disabled");
                handle.handle.setAttribute("tabindex", "0");
            });
        }
    }
    function removeTooltips() {
        if (scope_Tooltips) {
            removeEvent("update" + INTERNAL_EVENT_NS.tooltips);
            scope_Tooltips.forEach(function (tooltip) {
                if (tooltip) {
                    removeElement(tooltip);
                }
            });
            scope_Tooltips = null;
        }
    }
    // The tooltips option is a shorthand for using the 'update' event.
    function tooltips() {
        removeTooltips();
        // Tooltips are added with options.tooltips in original order.
        scope_Tooltips = scope_Handles.map(addTooltip);
        bindEvent("update" + INTERNAL_EVENT_NS.tooltips, function (values, handleNumber, unencoded) {
            if (!scope_Tooltips || !options.tooltips) {
                return;
            }
            if (scope_Tooltips[handleNumber] === false) {
                return;
            }
            var formattedValue = values[handleNumber];
            if (options.tooltips[handleNumber] !== true) {
                formattedValue = options.tooltips[handleNumber].to(unencoded[handleNumber]);
            }
            scope_Tooltips[handleNumber].innerHTML = formattedValue;
        });
    }
    function aria() {
        removeEvent("update" + INTERNAL_EVENT_NS.aria);
        bindEvent("update" + INTERNAL_EVENT_NS.aria, function (values, handleNumber, unencoded, tap, positions) {
            // Update Aria Values for all handles, as a change in one changes min and max values for the next.
            scope_HandleNumbers.forEach(function (index) {
                var handle = scope_Handles[index];
                var min = checkHandlePosition(scope_Locations, index, 0, true, true, true);
                var max = checkHandlePosition(scope_Locations, index, 100, true, true, true);
                var now = positions[index];
                // Formatted value for display
                var text = String(options.ariaFormat.to(unencoded[index]));
                // Map to slider range values
                min = scope_Spectrum.fromStepping(min).toFixed(1);
                max = scope_Spectrum.fromStepping(max).toFixed(1);
                now = scope_Spectrum.fromStepping(now).toFixed(1);
                handle.children[0].setAttribute("aria-valuemin", min);
                handle.children[0].setAttribute("aria-valuemax", max);
                handle.children[0].setAttribute("aria-valuenow", now);
                handle.children[0].setAttribute("aria-valuetext", text);
            });
        });
    }
    function getGroup(pips) {
        // Use the range.
        if (pips.mode === PipsMode.Range || pips.mode === PipsMode.Steps) {
            return scope_Spectrum.xVal;
        }
        if (pips.mode === PipsMode.Count) {
            if (pips.values < 2) {
                throw new Error("noUiSlider: 'values' (>= 2) required for mode 'count'.");
            }
            // Divide 0 - 100 in 'count' parts.
            var interval = pips.values - 1;
            var spread = 100 / interval;
            var values = [];
            // List these parts and have them handled as 'positions'.
            while (interval--) {
                values[interval] = interval * spread;
            }
            values.push(100);
            return mapToRange(values, pips.stepped);
        }
        if (pips.mode === PipsMode.Positions) {
            // Map all percentages to on-range values.
            return mapToRange(pips.values, pips.stepped);
        }
        if (pips.mode === PipsMode.Values) {
            // If the value must be stepped, it needs to be converted to a percentage first.
            if (pips.stepped) {
                return pips.values.map(function (value) {
                    // Convert to percentage, apply step, return to value.
                    return scope_Spectrum.fromStepping(scope_Spectrum.getStep(scope_Spectrum.toStepping(value)));
                });
            }
            // Otherwise, we can simply use the values.
            return pips.values;
        }
        return []; // pips.mode = never
    }
    function mapToRange(values, stepped) {
        return values.map(function (value) {
            return scope_Spectrum.fromStepping(stepped ? scope_Spectrum.getStep(value) : value);
        });
    }
    function generateSpread(pips) {
        function safeIncrement(value, increment) {
            // Avoid floating point variance by dropping the smallest decimal places.
            return Number((value + increment).toFixed(7));
        }
        var group = getGroup(pips);
        var indexes = {};
        var firstInRange = scope_Spectrum.xVal[0];
        var lastInRange = scope_Spectrum.xVal[scope_Spectrum.xVal.length - 1];
        var ignoreFirst = false;
        var ignoreLast = false;
        var prevPct = 0;
        // Create a copy of the group, sort it and filter away all duplicates.
        group = unique(group.slice().sort(function (a, b) {
            return a - b;
        }));
        // Make sure the range starts with the first element.
        if (group[0] !== firstInRange) {
            group.unshift(firstInRange);
            ignoreFirst = true;
        }
        // Likewise for the last one.
        if (group[group.length - 1] !== lastInRange) {
            group.push(lastInRange);
            ignoreLast = true;
        }
        group.forEach(function (current, index) {
            // Get the current step and the lower + upper positions.
            var step;
            var i;
            var q;
            var low = current;
            var high = group[index + 1];
            var newPct;
            var pctDifference;
            var pctPos;
            var type;
            var steps;
            var realSteps;
            var stepSize;
            var isSteps = pips.mode === PipsMode.Steps;
            // When using 'steps' mode, use the provided steps.
            // Otherwise, we'll step on to the next subrange.
            if (isSteps) {
                step = scope_Spectrum.xNumSteps[index];
            }
            // Default to a 'full' step.
            if (!step) {
                step = high - low;
            }
            // If high is undefined we are at the last subrange. Make sure it iterates once (#1088)
            if (high === undefined) {
                high = low;
            }
            // Make sure step isn't 0, which would cause an infinite loop (#654)
            step = Math.max(step, 0.0000001);
            // Find all steps in the subrange.
            for (i = low; i <= high; i = safeIncrement(i, step)) {
                // Get the percentage value for the current step,
                // calculate the size for the subrange.
                newPct = scope_Spectrum.toStepping(i);
                pctDifference = newPct - prevPct;
                steps = pctDifference / (pips.density || 1);
                realSteps = Math.round(steps);
                // This ratio represents the amount of percentage-space a point indicates.
                // For a density 1 the points/percentage = 1. For density 2, that percentage needs to be re-divided.
                // Round the percentage offset to an even number, then divide by two
                // to spread the offset on both sides of the range.
                stepSize = pctDifference / realSteps;
                // Divide all points evenly, adding the correct number to this subrange.
                // Run up to <= so that 100% gets a point, event if ignoreLast is set.
                for (q = 1; q <= realSteps; q += 1) {
                    // The ratio between the rounded value and the actual size might be ~1% off.
                    // Correct the percentage offset by the number of points
                    // per subrange. density = 1 will result in 100 points on the
                    // full range, 2 for 50, 4 for 25, etc.
                    pctPos = prevPct + q * stepSize;
                    indexes[pctPos.toFixed(5)] = [scope_Spectrum.fromStepping(pctPos), 0];
                }
                // Determine the point type.
                type = group.indexOf(i) > -1 ? PipsType.LargeValue : isSteps ? PipsType.SmallValue : PipsType.NoValue;
                // Enforce the 'ignoreFirst' option by overwriting the type for 0.
                if (!index && ignoreFirst && i !== high) {
                    type = 0;
                }
                if (!(i === high && ignoreLast)) {
                    // Mark the 'type' of this point. 0 = plain, 1 = real value, 2 = step value.
                    indexes[newPct.toFixed(5)] = [i, type];
                }
                // Update the percentage count.
                prevPct = newPct;
            }
        });
        return indexes;
    }
    function addMarking(spread, filterFunc, formatter) {
        var _a, _b;
        var element = scope_Document.createElement("div");
        var valueSizeClasses = (_a = {},
            _a[PipsType.None] = "",
            _a[PipsType.NoValue] = options.cssClasses.valueNormal,
            _a[PipsType.LargeValue] = options.cssClasses.valueLarge,
            _a[PipsType.SmallValue] = options.cssClasses.valueSub,
            _a);
        var markerSizeClasses = (_b = {},
            _b[PipsType.None] = "",
            _b[PipsType.NoValue] = options.cssClasses.markerNormal,
            _b[PipsType.LargeValue] = options.cssClasses.markerLarge,
            _b[PipsType.SmallValue] = options.cssClasses.markerSub,
            _b);
        var valueOrientationClasses = [options.cssClasses.valueHorizontal, options.cssClasses.valueVertical];
        var markerOrientationClasses = [options.cssClasses.markerHorizontal, options.cssClasses.markerVertical];
        addClass(element, options.cssClasses.pips);
        addClass(element, options.ort === 0 ? options.cssClasses.pipsHorizontal : options.cssClasses.pipsVertical);
        function getClasses(type, source) {
            var a = source === options.cssClasses.value;
            var orientationClasses = a ? valueOrientationClasses : markerOrientationClasses;
            var sizeClasses = a ? valueSizeClasses : markerSizeClasses;
            return source + " " + orientationClasses[options.ort] + " " + sizeClasses[type];
        }
        function addSpread(offset, value, type) {
            // Apply the filter function, if it is set.
            type = filterFunc ? filterFunc(value, type) : type;
            if (type === PipsType.None) {
                return;
            }
            // Add a marker for every point
            var node = addNodeTo(element, false);
            node.className = getClasses(type, options.cssClasses.marker);
            node.style[options.style] = offset + "%";
            // Values are only appended for points marked '1' or '2'.
            if (type > PipsType.NoValue) {
                node = addNodeTo(element, false);
                node.className = getClasses(type, options.cssClasses.value);
                node.setAttribute("data-value", String(value));
                node.style[options.style] = offset + "%";
                node.innerHTML = String(formatter.to(value));
            }
        }
        // Append all points.
        Object.keys(spread).forEach(function (offset) {
            addSpread(offset, spread[offset][0], spread[offset][1]);
        });
        return element;
    }
    function removePips() {
        if (scope_Pips) {
            removeElement(scope_Pips);
            scope_Pips = null;
        }
    }
    function pips(pips) {
        // Fix #669
        removePips();
        var spread = generateSpread(pips);
        var filter = pips.filter;
        var format = pips.format || {
            to: function (value) {
                return String(Math.round(value));
            },
        };
        scope_Pips = scope_Target.appendChild(addMarking(spread, filter, format));
        return scope_Pips;
    }
    // Shorthand for base dimensions.
    function baseSize() {
        var rect = scope_Base.getBoundingClientRect();
        var alt = ("offset" + ["Width", "Height"][options.ort]);
        return options.ort === 0 ? rect.width || scope_Base[alt] : rect.height || scope_Base[alt];
    }
    // Handler for attaching events trough a proxy.
    function attachEvent(events, element, callback, data) {
        // This function can be used to 'filter' events to the slider.
        // element is a node, not a nodeList
        var method = function (event) {
            var e = fixEvent(event, data.pageOffset, data.target || element);
            // fixEvent returns false if this event has a different target
            // when handling (multi-) touch events;
            if (!e) {
                return false;
            }
            // doNotReject is passed by all end events to make sure released touches
            // are not rejected, leaving the slider "stuck" to the cursor;
            if (isSliderDisabled() && !data.doNotReject) {
                return false;
            }
            // Stop if an active 'tap' transition is taking place.
            if (hasClass(scope_Target, options.cssClasses.tap) && !data.doNotReject) {
                return false;
            }
            // Ignore right or middle clicks on start #454
            if (events === actions.start && e.buttons !== undefined && e.buttons > 1) {
                return false;
            }
            // Ignore right or middle clicks on start #454
            if (data.hover && e.buttons) {
                return false;
            }
            // 'supportsPassive' is only true if a browser also supports touch-action: none in CSS.
            // iOS safari does not, so it doesn't get to benefit from passive scrolling. iOS does support
            // touch-action: manipulation, but that allows panning, which breaks
            // sliders after zooming/on non-responsive pages.
            // See: https://bugs.webkit.org/show_bug.cgi?id=133112
            if (!supportsPassive) {
                e.preventDefault();
            }
            e.calcPoint = e.points[options.ort];
            // Call the event handler with the event [ and additional data ].
            callback(e, data);
            return;
        };
        var methods = [];
        // Bind a closure on the target for every event type.
        events.split(" ").forEach(function (eventName) {
            element.addEventListener(eventName, method, supportsPassive ? { passive: true } : false);
            methods.push([eventName, method]);
        });
        return methods;
    }
    // Provide a clean event with standardized offset values.
    function fixEvent(e, pageOffset, eventTarget) {
        // Filter the event to register the type, which can be
        // touch, mouse or pointer. Offset changes need to be
        // made on an event specific basis.
        var touch = e.type.indexOf("touch") === 0;
        var mouse = e.type.indexOf("mouse") === 0;
        var pointer = e.type.indexOf("pointer") === 0;
        var x = 0;
        var y = 0;
        // IE10 implemented pointer events with a prefix;
        if (e.type.indexOf("MSPointer") === 0) {
            pointer = true;
        }
        // Erroneous events seem to be passed in occasionally on iOS/iPadOS after user finishes interacting with
        // the slider. They appear to be of type MouseEvent, yet they don't have usual properties set. Ignore
        // events that have no touches or buttons associated with them. (#1057, #1079, #1095)
        if (e.type === "mousedown" && !e.buttons && !e.touches) {
            return false;
        }
        // The only thing one handle should be concerned about is the touches that originated on top of it.
        if (touch) {
            // Returns true if a touch originated on the target.
            var isTouchOnTarget = function (checkTouch) {
                var target = checkTouch.target;
                return (target === eventTarget ||
                    eventTarget.contains(target) ||
                    (e.composed && e.composedPath().shift() === eventTarget));
            };
            // In the case of touchstart events, we need to make sure there is still no more than one
            // touch on the target so we look amongst all touches.
            if (e.type === "touchstart") {
                var targetTouches = Array.prototype.filter.call(e.touches, isTouchOnTarget);
                // Do not support more than one touch per handle.
                if (targetTouches.length > 1) {
                    return false;
                }
                x = targetTouches[0].pageX;
                y = targetTouches[0].pageY;
            }
            else {
                // In the other cases, find on changedTouches is enough.
                var targetTouch = Array.prototype.find.call(e.changedTouches, isTouchOnTarget);
                // Cancel if the target touch has not moved.
                if (!targetTouch) {
                    return false;
                }
                x = targetTouch.pageX;
                y = targetTouch.pageY;
            }
        }
        pageOffset = pageOffset || getPageOffset(scope_Document);
        if (mouse || pointer) {
            x = e.clientX + pageOffset.x;
            y = e.clientY + pageOffset.y;
        }
        e.pageOffset = pageOffset;
        e.points = [x, y];
        e.cursor = mouse || pointer; // Fix #435
        return e;
    }
    // Translate a coordinate in the document to a percentage on the slider
    function calcPointToPercentage(calcPoint) {
        var location = calcPoint - offset(scope_Base, options.ort);
        var proposal = (location * 100) / baseSize();
        // Clamp proposal between 0% and 100%
        // Out-of-bound coordinates may occur when .noUi-base pseudo-elements
        // are used (e.g. contained handles feature)
        proposal = limit(proposal);
        return options.dir ? 100 - proposal : proposal;
    }
    // Find handle closest to a certain percentage on the slider
    function getClosestHandle(clickedPosition) {
        var smallestDifference = 100;
        var handleNumber = false;
        scope_Handles.forEach(function (handle, index) {
            // Disabled handles are ignored
            if (isHandleDisabled(index)) {
                return;
            }
            var handlePosition = scope_Locations[index];
            var differenceWithThisHandle = Math.abs(handlePosition - clickedPosition);
            // Initial state
            var clickAtEdge = differenceWithThisHandle === 100 && smallestDifference === 100;
            // Difference with this handle is smaller than the previously checked handle
            var isCloser = differenceWithThisHandle < smallestDifference;
            var isCloserAfter = differenceWithThisHandle <= smallestDifference && clickedPosition > handlePosition;
            if (isCloser || isCloserAfter || clickAtEdge) {
                handleNumber = index;
                smallestDifference = differenceWithThisHandle;
            }
        });
        return handleNumber;
    }
    // Fire 'end' when a mouse or pen leaves the document.
    function documentLeave(event, data) {
        if (event.type === "mouseout" &&
            event.target.nodeName === "HTML" &&
            event.relatedTarget === null) {
            eventEnd(event, data);
        }
    }
    // Handle movement on document for handle and range drag.
    function eventMove(event, data) {
        // Fix #498
        // Check value of .buttons in 'start' to work around a bug in IE10 mobile (data.buttonsProperty).
        // https://connect.microsoft.com/IE/feedback/details/927005/mobile-ie10-windows-phone-buttons-property-of-pointermove-event-always-zero
        // IE9 has .buttons and .which zero on mousemove.
        // Firefox breaks the spec MDN defines.
        if (navigator.appVersion.indexOf("MSIE 9") === -1 && event.buttons === 0 && data.buttonsProperty !== 0) {
            return eventEnd(event, data);
        }
        // Check if we are moving up or down
        var movement = (options.dir ? -1 : 1) * (event.calcPoint - data.startCalcPoint);
        // Convert the movement into a percentage of the slider width/height
        var proposal = (movement * 100) / data.baseSize;
        moveHandles(movement > 0, proposal, data.locations, data.handleNumbers, data.connect);
    }
    // Unbind move events on document, call callbacks.
    function eventEnd(event, data) {
        // The handle is no longer active, so remove the class.
        if (data.handle) {
            removeClass(data.handle, options.cssClasses.active);
            scope_ActiveHandlesCount -= 1;
        }
        // Unbind the move and end events, which are added on 'start'.
        data.listeners.forEach(function (c) {
            scope_DocumentElement.removeEventListener(c[0], c[1]);
        });
        if (scope_ActiveHandlesCount === 0) {
            // Remove dragging class.
            removeClass(scope_Target, options.cssClasses.drag);
            setZindex();
            // Remove cursor styles and text-selection events bound to the body.
            if (event.cursor) {
                scope_Body.style.cursor = "";
                scope_Body.removeEventListener("selectstart", preventDefault);
            }
        }
        if (options.events.smoothSteps) {
            data.handleNumbers.forEach(function (handleNumber) {
                setHandle(handleNumber, scope_Locations[handleNumber], true, true, false, false);
            });
            data.handleNumbers.forEach(function (handleNumber) {
                fireEvent("update", handleNumber);
            });
        }
        data.handleNumbers.forEach(function (handleNumber) {
            fireEvent("change", handleNumber);
            fireEvent("set", handleNumber);
            fireEvent("end", handleNumber);
        });
    }
    // Bind move events on document.
    function eventStart(event, data) {
        // Ignore event if any handle is disabled
        if (data.handleNumbers.some(isHandleDisabled)) {
            return;
        }
        var handle;
        if (data.handleNumbers.length === 1) {
            var handleOrigin = scope_Handles[data.handleNumbers[0]];
            handle = handleOrigin.children[0];
            scope_ActiveHandlesCount += 1;
            // Mark the handle as 'active' so it can be styled.
            addClass(handle, options.cssClasses.active);
        }
        // A drag should never propagate up to the 'tap' event.
        event.stopPropagation();
        // Record the event listeners.
        var listeners = [];
        // Attach the move and end events.
        var moveEvent = attachEvent(actions.move, scope_DocumentElement, eventMove, {
            // The event target has changed so we need to propagate the original one so that we keep
            // relying on it to extract target touches.
            target: event.target,
            handle: handle,
            connect: data.connect,
            listeners: listeners,
            startCalcPoint: event.calcPoint,
            baseSize: baseSize(),
            pageOffset: event.pageOffset,
            handleNumbers: data.handleNumbers,
            buttonsProperty: event.buttons,
            locations: scope_Locations.slice(),
        });
        var endEvent = attachEvent(actions.end, scope_DocumentElement, eventEnd, {
            target: event.target,
            handle: handle,
            listeners: listeners,
            doNotReject: true,
            handleNumbers: data.handleNumbers,
        });
        var outEvent = attachEvent("mouseout", scope_DocumentElement, documentLeave, {
            target: event.target,
            handle: handle,
            listeners: listeners,
            doNotReject: true,
            handleNumbers: data.handleNumbers,
        });
        // We want to make sure we pushed the listeners in the listener list rather than creating
        // a new one as it has already been passed to the event handlers.
        listeners.push.apply(listeners, moveEvent.concat(endEvent, outEvent));
        // Text selection isn't an issue on touch devices,
        // so adding cursor styles can be skipped.
        if (event.cursor) {
            // Prevent the 'I' cursor and extend the range-drag cursor.
            scope_Body.style.cursor = getComputedStyle(event.target).cursor;
            // Mark the target with a dragging state.
            if (scope_Handles.length > 1) {
                addClass(scope_Target, options.cssClasses.drag);
            }
            // Prevent text selection when dragging the handles.
            // In noUiSlider <= 9.2.0, this was handled by calling preventDefault on mouse/touch start/move,
            // which is scroll blocking. The selectstart event is supported by FireFox starting from version 52,
            // meaning the only holdout is iOS Safari. This doesn't matter: text selection isn't triggered there.
            // The 'cursor' flag is false.
            // See: http://caniuse.com/#search=selectstart
            scope_Body.addEventListener("selectstart", preventDefault, false);
        }
        data.handleNumbers.forEach(function (handleNumber) {
            fireEvent("start", handleNumber);
        });
    }
    // Move closest handle to tapped location.
    function eventTap(event) {
        // The tap event shouldn't propagate up
        event.stopPropagation();
        var proposal = calcPointToPercentage(event.calcPoint);
        var handleNumber = getClosestHandle(proposal);
        // Tackle the case that all handles are 'disabled'.
        if (handleNumber === false) {
            return;
        }
        // Flag the slider as it is now in a transitional state.
        // Transition takes a configurable amount of ms (default 300). Re-enable the slider after that.
        if (!options.events.snap) {
            addClassFor(scope_Target, options.cssClasses.tap, options.animationDuration);
        }
        setHandle(handleNumber, proposal, true, true);
        setZindex();
        fireEvent("slide", handleNumber, true);
        fireEvent("update", handleNumber, true);
        if (!options.events.snap) {
            fireEvent("change", handleNumber, true);
            fireEvent("set", handleNumber, true);
        }
        else {
            eventStart(event, { handleNumbers: [handleNumber] });
        }
    }
    // Fires a 'hover' event for a hovered mouse/pen position.
    function eventHover(event) {
        var proposal = calcPointToPercentage(event.calcPoint);
        var to = scope_Spectrum.getStep(proposal);
        var value = scope_Spectrum.fromStepping(to);
        Object.keys(scope_Events).forEach(function (targetEvent) {
            if ("hover" === targetEvent.split(".")[0]) {
                scope_Events[targetEvent].forEach(function (callback) {
                    callback.call(scope_Self, value);
                });
            }
        });
    }
    // Handles keydown on focused handles
    // Don't move the document when pressing arrow keys on focused handles
    function eventKeydown(event, handleNumber) {
        if (isSliderDisabled() || isHandleDisabled(handleNumber)) {
            return false;
        }
        var horizontalKeys = ["Left", "Right"];
        var verticalKeys = ["Down", "Up"];
        var largeStepKeys = ["PageDown", "PageUp"];
        var edgeKeys = ["Home", "End"];
        if (options.dir && !options.ort) {
            // On an right-to-left slider, the left and right keys act inverted
            horizontalKeys.reverse();
        }
        else if (options.ort && !options.dir) {
            // On a top-to-bottom slider, the up and down keys act inverted
            verticalKeys.reverse();
            largeStepKeys.reverse();
        }
        // Strip "Arrow" for IE compatibility. https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/key
        var key = event.key.replace("Arrow", "");
        var isLargeDown = key === largeStepKeys[0];
        var isLargeUp = key === largeStepKeys[1];
        var isDown = key === verticalKeys[0] || key === horizontalKeys[0] || isLargeDown;
        var isUp = key === verticalKeys[1] || key === horizontalKeys[1] || isLargeUp;
        var isMin = key === edgeKeys[0];
        var isMax = key === edgeKeys[1];
        if (!isDown && !isUp && !isMin && !isMax) {
            return true;
        }
        event.preventDefault();
        var to;
        if (isUp || isDown) {
            var direction = isDown ? 0 : 1;
            var steps = getNextStepsForHandle(handleNumber);
            var step = steps[direction];
            // At the edge of a slider, do nothing
            if (step === null) {
                return false;
            }
            // No step set, use the default of 10% of the sub-range
            if (step === false) {
                step = scope_Spectrum.getDefaultStep(scope_Locations[handleNumber], isDown, options.keyboardDefaultStep);
            }
            if (isLargeUp || isLargeDown) {
                step *= options.keyboardPageMultiplier;
            }
            else {
                step *= options.keyboardMultiplier;
            }
            // Step over zero-length ranges (#948);
            step = Math.max(step, 0.0000001);
            // Decrement for down steps
            step = (isDown ? -1 : 1) * step;
            to = scope_Values[handleNumber] + step;
        }
        else if (isMax) {
            // End key
            to = options.spectrum.xVal[options.spectrum.xVal.length - 1];
        }
        else {
            // Home key
            to = options.spectrum.xVal[0];
        }
        setHandle(handleNumber, scope_Spectrum.toStepping(to), true, true);
        fireEvent("slide", handleNumber);
        fireEvent("update", handleNumber);
        fireEvent("change", handleNumber);
        fireEvent("set", handleNumber);
        return false;
    }
    // Attach events to several slider parts.
    function bindSliderEvents(behaviour) {
        // Attach the standard drag event to the handles.
        if (!behaviour.fixed) {
            scope_Handles.forEach(function (handle, index) {
                // These events are only bound to the visual handle
                // element, not the 'real' origin element.
                attachEvent(actions.start, handle.children[0], eventStart, {
                    handleNumbers: [index],
                });
            });
        }
        // Attach the tap event to the slider base.
        if (behaviour.tap) {
            attachEvent(actions.start, scope_Base, eventTap, {});
        }
        // Fire hover events
        if (behaviour.hover) {
            attachEvent(actions.move, scope_Base, eventHover, {
                hover: true,
            });
        }
        // Make the range draggable.
        if (behaviour.drag) {
            scope_Connects.forEach(function (connect, index) {
                if (connect === false || index === 0 || index === scope_Connects.length - 1) {
                    return;
                }
                var handleBefore = scope_Handles[index - 1];
                var handleAfter = scope_Handles[index];
                var eventHolders = [connect];
                var handlesToDrag = [handleBefore, handleAfter];
                var handleNumbersToDrag = [index - 1, index];
                addClass(connect, options.cssClasses.draggable);
                // When the range is fixed, the entire range can
                // be dragged by the handles. The handle in the first
                // origin will propagate the start event upward,
                // but it needs to be bound manually on the other.
                if (behaviour.fixed) {
                    eventHolders.push(handleBefore.children[0]);
                    eventHolders.push(handleAfter.children[0]);
                }
                if (behaviour.dragAll) {
                    handlesToDrag = scope_Handles;
                    handleNumbersToDrag = scope_HandleNumbers;
                }
                eventHolders.forEach(function (eventHolder) {
                    attachEvent(actions.start, eventHolder, eventStart, {
                        handles: handlesToDrag,
                        handleNumbers: handleNumbersToDrag,
                        connect: connect,
                    });
                });
            });
        }
    }
    // Attach an event to this slider, possibly including a namespace
    function bindEvent(namespacedEvent, callback) {
        scope_Events[namespacedEvent] = scope_Events[namespacedEvent] || [];
        scope_Events[namespacedEvent].push(callback);
        // If the event bound is 'update,' fire it immediately for all handles.
        if (namespacedEvent.split(".")[0] === "update") {
            scope_Handles.forEach(function (a, index) {
                fireEvent("update", index);
            });
        }
    }
    function isInternalNamespace(namespace) {
        return namespace === INTERNAL_EVENT_NS.aria || namespace === INTERNAL_EVENT_NS.tooltips;
    }
    // Undo attachment of event
    function removeEvent(namespacedEvent) {
        var event = namespacedEvent && namespacedEvent.split(".")[0];
        var namespace = event ? namespacedEvent.substring(event.length) : namespacedEvent;
        Object.keys(scope_Events).forEach(function (bind) {
            var tEvent = bind.split(".")[0];
            var tNamespace = bind.substring(tEvent.length);
            if ((!event || event === tEvent) && (!namespace || namespace === tNamespace)) {
                // only delete protected internal event if intentional
                if (!isInternalNamespace(tNamespace) || namespace === tNamespace) {
                    delete scope_Events[bind];
                }
            }
        });
    }
    // External event handling
    function fireEvent(eventName, handleNumber, tap) {
        Object.keys(scope_Events).forEach(function (targetEvent) {
            var eventType = targetEvent.split(".")[0];
            if (eventName === eventType) {
                scope_Events[targetEvent].forEach(function (callback) {
                    callback.call(
                    // Use the slider public API as the scope ('this')
                    scope_Self, 
                    // Return values as array, so arg_1[arg_2] is always valid.
                    scope_Values.map(options.format.to), 
                    // Handle index, 0 or 1
                    handleNumber, 
                    // Un-formatted slider values
                    scope_Values.slice(), 
                    // Event is fired by tap, true or false
                    tap || false, 
                    // Left offset of the handle, in relation to the slider
                    scope_Locations.slice(), 
                    // add the slider public API to an accessible parameter when this is unavailable
                    scope_Self);
                });
            }
        });
    }
    // Split out the handle positioning logic so the Move event can use it, too
    function checkHandlePosition(reference, handleNumber, to, lookBackward, lookForward, getValue, smoothSteps) {
        var distance;
        // For sliders with multiple handles, limit movement to the other handle.
        // Apply the margin option by adding it to the handle positions.
        if (scope_Handles.length > 1 && !options.events.unconstrained) {
            if (lookBackward && handleNumber > 0) {
                distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber - 1], options.margin, false);
                to = Math.max(to, distance);
            }
            if (lookForward && handleNumber < scope_Handles.length - 1) {
                distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber + 1], options.margin, true);
                to = Math.min(to, distance);
            }
        }
        // The limit option has the opposite effect, limiting handles to a
        // maximum distance from another. Limit must be > 0, as otherwise
        // handles would be unmovable.
        if (scope_Handles.length > 1 && options.limit) {
            if (lookBackward && handleNumber > 0) {
                distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber - 1], options.limit, false);
                to = Math.min(to, distance);
            }
            if (lookForward && handleNumber < scope_Handles.length - 1) {
                distance = scope_Spectrum.getAbsoluteDistance(reference[handleNumber + 1], options.limit, true);
                to = Math.max(to, distance);
            }
        }
        // The padding option keeps the handles a certain distance from the
        // edges of the slider. Padding must be > 0.
        if (options.padding) {
            if (handleNumber === 0) {
                distance = scope_Spectrum.getAbsoluteDistance(0, options.padding[0], false);
                to = Math.max(to, distance);
            }
            if (handleNumber === scope_Handles.length - 1) {
                distance = scope_Spectrum.getAbsoluteDistance(100, options.padding[1], true);
                to = Math.min(to, distance);
            }
        }
        if (!smoothSteps) {
            to = scope_Spectrum.getStep(to);
        }
        // Limit percentage to the 0 - 100 range
        to = limit(to);
        // Return false if handle can't move
        if (to === reference[handleNumber] && !getValue) {
            return false;
        }
        return to;
    }
    // Uses slider orientation to create CSS rules. a = base value;
    function inRuleOrder(v, a) {
        var o = options.ort;
        return (o ? a : v) + ", " + (o ? v : a);
    }
    // Moves handle(s) by a percentage
    // (bool, % to move, [% where handle started, ...], [index in scope_Handles, ...])
    function moveHandles(upward, proposal, locations, handleNumbers, connect) {
        var proposals = locations.slice();
        // Store first handle now, so we still have it in case handleNumbers is reversed
        var firstHandle = handleNumbers[0];
        var smoothSteps = options.events.smoothSteps;
        var b = [!upward, upward];
        var f = [upward, !upward];
        // Copy handleNumbers so we don't change the dataset
        handleNumbers = handleNumbers.slice();
        // Check to see which handle is 'leading'.
        // If that one can't move the second can't either.
        if (upward) {
            handleNumbers.reverse();
        }
        // Step 1: get the maximum percentage that any of the handles can move
        if (handleNumbers.length > 1) {
            handleNumbers.forEach(function (handleNumber, o) {
                var to = checkHandlePosition(proposals, handleNumber, proposals[handleNumber] + proposal, b[o], f[o], false, smoothSteps);
                // Stop if one of the handles can't move.
                if (to === false) {
                    proposal = 0;
                }
                else {
                    proposal = to - proposals[handleNumber];
                    proposals[handleNumber] = to;
                }
            });
        }
        // If using one handle, check backward AND forward
        else {
            b = f = [true];
        }
        var state = false;
        // Step 2: Try to set the handles with the found percentage
        handleNumbers.forEach(function (handleNumber, o) {
            state =
                setHandle(handleNumber, locations[handleNumber] + proposal, b[o], f[o], false, smoothSteps) || state;
        });
        // Step 3: If a handle moved, fire events
        if (state) {
            handleNumbers.forEach(function (handleNumber) {
                fireEvent("update", handleNumber);
                fireEvent("slide", handleNumber);
            });
            // If target is a connect, then fire drag event
            if (connect != undefined) {
                fireEvent("drag", firstHandle);
            }
        }
    }
    // Takes a base value and an offset. This offset is used for the connect bar size.
    // In the initial design for this feature, the origin element was 1% wide.
    // Unfortunately, a rounding bug in Chrome makes it impossible to implement this feature
    // in this manner: https://bugs.chromium.org/p/chromium/issues/detail?id=798223
    function transformDirection(a, b) {
        return options.dir ? 100 - a - b : a;
    }
    // Updates scope_Locations and scope_Values, updates visual state
    function updateHandlePosition(handleNumber, to) {
        // Update locations.
        scope_Locations[handleNumber] = to;
        // Convert the value to the slider stepping/range.
        scope_Values[handleNumber] = scope_Spectrum.fromStepping(to);
        var translation = transformDirection(to, 0) - scope_DirOffset;
        var translateRule = "translate(" + inRuleOrder(translation + "%", "0") + ")";
        scope_Handles[handleNumber].style[options.transformRule] = translateRule;
        // sanity check for at least 2 handles (e.g. during setup)
        if (options.events.invertConnects && scope_Locations.length > 1) {
            // check if handles passed each other, but don't match the ConnectsInverted state
            var handlesAreInOrder = scope_Locations.every(function (position, index, locations) {
                return index === 0 || position >= locations[index - 1];
            });
            if (scope_ConnectsInverted !== !handlesAreInOrder) {
                // invert connects when handles pass each other
                invertConnects();
                // invertConnects already updates all connect elements
                return;
            }
        }
        updateConnect(handleNumber);
        updateConnect(handleNumber + 1);
        if (scope_ConnectsInverted) {
            // When connects are inverted, we also have to update adjacent connects
            updateConnect(handleNumber - 1);
            updateConnect(handleNumber + 2);
        }
    }
    // Handles before the slider middle are stacked later = higher,
    // Handles after the middle later is lower
    // [[7] [8] .......... | .......... [5] [4]
    function setZindex() {
        scope_HandleNumbers.forEach(function (handleNumber) {
            var dir = scope_Locations[handleNumber] > 50 ? -1 : 1;
            var zIndex = 3 + (scope_Handles.length + dir * handleNumber);
            scope_Handles[handleNumber].style.zIndex = String(zIndex);
        });
    }
    // Test suggested values and apply margin, step.
    // if exactInput is true, don't run checkHandlePosition, then the handle can be placed in between steps (#436)
    function setHandle(handleNumber, to, lookBackward, lookForward, exactInput, smoothSteps) {
        if (!exactInput) {
            to = checkHandlePosition(scope_Locations, handleNumber, to, lookBackward, lookForward, false, smoothSteps);
        }
        if (to === false) {
            return false;
        }
        updateHandlePosition(handleNumber, to);
        return true;
    }
    // Updates style attribute for connect nodes
    function updateConnect(index) {
        // Skip connects set to false
        if (!scope_Connects[index]) {
            return;
        }
        // Create a copy of locations, so we can sort them for the local scope logic
        var locations = scope_Locations.slice();
        if (scope_ConnectsInverted) {
            locations.sort(function (a, b) {
                return a - b;
            });
        }
        var l = 0;
        var h = 100;
        if (index !== 0) {
            l = locations[index - 1];
        }
        if (index !== scope_Connects.length - 1) {
            h = locations[index];
        }
        // We use two rules:
        // 'translate' to change the left/top offset;
        // 'scale' to change the width of the element;
        // As the element has a width of 100%, a translation of 100% is equal to 100% of the parent (.noUi-base)
        var connectWidth = h - l;
        var translateRule = "translate(" + inRuleOrder(transformDirection(l, connectWidth) + "%", "0") + ")";
        var scaleRule = "scale(" + inRuleOrder(connectWidth / 100, "1") + ")";
        scope_Connects[index].style[options.transformRule] =
            translateRule + " " + scaleRule;
    }
    // Parses value passed to .set method. Returns current value if not parse-able.
    function resolveToValue(to, handleNumber) {
        // Setting with null indicates an 'ignore'.
        // Inputting 'false' is invalid.
        if (to === null || to === false || to === undefined) {
            return scope_Locations[handleNumber];
        }
        // If a formatted number was passed, attempt to decode it.
        if (typeof to === "number") {
            to = String(to);
        }
        to = options.format.from(to);
        if (to !== false) {
            to = scope_Spectrum.toStepping(to);
        }
        // If parsing the number failed, use the current value.
        if (to === false || isNaN(to)) {
            return scope_Locations[handleNumber];
        }
        return to;
    }
    // Set the slider value.
    function valueSet(input, fireSetEvent, exactInput) {
        var values = asArray(input);
        var isInit = scope_Locations[0] === undefined;
        // Event fires by default
        fireSetEvent = fireSetEvent === undefined ? true : fireSetEvent;
        // Animation is optional.
        // Make sure the initial values were set before using animated placement.
        if (options.animate && !isInit) {
            addClassFor(scope_Target, options.cssClasses.tap, options.animationDuration);
        }
        // First pass, without lookAhead but with lookBackward. Values are set from left to right.
        scope_HandleNumbers.forEach(function (handleNumber) {
            setHandle(handleNumber, resolveToValue(values[handleNumber], handleNumber), true, false, exactInput);
        });
        var i = scope_HandleNumbers.length === 1 ? 0 : 1;
        // Spread handles evenly across the slider if the range has no size (min=max)
        if (isInit && scope_Spectrum.hasNoSize()) {
            exactInput = true;
            scope_Locations[0] = 0;
            if (scope_HandleNumbers.length > 1) {
                var space_1 = 100 / (scope_HandleNumbers.length - 1);
                scope_HandleNumbers.forEach(function (handleNumber) {
                    scope_Locations[handleNumber] = handleNumber * space_1;
                });
            }
        }
        // Secondary passes. Now that all base values are set, apply constraints.
        // Iterate all handles to ensure constraints are applied for the entire slider (Issue #1009)
        for (; i < scope_HandleNumbers.length; ++i) {
            scope_HandleNumbers.forEach(function (handleNumber) {
                setHandle(handleNumber, scope_Locations[handleNumber], true, true, exactInput);
            });
        }
        setZindex();
        scope_HandleNumbers.forEach(function (handleNumber) {
            fireEvent("update", handleNumber);
            // Fire the event only for handles that received a new value, as per #579
            if (values[handleNumber] !== null && fireSetEvent) {
                fireEvent("set", handleNumber);
            }
        });
    }
    // Reset slider to initial values
    function valueReset(fireSetEvent) {
        valueSet(options.start, fireSetEvent);
    }
    // Set value for a single handle
    function valueSetHandle(handleNumber, value, fireSetEvent, exactInput) {
        // Ensure numeric input
        handleNumber = Number(handleNumber);
        if (!(handleNumber >= 0 && handleNumber < scope_HandleNumbers.length)) {
            throw new Error("noUiSlider: invalid handle number, got: " + handleNumber);
        }
        // Look both backward and forward, since we don't want this handle to "push" other handles (#960);
        // The exactInput argument can be used to ignore slider stepping (#436)
        setHandle(handleNumber, resolveToValue(value, handleNumber), true, true, exactInput);
        fireEvent("update", handleNumber);
        if (fireSetEvent) {
            fireEvent("set", handleNumber);
        }
    }
    // Get the slider value.
    function valueGet(unencoded) {
        if (unencoded === void 0) { unencoded = false; }
        if (unencoded) {
            // return a copy of the raw values
            return scope_Values.length === 1 ? scope_Values[0] : scope_Values.slice(0);
        }
        var values = scope_Values.map(options.format.to);
        // If only one handle is used, return a single value.
        if (values.length === 1) {
            return values[0];
        }
        return values;
    }
    // Removes classes from the root and empties it.
    function destroy() {
        // remove protected internal listeners
        removeEvent(INTERNAL_EVENT_NS.aria);
        removeEvent(INTERNAL_EVENT_NS.tooltips);
        Object.keys(options.cssClasses).forEach(function (key) {
            removeClass(scope_Target, options.cssClasses[key]);
        });
        while (scope_Target.firstChild) {
            scope_Target.removeChild(scope_Target.firstChild);
        }
        delete scope_Target.noUiSlider;
    }
    function getNextStepsForHandle(handleNumber) {
        var location = scope_Locations[handleNumber];
        var nearbySteps = scope_Spectrum.getNearbySteps(location);
        var value = scope_Values[handleNumber];
        var increment = nearbySteps.thisStep.step;
        var decrement = null;
        // If snapped, directly use defined step value
        if (options.snap) {
            return [
                value - nearbySteps.stepBefore.startValue || null,
                nearbySteps.stepAfter.startValue - value || null,
            ];
        }
        // If the next value in this step moves into the next step,
        // the increment is the start of the next step - the current value
        if (increment !== false) {
            if (value + increment > nearbySteps.stepAfter.startValue) {
                increment = nearbySteps.stepAfter.startValue - value;
            }
        }
        // If the value is beyond the starting point
        if (value > nearbySteps.thisStep.startValue) {
            decrement = nearbySteps.thisStep.step;
        }
        else if (nearbySteps.stepBefore.step === false) {
            decrement = false;
        }
        // If a handle is at the start of a step, it always steps back into the previous step first
        else {
            decrement = value - nearbySteps.stepBefore.highestStep;
        }
        // Now, if at the slider edges, there is no in/decrement
        if (location === 100) {
            increment = null;
        }
        else if (location === 0) {
            decrement = null;
        }
        // As per #391, the comparison for the decrement step can have some rounding issues.
        var stepDecimals = scope_Spectrum.countStepDecimals();
        // Round per #391
        if (increment !== null && increment !== false) {
            increment = Number(increment.toFixed(stepDecimals));
        }
        if (decrement !== null && decrement !== false) {
            decrement = Number(decrement.toFixed(stepDecimals));
        }
        return [decrement, increment];
    }
    // Get the current step size for the slider.
    function getNextSteps() {
        return scope_HandleNumbers.map(getNextStepsForHandle);
    }
    // Updatable: margin, limit, padding, step, range, animate, snap
    function updateOptions(optionsToUpdate, fireSetEvent) {
        // Spectrum is created using the range, snap, direction and step options.
        // 'snap' and 'step' can be updated.
        // If 'snap' and 'step' are not passed, they should remain unchanged.
        var v = valueGet();
        var updateAble = [
            "margin",
            "limit",
            "padding",
            "range",
            "animate",
            "snap",
            "step",
            "format",
            "pips",
            "tooltips",
            "connect",
        ];
        // Only change options that we're actually passed to update.
        updateAble.forEach(function (name) {
            // Check for undefined. null removes the value.
            if (optionsToUpdate[name] !== undefined) {
                originalOptions[name] = optionsToUpdate[name];
            }
        });
        var newOptions = testOptions(originalOptions);
        // Load new options into the slider state
        updateAble.forEach(function (name) {
            if (optionsToUpdate[name] !== undefined) {
                options[name] = newOptions[name];
            }
        });
        scope_Spectrum = newOptions.spectrum;
        // Limit, margin and padding depend on the spectrum but are stored outside of it. (#677)
        options.margin = newOptions.margin;
        options.limit = newOptions.limit;
        options.padding = newOptions.padding;
        // Update pips, removes existing.
        if (options.pips) {
            pips(options.pips);
        }
        else {
            removePips();
        }
        // Update tooltips, removes existing.
        if (options.tooltips) {
            tooltips();
        }
        else {
            removeTooltips();
        }
        // Invalidate the current positioning so valueSet forces an update.
        scope_Locations = [];
        valueSet(isSet(optionsToUpdate.start) ? optionsToUpdate.start : v, fireSetEvent);
        // Update connects only if it was set
        if (optionsToUpdate.connect) {
            updateConnectOption();
        }
    }
    function updateConnectOption() {
        // IE supported way of removing children including event handlers
        while (scope_ConnectBase.firstChild) {
            scope_ConnectBase.removeChild(scope_ConnectBase.firstChild);
        }
        // Adding new connects according to the new connect options
        for (var i = 0; i <= options.handles; i++) {
            scope_Connects[i] = addConnect(scope_ConnectBase, options.connect[i]);
            updateConnect(i);
        }
        // re-adding drag events for the new connect elements
        // to ignore the other events we have to negate the 'if (!behaviour.fixed)' check
        bindSliderEvents({ drag: options.events.drag, fixed: true });
    }
    // Invert options for connect handles
    function invertConnects() {
        scope_ConnectsInverted = !scope_ConnectsInverted;
        testConnect(options, 
        // inverse the connect boolean array
        options.connect.map(function (b) { return !b; }));
        updateConnectOption();
    }
    // Initialization steps
    function setupSlider() {
        // Create the base element, initialize HTML and set classes.
        // Add handles and connect elements.
        scope_Base = addSlider(scope_Target);
        addElements(options.connect, scope_Base);
        // Attach user events.
        bindSliderEvents(options.events);
        // Use the public value method to set the start values.
        valueSet(options.start);
        if (options.pips) {
            pips(options.pips);
        }
        if (options.tooltips) {
            tooltips();
        }
        aria();
    }
    setupSlider();
    var scope_Self = {
        destroy: destroy,
        steps: getNextSteps,
        on: bindEvent,
        off: removeEvent,
        get: valueGet,
        set: valueSet,
        setHandle: valueSetHandle,
        reset: valueReset,
        disable: disable,
        enable: enable,
        // Exposed for unit testing, don't use this in your application.
        __moveHandles: function (upward, proposal, handleNumbers) {
            moveHandles(upward, proposal, scope_Locations, handleNumbers);
        },
        options: originalOptions,
        updateOptions: updateOptions,
        target: scope_Target,
        removePips: removePips,
        removeTooltips: removeTooltips,
        getPositions: function () {
            return scope_Locations.slice();
        },
        getTooltips: function () {
            return scope_Tooltips;
        },
        getOrigins: function () {
            return scope_Handles;
        },
        pips: pips, // Issue #594
    };
    return scope_Self;
}
// Run the standard initializer
function initialize(target, originalOptions) {
    if (!target || !target.nodeName) {
        throw new Error("noUiSlider: create requires a single element, got: " + target);
    }
    // Throw an error if the slider was already initialized.
    if (target.noUiSlider) {
        throw new Error("noUiSlider: Slider was already initialized.");
    }
    // Test the options and create the slider environment;
    var options = testOptions(originalOptions);
    var api = scope(target, options, originalOptions);
    target.noUiSlider = api;
    return api;
}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
    // Exposed for unit testing, don't use this in your application.
    __spectrum: Spectrum,
    // A reference to the default classes, allows global changes.
    // Use the cssClasses option for changes to one slider.
    cssClasses: cssClasses,
    create: initialize,
});


/***/ }),

/***/ "./node_modules/tom-select/dist/js/tom-select.complete.js":
/*!****************************************************************!*\
  !*** ./node_modules/tom-select/dist/js/tom-select.complete.js ***!
  \****************************************************************/
/***/ (function(module) {

/**
* Tom Select v2.3.1
* Licensed under the Apache License, Version 2.0 (the "License");
*/

(function (global, factory) {
	 true ? module.exports = factory() :
	0;
})(this, (function () { 'use strict';

	/**
	 * MicroEvent - to make any js object an event emitter
	 *
	 * - pure javascript - server compatible, browser compatible
	 * - dont rely on the browser doms
	 * - super simple - you get it immediatly, no mistery, no magic involved
	 *
	 * @author Jerome Etienne (https://github.com/jeromeetienne)
	 */

	/**
	 * Execute callback for each event in space separated list of event names
	 *
	 */
	function forEvents(events, callback) {
	  events.split(/\s+/).forEach(event => {
	    callback(event);
	  });
	}
	class MicroEvent {
	  constructor() {
	    this._events = void 0;
	    this._events = {};
	  }
	  on(events, fct) {
	    forEvents(events, event => {
	      const event_array = this._events[event] || [];
	      event_array.push(fct);
	      this._events[event] = event_array;
	    });
	  }
	  off(events, fct) {
	    var n = arguments.length;
	    if (n === 0) {
	      this._events = {};
	      return;
	    }
	    forEvents(events, event => {
	      if (n === 1) {
	        delete this._events[event];
	        return;
	      }
	      const event_array = this._events[event];
	      if (event_array === undefined) return;
	      event_array.splice(event_array.indexOf(fct), 1);
	      this._events[event] = event_array;
	    });
	  }
	  trigger(events, ...args) {
	    var self = this;
	    forEvents(events, event => {
	      const event_array = self._events[event];
	      if (event_array === undefined) return;
	      event_array.forEach(fct => {
	        fct.apply(self, args);
	      });
	    });
	  }
	}

	/**
	 * microplugin.js
	 * Copyright (c) 2013 Brian Reavis & contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 * @author Brian Reavis <brian@thirdroute.com>
	 */

	function MicroPlugin(Interface) {
	  Interface.plugins = {};
	  return class extends Interface {
	    constructor(...args) {
	      super(...args);
	      this.plugins = {
	        names: [],
	        settings: {},
	        requested: {},
	        loaded: {}
	      };
	    }
	    /**
	     * Registers a plugin.
	     *
	     * @param {function} fn
	     */
	    static define(name, fn) {
	      Interface.plugins[name] = {
	        'name': name,
	        'fn': fn
	      };
	    }

	    /**
	     * Initializes the listed plugins (with options).
	     * Acceptable formats:
	     *
	     * List (without options):
	     *   ['a', 'b', 'c']
	     *
	     * List (with options):
	     *   [{'name': 'a', options: {}}, {'name': 'b', options: {}}]
	     *
	     * Hash (with options):
	     *   {'a': { ... }, 'b': { ... }, 'c': { ... }}
	     *
	     * @param {array|object} plugins
	     */
	    initializePlugins(plugins) {
	      var key, name;
	      const self = this;
	      const queue = [];
	      if (Array.isArray(plugins)) {
	        plugins.forEach(plugin => {
	          if (typeof plugin === 'string') {
	            queue.push(plugin);
	          } else {
	            self.plugins.settings[plugin.name] = plugin.options;
	            queue.push(plugin.name);
	          }
	        });
	      } else if (plugins) {
	        for (key in plugins) {
	          if (plugins.hasOwnProperty(key)) {
	            self.plugins.settings[key] = plugins[key];
	            queue.push(key);
	          }
	        }
	      }
	      while (name = queue.shift()) {
	        self.require(name);
	      }
	    }
	    loadPlugin(name) {
	      var self = this;
	      var plugins = self.plugins;
	      var plugin = Interface.plugins[name];
	      if (!Interface.plugins.hasOwnProperty(name)) {
	        throw new Error('Unable to find "' + name + '" plugin');
	      }
	      plugins.requested[name] = true;
	      plugins.loaded[name] = plugin.fn.apply(self, [self.plugins.settings[name] || {}]);
	      plugins.names.push(name);
	    }

	    /**
	     * Initializes a plugin.
	     *
	     */
	    require(name) {
	      var self = this;
	      var plugins = self.plugins;
	      if (!self.plugins.loaded.hasOwnProperty(name)) {
	        if (plugins.requested[name]) {
	          throw new Error('Plugin has circular dependency ("' + name + '")');
	        }
	        self.loadPlugin(name);
	      }
	      return plugins.loaded[name];
	    }
	  };
	}

	/*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */
	/**
	 * Convert array of strings to a regular expression
	 *	ex ['ab','a'] => (?:ab|a)
	 * 	ex ['a','b'] => [ab]
	 * @param {string[]} chars
	 * @return {string}
	 */
	const arrayToPattern = chars => {
	  chars = chars.filter(Boolean);

	  if (chars.length < 2) {
	    return chars[0] || '';
	  }

	  return maxValueLength(chars) == 1 ? '[' + chars.join('') + ']' : '(?:' + chars.join('|') + ')';
	};
	/**
	 * @param {string[]} array
	 * @return {string}
	 */

	const sequencePattern = array => {
	  if (!hasDuplicates(array)) {
	    return array.join('');
	  }

	  let pattern = '';
	  let prev_char_count = 0;

	  const prev_pattern = () => {
	    if (prev_char_count > 1) {
	      pattern += '{' + prev_char_count + '}';
	    }
	  };

	  array.forEach((char, i) => {
	    if (char === array[i - 1]) {
	      prev_char_count++;
	      return;
	    }

	    prev_pattern();
	    pattern += char;
	    prev_char_count = 1;
	  });
	  prev_pattern();
	  return pattern;
	};
	/**
	 * Convert array of strings to a regular expression
	 *	ex ['ab','a'] => (?:ab|a)
	 * 	ex ['a','b'] => [ab]
	 * @param {Set<string>} chars
	 * @return {string}
	 */

	const setToPattern = chars => {
	  let array = toArray(chars);
	  return arrayToPattern(array);
	};
	/**
	 *
	 * https://stackoverflow.com/questions/7376598/in-javascript-how-do-i-check-if-an-array-has-duplicate-values
	 * @param {any[]} array
	 */

	const hasDuplicates = array => {
	  return new Set(array).size !== array.length;
	};
	/**
	 * https://stackoverflow.com/questions/63006601/why-does-u-throw-an-invalid-escape-error
	 * @param {string} str
	 * @return {string}
	 */

	const escape_regex = str => {
	  return (str + '').replace(/([\$\(\)\*\+\.\?\[\]\^\{\|\}\\])/gu, '\\$1');
	};
	/**
	 * Return the max length of array values
	 * @param {string[]} array
	 *
	 */

	const maxValueLength = array => {
	  return array.reduce((longest, value) => Math.max(longest, unicodeLength(value)), 0);
	};
	/**
	 * @param {string} str
	 */

	const unicodeLength = str => {
	  return toArray(str).length;
	};
	/**
	 * @param {any} p
	 * @return {any[]}
	 */

	const toArray = p => Array.from(p);

	/*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */
	/**
	 * Get all possible combinations of substrings that add up to the given string
	 * https://stackoverflow.com/questions/30169587/find-all-the-combination-of-substrings-that-add-up-to-the-given-string
	 * @param {string} input
	 * @return {string[][]}
	 */
	const allSubstrings = input => {
	  if (input.length === 1) return [[input]];
	  /** @type {string[][]} */

	  let result = [];
	  const start = input.substring(1);
	  const suba = allSubstrings(start);
	  suba.forEach(function (subresult) {
	    let tmp = subresult.slice(0);
	    tmp[0] = input.charAt(0) + tmp[0];
	    result.push(tmp);
	    tmp = subresult.slice(0);
	    tmp.unshift(input.charAt(0));
	    result.push(tmp);
	  });
	  return result;
	};

	/*! @orchidjs/unicode-variants | https://github.com/orchidjs/unicode-variants | Apache License (v2) */

	/**
	 * @typedef {{[key:string]:string}} TUnicodeMap
	 * @typedef {{[key:string]:Set<string>}} TUnicodeSets
	 * @typedef {[[number,number]]} TCodePoints
	 * @typedef {{folded:string,composed:string,code_point:number}} TCodePointObj
	 * @typedef {{start:number,end:number,length:number,substr:string}} TSequencePart
	 */
	/** @type {TCodePoints} */

	const code_points = [[0, 65535]];
	const accent_pat = '[\u0300-\u036F\u{b7}\u{2be}\u{2bc}]';
	/** @type {TUnicodeMap} */

	let unicode_map;
	/** @type {RegExp} */

	let multi_char_reg;
	const max_char_length = 3;
	/** @type {TUnicodeMap} */

	const latin_convert = {};
	/** @type {TUnicodeMap} */

	const latin_condensed = {
	  '/': '⁄∕',
	  '0': '߀',
	  "a": "ⱥɐɑ",
	  "aa": "ꜳ",
	  "ae": "æǽǣ",
	  "ao": "ꜵ",
	  "au": "ꜷ",
	  "av": "ꜹꜻ",
	  "ay": "ꜽ",
	  "b": "ƀɓƃ",
	  "c": "ꜿƈȼↄ",
	  "d": "đɗɖᴅƌꮷԁɦ",
	  "e": "ɛǝᴇɇ",
	  "f": "ꝼƒ",
	  "g": "ǥɠꞡᵹꝿɢ",
	  "h": "ħⱨⱶɥ",
	  "i": "ɨı",
	  "j": "ɉȷ",
	  "k": "ƙⱪꝁꝃꝅꞣ",
	  "l": "łƚɫⱡꝉꝇꞁɭ",
	  "m": "ɱɯϻ",
	  "n": "ꞥƞɲꞑᴎлԉ",
	  "o": "øǿɔɵꝋꝍᴑ",
	  "oe": "œ",
	  "oi": "ƣ",
	  "oo": "ꝏ",
	  "ou": "ȣ",
	  "p": "ƥᵽꝑꝓꝕρ",
	  "q": "ꝗꝙɋ",
	  "r": "ɍɽꝛꞧꞃ",
	  "s": "ßȿꞩꞅʂ",
	  "t": "ŧƭʈⱦꞇ",
	  "th": "þ",
	  "tz": "ꜩ",
	  "u": "ʉ",
	  "v": "ʋꝟʌ",
	  "vy": "ꝡ",
	  "w": "ⱳ",
	  "y": "ƴɏỿ",
	  "z": "ƶȥɀⱬꝣ",
	  "hv": "ƕ"
	};

	for (let latin in latin_condensed) {
	  let unicode = latin_condensed[latin] || '';

	  for (let i = 0; i < unicode.length; i++) {
	    let char = unicode.substring(i, i + 1);
	    latin_convert[char] = latin;
	  }
	}

	const convert_pat = new RegExp(Object.keys(latin_convert).join('|') + '|' + accent_pat, 'gu');
	/**
	 * Initialize the unicode_map from the give code point ranges
	 *
	 * @param {TCodePoints=} _code_points
	 */

	const initialize = _code_points => {
	  if (unicode_map !== undefined) return;
	  unicode_map = generateMap(_code_points || code_points);
	};
	/**
	 * Helper method for normalize a string
	 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/normalize
	 * @param {string} str
	 * @param {string} form
	 */

	const normalize = (str, form = 'NFKD') => str.normalize(form);
	/**
	 * Remove accents without reordering string
	 * calling str.normalize('NFKD') on \u{594}\u{595}\u{596} becomes \u{596}\u{594}\u{595}
	 * via https://github.com/krisk/Fuse/issues/133#issuecomment-318692703
	 * @param {string} str
	 * @return {string}
	 */

	const asciifold = str => {
	  return toArray(str).reduce(
	  /**
	   * @param {string} result
	   * @param {string} char
	   */
	  (result, char) => {
	    return result + _asciifold(char);
	  }, '');
	};
	/**
	 * @param {string} str
	 * @return {string}
	 */

	const _asciifold = str => {
	  str = normalize(str).toLowerCase().replace(convert_pat, (
	  /** @type {string} */
	  char) => {
	    return latin_convert[char] || '';
	  }); //return str;

	  return normalize(str, 'NFC');
	};
	/**
	 * Generate a list of unicode variants from the list of code points
	 * @param {TCodePoints} code_points
	 * @yield {TCodePointObj}
	 */

	function* generator(code_points) {
	  for (const [code_point_min, code_point_max] of code_points) {
	    for (let i = code_point_min; i <= code_point_max; i++) {
	      let composed = String.fromCharCode(i);
	      let folded = asciifold(composed);

	      if (folded == composed.toLowerCase()) {
	        continue;
	      } // skip when folded is a string longer than 3 characters long
	      // bc the resulting regex patterns will be long
	      // eg:
	      // folded صلى الله عليه وسلم length 18 code point 65018
	      // folded جل جلاله length 8 code point 65019


	      if (folded.length > max_char_length) {
	        continue;
	      }

	      if (folded.length == 0) {
	        continue;
	      }

	      yield {
	        folded: folded,
	        composed: composed,
	        code_point: i
	      };
	    }
	  }
	}
	/**
	 * Generate a unicode map from the list of code points
	 * @param {TCodePoints} code_points
	 * @return {TUnicodeSets}
	 */

	const generateSets = code_points => {
	  /** @type {{[key:string]:Set<string>}} */
	  const unicode_sets = {};
	  /**
	   * @param {string} folded
	   * @param {string} to_add
	   */

	  const addMatching = (folded, to_add) => {
	    /** @type {Set<string>} */
	    const folded_set = unicode_sets[folded] || new Set();
	    const patt = new RegExp('^' + setToPattern(folded_set) + '$', 'iu');

	    if (to_add.match(patt)) {
	      return;
	    }

	    folded_set.add(escape_regex(to_add));
	    unicode_sets[folded] = folded_set;
	  };

	  for (let value of generator(code_points)) {
	    addMatching(value.folded, value.folded);
	    addMatching(value.folded, value.composed);
	  }

	  return unicode_sets;
	};
	/**
	 * Generate a unicode map from the list of code points
	 * ae => (?:(?:ae|Æ|Ǽ|Ǣ)|(?:A|Ⓐ|Ａ...)(?:E|ɛ|Ⓔ...))
	 *
	 * @param {TCodePoints} code_points
	 * @return {TUnicodeMap}
	 */

	const generateMap = code_points => {
	  /** @type {TUnicodeSets} */
	  const unicode_sets = generateSets(code_points);
	  /** @type {TUnicodeMap} */

	  const unicode_map = {};
	  /** @type {string[]} */

	  let multi_char = [];

	  for (let folded in unicode_sets) {
	    let set = unicode_sets[folded];

	    if (set) {
	      unicode_map[folded] = setToPattern(set);
	    }

	    if (folded.length > 1) {
	      multi_char.push(escape_regex(folded));
	    }
	  }

	  multi_char.sort((a, b) => b.length - a.length);
	  const multi_char_patt = arrayToPattern(multi_char);
	  multi_char_reg = new RegExp('^' + multi_char_patt, 'u');
	  return unicode_map;
	};
	/**
	 * Map each element of an array from it's folded value to all possible unicode matches
	 * @param {string[]} strings
	 * @param {number} min_replacement
	 * @return {string}
	 */

	const mapSequence = (strings, min_replacement = 1) => {
	  let chars_replaced = 0;
	  strings = strings.map(str => {
	    if (unicode_map[str]) {
	      chars_replaced += str.length;
	    }

	    return unicode_map[str] || str;
	  });

	  if (chars_replaced >= min_replacement) {
	    return sequencePattern(strings);
	  }

	  return '';
	};
	/**
	 * Convert a short string and split it into all possible patterns
	 * Keep a pattern only if min_replacement is met
	 *
	 * 'abc'
	 * 		=> [['abc'],['ab','c'],['a','bc'],['a','b','c']]
	 *		=> ['abc-pattern','ab-c-pattern'...]
	 *
	 *
	 * @param {string} str
	 * @param {number} min_replacement
	 * @return {string}
	 */

	const substringsToPattern = (str, min_replacement = 1) => {
	  min_replacement = Math.max(min_replacement, str.length - 1);
	  return arrayToPattern(allSubstrings(str).map(sub_pat => {
	    return mapSequence(sub_pat, min_replacement);
	  }));
	};
	/**
	 * Convert an array of sequences into a pattern
	 * [{start:0,end:3,length:3,substr:'iii'}...] => (?:iii...)
	 *
	 * @param {Sequence[]} sequences
	 * @param {boolean} all
	 */

	const sequencesToPattern = (sequences, all = true) => {
	  let min_replacement = sequences.length > 1 ? 1 : 0;
	  return arrayToPattern(sequences.map(sequence => {
	    let seq = [];
	    const len = all ? sequence.length() : sequence.length() - 1;

	    for (let j = 0; j < len; j++) {
	      seq.push(substringsToPattern(sequence.substrs[j] || '', min_replacement));
	    }

	    return sequencePattern(seq);
	  }));
	};
	/**
	 * Return true if the sequence is already in the sequences
	 * @param {Sequence} needle_seq
	 * @param {Sequence[]} sequences
	 */


	const inSequences = (needle_seq, sequences) => {
	  for (const seq of sequences) {
	    if (seq.start != needle_seq.start || seq.end != needle_seq.end) {
	      continue;
	    }

	    if (seq.substrs.join('') !== needle_seq.substrs.join('')) {
	      continue;
	    }

	    let needle_parts = needle_seq.parts;
	    /**
	     * @param {TSequencePart} part
	     */

	    const filter = part => {
	      for (const needle_part of needle_parts) {
	        if (needle_part.start === part.start && needle_part.substr === part.substr) {
	          return false;
	        }

	        if (part.length == 1 || needle_part.length == 1) {
	          continue;
	        } // check for overlapping parts
	        // a = ['::=','==']
	        // b = ['::','===']
	        // a = ['r','sm']
	        // b = ['rs','m']


	        if (part.start < needle_part.start && part.end > needle_part.start) {
	          return true;
	        }

	        if (needle_part.start < part.start && needle_part.end > part.start) {
	          return true;
	        }
	      }

	      return false;
	    };

	    let filtered = seq.parts.filter(filter);

	    if (filtered.length > 0) {
	      continue;
	    }

	    return true;
	  }

	  return false;
	};

	class Sequence {
	  constructor() {
	    /** @type {TSequencePart[]} */
	    this.parts = [];
	    /** @type {string[]} */

	    this.substrs = [];
	    this.start = 0;
	    this.end = 0;
	  }
	  /**
	   * @param {TSequencePart|undefined} part
	   */


	  add(part) {
	    if (part) {
	      this.parts.push(part);
	      this.substrs.push(part.substr);
	      this.start = Math.min(part.start, this.start);
	      this.end = Math.max(part.end, this.end);
	    }
	  }

	  last() {
	    return this.parts[this.parts.length - 1];
	  }

	  length() {
	    return this.parts.length;
	  }
	  /**
	   * @param {number} position
	   * @param {TSequencePart} last_piece
	   */


	  clone(position, last_piece) {
	    let clone = new Sequence();
	    let parts = JSON.parse(JSON.stringify(this.parts));
	    let last_part = parts.pop();

	    for (const part of parts) {
	      clone.add(part);
	    }

	    let last_substr = last_piece.substr.substring(0, position - last_part.start);
	    let clone_last_len = last_substr.length;
	    clone.add({
	      start: last_part.start,
	      end: last_part.start + clone_last_len,
	      length: clone_last_len,
	      substr: last_substr
	    });
	    return clone;
	  }

	}
	/**
	 * Expand a regular expression pattern to include unicode variants
	 * 	eg /a/ becomes /aⓐａẚàáâầấẫẩãāăằắẵẳȧǡäǟảåǻǎȁȃạậặḁąⱥɐɑAⒶＡÀÁÂẦẤẪẨÃĀĂẰẮẴẲȦǠÄǞẢÅǺǍȀȂẠẬẶḀĄȺⱯ/
	 *
	 * Issue:
	 *  ﺊﺋ [ 'ﺊ = \\u{fe8a}', 'ﺋ = \\u{fe8b}' ]
	 *	becomes:	ئئ [ 'ي = \\u{64a}', 'ٔ = \\u{654}', 'ي = \\u{64a}', 'ٔ = \\u{654}' ]
	 *
	 *	İĲ = IIJ = ⅡJ
	 *
	 * 	1/2/4
	 *
	 * @param {string} str
	 * @return {string|undefined}
	 */


	const getPattern = str => {
	  initialize();
	  str = asciifold(str);
	  let pattern = '';
	  let sequences = [new Sequence()];

	  for (let i = 0; i < str.length; i++) {
	    let substr = str.substring(i);
	    let match = substr.match(multi_char_reg);
	    const char = str.substring(i, i + 1);
	    const match_str = match ? match[0] : null; // loop through sequences
	    // add either the char or multi_match

	    let overlapping = [];
	    let added_types = new Set();

	    for (const sequence of sequences) {
	      const last_piece = sequence.last();

	      if (!last_piece || last_piece.length == 1 || last_piece.end <= i) {
	        // if we have a multi match
	        if (match_str) {
	          const len = match_str.length;
	          sequence.add({
	            start: i,
	            end: i + len,
	            length: len,
	            substr: match_str
	          });
	          added_types.add('1');
	        } else {
	          sequence.add({
	            start: i,
	            end: i + 1,
	            length: 1,
	            substr: char
	          });
	          added_types.add('2');
	        }
	      } else if (match_str) {
	        let clone = sequence.clone(i, last_piece);
	        const len = match_str.length;
	        clone.add({
	          start: i,
	          end: i + len,
	          length: len,
	          substr: match_str
	        });
	        overlapping.push(clone);
	      } else {
	        // don't add char
	        // adding would create invalid patterns: 234 => [2,34,4]
	        added_types.add('3');
	      }
	    } // if we have overlapping


	    if (overlapping.length > 0) {
	      // ['ii','iii'] before ['i','i','iii']
	      overlapping = overlapping.sort((a, b) => {
	        return a.length() - b.length();
	      });

	      for (let clone of overlapping) {
	        // don't add if we already have an equivalent sequence
	        if (inSequences(clone, sequences)) {
	          continue;
	        }

	        sequences.push(clone);
	      }

	      continue;
	    } // if we haven't done anything unique
	    // clean up the patterns
	    // helps keep patterns smaller
	    // if str = 'r₨㎧aarss', pattern will be 446 instead of 655


	    if (i > 0 && added_types.size == 1 && !added_types.has('3')) {
	      pattern += sequencesToPattern(sequences, false);
	      let new_seq = new Sequence();
	      const old_seq = sequences[0];

	      if (old_seq) {
	        new_seq.add(old_seq.last());
	      }

	      sequences = [new_seq];
	    }
	  }

	  pattern += sequencesToPattern(sequences, true);
	  return pattern;
	};

	/*! sifter.js | https://github.com/orchidjs/sifter.js | Apache License (v2) */

	/**
	 * A property getter resolving dot-notation
	 * @param  {Object}  obj     The root object to fetch property on
	 * @param  {String}  name    The optionally dotted property name to fetch
	 * @return {Object}          The resolved property value
	 */
	const getAttr = (obj, name) => {
	  if (!obj) return;
	  return obj[name];
	};
	/**
	 * A property getter resolving dot-notation
	 * @param  {Object}  obj     The root object to fetch property on
	 * @param  {String}  name    The optionally dotted property name to fetch
	 * @return {Object}          The resolved property value
	 */

	const getAttrNesting = (obj, name) => {
	  if (!obj) return;
	  var part,
	      names = name.split(".");

	  while ((part = names.shift()) && (obj = obj[part]));

	  return obj;
	};
	/**
	 * Calculates how close of a match the
	 * given value is against a search token.
	 *
	 */

	const scoreValue = (value, token, weight) => {
	  var score, pos;
	  if (!value) return 0;
	  value = value + '';
	  if (token.regex == null) return 0;
	  pos = value.search(token.regex);
	  if (pos === -1) return 0;
	  score = token.string.length / value.length;
	  if (pos === 0) score += 0.5;
	  return score * weight;
	};
	/**
	 * Cast object property to an array if it exists and has a value
	 *
	 */

	const propToArray = (obj, key) => {
	  var value = obj[key];
	  if (typeof value == 'function') return value;

	  if (value && !Array.isArray(value)) {
	    obj[key] = [value];
	  }
	};
	/**
	 * Iterates over arrays and hashes.
	 *
	 * ```
	 * iterate(this.items, function(item, id) {
	 *    // invoked for each item
	 * });
	 * ```
	 *
	 */

	const iterate$1 = (object, callback) => {
	  if (Array.isArray(object)) {
	    object.forEach(callback);
	  } else {
	    for (var key in object) {
	      if (object.hasOwnProperty(key)) {
	        callback(object[key], key);
	      }
	    }
	  }
	};
	const cmp = (a, b) => {
	  if (typeof a === 'number' && typeof b === 'number') {
	    return a > b ? 1 : a < b ? -1 : 0;
	  }

	  a = asciifold(a + '').toLowerCase();
	  b = asciifold(b + '').toLowerCase();
	  if (a > b) return 1;
	  if (b > a) return -1;
	  return 0;
	};

	/*! sifter.js | https://github.com/orchidjs/sifter.js | Apache License (v2) */

	/**
	 * sifter.js
	 * Copyright (c) 2013–2020 Brian Reavis & contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 * @author Brian Reavis <brian@thirdroute.com>
	 */

	class Sifter {
	  // []|{};

	  /**
	   * Textually searches arrays and hashes of objects
	   * by property (or multiple properties). Designed
	   * specifically for autocomplete.
	   *
	   */
	  constructor(items, settings) {
	    this.items = void 0;
	    this.settings = void 0;
	    this.items = items;
	    this.settings = settings || {
	      diacritics: true
	    };
	  }

	  /**
	   * Splits a search string into an array of individual
	   * regexps to be used to match results.
	   *
	   */
	  tokenize(query, respect_word_boundaries, weights) {
	    if (!query || !query.length) return [];
	    const tokens = [];
	    const words = query.split(/\s+/);
	    var field_regex;

	    if (weights) {
	      field_regex = new RegExp('^(' + Object.keys(weights).map(escape_regex).join('|') + ')\:(.*)$');
	    }

	    words.forEach(word => {
	      let field_match;
	      let field = null;
	      let regex = null; // look for "field:query" tokens

	      if (field_regex && (field_match = word.match(field_regex))) {
	        field = field_match[1];
	        word = field_match[2];
	      }

	      if (word.length > 0) {
	        if (this.settings.diacritics) {
	          regex = getPattern(word) || null;
	        } else {
	          regex = escape_regex(word);
	        }

	        if (regex && respect_word_boundaries) regex = "\\b" + regex;
	      }

	      tokens.push({
	        string: word,
	        regex: regex ? new RegExp(regex, 'iu') : null,
	        field: field
	      });
	    });
	    return tokens;
	  }

	  /**
	   * Returns a function to be used to score individual results.
	   *
	   * Good matches will have a higher score than poor matches.
	   * If an item is not a match, 0 will be returned by the function.
	   *
	   * @returns {T.ScoreFn}
	   */
	  getScoreFunction(query, options) {
	    var search = this.prepareSearch(query, options);
	    return this._getScoreFunction(search);
	  }
	  /**
	   * @returns {T.ScoreFn}
	   *
	   */


	  _getScoreFunction(search) {
	    const tokens = search.tokens,
	          token_count = tokens.length;

	    if (!token_count) {
	      return function () {
	        return 0;
	      };
	    }

	    const fields = search.options.fields,
	          weights = search.weights,
	          field_count = fields.length,
	          getAttrFn = search.getAttrFn;

	    if (!field_count) {
	      return function () {
	        return 1;
	      };
	    }
	    /**
	     * Calculates the score of an object
	     * against the search query.
	     *
	     */


	    const scoreObject = function () {
	      if (field_count === 1) {
	        return function (token, data) {
	          const field = fields[0].field;
	          return scoreValue(getAttrFn(data, field), token, weights[field] || 1);
	        };
	      }

	      return function (token, data) {
	        var sum = 0; // is the token specific to a field?

	        if (token.field) {
	          const value = getAttrFn(data, token.field);

	          if (!token.regex && value) {
	            sum += 1 / field_count;
	          } else {
	            sum += scoreValue(value, token, 1);
	          }
	        } else {
	          iterate$1(weights, (weight, field) => {
	            sum += scoreValue(getAttrFn(data, field), token, weight);
	          });
	        }

	        return sum / field_count;
	      };
	    }();

	    if (token_count === 1) {
	      return function (data) {
	        return scoreObject(tokens[0], data);
	      };
	    }

	    if (search.options.conjunction === 'and') {
	      return function (data) {
	        var score,
	            sum = 0;

	        for (let token of tokens) {
	          score = scoreObject(token, data);
	          if (score <= 0) return 0;
	          sum += score;
	        }

	        return sum / token_count;
	      };
	    } else {
	      return function (data) {
	        var sum = 0;
	        iterate$1(tokens, token => {
	          sum += scoreObject(token, data);
	        });
	        return sum / token_count;
	      };
	    }
	  }

	  /**
	   * Returns a function that can be used to compare two
	   * results, for sorting purposes. If no sorting should
	   * be performed, `null` will be returned.
	   *
	   * @return function(a,b)
	   */
	  getSortFunction(query, options) {
	    var search = this.prepareSearch(query, options);
	    return this._getSortFunction(search);
	  }

	  _getSortFunction(search) {
	    var implicit_score,
	        sort_flds = [];
	    const self = this,
	          options = search.options,
	          sort = !search.query && options.sort_empty ? options.sort_empty : options.sort;

	    if (typeof sort == 'function') {
	      return sort.bind(this);
	    }
	    /**
	     * Fetches the specified sort field value
	     * from a search result item.
	     *
	     */


	    const get_field = function get_field(name, result) {
	      if (name === '$score') return result.score;
	      return search.getAttrFn(self.items[result.id], name);
	    }; // parse options


	    if (sort) {
	      for (let s of sort) {
	        if (search.query || s.field !== '$score') {
	          sort_flds.push(s);
	        }
	      }
	    } // the "$score" field is implied to be the primary
	    // sort field, unless it's manually specified


	    if (search.query) {
	      implicit_score = true;

	      for (let fld of sort_flds) {
	        if (fld.field === '$score') {
	          implicit_score = false;
	          break;
	        }
	      }

	      if (implicit_score) {
	        sort_flds.unshift({
	          field: '$score',
	          direction: 'desc'
	        });
	      } // without a search.query, all items will have the same score

	    } else {
	      sort_flds = sort_flds.filter(fld => fld.field !== '$score');
	    } // build function


	    const sort_flds_count = sort_flds.length;

	    if (!sort_flds_count) {
	      return null;
	    }

	    return function (a, b) {
	      var result, field;

	      for (let sort_fld of sort_flds) {
	        field = sort_fld.field;
	        let multiplier = sort_fld.direction === 'desc' ? -1 : 1;
	        result = multiplier * cmp(get_field(field, a), get_field(field, b));
	        if (result) return result;
	      }

	      return 0;
	    };
	  }

	  /**
	   * Parses a search query and returns an object
	   * with tokens and fields ready to be populated
	   * with results.
	   *
	   */
	  prepareSearch(query, optsUser) {
	    const weights = {};
	    var options = Object.assign({}, optsUser);
	    propToArray(options, 'sort');
	    propToArray(options, 'sort_empty'); // convert fields to new format

	    if (options.fields) {
	      propToArray(options, 'fields');
	      const fields = [];
	      options.fields.forEach(field => {
	        if (typeof field == 'string') {
	          field = {
	            field: field,
	            weight: 1
	          };
	        }

	        fields.push(field);
	        weights[field.field] = 'weight' in field ? field.weight : 1;
	      });
	      options.fields = fields;
	    }

	    return {
	      options: options,
	      query: query.toLowerCase().trim(),
	      tokens: this.tokenize(query, options.respect_word_boundaries, weights),
	      total: 0,
	      items: [],
	      weights: weights,
	      getAttrFn: options.nesting ? getAttrNesting : getAttr
	    };
	  }

	  /**
	   * Searches through all items and returns a sorted array of matches.
	   *
	   */
	  search(query, options) {
	    var self = this,
	        score,
	        search;
	    search = this.prepareSearch(query, options);
	    options = search.options;
	    query = search.query; // generate result scoring function

	    const fn_score = options.score || self._getScoreFunction(search); // perform search and sort


	    if (query.length) {
	      iterate$1(self.items, (item, id) => {
	        score = fn_score(item);

	        if (options.filter === false || score > 0) {
	          search.items.push({
	            'score': score,
	            'id': id
	          });
	        }
	      });
	    } else {
	      iterate$1(self.items, (_, id) => {
	        search.items.push({
	          'score': 1,
	          'id': id
	        });
	      });
	    }

	    const fn_sort = self._getSortFunction(search);

	    if (fn_sort) search.items.sort(fn_sort); // apply limits

	    search.total = search.items.length;

	    if (typeof options.limit === 'number') {
	      search.items = search.items.slice(0, options.limit);
	    }

	    return search;
	  }

	}

	/**
	 * Iterates over arrays and hashes.
	 *
	 * ```
	 * iterate(this.items, function(item, id) {
	 *    // invoked for each item
	 * });
	 * ```
	 *
	 */
	const iterate = (object, callback) => {
	  if (Array.isArray(object)) {
	    object.forEach(callback);
	  } else {
	    for (var key in object) {
	      if (object.hasOwnProperty(key)) {
	        callback(object[key], key);
	      }
	    }
	  }
	};

	/**
	 * Return a dom element from either a dom query string, jQuery object, a dom element or html string
	 * https://stackoverflow.com/questions/494143/creating-a-new-dom-element-from-an-html-string-using-built-in-dom-methods-or-pro/35385518#35385518
	 *
	 * param query should be {}
	 */
	const getDom = query => {
	  if (query.jquery) {
	    return query[0];
	  }
	  if (query instanceof HTMLElement) {
	    return query;
	  }
	  if (isHtmlString(query)) {
	    var tpl = document.createElement('template');
	    tpl.innerHTML = query.trim(); // Never return a text node of whitespace as the result
	    return tpl.content.firstChild;
	  }
	  return document.querySelector(query);
	};
	const isHtmlString = arg => {
	  if (typeof arg === 'string' && arg.indexOf('<') > -1) {
	    return true;
	  }
	  return false;
	};
	const escapeQuery = query => {
	  return query.replace(/['"\\]/g, '\\$&');
	};

	/**
	 * Dispatch an event
	 *
	 */
	const triggerEvent = (dom_el, event_name) => {
	  var event = document.createEvent('HTMLEvents');
	  event.initEvent(event_name, true, false);
	  dom_el.dispatchEvent(event);
	};

	/**
	 * Apply CSS rules to a dom element
	 *
	 */
	const applyCSS = (dom_el, css) => {
	  Object.assign(dom_el.style, css);
	};

	/**
	 * Add css classes
	 *
	 */
	const addClasses = (elmts, ...classes) => {
	  var norm_classes = classesArray(classes);
	  elmts = castAsArray(elmts);
	  elmts.map(el => {
	    norm_classes.map(cls => {
	      el.classList.add(cls);
	    });
	  });
	};

	/**
	 * Remove css classes
	 *
	 */
	const removeClasses = (elmts, ...classes) => {
	  var norm_classes = classesArray(classes);
	  elmts = castAsArray(elmts);
	  elmts.map(el => {
	    norm_classes.map(cls => {
	      el.classList.remove(cls);
	    });
	  });
	};

	/**
	 * Return arguments
	 *
	 */
	const classesArray = args => {
	  var classes = [];
	  iterate(args, _classes => {
	    if (typeof _classes === 'string') {
	      _classes = _classes.trim().split(/[\11\12\14\15\40]/);
	    }
	    if (Array.isArray(_classes)) {
	      classes = classes.concat(_classes);
	    }
	  });
	  return classes.filter(Boolean);
	};

	/**
	 * Create an array from arg if it's not already an array
	 *
	 */
	const castAsArray = arg => {
	  if (!Array.isArray(arg)) {
	    arg = [arg];
	  }
	  return arg;
	};

	/**
	 * Get the closest node to the evt.target matching the selector
	 * Stops at wrapper
	 *
	 */
	const parentMatch = (target, selector, wrapper) => {
	  if (wrapper && !wrapper.contains(target)) {
	    return;
	  }
	  while (target && target.matches) {
	    if (target.matches(selector)) {
	      return target;
	    }
	    target = target.parentNode;
	  }
	};

	/**
	 * Get the first or last item from an array
	 *
	 * > 0 - right (last)
	 * <= 0 - left (first)
	 *
	 */
	const getTail = (list, direction = 0) => {
	  if (direction > 0) {
	    return list[list.length - 1];
	  }
	  return list[0];
	};

	/**
	 * Return true if an object is empty
	 *
	 */
	const isEmptyObject = obj => {
	  return Object.keys(obj).length === 0;
	};

	/**
	 * Get the index of an element amongst sibling nodes of the same type
	 *
	 */
	const nodeIndex = (el, amongst) => {
	  if (!el) return -1;
	  amongst = amongst || el.nodeName;
	  var i = 0;
	  while (el = el.previousElementSibling) {
	    if (el.matches(amongst)) {
	      i++;
	    }
	  }
	  return i;
	};

	/**
	 * Set attributes of an element
	 *
	 */
	const setAttr = (el, attrs) => {
	  iterate(attrs, (val, attr) => {
	    if (val == null) {
	      el.removeAttribute(attr);
	    } else {
	      el.setAttribute(attr, '' + val);
	    }
	  });
	};

	/**
	 * Replace a node
	 */
	const replaceNode = (existing, replacement) => {
	  if (existing.parentNode) existing.parentNode.replaceChild(replacement, existing);
	};

	/**
	 * highlight v3 | MIT license | Johann Burkard <jb@eaio.com>
	 * Highlights arbitrary terms in a node.
	 *
	 * - Modified by Marshal <beatgates@gmail.com> 2011-6-24 (added regex)
	 * - Modified by Brian Reavis <brian@thirdroute.com> 2012-8-27 (cleanup)
	 */

	const highlight = (element, regex) => {
	  if (regex === null) return;

	  // convet string to regex
	  if (typeof regex === 'string') {
	    if (!regex.length) return;
	    regex = new RegExp(regex, 'i');
	  }

	  // Wrap matching part of text node with highlighting <span>, e.g.
	  // Soccer  ->  <span class="highlight">Soc</span>cer  for regex = /soc/i
	  const highlightText = node => {
	    var match = node.data.match(regex);
	    if (match && node.data.length > 0) {
	      var spannode = document.createElement('span');
	      spannode.className = 'highlight';
	      var middlebit = node.splitText(match.index);
	      middlebit.splitText(match[0].length);
	      var middleclone = middlebit.cloneNode(true);
	      spannode.appendChild(middleclone);
	      replaceNode(middlebit, spannode);
	      return 1;
	    }
	    return 0;
	  };

	  // Recurse element node, looking for child text nodes to highlight, unless element
	  // is childless, <script>, <style>, or already highlighted: <span class="hightlight">
	  const highlightChildren = node => {
	    if (node.nodeType === 1 && node.childNodes && !/(script|style)/i.test(node.tagName) && (node.className !== 'highlight' || node.tagName !== 'SPAN')) {
	      Array.from(node.childNodes).forEach(element => {
	        highlightRecursive(element);
	      });
	    }
	  };
	  const highlightRecursive = node => {
	    if (node.nodeType === 3) {
	      return highlightText(node);
	    }
	    highlightChildren(node);
	    return 0;
	  };
	  highlightRecursive(element);
	};

	/**
	 * removeHighlight fn copied from highlight v5 and
	 * edited to remove with(), pass js strict mode, and use without jquery
	 */
	const removeHighlight = el => {
	  var elements = el.querySelectorAll("span.highlight");
	  Array.prototype.forEach.call(elements, function (el) {
	    var parent = el.parentNode;
	    parent.replaceChild(el.firstChild, el);
	    parent.normalize();
	  });
	};

	const KEY_A = 65;
	const KEY_RETURN = 13;
	const KEY_ESC = 27;
	const KEY_LEFT = 37;
	const KEY_UP = 38;
	const KEY_RIGHT = 39;
	const KEY_DOWN = 40;
	const KEY_BACKSPACE = 8;
	const KEY_DELETE = 46;
	const KEY_TAB = 9;
	const IS_MAC = typeof navigator === 'undefined' ? false : /Mac/.test(navigator.userAgent);
	const KEY_SHORTCUT = IS_MAC ? 'metaKey' : 'ctrlKey'; // ctrl key or apple key for ma

	var defaults = {
	  options: [],
	  optgroups: [],
	  plugins: [],
	  delimiter: ',',
	  splitOn: null,
	  // regexp or string for splitting up values from a paste command
	  persist: true,
	  diacritics: true,
	  create: null,
	  createOnBlur: false,
	  createFilter: null,
	  highlight: true,
	  openOnFocus: true,
	  shouldOpen: null,
	  maxOptions: 50,
	  maxItems: null,
	  hideSelected: null,
	  duplicates: false,
	  addPrecedence: false,
	  selectOnTab: false,
	  preload: null,
	  allowEmptyOption: false,
	  //closeAfterSelect: false,
	  refreshThrottle: 300,
	  loadThrottle: 300,
	  loadingClass: 'loading',
	  dataAttr: null,
	  //'data-data',
	  optgroupField: 'optgroup',
	  valueField: 'value',
	  labelField: 'text',
	  disabledField: 'disabled',
	  optgroupLabelField: 'label',
	  optgroupValueField: 'value',
	  lockOptgroupOrder: false,
	  sortField: '$order',
	  searchField: ['text'],
	  searchConjunction: 'and',
	  mode: null,
	  wrapperClass: 'ts-wrapper',
	  controlClass: 'ts-control',
	  dropdownClass: 'ts-dropdown',
	  dropdownContentClass: 'ts-dropdown-content',
	  itemClass: 'item',
	  optionClass: 'option',
	  dropdownParent: null,
	  controlInput: '<input type="text" autocomplete="off" size="1" />',
	  copyClassesToDropdown: false,
	  placeholder: null,
	  hidePlaceholder: null,
	  shouldLoad: function (query) {
	    return query.length > 0;
	  },
	  /*
	  load                 : null, // function(query, callback) { ... }
	  score                : null, // function(search) { ... }
	  onInitialize         : null, // function() { ... }
	  onChange             : null, // function(value) { ... }
	  onItemAdd            : null, // function(value, $item) { ... }
	  onItemRemove         : null, // function(value) { ... }
	  onClear              : null, // function() { ... }
	  onOptionAdd          : null, // function(value, data) { ... }
	  onOptionRemove       : null, // function(value) { ... }
	  onOptionClear        : null, // function() { ... }
	  onOptionGroupAdd     : null, // function(id, data) { ... }
	  onOptionGroupRemove  : null, // function(id) { ... }
	  onOptionGroupClear   : null, // function() { ... }
	  onDropdownOpen       : null, // function(dropdown) { ... }
	  onDropdownClose      : null, // function(dropdown) { ... }
	  onType               : null, // function(str) { ... }
	  onDelete             : null, // function(values) { ... }
	  */

	  render: {
	    /*
	    item: null,
	    optgroup: null,
	    optgroup_header: null,
	    option: null,
	    option_create: null
	    */
	  }
	};

	/**
	 * Converts a scalar to its best string representation
	 * for hash keys and HTML attribute values.
	 *
	 * Transformations:
	 *   'str'     -> 'str'
	 *   null      -> ''
	 *   undefined -> ''
	 *   true      -> '1'
	 *   false     -> '0'
	 *   0         -> '0'
	 *   1         -> '1'
	 *
	 */
	const hash_key = value => {
	  if (typeof value === 'undefined' || value === null) return null;
	  return get_hash(value);
	};
	const get_hash = value => {
	  if (typeof value === 'boolean') return value ? '1' : '0';
	  return value + '';
	};

	/**
	 * Escapes a string for use within HTML.
	 *
	 */
	const escape_html = str => {
	  return (str + '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	};

	/**
	 * use setTimeout if timeout > 0 
	 */
	const timeout = (fn, timeout) => {
	  if (timeout > 0) {
	    return setTimeout(fn, timeout);
	  }
	  fn.call(null);
	  return null;
	};

	/**
	 * Debounce the user provided load function
	 *
	 */
	const loadDebounce = (fn, delay) => {
	  var timeout;
	  return function (value, callback) {
	    var self = this;
	    if (timeout) {
	      self.loading = Math.max(self.loading - 1, 0);
	      clearTimeout(timeout);
	    }
	    timeout = setTimeout(function () {
	      timeout = null;
	      self.loadedSearches[value] = true;
	      fn.call(self, value, callback);
	    }, delay);
	  };
	};

	/**
	 * Debounce all fired events types listed in `types`
	 * while executing the provided `fn`.
	 *
	 */
	const debounce_events = (self, types, fn) => {
	  var type;
	  var trigger = self.trigger;
	  var event_args = {};

	  // override trigger method
	  self.trigger = function () {
	    var type = arguments[0];
	    if (types.indexOf(type) !== -1) {
	      event_args[type] = arguments;
	    } else {
	      return trigger.apply(self, arguments);
	    }
	  };

	  // invoke provided function
	  fn.apply(self, []);
	  self.trigger = trigger;

	  // trigger queued events
	  for (type of types) {
	    if (type in event_args) {
	      trigger.apply(self, event_args[type]);
	    }
	  }
	};

	/**
	 * Determines the current selection within a text input control.
	 * Returns an object containing:
	 *   - start
	 *   - length
	 *
	 * Note: "selectionStart, selectionEnd ... apply only to inputs of types text, search, URL, tel and password"
	 * 	- https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement/setSelectionRange
	 */
	const getSelection = input => {
	  return {
	    start: input.selectionStart || 0,
	    length: (input.selectionEnd || 0) - (input.selectionStart || 0)
	  };
	};

	/**
	 * Prevent default
	 *
	 */
	const preventDefault = (evt, stop = false) => {
	  if (evt) {
	    evt.preventDefault();
	    if (stop) {
	      evt.stopPropagation();
	    }
	  }
	};

	/**
	 * Add event helper
	 *
	 */
	const addEvent = (target, type, callback, options) => {
	  target.addEventListener(type, callback, options);
	};

	/**
	 * Return true if the requested key is down
	 * Will return false if more than one control character is pressed ( when [ctrl+shift+a] != [ctrl+a] )
	 * The current evt may not always set ( eg calling advanceSelection() )
	 *
	 */
	const isKeyDown = (key_name, evt) => {
	  if (!evt) {
	    return false;
	  }
	  if (!evt[key_name]) {
	    return false;
	  }
	  var count = (evt.altKey ? 1 : 0) + (evt.ctrlKey ? 1 : 0) + (evt.shiftKey ? 1 : 0) + (evt.metaKey ? 1 : 0);
	  if (count === 1) {
	    return true;
	  }
	  return false;
	};

	/**
	 * Get the id of an element
	 * If the id attribute is not set, set the attribute with the given id
	 *
	 */
	const getId = (el, id) => {
	  const existing_id = el.getAttribute('id');
	  if (existing_id) {
	    return existing_id;
	  }
	  el.setAttribute('id', id);
	  return id;
	};

	/**
	 * Returns a string with backslashes added before characters that need to be escaped.
	 */
	const addSlashes = str => {
	  return str.replace(/[\\"']/g, '\\$&');
	};

	/**
	 *
	 */
	const append = (parent, node) => {
	  if (node) parent.append(node);
	};

	function getSettings(input, settings_user) {
	  var settings = Object.assign({}, defaults, settings_user);
	  var attr_data = settings.dataAttr;
	  var field_label = settings.labelField;
	  var field_value = settings.valueField;
	  var field_disabled = settings.disabledField;
	  var field_optgroup = settings.optgroupField;
	  var field_optgroup_label = settings.optgroupLabelField;
	  var field_optgroup_value = settings.optgroupValueField;
	  var tag_name = input.tagName.toLowerCase();
	  var placeholder = input.getAttribute('placeholder') || input.getAttribute('data-placeholder');
	  if (!placeholder && !settings.allowEmptyOption) {
	    let option = input.querySelector('option[value=""]');
	    if (option) {
	      placeholder = option.textContent;
	    }
	  }
	  var settings_element = {
	    placeholder: placeholder,
	    options: [],
	    optgroups: [],
	    items: [],
	    maxItems: null
	  };

	  /**
	   * Initialize from a <select> element.
	   *
	   */
	  var init_select = () => {
	    var tagName;
	    var options = settings_element.options;
	    var optionsMap = {};
	    var group_count = 1;
	    let $order = 0;
	    var readData = el => {
	      var data = Object.assign({}, el.dataset); // get plain object from DOMStringMap
	      var json = attr_data && data[attr_data];
	      if (typeof json === 'string' && json.length) {
	        data = Object.assign(data, JSON.parse(json));
	      }
	      return data;
	    };
	    var addOption = (option, group) => {
	      var value = hash_key(option.value);
	      if (value == null) return;
	      if (!value && !settings.allowEmptyOption) return;

	      // if the option already exists, it's probably been
	      // duplicated in another optgroup. in this case, push
	      // the current group to the "optgroup" property on the
	      // existing option so that it's rendered in both places.
	      if (optionsMap.hasOwnProperty(value)) {
	        if (group) {
	          var arr = optionsMap[value][field_optgroup];
	          if (!arr) {
	            optionsMap[value][field_optgroup] = group;
	          } else if (!Array.isArray(arr)) {
	            optionsMap[value][field_optgroup] = [arr, group];
	          } else {
	            arr.push(group);
	          }
	        }
	      } else {
	        var option_data = readData(option);
	        option_data[field_label] = option_data[field_label] || option.textContent;
	        option_data[field_value] = option_data[field_value] || value;
	        option_data[field_disabled] = option_data[field_disabled] || option.disabled;
	        option_data[field_optgroup] = option_data[field_optgroup] || group;
	        option_data.$option = option;
	        option_data.$order = option_data.$order || ++$order;
	        optionsMap[value] = option_data;
	        options.push(option_data);
	      }
	      if (option.selected) {
	        settings_element.items.push(value);
	      }
	    };
	    var addGroup = optgroup => {
	      var id, optgroup_data;
	      optgroup_data = readData(optgroup);
	      optgroup_data[field_optgroup_label] = optgroup_data[field_optgroup_label] || optgroup.getAttribute('label') || '';
	      optgroup_data[field_optgroup_value] = optgroup_data[field_optgroup_value] || group_count++;
	      optgroup_data[field_disabled] = optgroup_data[field_disabled] || optgroup.disabled;
	      optgroup_data.$order = optgroup_data.$order || ++$order;
	      settings_element.optgroups.push(optgroup_data);
	      id = optgroup_data[field_optgroup_value];
	      iterate(optgroup.children, option => {
	        addOption(option, id);
	      });
	    };
	    settings_element.maxItems = input.hasAttribute('multiple') ? null : 1;
	    iterate(input.children, child => {
	      tagName = child.tagName.toLowerCase();
	      if (tagName === 'optgroup') {
	        addGroup(child);
	      } else if (tagName === 'option') {
	        addOption(child);
	      }
	    });
	  };

	  /**
	   * Initialize from a <input type="text"> element.
	   *
	   */
	  var init_textbox = () => {
	    const data_raw = input.getAttribute(attr_data);
	    if (!data_raw) {
	      var value = input.value.trim() || '';
	      if (!settings.allowEmptyOption && !value.length) return;
	      const values = value.split(settings.delimiter);
	      iterate(values, value => {
	        const option = {};
	        option[field_label] = value;
	        option[field_value] = value;
	        settings_element.options.push(option);
	      });
	      settings_element.items = values;
	    } else {
	      settings_element.options = JSON.parse(data_raw);
	      iterate(settings_element.options, opt => {
	        settings_element.items.push(opt[field_value]);
	      });
	    }
	  };
	  if (tag_name === 'select') {
	    init_select();
	  } else {
	    init_textbox();
	  }
	  return Object.assign({}, defaults, settings_element, settings_user);
	}

	var instance_i = 0;
	class TomSelect extends MicroPlugin(MicroEvent) {
	  constructor(input_arg, user_settings) {
	    super();
	    this.control_input = void 0;
	    this.wrapper = void 0;
	    this.dropdown = void 0;
	    this.control = void 0;
	    this.dropdown_content = void 0;
	    this.focus_node = void 0;
	    this.order = 0;
	    this.settings = void 0;
	    this.input = void 0;
	    this.tabIndex = void 0;
	    this.is_select_tag = void 0;
	    this.rtl = void 0;
	    this.inputId = void 0;
	    this._destroy = void 0;
	    this.sifter = void 0;
	    this.isOpen = false;
	    this.isDisabled = false;
	    this.isReadOnly = false;
	    this.isRequired = void 0;
	    this.isInvalid = false;
	    // @deprecated 1.8
	    this.isValid = true;
	    this.isLocked = false;
	    this.isFocused = false;
	    this.isInputHidden = false;
	    this.isSetup = false;
	    this.ignoreFocus = false;
	    this.ignoreHover = false;
	    this.hasOptions = false;
	    this.currentResults = void 0;
	    this.lastValue = '';
	    this.caretPos = 0;
	    this.loading = 0;
	    this.loadedSearches = {};
	    this.activeOption = null;
	    this.activeItems = [];
	    this.optgroups = {};
	    this.options = {};
	    this.userOptions = {};
	    this.items = [];
	    this.refreshTimeout = null;
	    instance_i++;
	    var dir;
	    var input = getDom(input_arg);
	    if (input.tomselect) {
	      throw new Error('Tom Select already initialized on this element');
	    }
	    input.tomselect = this;

	    // detect rtl environment
	    var computedStyle = window.getComputedStyle && window.getComputedStyle(input, null);
	    dir = computedStyle.getPropertyValue('direction');

	    // setup default state
	    const settings = getSettings(input, user_settings);
	    this.settings = settings;
	    this.input = input;
	    this.tabIndex = input.tabIndex || 0;
	    this.is_select_tag = input.tagName.toLowerCase() === 'select';
	    this.rtl = /rtl/i.test(dir);
	    this.inputId = getId(input, 'tomselect-' + instance_i);
	    this.isRequired = input.required;

	    // search system
	    this.sifter = new Sifter(this.options, {
	      diacritics: settings.diacritics
	    });

	    // option-dependent defaults
	    settings.mode = settings.mode || (settings.maxItems === 1 ? 'single' : 'multi');
	    if (typeof settings.hideSelected !== 'boolean') {
	      settings.hideSelected = settings.mode === 'multi';
	    }
	    if (typeof settings.hidePlaceholder !== 'boolean') {
	      settings.hidePlaceholder = settings.mode !== 'multi';
	    }

	    // set up createFilter callback
	    var filter = settings.createFilter;
	    if (typeof filter !== 'function') {
	      if (typeof filter === 'string') {
	        filter = new RegExp(filter);
	      }
	      if (filter instanceof RegExp) {
	        settings.createFilter = input => filter.test(input);
	      } else {
	        settings.createFilter = value => {
	          return this.settings.duplicates || !this.options[value];
	        };
	      }
	    }
	    this.initializePlugins(settings.plugins);
	    this.setupCallbacks();
	    this.setupTemplates();

	    // Create all elements
	    const wrapper = getDom('<div>');
	    const control = getDom('<div>');
	    const dropdown = this._render('dropdown');
	    const dropdown_content = getDom(`<div role="listbox" tabindex="-1">`);
	    const classes = this.input.getAttribute('class') || '';
	    const inputMode = settings.mode;
	    var control_input;
	    addClasses(wrapper, settings.wrapperClass, classes, inputMode);
	    addClasses(control, settings.controlClass);
	    append(wrapper, control);
	    addClasses(dropdown, settings.dropdownClass, inputMode);
	    if (settings.copyClassesToDropdown) {
	      addClasses(dropdown, classes);
	    }
	    addClasses(dropdown_content, settings.dropdownContentClass);
	    append(dropdown, dropdown_content);
	    getDom(settings.dropdownParent || wrapper).appendChild(dropdown);

	    // default controlInput
	    if (isHtmlString(settings.controlInput)) {
	      control_input = getDom(settings.controlInput);

	      // set attributes
	      var attrs = ['autocorrect', 'autocapitalize', 'autocomplete', 'spellcheck'];
	      iterate$1(attrs, attr => {
	        if (input.getAttribute(attr)) {
	          setAttr(control_input, {
	            [attr]: input.getAttribute(attr)
	          });
	        }
	      });
	      control_input.tabIndex = -1;
	      control.appendChild(control_input);
	      this.focus_node = control_input;

	      // dom element
	    } else if (settings.controlInput) {
	      control_input = getDom(settings.controlInput);
	      this.focus_node = control_input;
	    } else {
	      control_input = getDom('<input/>');
	      this.focus_node = control;
	    }
	    this.wrapper = wrapper;
	    this.dropdown = dropdown;
	    this.dropdown_content = dropdown_content;
	    this.control = control;
	    this.control_input = control_input;
	    this.setup();
	  }

	  /**
	   * set up event bindings.
	   *
	   */
	  setup() {
	    const self = this;
	    const settings = self.settings;
	    const control_input = self.control_input;
	    const dropdown = self.dropdown;
	    const dropdown_content = self.dropdown_content;
	    const wrapper = self.wrapper;
	    const control = self.control;
	    const input = self.input;
	    const focus_node = self.focus_node;
	    const passive_event = {
	      passive: true
	    };
	    const listboxId = self.inputId + '-ts-dropdown';
	    setAttr(dropdown_content, {
	      id: listboxId
	    });
	    setAttr(focus_node, {
	      role: 'combobox',
	      'aria-haspopup': 'listbox',
	      'aria-expanded': 'false',
	      'aria-controls': listboxId
	    });
	    const control_id = getId(focus_node, self.inputId + '-ts-control');
	    const query = "label[for='" + escapeQuery(self.inputId) + "']";
	    const label = document.querySelector(query);
	    const label_click = self.focus.bind(self);
	    if (label) {
	      addEvent(label, 'click', label_click);
	      setAttr(label, {
	        for: control_id
	      });
	      const label_id = getId(label, self.inputId + '-ts-label');
	      setAttr(focus_node, {
	        'aria-labelledby': label_id
	      });
	      setAttr(dropdown_content, {
	        'aria-labelledby': label_id
	      });
	    }
	    wrapper.style.width = input.style.width;
	    if (self.plugins.names.length) {
	      const classes_plugins = 'plugin-' + self.plugins.names.join(' plugin-');
	      addClasses([wrapper, dropdown], classes_plugins);
	    }
	    if ((settings.maxItems === null || settings.maxItems > 1) && self.is_select_tag) {
	      setAttr(input, {
	        multiple: 'multiple'
	      });
	    }
	    if (settings.placeholder) {
	      setAttr(control_input, {
	        placeholder: settings.placeholder
	      });
	    }

	    // if splitOn was not passed in, construct it from the delimiter to allow pasting universally
	    if (!settings.splitOn && settings.delimiter) {
	      settings.splitOn = new RegExp('\\s*' + escape_regex(settings.delimiter) + '+\\s*');
	    }

	    // debounce user defined load() if loadThrottle > 0
	    // after initializePlugins() so plugins can create/modify user defined loaders
	    if (settings.load && settings.loadThrottle) {
	      settings.load = loadDebounce(settings.load, settings.loadThrottle);
	    }
	    addEvent(dropdown, 'mousemove', () => {
	      self.ignoreHover = false;
	    });
	    addEvent(dropdown, 'mouseenter', e => {
	      var target_match = parentMatch(e.target, '[data-selectable]', dropdown);
	      if (target_match) self.onOptionHover(e, target_match);
	    }, {
	      capture: true
	    });

	    // clicking on an option should select it
	    addEvent(dropdown, 'click', evt => {
	      const option = parentMatch(evt.target, '[data-selectable]');
	      if (option) {
	        self.onOptionSelect(evt, option);
	        preventDefault(evt, true);
	      }
	    });
	    addEvent(control, 'click', evt => {
	      var target_match = parentMatch(evt.target, '[data-ts-item]', control);
	      if (target_match && self.onItemSelect(evt, target_match)) {
	        preventDefault(evt, true);
	        return;
	      }

	      // retain focus (see control_input mousedown)
	      if (control_input.value != '') {
	        return;
	      }
	      self.onClick();
	      preventDefault(evt, true);
	    });

	    // keydown on focus_node for arrow_down/arrow_up
	    addEvent(focus_node, 'keydown', e => self.onKeyDown(e));

	    // keypress and input/keyup
	    addEvent(control_input, 'keypress', e => self.onKeyPress(e));
	    addEvent(control_input, 'input', e => self.onInput(e));
	    addEvent(focus_node, 'blur', e => self.onBlur(e));
	    addEvent(focus_node, 'focus', e => self.onFocus(e));
	    addEvent(control_input, 'paste', e => self.onPaste(e));
	    const doc_mousedown = evt => {
	      // blur if target is outside of this instance
	      // dropdown is not always inside wrapper
	      const target = evt.composedPath()[0];
	      if (!wrapper.contains(target) && !dropdown.contains(target)) {
	        if (self.isFocused) {
	          self.blur();
	        }
	        self.inputState();
	        return;
	      }

	      // retain focus by preventing native handling. if the
	      // event target is the input it should not be modified.
	      // otherwise, text selection within the input won't work.
	      // Fixes bug #212 which is no covered by tests
	      if (target == control_input && self.isOpen) {
	        evt.stopPropagation();

	        // clicking anywhere in the control should not blur the control_input (which would close the dropdown)
	      } else {
	        preventDefault(evt, true);
	      }
	    };
	    const win_scroll = () => {
	      if (self.isOpen) {
	        self.positionDropdown();
	      }
	    };
	    addEvent(document, 'mousedown', doc_mousedown);
	    addEvent(window, 'scroll', win_scroll, passive_event);
	    addEvent(window, 'resize', win_scroll, passive_event);
	    this._destroy = () => {
	      document.removeEventListener('mousedown', doc_mousedown);
	      window.removeEventListener('scroll', win_scroll);
	      window.removeEventListener('resize', win_scroll);
	      if (label) label.removeEventListener('click', label_click);
	    };

	    // store original html and tab index so that they can be
	    // restored when the destroy() method is called.
	    this.revertSettings = {
	      innerHTML: input.innerHTML,
	      tabIndex: input.tabIndex
	    };
	    input.tabIndex = -1;
	    input.insertAdjacentElement('afterend', self.wrapper);
	    self.sync(false);
	    settings.items = [];
	    delete settings.optgroups;
	    delete settings.options;
	    addEvent(input, 'invalid', () => {
	      if (self.isValid) {
	        self.isValid = false;
	        self.isInvalid = true;
	        self.refreshState();
	      }
	    });
	    self.updateOriginalInput();
	    self.refreshItems();
	    self.close(false);
	    self.inputState();
	    self.isSetup = true;
	    if (input.disabled) {
	      self.disable();
	    } else if (input.readOnly) {
	      self.setReadOnly(true);
	    } else {
	      self.enable(); //sets tabIndex
	    }

	    self.on('change', this.onChange);
	    addClasses(input, 'tomselected', 'ts-hidden-accessible');
	    self.trigger('initialize');

	    // preload options
	    if (settings.preload === true) {
	      self.preload();
	    }
	  }

	  /**
	   * Register options and optgroups
	   *
	   */
	  setupOptions(options = [], optgroups = []) {
	    // build options table
	    this.addOptions(options);

	    // build optgroup table
	    iterate$1(optgroups, optgroup => {
	      this.registerOptionGroup(optgroup);
	    });
	  }

	  /**
	   * Sets up default rendering functions.
	   */
	  setupTemplates() {
	    var self = this;
	    var field_label = self.settings.labelField;
	    var field_optgroup = self.settings.optgroupLabelField;
	    var templates = {
	      'optgroup': data => {
	        let optgroup = document.createElement('div');
	        optgroup.className = 'optgroup';
	        optgroup.appendChild(data.options);
	        return optgroup;
	      },
	      'optgroup_header': (data, escape) => {
	        return '<div class="optgroup-header">' + escape(data[field_optgroup]) + '</div>';
	      },
	      'option': (data, escape) => {
	        return '<div>' + escape(data[field_label]) + '</div>';
	      },
	      'item': (data, escape) => {
	        return '<div>' + escape(data[field_label]) + '</div>';
	      },
	      'option_create': (data, escape) => {
	        return '<div class="create">Add <strong>' + escape(data.input) + '</strong>&hellip;</div>';
	      },
	      'no_results': () => {
	        return '<div class="no-results">No results found</div>';
	      },
	      'loading': () => {
	        return '<div class="spinner"></div>';
	      },
	      'not_loading': () => {},
	      'dropdown': () => {
	        return '<div></div>';
	      }
	    };
	    self.settings.render = Object.assign({}, templates, self.settings.render);
	  }

	  /**
	   * Maps fired events to callbacks provided
	   * in the settings used when creating the control.
	   */
	  setupCallbacks() {
	    var key, fn;
	    var callbacks = {
	      'initialize': 'onInitialize',
	      'change': 'onChange',
	      'item_add': 'onItemAdd',
	      'item_remove': 'onItemRemove',
	      'item_select': 'onItemSelect',
	      'clear': 'onClear',
	      'option_add': 'onOptionAdd',
	      'option_remove': 'onOptionRemove',
	      'option_clear': 'onOptionClear',
	      'optgroup_add': 'onOptionGroupAdd',
	      'optgroup_remove': 'onOptionGroupRemove',
	      'optgroup_clear': 'onOptionGroupClear',
	      'dropdown_open': 'onDropdownOpen',
	      'dropdown_close': 'onDropdownClose',
	      'type': 'onType',
	      'load': 'onLoad',
	      'focus': 'onFocus',
	      'blur': 'onBlur'
	    };
	    for (key in callbacks) {
	      fn = this.settings[callbacks[key]];
	      if (fn) this.on(key, fn);
	    }
	  }

	  /**
	   * Sync the Tom Select instance with the original input or select
	   *
	   */
	  sync(get_settings = true) {
	    const self = this;
	    const settings = get_settings ? getSettings(self.input, {
	      delimiter: self.settings.delimiter
	    }) : self.settings;
	    self.setupOptions(settings.options, settings.optgroups);
	    self.setValue(settings.items || [], true); // silent prevents recursion

	    self.lastQuery = null; // so updated options will be displayed in dropdown
	  }

	  /**
	   * Triggered when the main control element
	   * has a click event.
	   *
	   */
	  onClick() {
	    var self = this;
	    if (self.activeItems.length > 0) {
	      self.clearActiveItems();
	      self.focus();
	      return;
	    }
	    if (self.isFocused && self.isOpen) {
	      self.blur();
	    } else {
	      self.focus();
	    }
	  }

	  /**
	   * @deprecated v1.7
	   *
	   */
	  onMouseDown() {}

	  /**
	   * Triggered when the value of the control has been changed.
	   * This should propagate the event to the original DOM
	   * input / select element.
	   */
	  onChange() {
	    triggerEvent(this.input, 'input');
	    triggerEvent(this.input, 'change');
	  }

	  /**
	   * Triggered on <input> paste.
	   *
	   */
	  onPaste(e) {
	    var self = this;
	    if (self.isInputHidden || self.isLocked) {
	      preventDefault(e);
	      return;
	    }

	    // If a regex or string is included, this will split the pasted
	    // input and create Items for each separate value
	    if (!self.settings.splitOn) {
	      return;
	    }

	    // Wait for pasted text to be recognized in value
	    setTimeout(() => {
	      var pastedText = self.inputValue();
	      if (!pastedText.match(self.settings.splitOn)) {
	        return;
	      }
	      var splitInput = pastedText.trim().split(self.settings.splitOn);
	      iterate$1(splitInput, piece => {
	        const hash = hash_key(piece);
	        if (hash) {
	          if (this.options[piece]) {
	            self.addItem(piece);
	          } else {
	            self.createItem(piece);
	          }
	        }
	      });
	    }, 0);
	  }

	  /**
	   * Triggered on <input> keypress.
	   *
	   */
	  onKeyPress(e) {
	    var self = this;
	    if (self.isLocked) {
	      preventDefault(e);
	      return;
	    }
	    var character = String.fromCharCode(e.keyCode || e.which);
	    if (self.settings.create && self.settings.mode === 'multi' && character === self.settings.delimiter) {
	      self.createItem();
	      preventDefault(e);
	      return;
	    }
	  }

	  /**
	   * Triggered on <input> keydown.
	   *
	   */
	  onKeyDown(e) {
	    var self = this;
	    self.ignoreHover = true;
	    if (self.isLocked) {
	      if (e.keyCode !== KEY_TAB) {
	        preventDefault(e);
	      }
	      return;
	    }
	    switch (e.keyCode) {
	      // ctrl+A: select all
	      case KEY_A:
	        if (isKeyDown(KEY_SHORTCUT, e)) {
	          if (self.control_input.value == '') {
	            preventDefault(e);
	            self.selectAll();
	            return;
	          }
	        }
	        break;

	      // esc: close dropdown
	      case KEY_ESC:
	        if (self.isOpen) {
	          preventDefault(e, true);
	          self.close();
	        }
	        self.clearActiveItems();
	        return;

	      // down: open dropdown or move selection down
	      case KEY_DOWN:
	        if (!self.isOpen && self.hasOptions) {
	          self.open();
	        } else if (self.activeOption) {
	          let next = self.getAdjacent(self.activeOption, 1);
	          if (next) self.setActiveOption(next);
	        }
	        preventDefault(e);
	        return;

	      // up: move selection up
	      case KEY_UP:
	        if (self.activeOption) {
	          let prev = self.getAdjacent(self.activeOption, -1);
	          if (prev) self.setActiveOption(prev);
	        }
	        preventDefault(e);
	        return;

	      // return: select active option
	      case KEY_RETURN:
	        if (self.canSelect(self.activeOption)) {
	          self.onOptionSelect(e, self.activeOption);
	          preventDefault(e);

	          // if the option_create=null, the dropdown might be closed
	        } else if (self.settings.create && self.createItem()) {
	          preventDefault(e);

	          // don't submit form when searching for a value
	        } else if (document.activeElement == self.control_input && self.isOpen) {
	          preventDefault(e);
	        }
	        return;

	      // left: modifiy item selection to the left
	      case KEY_LEFT:
	        self.advanceSelection(-1, e);
	        return;

	      // right: modifiy item selection to the right
	      case KEY_RIGHT:
	        self.advanceSelection(1, e);
	        return;

	      // tab: select active option and/or create item
	      case KEY_TAB:
	        if (self.settings.selectOnTab) {
	          if (self.canSelect(self.activeOption)) {
	            self.onOptionSelect(e, self.activeOption);

	            // prevent default [tab] behaviour of jump to the next field
	            // if select isFull, then the dropdown won't be open and [tab] will work normally
	            preventDefault(e);
	          }
	          if (self.settings.create && self.createItem()) {
	            preventDefault(e);
	          }
	        }
	        return;

	      // delete|backspace: delete items
	      case KEY_BACKSPACE:
	      case KEY_DELETE:
	        self.deleteSelection(e);
	        return;
	    }

	    // don't enter text in the control_input when active items are selected
	    if (self.isInputHidden && !isKeyDown(KEY_SHORTCUT, e)) {
	      preventDefault(e);
	    }
	  }

	  /**
	   * Triggered on <input> keyup.
	   *
	   */
	  onInput(e) {
	    if (this.isLocked) {
	      return;
	    }
	    const value = this.inputValue();
	    if (this.lastValue === value) return;
	    this.lastValue = value;
	    if (value == '') {
	      this._onInput();
	      return;
	    }
	    if (this.refreshTimeout) {
	      clearTimeout(this.refreshTimeout);
	    }
	    this.refreshTimeout = timeout(() => {
	      this.refreshTimeout = null;
	      this._onInput();
	    }, this.settings.refreshThrottle);
	  }
	  _onInput() {
	    const value = this.lastValue;
	    if (this.settings.shouldLoad.call(this, value)) {
	      this.load(value);
	    }
	    this.refreshOptions();
	    this.trigger('type', value);
	  }

	  /**
	   * Triggered when the user rolls over
	   * an option in the autocomplete dropdown menu.
	   *
	   */
	  onOptionHover(evt, option) {
	    if (this.ignoreHover) return;
	    this.setActiveOption(option, false);
	  }

	  /**
	   * Triggered on <input> focus.
	   *
	   */
	  onFocus(e) {
	    var self = this;
	    var wasFocused = self.isFocused;
	    if (self.isDisabled || self.isReadOnly) {
	      self.blur();
	      preventDefault(e);
	      return;
	    }
	    if (self.ignoreFocus) return;
	    self.isFocused = true;
	    if (self.settings.preload === 'focus') self.preload();
	    if (!wasFocused) self.trigger('focus');
	    if (!self.activeItems.length) {
	      self.inputState();
	      self.refreshOptions(!!self.settings.openOnFocus);
	    }
	    self.refreshState();
	  }

	  /**
	   * Triggered on <input> blur.
	   *
	   */
	  onBlur(e) {
	    if (document.hasFocus() === false) return;
	    var self = this;
	    if (!self.isFocused) return;
	    self.isFocused = false;
	    self.ignoreFocus = false;
	    var deactivate = () => {
	      self.close();
	      self.setActiveItem();
	      self.setCaret(self.items.length);
	      self.trigger('blur');
	    };
	    if (self.settings.create && self.settings.createOnBlur) {
	      self.createItem(null, deactivate);
	    } else {
	      deactivate();
	    }
	  }

	  /**
	   * Triggered when the user clicks on an option
	   * in the autocomplete dropdown menu.
	   *
	   */
	  onOptionSelect(evt, option) {
	    var value,
	      self = this;

	    // should not be possible to trigger a option under a disabled optgroup
	    if (option.parentElement && option.parentElement.matches('[data-disabled]')) {
	      return;
	    }
	    if (option.classList.contains('create')) {
	      self.createItem(null, () => {
	        if (self.settings.closeAfterSelect) {
	          self.close();
	        }
	      });
	    } else {
	      value = option.dataset.value;
	      if (typeof value !== 'undefined') {
	        self.lastQuery = null;
	        self.addItem(value);
	        if (self.settings.closeAfterSelect) {
	          self.close();
	        }
	        if (!self.settings.hideSelected && evt.type && /click/.test(evt.type)) {
	          self.setActiveOption(option);
	        }
	      }
	    }
	  }

	  /**
	   * Return true if the given option can be selected
	   *
	   */
	  canSelect(option) {
	    if (this.isOpen && option && this.dropdown_content.contains(option)) {
	      return true;
	    }
	    return false;
	  }

	  /**
	   * Triggered when the user clicks on an item
	   * that has been selected.
	   *
	   */
	  onItemSelect(evt, item) {
	    var self = this;
	    if (!self.isLocked && self.settings.mode === 'multi') {
	      preventDefault(evt);
	      self.setActiveItem(item, evt);
	      return true;
	    }
	    return false;
	  }

	  /**
	   * Determines whether or not to invoke
	   * the user-provided option provider / loader
	   *
	   * Note, there is a subtle difference between
	   * this.canLoad() and this.settings.shouldLoad();
	   *
	   *	- settings.shouldLoad() is a user-input validator.
	   *	When false is returned, the not_loading template
	   *	will be added to the dropdown
	   *
	   *	- canLoad() is lower level validator that checks
	   * 	the Tom Select instance. There is no inherent user
	   *	feedback when canLoad returns false
	   *
	   */
	  canLoad(value) {
	    if (!this.settings.load) return false;
	    if (this.loadedSearches.hasOwnProperty(value)) return false;
	    return true;
	  }

	  /**
	   * Invokes the user-provided option provider / loader.
	   *
	   */
	  load(value) {
	    const self = this;
	    if (!self.canLoad(value)) return;
	    addClasses(self.wrapper, self.settings.loadingClass);
	    self.loading++;
	    const callback = self.loadCallback.bind(self);
	    self.settings.load.call(self, value, callback);
	  }

	  /**
	   * Invoked by the user-provided option provider
	   *
	   */
	  loadCallback(options, optgroups) {
	    const self = this;
	    self.loading = Math.max(self.loading - 1, 0);
	    self.lastQuery = null;
	    self.clearActiveOption(); // when new results load, focus should be on first option
	    self.setupOptions(options, optgroups);
	    self.refreshOptions(self.isFocused && !self.isInputHidden);
	    if (!self.loading) {
	      removeClasses(self.wrapper, self.settings.loadingClass);
	    }
	    self.trigger('load', options, optgroups);
	  }
	  preload() {
	    var classList = this.wrapper.classList;
	    if (classList.contains('preloaded')) return;
	    classList.add('preloaded');
	    this.load('');
	  }

	  /**
	   * Sets the input field of the control to the specified value.
	   *
	   */
	  setTextboxValue(value = '') {
	    var input = this.control_input;
	    var changed = input.value !== value;
	    if (changed) {
	      input.value = value;
	      triggerEvent(input, 'update');
	      this.lastValue = value;
	    }
	  }

	  /**
	   * Returns the value of the control. If multiple items
	   * can be selected (e.g. <select multiple>), this returns
	   * an array. If only one item can be selected, this
	   * returns a string.
	   *
	   */
	  getValue() {
	    if (this.is_select_tag && this.input.hasAttribute('multiple')) {
	      return this.items;
	    }
	    return this.items.join(this.settings.delimiter);
	  }

	  /**
	   * Resets the selected items to the given value.
	   *
	   */
	  setValue(value, silent) {
	    var events = silent ? [] : ['change'];
	    debounce_events(this, events, () => {
	      this.clear(silent);
	      this.addItems(value, silent);
	    });
	  }

	  /**
	   * Resets the number of max items to the given value
	   *
	   */
	  setMaxItems(value) {
	    if (value === 0) value = null; //reset to unlimited items.
	    this.settings.maxItems = value;
	    this.refreshState();
	  }

	  /**
	   * Sets the selected item.
	   *
	   */
	  setActiveItem(item, e) {
	    var self = this;
	    var eventName;
	    var i, begin, end, swap;
	    var last;
	    if (self.settings.mode === 'single') return;

	    // clear the active selection
	    if (!item) {
	      self.clearActiveItems();
	      if (self.isFocused) {
	        self.inputState();
	      }
	      return;
	    }

	    // modify selection
	    eventName = e && e.type.toLowerCase();
	    if (eventName === 'click' && isKeyDown('shiftKey', e) && self.activeItems.length) {
	      last = self.getLastActive();
	      begin = Array.prototype.indexOf.call(self.control.children, last);
	      end = Array.prototype.indexOf.call(self.control.children, item);
	      if (begin > end) {
	        swap = begin;
	        begin = end;
	        end = swap;
	      }
	      for (i = begin; i <= end; i++) {
	        item = self.control.children[i];
	        if (self.activeItems.indexOf(item) === -1) {
	          self.setActiveItemClass(item);
	        }
	      }
	      preventDefault(e);
	    } else if (eventName === 'click' && isKeyDown(KEY_SHORTCUT, e) || eventName === 'keydown' && isKeyDown('shiftKey', e)) {
	      if (item.classList.contains('active')) {
	        self.removeActiveItem(item);
	      } else {
	        self.setActiveItemClass(item);
	      }
	    } else {
	      self.clearActiveItems();
	      self.setActiveItemClass(item);
	    }

	    // ensure control has focus
	    self.inputState();
	    if (!self.isFocused) {
	      self.focus();
	    }
	  }

	  /**
	   * Set the active and last-active classes
	   *
	   */
	  setActiveItemClass(item) {
	    const self = this;
	    const last_active = self.control.querySelector('.last-active');
	    if (last_active) removeClasses(last_active, 'last-active');
	    addClasses(item, 'active last-active');
	    self.trigger('item_select', item);
	    if (self.activeItems.indexOf(item) == -1) {
	      self.activeItems.push(item);
	    }
	  }

	  /**
	   * Remove active item
	   *
	   */
	  removeActiveItem(item) {
	    var idx = this.activeItems.indexOf(item);
	    this.activeItems.splice(idx, 1);
	    removeClasses(item, 'active');
	  }

	  /**
	   * Clears all the active items
	   *
	   */
	  clearActiveItems() {
	    removeClasses(this.activeItems, 'active');
	    this.activeItems = [];
	  }

	  /**
	   * Sets the selected item in the dropdown menu
	   * of available options.
	   *
	   */
	  setActiveOption(option, scroll = true) {
	    if (option === this.activeOption) {
	      return;
	    }
	    this.clearActiveOption();
	    if (!option) return;
	    this.activeOption = option;
	    setAttr(this.focus_node, {
	      'aria-activedescendant': option.getAttribute('id')
	    });
	    setAttr(option, {
	      'aria-selected': 'true'
	    });
	    addClasses(option, 'active');
	    if (scroll) this.scrollToOption(option);
	  }

	  /**
	   * Sets the dropdown_content scrollTop to display the option
	   *
	   */
	  scrollToOption(option, behavior) {
	    if (!option) return;
	    const content = this.dropdown_content;
	    const height_menu = content.clientHeight;
	    const scrollTop = content.scrollTop || 0;
	    const height_item = option.offsetHeight;
	    const y = option.getBoundingClientRect().top - content.getBoundingClientRect().top + scrollTop;
	    if (y + height_item > height_menu + scrollTop) {
	      this.scroll(y - height_menu + height_item, behavior);
	    } else if (y < scrollTop) {
	      this.scroll(y, behavior);
	    }
	  }

	  /**
	   * Scroll the dropdown to the given position
	   *
	   */
	  scroll(scrollTop, behavior) {
	    const content = this.dropdown_content;
	    if (behavior) {
	      content.style.scrollBehavior = behavior;
	    }
	    content.scrollTop = scrollTop;
	    content.style.scrollBehavior = '';
	  }

	  /**
	   * Clears the active option
	   *
	   */
	  clearActiveOption() {
	    if (this.activeOption) {
	      removeClasses(this.activeOption, 'active');
	      setAttr(this.activeOption, {
	        'aria-selected': null
	      });
	    }
	    this.activeOption = null;
	    setAttr(this.focus_node, {
	      'aria-activedescendant': null
	    });
	  }

	  /**
	   * Selects all items (CTRL + A).
	   */
	  selectAll() {
	    const self = this;
	    if (self.settings.mode === 'single') return;
	    const activeItems = self.controlChildren();
	    if (!activeItems.length) return;
	    self.inputState();
	    self.close();
	    self.activeItems = activeItems;
	    iterate$1(activeItems, item => {
	      self.setActiveItemClass(item);
	    });
	  }

	  /**
	   * Determines if the control_input should be in a hidden or visible state
	   *
	   */
	  inputState() {
	    var self = this;
	    if (!self.control.contains(self.control_input)) return;
	    setAttr(self.control_input, {
	      placeholder: self.settings.placeholder
	    });
	    if (self.activeItems.length > 0 || !self.isFocused && self.settings.hidePlaceholder && self.items.length > 0) {
	      self.setTextboxValue();
	      self.isInputHidden = true;
	    } else {
	      if (self.settings.hidePlaceholder && self.items.length > 0) {
	        setAttr(self.control_input, {
	          placeholder: ''
	        });
	      }
	      self.isInputHidden = false;
	    }
	    self.wrapper.classList.toggle('input-hidden', self.isInputHidden);
	  }

	  /**
	   * Get the input value
	   */
	  inputValue() {
	    return this.control_input.value.trim();
	  }

	  /**
	   * Gives the control focus.
	   */
	  focus() {
	    var self = this;
	    if (self.isDisabled || self.isReadOnly) return;
	    self.ignoreFocus = true;
	    if (self.control_input.offsetWidth) {
	      self.control_input.focus();
	    } else {
	      self.focus_node.focus();
	    }
	    setTimeout(() => {
	      self.ignoreFocus = false;
	      self.onFocus();
	    }, 0);
	  }

	  /**
	   * Forces the control out of focus.
	   *
	   */
	  blur() {
	    this.focus_node.blur();
	    this.onBlur();
	  }

	  /**
	   * Returns a function that scores an object
	   * to show how good of a match it is to the
	   * provided query.
	   *
	   * @return {function}
	   */
	  getScoreFunction(query) {
	    return this.sifter.getScoreFunction(query, this.getSearchOptions());
	  }

	  /**
	   * Returns search options for sifter (the system
	   * for scoring and sorting results).
	   *
	   * @see https://github.com/orchidjs/sifter.js
	   * @return {object}
	   */
	  getSearchOptions() {
	    var settings = this.settings;
	    var sort = settings.sortField;
	    if (typeof settings.sortField === 'string') {
	      sort = [{
	        field: settings.sortField
	      }];
	    }
	    return {
	      fields: settings.searchField,
	      conjunction: settings.searchConjunction,
	      sort: sort,
	      nesting: settings.nesting
	    };
	  }

	  /**
	   * Searches through available options and returns
	   * a sorted array of matches.
	   *
	   */
	  search(query) {
	    var result, calculateScore;
	    var self = this;
	    var options = this.getSearchOptions();

	    // validate user-provided result scoring function
	    if (self.settings.score) {
	      calculateScore = self.settings.score.call(self, query);
	      if (typeof calculateScore !== 'function') {
	        throw new Error('Tom Select "score" setting must be a function that returns a function');
	      }
	    }

	    // perform search
	    if (query !== self.lastQuery) {
	      self.lastQuery = query;
	      result = self.sifter.search(query, Object.assign(options, {
	        score: calculateScore
	      }));
	      self.currentResults = result;
	    } else {
	      result = Object.assign({}, self.currentResults);
	    }

	    // filter out selected items
	    if (self.settings.hideSelected) {
	      result.items = result.items.filter(item => {
	        let hashed = hash_key(item.id);
	        return !(hashed && self.items.indexOf(hashed) !== -1);
	      });
	    }
	    return result;
	  }

	  /**
	   * Refreshes the list of available options shown
	   * in the autocomplete dropdown menu.
	   *
	   */
	  refreshOptions(triggerDropdown = true) {
	    var i, j, k, n, optgroup, optgroups, html, has_create_option, active_group;
	    var create;
	    const groups = {};
	    const groups_order = [];
	    var self = this;
	    var query = self.inputValue();
	    const same_query = query === self.lastQuery || query == '' && self.lastQuery == null;
	    var results = self.search(query);
	    var active_option = null;
	    var show_dropdown = self.settings.shouldOpen || false;
	    var dropdown_content = self.dropdown_content;
	    if (same_query) {
	      active_option = self.activeOption;
	      if (active_option) {
	        active_group = active_option.closest('[data-group]');
	      }
	    }

	    // build markup
	    n = results.items.length;
	    if (typeof self.settings.maxOptions === 'number') {
	      n = Math.min(n, self.settings.maxOptions);
	    }
	    if (n > 0) {
	      show_dropdown = true;
	    }

	    // get fragment for group and the position of the group in group_order
	    const getGroupFragment = (optgroup, order) => {
	      let group_order_i = groups[optgroup];
	      if (group_order_i !== undefined) {
	        let order_group = groups_order[group_order_i];
	        if (order_group !== undefined) {
	          return [group_order_i, order_group.fragment];
	        }
	      }
	      let group_fragment = document.createDocumentFragment();
	      group_order_i = groups_order.length;
	      groups_order.push({
	        fragment: group_fragment,
	        order,
	        optgroup
	      });
	      return [group_order_i, group_fragment];
	    };

	    // render and group available options individually
	    for (i = 0; i < n; i++) {
	      // get option dom element
	      let item = results.items[i];
	      if (!item) continue;
	      let opt_value = item.id;
	      let option = self.options[opt_value];
	      if (option === undefined) continue;
	      let opt_hash = get_hash(opt_value);
	      let option_el = self.getOption(opt_hash, true);

	      // toggle 'selected' class
	      if (!self.settings.hideSelected) {
	        option_el.classList.toggle('selected', self.items.includes(opt_hash));
	      }
	      optgroup = option[self.settings.optgroupField] || '';
	      optgroups = Array.isArray(optgroup) ? optgroup : [optgroup];
	      for (j = 0, k = optgroups && optgroups.length; j < k; j++) {
	        optgroup = optgroups[j];
	        let order = option.$order;
	        let self_optgroup = self.optgroups[optgroup];
	        if (self_optgroup === undefined) {
	          optgroup = '';
	        } else {
	          order = self_optgroup.$order;
	        }
	        const [group_order_i, group_fragment] = getGroupFragment(optgroup, order);

	        // nodes can only have one parent, so if the option is in mutple groups, we need a clone
	        if (j > 0) {
	          option_el = option_el.cloneNode(true);
	          setAttr(option_el, {
	            id: option.$id + '-clone-' + j,
	            'aria-selected': null
	          });
	          option_el.classList.add('ts-cloned');
	          removeClasses(option_el, 'active');

	          // make sure we keep the activeOption in the same group
	          if (self.activeOption && self.activeOption.dataset.value == opt_value) {
	            if (active_group && active_group.dataset.group === optgroup.toString()) {
	              active_option = option_el;
	            }
	          }
	        }
	        group_fragment.appendChild(option_el);
	        if (optgroup != '') {
	          groups[optgroup] = group_order_i;
	        }
	      }
	    }

	    // sort optgroups
	    if (self.settings.lockOptgroupOrder) {
	      groups_order.sort((a, b) => {
	        return a.order - b.order;
	      });
	    }

	    // render optgroup headers & join groups
	    html = document.createDocumentFragment();
	    iterate$1(groups_order, group_order => {
	      let group_fragment = group_order.fragment;
	      let optgroup = group_order.optgroup;
	      if (!group_fragment || !group_fragment.children.length) return;
	      let group_heading = self.optgroups[optgroup];
	      if (group_heading !== undefined) {
	        let group_options = document.createDocumentFragment();
	        let header = self.render('optgroup_header', group_heading);
	        append(group_options, header);
	        append(group_options, group_fragment);
	        let group_html = self.render('optgroup', {
	          group: group_heading,
	          options: group_options
	        });
	        append(html, group_html);
	      } else {
	        append(html, group_fragment);
	      }
	    });
	    dropdown_content.innerHTML = '';
	    append(dropdown_content, html);

	    // highlight matching terms inline
	    if (self.settings.highlight) {
	      removeHighlight(dropdown_content);
	      if (results.query.length && results.tokens.length) {
	        iterate$1(results.tokens, tok => {
	          highlight(dropdown_content, tok.regex);
	        });
	      }
	    }

	    // helper method for adding templates to dropdown
	    var add_template = template => {
	      let content = self.render(template, {
	        input: query
	      });
	      if (content) {
	        show_dropdown = true;
	        dropdown_content.insertBefore(content, dropdown_content.firstChild);
	      }
	      return content;
	    };

	    // add loading message
	    if (self.loading) {
	      add_template('loading');

	      // invalid query
	    } else if (!self.settings.shouldLoad.call(self, query)) {
	      add_template('not_loading');

	      // add no_results message
	    } else if (results.items.length === 0) {
	      add_template('no_results');
	    }

	    // add create option
	    has_create_option = self.canCreate(query);
	    if (has_create_option) {
	      create = add_template('option_create');
	    }

	    // activate
	    self.hasOptions = results.items.length > 0 || has_create_option;
	    if (show_dropdown) {
	      if (results.items.length > 0) {
	        if (!active_option && self.settings.mode === 'single' && self.items[0] != undefined) {
	          active_option = self.getOption(self.items[0]);
	        }
	        if (!dropdown_content.contains(active_option)) {
	          let active_index = 0;
	          if (create && !self.settings.addPrecedence) {
	            active_index = 1;
	          }
	          active_option = self.selectable()[active_index];
	        }
	      } else if (create) {
	        active_option = create;
	      }
	      if (triggerDropdown && !self.isOpen) {
	        self.open();
	        self.scrollToOption(active_option, 'auto');
	      }
	      self.setActiveOption(active_option);
	    } else {
	      self.clearActiveOption();
	      if (triggerDropdown && self.isOpen) {
	        self.close(false); // if create_option=null, we want the dropdown to close but not reset the textbox value
	      }
	    }
	  }

	  /**
	   * Return list of selectable options
	   *
	   */
	  selectable() {
	    return this.dropdown_content.querySelectorAll('[data-selectable]');
	  }

	  /**
	   * Adds an available option. If it already exists,
	   * nothing will happen. Note: this does not refresh
	   * the options list dropdown (use `refreshOptions`
	   * for that).
	   *
	   * Usage:
	   *
	   *   this.addOption(data)
	   *
	   */
	  addOption(data, user_created = false) {
	    const self = this;

	    // @deprecated 1.7.7
	    // use addOptions( array, user_created ) for adding multiple options
	    if (Array.isArray(data)) {
	      self.addOptions(data, user_created);
	      return false;
	    }
	    const key = hash_key(data[self.settings.valueField]);
	    if (key === null || self.options.hasOwnProperty(key)) {
	      return false;
	    }
	    data.$order = data.$order || ++self.order;
	    data.$id = self.inputId + '-opt-' + data.$order;
	    self.options[key] = data;
	    self.lastQuery = null;
	    if (user_created) {
	      self.userOptions[key] = user_created;
	      self.trigger('option_add', key, data);
	    }
	    return key;
	  }

	  /**
	   * Add multiple options
	   *
	   */
	  addOptions(data, user_created = false) {
	    iterate$1(data, dat => {
	      this.addOption(dat, user_created);
	    });
	  }

	  /**
	   * @deprecated 1.7.7
	   */
	  registerOption(data) {
	    return this.addOption(data);
	  }

	  /**
	   * Registers an option group to the pool of option groups.
	   *
	   * @return {boolean|string}
	   */
	  registerOptionGroup(data) {
	    var key = hash_key(data[this.settings.optgroupValueField]);
	    if (key === null) return false;
	    data.$order = data.$order || ++this.order;
	    this.optgroups[key] = data;
	    return key;
	  }

	  /**
	   * Registers a new optgroup for options
	   * to be bucketed into.
	   *
	   */
	  addOptionGroup(id, data) {
	    var hashed_id;
	    data[this.settings.optgroupValueField] = id;
	    if (hashed_id = this.registerOptionGroup(data)) {
	      this.trigger('optgroup_add', hashed_id, data);
	    }
	  }

	  /**
	   * Removes an existing option group.
	   *
	   */
	  removeOptionGroup(id) {
	    if (this.optgroups.hasOwnProperty(id)) {
	      delete this.optgroups[id];
	      this.clearCache();
	      this.trigger('optgroup_remove', id);
	    }
	  }

	  /**
	   * Clears all existing option groups.
	   */
	  clearOptionGroups() {
	    this.optgroups = {};
	    this.clearCache();
	    this.trigger('optgroup_clear');
	  }

	  /**
	   * Updates an option available for selection. If
	   * it is visible in the selected items or options
	   * dropdown, it will be re-rendered automatically.
	   *
	   */
	  updateOption(value, data) {
	    const self = this;
	    var item_new;
	    var index_item;
	    const value_old = hash_key(value);
	    const value_new = hash_key(data[self.settings.valueField]);

	    // sanity checks
	    if (value_old === null) return;
	    const data_old = self.options[value_old];
	    if (data_old == undefined) return;
	    if (typeof value_new !== 'string') throw new Error('Value must be set in option data');
	    const option = self.getOption(value_old);
	    const item = self.getItem(value_old);
	    data.$order = data.$order || data_old.$order;
	    delete self.options[value_old];

	    // invalidate render cache
	    // don't remove existing node yet, we'll remove it after replacing it
	    self.uncacheValue(value_new);
	    self.options[value_new] = data;

	    // update the option if it's in the dropdown
	    if (option) {
	      if (self.dropdown_content.contains(option)) {
	        const option_new = self._render('option', data);
	        replaceNode(option, option_new);
	        if (self.activeOption === option) {
	          self.setActiveOption(option_new);
	        }
	      }
	      option.remove();
	    }

	    // update the item if we have one
	    if (item) {
	      index_item = self.items.indexOf(value_old);
	      if (index_item !== -1) {
	        self.items.splice(index_item, 1, value_new);
	      }
	      item_new = self._render('item', data);
	      if (item.classList.contains('active')) addClasses(item_new, 'active');
	      replaceNode(item, item_new);
	    }

	    // invalidate last query because we might have updated the sortField
	    self.lastQuery = null;
	  }

	  /**
	   * Removes a single option.
	   *
	   */
	  removeOption(value, silent) {
	    const self = this;
	    value = get_hash(value);
	    self.uncacheValue(value);
	    delete self.userOptions[value];
	    delete self.options[value];
	    self.lastQuery = null;
	    self.trigger('option_remove', value);
	    self.removeItem(value, silent);
	  }

	  /**
	   * Clears all options.
	   */
	  clearOptions(filter) {
	    const boundFilter = (filter || this.clearFilter).bind(this);
	    this.loadedSearches = {};
	    this.userOptions = {};
	    this.clearCache();
	    const selected = {};
	    iterate$1(this.options, (option, key) => {
	      if (boundFilter(option, key)) {
	        selected[key] = option;
	      }
	    });
	    this.options = this.sifter.items = selected;
	    this.lastQuery = null;
	    this.trigger('option_clear');
	  }

	  /**
	   * Used by clearOptions() to decide whether or not an option should be removed
	   * Return true to keep an option, false to remove
	   *
	   */
	  clearFilter(option, value) {
	    if (this.items.indexOf(value) >= 0) {
	      return true;
	    }
	    return false;
	  }

	  /**
	   * Returns the dom element of the option
	   * matching the given value.
	   *
	   */
	  getOption(value, create = false) {
	    const hashed = hash_key(value);
	    if (hashed === null) return null;
	    const option = this.options[hashed];
	    if (option != undefined) {
	      if (option.$div) {
	        return option.$div;
	      }
	      if (create) {
	        return this._render('option', option);
	      }
	    }
	    return null;
	  }

	  /**
	   * Returns the dom element of the next or previous dom element of the same type
	   * Note: adjacent options may not be adjacent DOM elements (optgroups)
	   *
	   */
	  getAdjacent(option, direction, type = 'option') {
	    var self = this,
	      all;
	    if (!option) {
	      return null;
	    }
	    if (type == 'item') {
	      all = self.controlChildren();
	    } else {
	      all = self.dropdown_content.querySelectorAll('[data-selectable]');
	    }
	    for (let i = 0; i < all.length; i++) {
	      if (all[i] != option) {
	        continue;
	      }
	      if (direction > 0) {
	        return all[i + 1];
	      }
	      return all[i - 1];
	    }
	    return null;
	  }

	  /**
	   * Returns the dom element of the item
	   * matching the given value.
	   *
	   */
	  getItem(item) {
	    if (typeof item == 'object') {
	      return item;
	    }
	    var value = hash_key(item);
	    return value !== null ? this.control.querySelector(`[data-value="${addSlashes(value)}"]`) : null;
	  }

	  /**
	   * "Selects" multiple items at once. Adds them to the list
	   * at the current caret position.
	   *
	   */
	  addItems(values, silent) {
	    var self = this;
	    var items = Array.isArray(values) ? values : [values];
	    items = items.filter(x => self.items.indexOf(x) === -1);
	    const last_item = items[items.length - 1];
	    items.forEach(item => {
	      self.isPending = item !== last_item;
	      self.addItem(item, silent);
	    });
	  }

	  /**
	   * "Selects" an item. Adds it to the list
	   * at the current caret position.
	   *
	   */
	  addItem(value, silent) {
	    var events = silent ? [] : ['change', 'dropdown_close'];
	    debounce_events(this, events, () => {
	      var item, wasFull;
	      const self = this;
	      const inputMode = self.settings.mode;
	      const hashed = hash_key(value);
	      if (hashed && self.items.indexOf(hashed) !== -1) {
	        if (inputMode === 'single') {
	          self.close();
	        }
	        if (inputMode === 'single' || !self.settings.duplicates) {
	          return;
	        }
	      }
	      if (hashed === null || !self.options.hasOwnProperty(hashed)) return;
	      if (inputMode === 'single') self.clear(silent);
	      if (inputMode === 'multi' && self.isFull()) return;
	      item = self._render('item', self.options[hashed]);
	      if (self.control.contains(item)) {
	        // duplicates
	        item = item.cloneNode(true);
	      }
	      wasFull = self.isFull();
	      self.items.splice(self.caretPos, 0, hashed);
	      self.insertAtCaret(item);
	      if (self.isSetup) {
	        // update menu / remove the option (if this is not one item being added as part of series)
	        if (!self.isPending && self.settings.hideSelected) {
	          let option = self.getOption(hashed);
	          let next = self.getAdjacent(option, 1);
	          if (next) {
	            self.setActiveOption(next);
	          }
	        }

	        // refreshOptions after setActiveOption(),
	        // otherwise setActiveOption() will be called by refreshOptions() with the wrong value
	        if (!self.isPending && !self.settings.closeAfterSelect) {
	          self.refreshOptions(self.isFocused && inputMode !== 'single');
	        }

	        // hide the menu if the maximum number of items have been selected or no options are left
	        if (self.settings.closeAfterSelect != false && self.isFull()) {
	          self.close();
	        } else if (!self.isPending) {
	          self.positionDropdown();
	        }
	        self.trigger('item_add', hashed, item);
	        if (!self.isPending) {
	          self.updateOriginalInput({
	            silent: silent
	          });
	        }
	      }
	      if (!self.isPending || !wasFull && self.isFull()) {
	        self.inputState();
	        self.refreshState();
	      }
	    });
	  }

	  /**
	   * Removes the selected item matching
	   * the provided value.
	   *
	   */
	  removeItem(item = null, silent) {
	    const self = this;
	    item = self.getItem(item);
	    if (!item) return;
	    var i, idx;
	    const value = item.dataset.value;
	    i = nodeIndex(item);
	    item.remove();
	    if (item.classList.contains('active')) {
	      idx = self.activeItems.indexOf(item);
	      self.activeItems.splice(idx, 1);
	      removeClasses(item, 'active');
	    }
	    self.items.splice(i, 1);
	    self.lastQuery = null;
	    if (!self.settings.persist && self.userOptions.hasOwnProperty(value)) {
	      self.removeOption(value, silent);
	    }
	    if (i < self.caretPos) {
	      self.setCaret(self.caretPos - 1);
	    }
	    self.updateOriginalInput({
	      silent: silent
	    });
	    self.refreshState();
	    self.positionDropdown();
	    self.trigger('item_remove', value, item);
	  }

	  /**
	   * Invokes the `create` method provided in the
	   * TomSelect options that should provide the data
	   * for the new item, given the user input.
	   *
	   * Once this completes, it will be added
	   * to the item list.
	   *
	   */
	  createItem(input = null, callback = () => {}) {
	    // triggerDropdown parameter @deprecated 2.1.1
	    if (arguments.length === 3) {
	      callback = arguments[2];
	    }
	    if (typeof callback != 'function') {
	      callback = () => {};
	    }
	    var self = this;
	    var caret = self.caretPos;
	    var output;
	    input = input || self.inputValue();
	    if (!self.canCreate(input)) {
	      callback();
	      return false;
	    }
	    self.lock();
	    var created = false;
	    var create = data => {
	      self.unlock();
	      if (!data || typeof data !== 'object') return callback();
	      var value = hash_key(data[self.settings.valueField]);
	      if (typeof value !== 'string') {
	        return callback();
	      }
	      self.setTextboxValue();
	      self.addOption(data, true);
	      self.setCaret(caret);
	      self.addItem(value);
	      callback(data);
	      created = true;
	    };
	    if (typeof self.settings.create === 'function') {
	      output = self.settings.create.call(this, input, create);
	    } else {
	      output = {
	        [self.settings.labelField]: input,
	        [self.settings.valueField]: input
	      };
	    }
	    if (!created) {
	      create(output);
	    }
	    return true;
	  }

	  /**
	   * Re-renders the selected item lists.
	   */
	  refreshItems() {
	    var self = this;
	    self.lastQuery = null;
	    if (self.isSetup) {
	      self.addItems(self.items);
	    }
	    self.updateOriginalInput();
	    self.refreshState();
	  }

	  /**
	   * Updates all state-dependent attributes
	   * and CSS classes.
	   */
	  refreshState() {
	    const self = this;
	    self.refreshValidityState();
	    const isFull = self.isFull();
	    const isLocked = self.isLocked;
	    self.wrapper.classList.toggle('rtl', self.rtl);
	    const wrap_classList = self.wrapper.classList;
	    wrap_classList.toggle('focus', self.isFocused);
	    wrap_classList.toggle('disabled', self.isDisabled);
	    wrap_classList.toggle('readonly', self.isReadOnly);
	    wrap_classList.toggle('required', self.isRequired);
	    wrap_classList.toggle('invalid', !self.isValid);
	    wrap_classList.toggle('locked', isLocked);
	    wrap_classList.toggle('full', isFull);
	    wrap_classList.toggle('input-active', self.isFocused && !self.isInputHidden);
	    wrap_classList.toggle('dropdown-active', self.isOpen);
	    wrap_classList.toggle('has-options', isEmptyObject(self.options));
	    wrap_classList.toggle('has-items', self.items.length > 0);
	  }

	  /**
	   * Update the `required` attribute of both input and control input.
	   *
	   * The `required` property needs to be activated on the control input
	   * for the error to be displayed at the right place. `required` also
	   * needs to be temporarily deactivated on the input since the input is
	   * hidden and can't show errors.
	   */
	  refreshValidityState() {
	    var self = this;
	    if (!self.input.validity) {
	      return;
	    }
	    self.isValid = self.input.validity.valid;
	    self.isInvalid = !self.isValid;
	  }

	  /**
	   * Determines whether or not more items can be added
	   * to the control without exceeding the user-defined maximum.
	   *
	   * @returns {boolean}
	   */
	  isFull() {
	    return this.settings.maxItems !== null && this.items.length >= this.settings.maxItems;
	  }

	  /**
	   * Refreshes the original <select> or <input>
	   * element to reflect the current state.
	   *
	   */
	  updateOriginalInput(opts = {}) {
	    const self = this;
	    var option, label;
	    const empty_option = self.input.querySelector('option[value=""]');
	    if (self.is_select_tag) {
	      const selected = [];
	      const has_selected = self.input.querySelectorAll('option:checked').length;
	      function AddSelected(option_el, value, label) {
	        if (!option_el) {
	          option_el = getDom('<option value="' + escape_html(value) + '">' + escape_html(label) + '</option>');
	        }

	        // don't move empty option from top of list
	        // fixes bug in firefox https://bugzilla.mozilla.org/show_bug.cgi?id=1725293
	        if (option_el != empty_option) {
	          self.input.append(option_el);
	        }
	        selected.push(option_el);

	        // marking empty option as selected can break validation
	        // fixes https://github.com/orchidjs/tom-select/issues/303
	        if (option_el != empty_option || has_selected > 0) {
	          option_el.selected = true;
	        }
	        return option_el;
	      }

	      // unselect all selected options
	      self.input.querySelectorAll('option:checked').forEach(option_el => {
	        option_el.selected = false;
	      });

	      // nothing selected?
	      if (self.items.length == 0 && self.settings.mode == 'single') {
	        AddSelected(empty_option, "", "");

	        // order selected <option> tags for values in self.items
	      } else {
	        self.items.forEach(value => {
	          option = self.options[value];
	          label = option[self.settings.labelField] || '';
	          if (selected.includes(option.$option)) {
	            const reuse_opt = self.input.querySelector(`option[value="${addSlashes(value)}"]:not(:checked)`);
	            AddSelected(reuse_opt, value, label);
	          } else {
	            option.$option = AddSelected(option.$option, value, label);
	          }
	        });
	      }
	    } else {
	      self.input.value = self.getValue();
	    }
	    if (self.isSetup) {
	      if (!opts.silent) {
	        self.trigger('change', self.getValue());
	      }
	    }
	  }

	  /**
	   * Shows the autocomplete dropdown containing
	   * the available options.
	   */
	  open() {
	    var self = this;
	    if (self.isLocked || self.isOpen || self.settings.mode === 'multi' && self.isFull()) return;
	    self.isOpen = true;
	    setAttr(self.focus_node, {
	      'aria-expanded': 'true'
	    });
	    self.refreshState();
	    applyCSS(self.dropdown, {
	      visibility: 'hidden',
	      display: 'block'
	    });
	    self.positionDropdown();
	    applyCSS(self.dropdown, {
	      visibility: 'visible',
	      display: 'block'
	    });
	    self.focus();
	    self.trigger('dropdown_open', self.dropdown);
	  }

	  /**
	   * Closes the autocomplete dropdown menu.
	   */
	  close(setTextboxValue = true) {
	    var self = this;
	    var trigger = self.isOpen;
	    if (setTextboxValue) {
	      // before blur() to prevent form onchange event
	      self.setTextboxValue();
	      if (self.settings.mode === 'single' && self.items.length) {
	        self.inputState();
	      }
	    }
	    self.isOpen = false;
	    setAttr(self.focus_node, {
	      'aria-expanded': 'false'
	    });
	    applyCSS(self.dropdown, {
	      display: 'none'
	    });
	    if (self.settings.hideSelected) {
	      self.clearActiveOption();
	    }
	    self.refreshState();
	    if (trigger) self.trigger('dropdown_close', self.dropdown);
	  }

	  /**
	   * Calculates and applies the appropriate
	   * position of the dropdown if dropdownParent = 'body'.
	   * Otherwise, position is determined by css
	   */
	  positionDropdown() {
	    if (this.settings.dropdownParent !== 'body') {
	      return;
	    }
	    var context = this.control;
	    var rect = context.getBoundingClientRect();
	    var top = context.offsetHeight + rect.top + window.scrollY;
	    var left = rect.left + window.scrollX;
	    applyCSS(this.dropdown, {
	      width: rect.width + 'px',
	      top: top + 'px',
	      left: left + 'px'
	    });
	  }

	  /**
	   * Resets / clears all selected items
	   * from the control.
	   *
	   */
	  clear(silent) {
	    var self = this;
	    if (!self.items.length) return;
	    var items = self.controlChildren();
	    iterate$1(items, item => {
	      self.removeItem(item, true);
	    });
	    self.inputState();
	    if (!silent) self.updateOriginalInput();
	    self.trigger('clear');
	  }

	  /**
	   * A helper method for inserting an element
	   * at the current caret position.
	   *
	   */
	  insertAtCaret(el) {
	    const self = this;
	    const caret = self.caretPos;
	    const target = self.control;
	    target.insertBefore(el, target.children[caret] || null);
	    self.setCaret(caret + 1);
	  }

	  /**
	   * Removes the current selected item(s).
	   *
	   */
	  deleteSelection(e) {
	    var direction, selection, caret, tail;
	    var self = this;
	    direction = e && e.keyCode === KEY_BACKSPACE ? -1 : 1;
	    selection = getSelection(self.control_input);

	    // determine items that will be removed
	    const rm_items = [];
	    if (self.activeItems.length) {
	      tail = getTail(self.activeItems, direction);
	      caret = nodeIndex(tail);
	      if (direction > 0) {
	        caret++;
	      }
	      iterate$1(self.activeItems, item => rm_items.push(item));
	    } else if ((self.isFocused || self.settings.mode === 'single') && self.items.length) {
	      const items = self.controlChildren();
	      let rm_item;
	      if (direction < 0 && selection.start === 0 && selection.length === 0) {
	        rm_item = items[self.caretPos - 1];
	      } else if (direction > 0 && selection.start === self.inputValue().length) {
	        rm_item = items[self.caretPos];
	      }
	      if (rm_item !== undefined) {
	        rm_items.push(rm_item);
	      }
	    }
	    if (!self.shouldDelete(rm_items, e)) {
	      return false;
	    }
	    preventDefault(e, true);

	    // perform removal
	    if (typeof caret !== 'undefined') {
	      self.setCaret(caret);
	    }
	    while (rm_items.length) {
	      self.removeItem(rm_items.pop());
	    }
	    self.inputState();
	    self.positionDropdown();
	    self.refreshOptions(false);
	    return true;
	  }

	  /**
	   * Return true if the items should be deleted
	   */
	  shouldDelete(items, evt) {
	    const values = items.map(item => item.dataset.value);

	    // allow the callback to abort
	    if (!values.length || typeof this.settings.onDelete === 'function' && this.settings.onDelete(values, evt) === false) {
	      return false;
	    }
	    return true;
	  }

	  /**
	   * Selects the previous / next item (depending on the `direction` argument).
	   *
	   * > 0 - right
	   * < 0 - left
	   *
	   */
	  advanceSelection(direction, e) {
	    var last_active,
	      adjacent,
	      self = this;
	    if (self.rtl) direction *= -1;
	    if (self.inputValue().length) return;

	    // add or remove to active items
	    if (isKeyDown(KEY_SHORTCUT, e) || isKeyDown('shiftKey', e)) {
	      last_active = self.getLastActive(direction);
	      if (last_active) {
	        if (!last_active.classList.contains('active')) {
	          adjacent = last_active;
	        } else {
	          adjacent = self.getAdjacent(last_active, direction, 'item');
	        }

	        // if no active item, get items adjacent to the control input
	      } else if (direction > 0) {
	        adjacent = self.control_input.nextElementSibling;
	      } else {
	        adjacent = self.control_input.previousElementSibling;
	      }
	      if (adjacent) {
	        if (adjacent.classList.contains('active')) {
	          self.removeActiveItem(last_active);
	        }
	        self.setActiveItemClass(adjacent); // mark as last_active !! after removeActiveItem() on last_active
	      }

	      // move caret to the left or right
	    } else {
	      self.moveCaret(direction);
	    }
	  }
	  moveCaret(direction) {}

	  /**
	   * Get the last active item
	   *
	   */
	  getLastActive(direction) {
	    let last_active = this.control.querySelector('.last-active');
	    if (last_active) {
	      return last_active;
	    }
	    var result = this.control.querySelectorAll('.active');
	    if (result) {
	      return getTail(result, direction);
	    }
	  }

	  /**
	   * Moves the caret to the specified index.
	   *
	   * The input must be moved by leaving it in place and moving the
	   * siblings, due to the fact that focus cannot be restored once lost
	   * on mobile webkit devices
	   *
	   */
	  setCaret(new_pos) {
	    this.caretPos = this.items.length;
	  }

	  /**
	   * Return list of item dom elements
	   *
	   */
	  controlChildren() {
	    return Array.from(this.control.querySelectorAll('[data-ts-item]'));
	  }

	  /**
	   * Disables user input on the control. Used while
	   * items are being asynchronously created.
	   */
	  lock() {
	    this.setLocked(true);
	  }

	  /**
	   * Re-enables user input on the control.
	   */
	  unlock() {
	    this.setLocked(false);
	  }

	  /**
	   * Disable or enable user input on the control
	   */
	  setLocked(lock = this.isReadOnly || this.isDisabled) {
	    this.isLocked = lock;
	    this.refreshState();
	  }

	  /**
	   * Disables user input on the control completely.
	   * While disabled, it cannot receive focus.
	   */
	  disable() {
	    this.setDisabled(true);
	    this.close();
	  }

	  /**
	   * Enables the control so that it can respond
	   * to focus and user input.
	   */
	  enable() {
	    this.setDisabled(false);
	  }
	  setDisabled(disabled) {
	    this.focus_node.tabIndex = disabled ? -1 : this.tabIndex;
	    this.isDisabled = disabled;
	    this.input.disabled = disabled;
	    this.control_input.disabled = disabled;
	    this.setLocked();
	  }
	  setReadOnly(isReadOnly) {
	    this.isReadOnly = isReadOnly;
	    this.input.readOnly = isReadOnly;
	    this.control_input.readOnly = isReadOnly;
	    this.setLocked();
	  }

	  /**
	   * Completely destroys the control and
	   * unbinds all event listeners so that it can
	   * be garbage collected.
	   */
	  destroy() {
	    var self = this;
	    var revertSettings = self.revertSettings;
	    self.trigger('destroy');
	    self.off();
	    self.wrapper.remove();
	    self.dropdown.remove();
	    self.input.innerHTML = revertSettings.innerHTML;
	    self.input.tabIndex = revertSettings.tabIndex;
	    removeClasses(self.input, 'tomselected', 'ts-hidden-accessible');
	    self._destroy();
	    delete self.input.tomselect;
	  }

	  /**
	   * A helper method for rendering "item" and
	   * "option" templates, given the data.
	   *
	   */
	  render(templateName, data) {
	    var id, html;
	    const self = this;
	    if (typeof this.settings.render[templateName] !== 'function') {
	      return null;
	    }

	    // render markup
	    html = self.settings.render[templateName].call(this, data, escape_html);
	    if (!html) {
	      return null;
	    }
	    html = getDom(html);

	    // add mandatory attributes
	    if (templateName === 'option' || templateName === 'option_create') {
	      if (data[self.settings.disabledField]) {
	        setAttr(html, {
	          'aria-disabled': 'true'
	        });
	      } else {
	        setAttr(html, {
	          'data-selectable': ''
	        });
	      }
	    } else if (templateName === 'optgroup') {
	      id = data.group[self.settings.optgroupValueField];
	      setAttr(html, {
	        'data-group': id
	      });
	      if (data.group[self.settings.disabledField]) {
	        setAttr(html, {
	          'data-disabled': ''
	        });
	      }
	    }
	    if (templateName === 'option' || templateName === 'item') {
	      const value = get_hash(data[self.settings.valueField]);
	      setAttr(html, {
	        'data-value': value
	      });

	      // make sure we have some classes if a template is overwritten
	      if (templateName === 'item') {
	        addClasses(html, self.settings.itemClass);
	        setAttr(html, {
	          'data-ts-item': ''
	        });
	      } else {
	        addClasses(html, self.settings.optionClass);
	        setAttr(html, {
	          role: 'option',
	          id: data.$id
	        });

	        // update cache
	        data.$div = html;
	        self.options[value] = data;
	      }
	    }
	    return html;
	  }

	  /**
	   * Type guarded rendering
	   *
	   */
	  _render(templateName, data) {
	    const html = this.render(templateName, data);
	    if (html == null) {
	      throw 'HTMLElement expected';
	    }
	    return html;
	  }

	  /**
	   * Clears the render cache for a template. If
	   * no template is given, clears all render
	   * caches.
	   *
	   */
	  clearCache() {
	    iterate$1(this.options, option => {
	      if (option.$div) {
	        option.$div.remove();
	        delete option.$div;
	      }
	    });
	  }

	  /**
	   * Removes a value from item and option caches
	   *
	   */
	  uncacheValue(value) {
	    const option_el = this.getOption(value);
	    if (option_el) option_el.remove();
	  }

	  /**
	   * Determines whether or not to display the
	   * create item prompt, given a user input.
	   *
	   */
	  canCreate(input) {
	    return this.settings.create && input.length > 0 && this.settings.createFilter.call(this, input);
	  }

	  /**
	   * Wraps this.`method` so that `new_fn` can be invoked 'before', 'after', or 'instead' of the original method
	   *
	   * this.hook('instead','onKeyDown',function( arg1, arg2 ...){
	   *
	   * });
	   */
	  hook(when, method, new_fn) {
	    var self = this;
	    var orig_method = self[method];
	    self[method] = function () {
	      var result, result_new;
	      if (when === 'after') {
	        result = orig_method.apply(self, arguments);
	      }
	      result_new = new_fn.apply(self, arguments);
	      if (when === 'instead') {
	        return result_new;
	      }
	      if (when === 'before') {
	        result = orig_method.apply(self, arguments);
	      }
	      return result;
	    };
	  }
	}

	/**
	 * Plugin: "change_listener" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function change_listener () {
	  addEvent(this.input, 'change', () => {
	    this.sync();
	  });
	}

	/**
	 * Plugin: "checkbox_options" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function checkbox_options (userOptions) {
	  var self = this;
	  var orig_onOptionSelect = self.onOptionSelect;
	  self.settings.hideSelected = false;
	  const cbOptions = Object.assign({
	    // so that the user may add different ones as well
	    className: "tomselect-checkbox",
	    // the following default to the historic plugin's values
	    checkedClassNames: undefined,
	    uncheckedClassNames: undefined
	  }, userOptions);
	  var UpdateChecked = function UpdateChecked(checkbox, toCheck) {
	    if (toCheck) {
	      checkbox.checked = true;
	      if (cbOptions.uncheckedClassNames) {
	        checkbox.classList.remove(...cbOptions.uncheckedClassNames);
	      }
	      if (cbOptions.checkedClassNames) {
	        checkbox.classList.add(...cbOptions.checkedClassNames);
	      }
	    } else {
	      checkbox.checked = false;
	      if (cbOptions.checkedClassNames) {
	        checkbox.classList.remove(...cbOptions.checkedClassNames);
	      }
	      if (cbOptions.uncheckedClassNames) {
	        checkbox.classList.add(...cbOptions.uncheckedClassNames);
	      }
	    }
	  };

	  // update the checkbox for an option
	  var UpdateCheckbox = function UpdateCheckbox(option) {
	    setTimeout(() => {
	      var checkbox = option.querySelector('input.' + cbOptions.className);
	      if (checkbox instanceof HTMLInputElement) {
	        UpdateChecked(checkbox, option.classList.contains('selected'));
	      }
	    }, 1);
	  };

	  // add checkbox to option template
	  self.hook('after', 'setupTemplates', () => {
	    var orig_render_option = self.settings.render.option;
	    self.settings.render.option = (data, escape_html) => {
	      var rendered = getDom(orig_render_option.call(self, data, escape_html));
	      var checkbox = document.createElement('input');
	      if (cbOptions.className) {
	        checkbox.classList.add(cbOptions.className);
	      }
	      checkbox.addEventListener('click', function (evt) {
	        preventDefault(evt);
	      });
	      checkbox.type = 'checkbox';
	      const hashed = hash_key(data[self.settings.valueField]);
	      UpdateChecked(checkbox, !!(hashed && self.items.indexOf(hashed) > -1));
	      rendered.prepend(checkbox);
	      return rendered;
	    };
	  });

	  // uncheck when item removed
	  self.on('item_remove', value => {
	    var option = self.getOption(value);
	    if (option) {
	      // if dropdown hasn't been opened yet, the option won't exist
	      option.classList.remove('selected'); // selected class won't be removed yet
	      UpdateCheckbox(option);
	    }
	  });

	  // check when item added
	  self.on('item_add', value => {
	    var option = self.getOption(value);
	    if (option) {
	      // if dropdown hasn't been opened yet, the option won't exist
	      UpdateCheckbox(option);
	    }
	  });

	  // remove items when selected option is clicked
	  self.hook('instead', 'onOptionSelect', (evt, option) => {
	    if (option.classList.contains('selected')) {
	      option.classList.remove('selected');
	      self.removeItem(option.dataset.value);
	      self.refreshOptions();
	      preventDefault(evt, true);
	      return;
	    }
	    orig_onOptionSelect.call(self, evt, option);
	    UpdateCheckbox(option);
	  });
	}

	/**
	 * Plugin: "dropdown_header" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function clear_button (userOptions) {
	  const self = this;
	  const options = Object.assign({
	    className: 'clear-button',
	    title: 'Clear All',
	    html: data => {
	      return `<div class="${data.className}" title="${data.title}">&#10799;</div>`;
	    }
	  }, userOptions);
	  self.on('initialize', () => {
	    var button = getDom(options.html(options));
	    button.addEventListener('click', evt => {
	      if (self.isLocked) return;
	      self.clear();
	      if (self.settings.mode === 'single' && self.settings.allowEmptyOption) {
	        self.addItem('');
	      }
	      evt.preventDefault();
	      evt.stopPropagation();
	    });
	    self.control.appendChild(button);
	  });
	}

	/**
	 * Plugin: "drag_drop" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	const insertAfter = (referenceNode, newNode) => {
	  var _referenceNode$parent;
	  (_referenceNode$parent = referenceNode.parentNode) == null || _referenceNode$parent.insertBefore(newNode, referenceNode.nextSibling);
	};
	const insertBefore = (referenceNode, newNode) => {
	  var _referenceNode$parent2;
	  (_referenceNode$parent2 = referenceNode.parentNode) == null || _referenceNode$parent2.insertBefore(newNode, referenceNode);
	};
	const isBefore = (referenceNode, newNode) => {
	  do {
	    var _newNode;
	    newNode = (_newNode = newNode) == null ? void 0 : _newNode.previousElementSibling;
	    if (referenceNode == newNode) {
	      return true;
	    }
	  } while (newNode && newNode.previousElementSibling);
	  return false;
	};
	function drag_drop () {
	  var self = this;
	  if (self.settings.mode !== 'multi') return;
	  var orig_lock = self.lock;
	  var orig_unlock = self.unlock;
	  let sortable = true;
	  let drag_item;

	  /**
	   * Add draggable attribute to item
	   */
	  self.hook('after', 'setupTemplates', () => {
	    var orig_render_item = self.settings.render.item;
	    self.settings.render.item = (data, escape) => {
	      const item = getDom(orig_render_item.call(self, data, escape));
	      setAttr(item, {
	        'draggable': 'true'
	      });

	      // prevent doc_mousedown (see tom-select.ts)
	      const mousedown = evt => {
	        if (!sortable) preventDefault(evt);
	        evt.stopPropagation();
	      };
	      const dragStart = evt => {
	        drag_item = item;
	        setTimeout(() => {
	          item.classList.add('ts-dragging');
	        }, 0);
	      };
	      const dragOver = evt => {
	        evt.preventDefault();
	        item.classList.add('ts-drag-over');
	        moveitem(item, drag_item);
	      };
	      const dragLeave = () => {
	        item.classList.remove('ts-drag-over');
	      };
	      const moveitem = (targetitem, dragitem) => {
	        if (dragitem === undefined) return;
	        if (isBefore(dragitem, item)) {
	          insertAfter(targetitem, dragitem);
	        } else {
	          insertBefore(targetitem, dragitem);
	        }
	      };
	      const dragend = () => {
	        var _drag_item;
	        document.querySelectorAll('.ts-drag-over').forEach(el => el.classList.remove('ts-drag-over'));
	        (_drag_item = drag_item) == null || _drag_item.classList.remove('ts-dragging');
	        drag_item = undefined;
	        var values = [];
	        self.control.querySelectorAll(`[data-value]`).forEach(el => {
	          if (el.dataset.value) {
	            let value = el.dataset.value;
	            if (value) {
	              values.push(value);
	            }
	          }
	        });
	        self.setValue(values);
	      };
	      addEvent(item, 'mousedown', mousedown);
	      addEvent(item, 'dragstart', dragStart);
	      addEvent(item, 'dragenter', dragOver);
	      addEvent(item, 'dragover', dragOver);
	      addEvent(item, 'dragleave', dragLeave);
	      addEvent(item, 'dragend', dragend);
	      return item;
	    };
	  });
	  self.hook('instead', 'lock', () => {
	    sortable = false;
	    return orig_lock.call(self);
	  });
	  self.hook('instead', 'unlock', () => {
	    sortable = true;
	    return orig_unlock.call(self);
	  });
	}

	/**
	 * Plugin: "dropdown_header" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function dropdown_header (userOptions) {
	  const self = this;
	  const options = Object.assign({
	    title: 'Untitled',
	    headerClass: 'dropdown-header',
	    titleRowClass: 'dropdown-header-title',
	    labelClass: 'dropdown-header-label',
	    closeClass: 'dropdown-header-close',
	    html: data => {
	      return '<div class="' + data.headerClass + '">' + '<div class="' + data.titleRowClass + '">' + '<span class="' + data.labelClass + '">' + data.title + '</span>' + '<a class="' + data.closeClass + '">&times;</a>' + '</div>' + '</div>';
	    }
	  }, userOptions);
	  self.on('initialize', () => {
	    var header = getDom(options.html(options));
	    var close_link = header.querySelector('.' + options.closeClass);
	    if (close_link) {
	      close_link.addEventListener('click', evt => {
	        preventDefault(evt, true);
	        self.close();
	      });
	    }
	    self.dropdown.insertBefore(header, self.dropdown.firstChild);
	  });
	}

	/**
	 * Plugin: "dropdown_input" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function caret_position () {
	  var self = this;

	  /**
	   * Moves the caret to the specified index.
	   *
	   * The input must be moved by leaving it in place and moving the
	   * siblings, due to the fact that focus cannot be restored once lost
	   * on mobile webkit devices
	   *
	   */
	  self.hook('instead', 'setCaret', new_pos => {
	    if (self.settings.mode === 'single' || !self.control.contains(self.control_input)) {
	      new_pos = self.items.length;
	    } else {
	      new_pos = Math.max(0, Math.min(self.items.length, new_pos));
	      if (new_pos != self.caretPos && !self.isPending) {
	        self.controlChildren().forEach((child, j) => {
	          if (j < new_pos) {
	            self.control_input.insertAdjacentElement('beforebegin', child);
	          } else {
	            self.control.appendChild(child);
	          }
	        });
	      }
	    }
	    self.caretPos = new_pos;
	  });
	  self.hook('instead', 'moveCaret', direction => {
	    if (!self.isFocused) return;

	    // move caret before or after selected items
	    const last_active = self.getLastActive(direction);
	    if (last_active) {
	      const idx = nodeIndex(last_active);
	      self.setCaret(direction > 0 ? idx + 1 : idx);
	      self.setActiveItem();
	      removeClasses(last_active, 'last-active');

	      // move caret left or right of current position
	    } else {
	      self.setCaret(self.caretPos + direction);
	    }
	  });
	}

	/**
	 * Plugin: "dropdown_input" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function dropdown_input () {
	  const self = this;
	  self.settings.shouldOpen = true; // make sure the input is shown even if there are no options to display in the dropdown

	  self.hook('before', 'setup', () => {
	    self.focus_node = self.control;
	    addClasses(self.control_input, 'dropdown-input');
	    const div = getDom('<div class="dropdown-input-wrap">');
	    div.append(self.control_input);
	    self.dropdown.insertBefore(div, self.dropdown.firstChild);

	    // set a placeholder in the select control
	    const placeholder = getDom('<input class="items-placeholder" tabindex="-1" />');
	    placeholder.placeholder = self.settings.placeholder || '';
	    self.control.append(placeholder);
	  });
	  self.on('initialize', () => {
	    // set tabIndex on control to -1, otherwise [shift+tab] will put focus right back on control_input
	    self.control_input.addEventListener('keydown', evt => {
	      //addEvent(self.control_input,'keydown' as const,(evt:KeyboardEvent) =>{
	      switch (evt.keyCode) {
	        case KEY_ESC:
	          if (self.isOpen) {
	            preventDefault(evt, true);
	            self.close();
	          }
	          self.clearActiveItems();
	          return;
	        case KEY_TAB:
	          self.focus_node.tabIndex = -1;
	          break;
	      }
	      return self.onKeyDown.call(self, evt);
	    });
	    self.on('blur', () => {
	      self.focus_node.tabIndex = self.isDisabled ? -1 : self.tabIndex;
	    });

	    // give the control_input focus when the dropdown is open
	    self.on('dropdown_open', () => {
	      self.control_input.focus();
	    });

	    // prevent onBlur from closing when focus is on the control_input
	    const orig_onBlur = self.onBlur;
	    self.hook('instead', 'onBlur', evt => {
	      if (evt && evt.relatedTarget == self.control_input) return;
	      return orig_onBlur.call(self);
	    });
	    addEvent(self.control_input, 'blur', () => self.onBlur());

	    // return focus to control to allow further keyboard input
	    self.hook('before', 'close', () => {
	      if (!self.isOpen) return;
	      self.focus_node.focus({
	        preventScroll: true
	      });
	    });
	  });
	}

	/**
	 * Plugin: "input_autogrow" (Tom Select)
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function input_autogrow () {
	  var self = this;
	  self.on('initialize', () => {
	    var test_input = document.createElement('span');
	    var control = self.control_input;
	    test_input.style.cssText = 'position:absolute; top:-99999px; left:-99999px; width:auto; padding:0; white-space:pre; ';
	    self.wrapper.appendChild(test_input);
	    var transfer_styles = ['letterSpacing', 'fontSize', 'fontFamily', 'fontWeight', 'textTransform'];
	    for (const style_name of transfer_styles) {
	      // @ts-ignore TS7015 https://stackoverflow.com/a/50506154/697576
	      test_input.style[style_name] = control.style[style_name];
	    }

	    /**
	     * Set the control width
	     *
	     */
	    var resize = () => {
	      test_input.textContent = control.value;
	      control.style.width = test_input.clientWidth + 'px';
	    };
	    resize();
	    self.on('update item_add item_remove', resize);
	    addEvent(control, 'input', resize);
	    addEvent(control, 'keyup', resize);
	    addEvent(control, 'blur', resize);
	    addEvent(control, 'update', resize);
	  });
	}

	/**
	 * Plugin: "input_autogrow" (Tom Select)
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function no_backspace_delete () {
	  var self = this;
	  var orig_deleteSelection = self.deleteSelection;
	  this.hook('instead', 'deleteSelection', evt => {
	    if (self.activeItems.length) {
	      return orig_deleteSelection.call(self, evt);
	    }
	    return false;
	  });
	}

	/**
	 * Plugin: "no_active_items" (Tom Select)
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function no_active_items () {
	  this.hook('instead', 'setActiveItem', () => {});
	  this.hook('instead', 'selectAll', () => {});
	}

	/**
	 * Plugin: "optgroup_columns" (Tom Select.js)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function optgroup_columns () {
	  var self = this;
	  var orig_keydown = self.onKeyDown;
	  self.hook('instead', 'onKeyDown', evt => {
	    var index, option, options, optgroup;
	    if (!self.isOpen || !(evt.keyCode === KEY_LEFT || evt.keyCode === KEY_RIGHT)) {
	      return orig_keydown.call(self, evt);
	    }
	    self.ignoreHover = true;
	    optgroup = parentMatch(self.activeOption, '[data-group]');
	    index = nodeIndex(self.activeOption, '[data-selectable]');
	    if (!optgroup) {
	      return;
	    }
	    if (evt.keyCode === KEY_LEFT) {
	      optgroup = optgroup.previousSibling;
	    } else {
	      optgroup = optgroup.nextSibling;
	    }
	    if (!optgroup) {
	      return;
	    }
	    options = optgroup.querySelectorAll('[data-selectable]');
	    option = options[Math.min(options.length - 1, index)];
	    if (option) {
	      self.setActiveOption(option);
	    }
	  });
	}

	/**
	 * Plugin: "remove_button" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function remove_button (userOptions) {
	  const options = Object.assign({
	    label: '&times;',
	    title: 'Remove',
	    className: 'remove',
	    append: true
	  }, userOptions);

	  //options.className = 'remove-single';
	  var self = this;

	  // override the render method to add remove button to each item
	  if (!options.append) {
	    return;
	  }
	  var html = '<a href="javascript:void(0)" class="' + options.className + '" tabindex="-1" title="' + escape_html(options.title) + '">' + options.label + '</a>';
	  self.hook('after', 'setupTemplates', () => {
	    var orig_render_item = self.settings.render.item;
	    self.settings.render.item = (data, escape) => {
	      var item = getDom(orig_render_item.call(self, data, escape));
	      var close_button = getDom(html);
	      item.appendChild(close_button);
	      addEvent(close_button, 'mousedown', evt => {
	        preventDefault(evt, true);
	      });
	      addEvent(close_button, 'click', evt => {
	        if (self.isLocked) return;

	        // propagating will trigger the dropdown to show for single mode
	        preventDefault(evt, true);
	        if (self.isLocked) return;
	        if (!self.shouldDelete([item], evt)) return;
	        self.removeItem(item);
	        self.refreshOptions(false);
	        self.inputState();
	      });
	      return item;
	    };
	  });
	}

	/**
	 * Plugin: "restore_on_backspace" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function restore_on_backspace (userOptions) {
	  const self = this;
	  const options = Object.assign({
	    text: option => {
	      return option[self.settings.labelField];
	    }
	  }, userOptions);
	  self.on('item_remove', function (value) {
	    if (!self.isFocused) {
	      return;
	    }
	    if (self.control_input.value.trim() === '') {
	      var option = self.options[value];
	      if (option) {
	        self.setTextboxValue(options.text.call(self, option));
	      }
	    }
	  });
	}

	/**
	 * Plugin: "restore_on_backspace" (Tom Select)
	 * Copyright (c) contributors
	 *
	 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
	 * file except in compliance with the License. You may obtain a copy of the License at:
	 * http://www.apache.org/licenses/LICENSE-2.0
	 *
	 * Unless required by applicable law or agreed to in writing, software distributed under
	 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
	 * ANY KIND, either express or implied. See the License for the specific language
	 * governing permissions and limitations under the License.
	 *
	 */

	function virtual_scroll () {
	  const self = this;
	  const orig_canLoad = self.canLoad;
	  const orig_clearActiveOption = self.clearActiveOption;
	  const orig_loadCallback = self.loadCallback;
	  var pagination = {};
	  var dropdown_content;
	  var loading_more = false;
	  var load_more_opt;
	  var default_values = [];
	  if (!self.settings.shouldLoadMore) {
	    // return true if additional results should be loaded
	    self.settings.shouldLoadMore = () => {
	      const scroll_percent = dropdown_content.clientHeight / (dropdown_content.scrollHeight - dropdown_content.scrollTop);
	      if (scroll_percent > 0.9) {
	        return true;
	      }
	      if (self.activeOption) {
	        var selectable = self.selectable();
	        var index = Array.from(selectable).indexOf(self.activeOption);
	        if (index >= selectable.length - 2) {
	          return true;
	        }
	      }
	      return false;
	    };
	  }
	  if (!self.settings.firstUrl) {
	    throw 'virtual_scroll plugin requires a firstUrl() method';
	  }

	  // in order for virtual scrolling to work,
	  // options need to be ordered the same way they're returned from the remote data source
	  self.settings.sortField = [{
	    field: '$order'
	  }, {
	    field: '$score'
	  }];

	  // can we load more results for given query?
	  const canLoadMore = query => {
	    if (typeof self.settings.maxOptions === 'number' && dropdown_content.children.length >= self.settings.maxOptions) {
	      return false;
	    }
	    if (query in pagination && pagination[query]) {
	      return true;
	    }
	    return false;
	  };
	  const clearFilter = (option, value) => {
	    if (self.items.indexOf(value) >= 0 || default_values.indexOf(value) >= 0) {
	      return true;
	    }
	    return false;
	  };

	  // set the next url that will be
	  self.setNextUrl = (value, next_url) => {
	    pagination[value] = next_url;
	  };

	  // getUrl() to be used in settings.load()
	  self.getUrl = query => {
	    if (query in pagination) {
	      const next_url = pagination[query];
	      pagination[query] = false;
	      return next_url;
	    }

	    // if the user goes back to a previous query
	    // we need to load the first page again
	    self.clearPagination();
	    return self.settings.firstUrl.call(self, query);
	  };

	  // clear pagination
	  self.clearPagination = () => {
	    pagination = {};
	  };

	  // don't clear the active option (and cause unwanted dropdown scroll)
	  // while loading more results
	  self.hook('instead', 'clearActiveOption', () => {
	    if (loading_more) {
	      return;
	    }
	    return orig_clearActiveOption.call(self);
	  });

	  // override the canLoad method
	  self.hook('instead', 'canLoad', query => {
	    // first time the query has been seen
	    if (!(query in pagination)) {
	      return orig_canLoad.call(self, query);
	    }
	    return canLoadMore(query);
	  });

	  // wrap the load
	  self.hook('instead', 'loadCallback', (options, optgroups) => {
	    if (!loading_more) {
	      self.clearOptions(clearFilter);
	    } else if (load_more_opt) {
	      const first_option = options[0];
	      if (first_option !== undefined) {
	        load_more_opt.dataset.value = first_option[self.settings.valueField];
	      }
	    }
	    orig_loadCallback.call(self, options, optgroups);
	    loading_more = false;
	  });

	  // add templates to dropdown
	  //	loading_more if we have another url in the queue
	  //	no_more_results if we don't have another url in the queue
	  self.hook('after', 'refreshOptions', () => {
	    const query = self.lastValue;
	    var option;
	    if (canLoadMore(query)) {
	      option = self.render('loading_more', {
	        query: query
	      });
	      if (option) {
	        option.setAttribute('data-selectable', ''); // so that navigating dropdown with [down] keypresses can navigate to this node
	        load_more_opt = option;
	      }
	    } else if (query in pagination && !dropdown_content.querySelector('.no-results')) {
	      option = self.render('no_more_results', {
	        query: query
	      });
	    }
	    if (option) {
	      addClasses(option, self.settings.optionClass);
	      dropdown_content.append(option);
	    }
	  });

	  // add scroll listener and default templates
	  self.on('initialize', () => {
	    default_values = Object.keys(self.options);
	    dropdown_content = self.dropdown_content;

	    // default templates
	    self.settings.render = Object.assign({}, {
	      loading_more: () => {
	        return `<div class="loading-more-results">Loading more results ... </div>`;
	      },
	      no_more_results: () => {
	        return `<div class="no-more-results">No more results</div>`;
	      }
	    }, self.settings.render);

	    // watch dropdown content scroll position
	    dropdown_content.addEventListener('scroll', () => {
	      if (!self.settings.shouldLoadMore.call(self)) {
	        return;
	      }

	      // !important: this will get checked again in load() but we still need to check here otherwise loading_more will be set to true
	      if (!canLoadMore(self.lastValue)) {
	        return;
	      }

	      // don't call load() too much
	      if (loading_more) return;
	      loading_more = true;
	      self.load.call(self, self.lastValue);
	    });
	  });
	}

	TomSelect.define('change_listener', change_listener);
	TomSelect.define('checkbox_options', checkbox_options);
	TomSelect.define('clear_button', clear_button);
	TomSelect.define('drag_drop', drag_drop);
	TomSelect.define('dropdown_header', dropdown_header);
	TomSelect.define('caret_position', caret_position);
	TomSelect.define('dropdown_input', dropdown_input);
	TomSelect.define('input_autogrow', input_autogrow);
	TomSelect.define('no_backspace_delete', no_backspace_delete);
	TomSelect.define('no_active_items', no_active_items);
	TomSelect.define('optgroup_columns', optgroup_columns);
	TomSelect.define('remove_button', remove_button);
	TomSelect.define('restore_on_backspace', restore_on_backspace);
	TomSelect.define('virtual_scroll', virtual_scroll);

	return TomSelect;

}));
var tomSelect=function(el,opts){return new TomSelect(el,opts);} 
//# sourceMappingURL=tom-select.complete.js.map


/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["url"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
(() => {
"use strict";
/*!********************************************************!*\
  !*** ./assets/js/frontend/tour-search/tour-search.tsx ***!
  \********************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _date_time__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./date-time */ "./assets/js/frontend/tour-search/date-time.js");
/* harmony import */ var _destination__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./destination */ "./assets/js/frontend/tour-search/destination.js");
/* harmony import */ var _price__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./price */ "./assets/js/frontend/tour-search/price.js");
/* harmony import */ var _selection__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./selection */ "./assets/js/frontend/tour-search/selection.js");
/* harmony import */ var _handle_search__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./handle-search */ "./assets/js/frontend/tour-search/handle-search.js");
/* harmony import */ var _fields__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./fields */ "./assets/js/frontend/tour-search/fields.js");






document.addEventListener('DOMContentLoaded', event => {
  (0,_fields__WEBPACK_IMPORTED_MODULE_5__["default"])();
  (0,_date_time__WEBPACK_IMPORTED_MODULE_0__["default"])();
  (0,_destination__WEBPACK_IMPORTED_MODULE_1__["default"])();
  (0,_price__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_selection__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_handle_search__WEBPACK_IMPORTED_MODULE_4__["default"])();
});
})();

/******/ })()
;
//# sourceMappingURL=tour-search.js.map