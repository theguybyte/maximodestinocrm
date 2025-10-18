const jQuerydynamicGallery = document.getElementById("ekits-product-columns");
const jQuerydynamicgal = document.querySelector('.dynamic-gal');
if (jQuerydynamicgal) {
	const data = jQuerydynamicgal.getAttribute('data-dynamicpath');
	const objs = JSON.parse(data);
	const modifiedData = objs.map(obj => { 
		return {
			src: obj.src,
			responsive: obj.responsive,
			thumb: obj.thumb,
			subHtml: obj.subHtml
		};
	});
	const dynamicGallery = window.lightGallery(jQuerydynamicGallery, {   
		dynamic: true,
		plugins: [lgThumbnail],
		dynamicEl: modifiedData,
		height: "80%",
		width: "65%",
		addClass: 'fixed-size',
		download: false,
		counter: true,
		// thumbWidth: "65%",
		// enableThumbSwipe:true,
		
	});
	document.querySelectorAll(".dynamic-gal").forEach((el, index) => {
		el.addEventListener("click", () => {
			dynamicGallery.openGallery(0);
		});
	});
}
const accordionTitles = document.querySelectorAll(".tour-faq-toggle");

accordionTitles.forEach((accordionTitle) => {
	accordionTitle.addEventListener("click", () => {
		if (accordionTitle.classList.contains("is-open")) {
			accordionTitle.classList.remove("is-open");
		} else {
			const accordionTitlesWithIsOpen = document.querySelectorAll(".is-open");
			accordionTitlesWithIsOpen.forEach((accordionTitleWithIsOpen) => {
				accordionTitleWithIsOpen.classList.remove("is-open");
			});
			accordionTitle.classList.add("is-open");
		}
	});
});

var tabLinks = document.querySelectorAll(".nav-tabs-item li");
var tabContent = document.querySelectorAll(".tab-content .tab-pane"); 

tabLinks.forEach(function (el) {
	el.addEventListener("click", openTabs);
});


function openTabs(el) {
	var btn = el.currentTarget;
	var electronic = btn.children[0].getAttribute("href");
	tabContent.forEach(function (el) {
		el.classList.remove("active");
	});

	tabLinks.forEach(function (el) {
		el.classList.remove("active");
	});

	document.querySelector(electronic).classList.add("active");

	btn.classList.add("active");
}
var travel_custom_js = {
	travel_search_attributes: function () {
		function query_search_atrributes_result(value_search, sortby) {  
			var attributes = jQuery('.search-attributes').data('attr'),
				layout = jQuery('.search-attributes').data('layout');
			var data = {
				action: 'search_arttibutes_item',
				value_search: value_search,
				sortby: sortby,
				attributes: attributes,
				layout: layout
			};
			jQuery.ajax({
				type: "POST",
				url: ajax.ajaxUrl,
				data: data,
				// dataType: "html",
				beforeSend: function () {
					// setting a timeout
					jQuery('.arrt-content-wrapper').addClass('loading');
					jQuery('.button-attributes').addClass('loading');
				},
				success: function (response) {
					// console.log(response);
					if (response.success) {
						jQuery('.arrt-content-wrapper').removeClass('loading');
						jQuery('.button-attributes').removeClass('loading');
						jQuery('.thim-ekits--tour__attributes__inner').html('')
						jQuery('.thim-ekits--tour__attributes__inner').html(response.content)
					}
				}
			});
		}
		jQuery('.sort_attributes').on('change', function () { 
			var value_search = jQuery('.search-attributes').find("input").val(),
				a = jQuery(this).val();
			
			//updateQueryStringParam('sortby', a)
			let new_url = "";
			if (window.location.search && window.location.search.indexOf('sortby=') != -1) {
				new_url = window.location.search.replace(/sortby=\w*\d*/, "sortby=" + a);
			} else if (window.location.search) {
				new_url = window.location.search + "&sortby=" + a;
			} else {
				new_url = window.location.search + "?sortby=" + a;
			}
			window.history.pushState({
				path: new_url
			}, '', new_url);
			location.reload(true)
		});

		jQuery('.search-attributes').find("input").keyup(function (e) {
			e.preventDefault();
			var value_search = jQuery(this).val(),
				sortby = jQuery('.sort_attributes').val(); 
			// var url = document.location.href+'?s_tour='+location;
			let new_url = "";
			if (window.location.search && window.location.search.indexOf('s_tour=') != -1) {
				new_url = window.location.search.replace(/s_tour=\w*\d*/, "s_tour=" + value_search);
			} else if (window.location.search) {
				new_url = window.location.search + "&s_tour=" + value_search;
			} else {
				new_url = window.location.search + "?s_tour=" + value_search;
			}
			// window.location.href = new_url;
			window.history.pushState({
				path: new_url
			}, '', new_url);
			if (value_search !== null && value_search !== '') {
				query_search_atrributes_result(value_search, sortby);
				jQuery('.pagination-archiver-attr').css('display', 'none');
			} else {
				location.reload(true)
			}
		});
	},
}
jQuery(document).ready(function () { 
	travel_custom_js.travel_search_attributes(); 
	if (jQuery('.lweather-widget').length) {
		jQuery(".lweather-widget").each(function () {
			let $el = jQuery(this),
				lat = $el.data('lat'),
				lon = $el.data('lng')
			if (lat && lon) {
				$el.flatWeatherPlugin({
					lat: lat,
					lon: lon,
					api: "openweathermap",
					// apikey: weatherkey,
					view: "full",
					timeformat: "12",
					displayCityNameOnly: false,
					forecast: 50,
					render: true,
					loadingAnimation: true,
				})
			}
		});
	}
}); 
jQuery(window).on('elementor/frontend/init', () => {
	const addHandler = ($element) => {
		elementorFrontend.elementsHandler.addHandler(window.ThimEkits.ThimSlider, { 
			$element,
		});
	};
	elementorFrontend.hooks.addAction('frontend/element_ready/thim-ekits-tours-related.default', addHandler);
	elementorFrontend.hooks.addAction('frontend/element_ready/thim-ekits-list-tours.default', addHandler);
	elementorFrontend.hooks.addAction('frontend/element_ready/thim-ekits-attributes.default', addHandler);
	elementorFrontend.hooks.addAction('frontend/element_ready/thim-ekits-tours-hightlight.default', addHandler);
}); 