/**
 * widget search tour js
 *
 * @author physcode
 * @version 2.0.12
 */

const tourWidgetSearch = () =>{
	jQuery(function ($) {
		'use strict';
		if ($('#tour-price-range').length) {
			let el_tour_price_range = document.getElementById('tour-price-range');
			if (el_tour_price_range) {
				let el_tour_min_price_show = $('.tour-min-price');
				let el_tour_max_price_show = $('.tour-max-price');
				let tour_start_price_filter = parseFloat($('input[name=tour_start_price_fitler]').val());
				let tour_end_price_filter = parseFloat($('input[name=tour_end_price_filter]').val());
				let el_tour_max_price = $('input[name=tour_max_price]');
				let el_tour_min_price = $('input[name=tour_min_price]');
				let min_price = parseFloat(el_tour_min_price.val());
				let max_price = parseFloat(el_tour_max_price.val());

				// console.log(min_price, max_price);
				if (min_price < max_price) {
					noUiSlider.create(el_tour_price_range, {
						start  : [min_price, max_price],
						connect: true,
						range  : {
							'min': tour_start_price_filter,
							'max': tour_end_price_filter
						}
					});

					el_tour_price_range.noUiSlider.on('update', function (values) {
						// console.log(values);
						let min_price = values[0];
						let max_price = values[1];

						el_tour_min_price.val(min_price);
						el_tour_max_price.val(max_price);
						el_tour_min_price_show.text(min_price);
						el_tour_max_price_show.text(max_price);
					});
				}

			}
		}

		if ($('#tour_rating').length) {
			$('#tour_rating').barrating({
				theme: 'css-stars'
			});
		}
	});
};

tourWidgetSearch();
