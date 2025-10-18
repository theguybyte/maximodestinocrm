/**
 * Booking tour js
 *
 * @author physcode
 * @version 2.0.8
 */

( function( $ ) {

	'use strict';
	const date_validate = [];
	const date_can_book = [];
	let tour_month = [];
	const tour_days = '';
	const date_book = '';
	let load_notify = false;

	let el_tourBookingForm = null;
	let el_tour_date_checkin_checkout = null;
	let el_tour_datepicker_range_checkin_checkout = null;
	let el_input_tour_datepicker_range_checkin_checkout = null;
	let el_number_ticket = null;
	let el_price_ticket = null;
	let el_show_price_ticket = null;
	let el_number_children = null;
	let el_price_children = null;
	let el_show_price_children = null;
	let el_field_travel_booking = null;
	let el_input_date_book = null;
	let tour_have_children_ticket = false;
	let el_tour_date_starts_after = null;
	let el_tour_duration_number = null;
	let el_tour_group_discount_data_phys;
	let tour_group_discount_data;
	let el_label_tour_group_discount = null;
	let el_tour_deposit = null;
	let el_tour_variations_options;
	let tour_variation_options;
	let info_user;
	let el_show_total_price;
	const price_dates_tour = {};
	let num_decimals = 0;

	$.fn.tour_booking_init = function() {
		el_tourBookingForm = $( 'form#tourBookingForm' );

		if ( getCookie( 'info_user' ) ) {
			info_user = JSON.parse( getCookie( 'info_user' ) );
		}

		if ( el_tourBookingForm.length > 0 ) {
			$.fn._get_elms_tour_booking_form();
			$.fn.tour_booking();

			if ( info_user ) {
				$( 'input[name=first_name]' ).val( info_user.first_name );
				$( 'input[name=last_name]' ).val( info_user.last_name );
				$( 'input[name=email_tour]' ).val( info_user.email );
				$( 'input[name=phone]' ).val( info_user.phone );
			}
		}

		if ( info_user ) {
			// for billing
			$( 'input[name=billing_first_name]' ).val( info_user.first_name );
			$( 'input[name=billing_last_name]' ).val( info_user.last_name );
			$( 'input[name=billing_email]' ).val( info_user.email );
			$( 'input[name=billing_phone]' ).val( info_user.phone );
		}
	};

	$.fn._get_elms_tour_booking_form = function() {
		el_price_ticket = el_tourBookingForm.find( 'input[name=price_ticket]' );
		el_number_ticket = el_tourBookingForm.find( 'input[name=number_ticket]' );
		el_show_price_ticket = el_tourBookingForm.find( '.price_ticket' );
		el_number_children = el_tourBookingForm.find( 'input[name=number_children]' );
		el_price_children = el_tourBookingForm.find( 'input[name=price_children]' );
		el_field_travel_booking = el_tourBookingForm.find( '.field-travel-booking' );
		el_show_price_children = el_tourBookingForm.find( '.price_child' );
		el_tour_date_checkin_checkout = el_tourBookingForm.find( '.tour_date_checkin_checkout' );
		el_input_date_book = el_tourBookingForm.find( 'input[name=date_book]' );
		el_tour_date_starts_after = el_tourBookingForm.find( 'input[name=tour_date_starts_after]' );
		el_tour_duration_number = el_tourBookingForm.find( 'input[name=tour_duration_number]' );
		el_tour_group_discount_data_phys = el_tourBookingForm.find( 'input[name=tour_group_discount_data_phys]' );
		el_label_tour_group_discount = el_tourBookingForm.find( '.label-tour-group-discount' );
		el_tour_deposit = el_tourBookingForm.find( 'input[name=webtomizer_tour_deposit]' );
		el_tour_variations_options = el_tourBookingForm.find( 'input[name=_tour_variations_options]' );
		const el_tour_month = el_tourBookingForm.find( 'input[name=tour_month]' );
		el_show_total_price = el_tourBookingForm.find( '.tour-subtotal' );
		num_decimals = $( 'input[name=woocommerce_price_num_decimals]' ).val();

		if ( el_number_children.length > 0 ) {
			tour_have_children_ticket = true;
		}

		if ( el_tour_month.length ) {
			tour_month = el_tour_month.val().split( ',' );
		}
	};

	$._dates_disable = null;
	$._max_year = null;
	$._date_format_default = 'yy/mm/dd';
	$._date_format_default_datepicker_range = 'YYYY/MM/DD';
	$._tour_date_format = null;
	// $._date_format_convert_php_js = {};
	// $._date_format_convert_php_js['d/m/Y'] = 'dd/mm/yy';
	// $._date_format_convert_php_js['Y/m/d'] = 'yy/mm/dd';
	// $._date_format_convert_php_js['D, d M, Y'] = 'D, d M, yy';
	// $._date_format_convert_php_js['l, d F, Y'] = 'DD, d MM, yy';
	$._date_format_convert_php_js = {
		'Y/m/d': 'yy/mm/dd',
		'Y-m-d': 'yy-mm-dd',
		'd/m/Y': 'dd/mm/yy',
		'd-m-Y': 'dd-mm-yy',
		'm/d/Y': 'mm/dd/yy',
		'm-d-Y': 'mm-dd-yy',
		'D, d M, Y': 'D, d M, yy',
		'l, d F, Y': 'DD, d MM, yy',
	};
	$._tour_date_check_in_old = '';
	let phys_tour_price_dates_type = null;
	let el_tour_date_check_in = null;
	let el_tour_date_check_out = null;
	$._tour_price_of_dates_option = null;

	$.fn.tour_booking = function() {
		let tour_date_starts_after = 0;
		const date_now = new Date();
		const date_min_can_book = new Date();
		const el_phys_tour_dates_disable = el_tourBookingForm.find( 'input[name=phys_tour_dates_disable]' );
		const el_phys_tour_max_year_enable = el_tourBookingForm.find( 'input[name=phys_tour_max_year_enable]' );
		// let el_tour_date_format = el_tourBookingForm.find('input[name=tour_date_format]');
		phys_tour_price_dates_type = el_tourBookingForm.find( 'input[name=phys_tour_price_dates_type]' ).val();
		const el_phys_tour_price_of_dates_option = el_tourBookingForm.find( 'input[name=phys_tour_price_of_dates_option]' );

		if ( el_tour_variations_options.length > 0 ) {
			try {
				tour_variation_options = JSON.parse( el_tour_variations_options.val() );
				//el_tour_variations_options.val( '' );

				//console.log( tour_variation_options );
			} catch ( e ) {
				tour_variation_options = null;
			}
		}

		if ( el_tour_group_discount_data_phys.length ) {
			try {
				tour_group_discount_data = JSON.parse( el_tour_group_discount_data_phys.val() );
				//el_tour_group_discount_data_phys.val( '' );
			} catch ( e ) {
				tour_group_discount_data = null;
			}
		}

		/*** Get subtotal price ***/
		$.fn.update_tour_total_price();

		if ( el_phys_tour_price_of_dates_option.length > 0 && el_phys_tour_price_of_dates_option.val() !== '' && el_phys_tour_price_of_dates_option.val() !== '{}' ) {
			try {
				$._tour_price_of_dates_option = JSON.parse( el_phys_tour_price_of_dates_option.val() );
				//el_phys_tour_price_of_dates_option.val( '' );
			} catch ( e ) {
				$._tour_price_of_dates_option = null;
			}
		}

		/*** Set date format ***/
		$._tour_date_format = $._date_format_default;

		if ( $._date_format_convert_php_js[ travel_booking.tour_date_format ] !== undefined ) {
			$._tour_date_format = $._date_format_convert_php_js[ travel_booking.tour_date_format ];
		}

		$._max_year = el_phys_tour_max_year_enable.val();

		if ( el_phys_tour_dates_disable.length > 0 && el_phys_tour_dates_disable.val() !== '' ) {
			$._dates_disable = JSON.parse( el_phys_tour_dates_disable.val() );
		}

		/*** Start After ***/
		if ( el_tour_date_starts_after.length > 0 ) {
			if ( el_tour_date_starts_after.val() != '' && parseInt( el_tour_date_starts_after.val() ) !== 0 ) {
				tour_date_starts_after = parseInt( el_tour_date_starts_after.val() );
			}
		}

		if ( $._dates_disable !== null ) {
			let dateMinCanBookStr = $.fn._convert_date_obj_by_format_default( date_min_can_book );

			while ( $._dates_disable.indexOf( dateMinCanBookStr ) > -1 ) {
				date_min_can_book.setDate( date_min_can_book.getDate() + 1 );
				dateMinCanBookStr = $.fn._convert_date_obj_by_format_default( date_min_can_book );
			}

			date_min_can_book.setDate( date_min_can_book.getDate() + tour_date_starts_after );

			// console.log(date_min_can_book);
		} else {
			date_min_can_book.setDate( date_now.getDate() + tour_date_starts_after );
		}

		const option_date_picker = {
			dateFormat: $._tour_date_format,
			minDate: date_min_can_book,
			yearRange: date_min_can_book.getFullYear() + ':' + $._max_year,
		};

		if ( $._dates_disable !== null ) {
			option_date_picker.beforeShowDay = function( date ) {
				date = $.fn._convert_date_obj_by_format_default( date );

				const indexOf = $._dates_disable.indexOf( date );

				if ( indexOf > -1 ) {
					return [ false, '', '' ];
				}
				return [ true, '', 'Available' ];
			};
		}

		// Date book
		if ( el_input_date_book.length > 0 ) {
			delete el_input_date_book.beforeShowDay;
			if ( jQuery().datepicker ) {
				el_input_date_book.datepicker( option_date_picker );
				el_input_date_book.datepicker( 'setDate', date_min_can_book );
				const duration = parseInt( el_tour_duration_number.val() );

				const function_anonymous_beforeShowDays = function( date ) {
					$._tour_dates_choose = [];

					if ( el_input_date_book.val() !== '' ) {
						const date_start_obj = new Date( $.fn._convert_date_to_format_default( el_input_date_book.val() ) );
						const date_next = date_start_obj;
						const start_date = $.fn._convert_date_obj_by_format_default( date_start_obj );
						const end_date_obj = new Date( $.fn._convert_date_to_format_default( el_input_date_book.val() ) );
						end_date_obj.setDate( date_start_obj.getDate() + duration );
						const end_date = $.fn._convert_date_obj_by_format_default( end_date_obj );

						while ( date_next < end_date_obj ) {
							// console.log(date_next);
							const date_string_by_format_default = $.fn._convert_date_obj_by_format_default( date_next );
							$._tour_dates_choose.push( date_string_by_format_default );
							date_next.setDate( date_next.getDate() + 1 );
						}

						date = $.fn._convert_date_obj_by_format_default( date );
						const indexOf = $._tour_dates_choose.indexOf( date );

						if ( $._dates_disable !== null ) {
							const indexOfDatesDisable = $._dates_disable.indexOf( date );

							if ( indexOf > -1 ) {
								if ( indexOfDatesDisable > -1 ) {
									if ( date == start_date ) {
										return [ false, 'date-picked start-date', '' ];
									} else if ( date == end_date ) {
										return [ false, 'date-picked end-date', '' ];
									}
									return [ false, 'date-picked', '' ];
								}
								if ( date == start_date ) {
									return [ true, 'date-picked start-date', 'Available' ];
								} else if ( date == end_date ) {
									return [ true, 'date-picked end-date', 'Available' ];
								}
								return [ true, 'date-picked', 'Available' ];
							}
							if ( indexOfDatesDisable > -1 ) {
								return [ false, '', '' ];
							}
							return [ true, '', 'Available' ];
						}
						if ( indexOf > -1 ) {
							if ( date == start_date ) {
								return [ true, 'date-picked start-date', '' ];
							} else if ( date == end_date ) {
								return [ true, 'date-picked end-date', '' ];
							}
							return [ true, 'date-picked', '' ];
						}
						return [ true, '', '' ];
					}
					date = $.fn._convert_date_obj_by_format_default( date );
					if ( $._dates_disable !== null ) {
						const indexOf = $._dates_disable.indexOf( date );

						if ( indexOf > -1 ) {
							return [ false, '', '' ];
						}
						return [ true, '', 'Available' ];
					}
					return [ true, '', 'Available' ];
				};

				el_input_date_book.datepicker( 'option', 'beforeShowDay', function_anonymous_beforeShowDays );
				el_input_date_book.datepicker( 'option', 'onClose', function( date ) {
					$.fn.update_tour_total_price();
				} );
			}
		}

		// Date checkin & checkout
		if ( el_tour_date_checkin_checkout.length > 0 ) {
			el_tour_date_check_in = el_tour_date_checkin_checkout.find( 'input[name=tour_date_check_in]' );
			el_tour_date_check_out = el_tour_date_checkin_checkout.find( 'input[name=tour_date_check_out]' );

			if ( jQuery().datepicker ) {
				el_tour_date_check_in.datepicker( option_date_picker );

				el_tour_date_check_in.datepicker( 'option', 'onClose', function( date ) {
					$.fn._show_range_dates_choose();
					// console.log(date);
					if ( date !== '' ) {
						const date_next = new Date( $.fn._convert_date_to_format_default( date ) );

						date_next.setDate( date_next.getDate() + 1 );

						el_tour_date_check_out.datepicker( 'option', 'minDate', date_next );

						$.fn.update_tour_total_price();

						if ( date !== $._tour_date_check_in_old ) {
							$._tour_date_check_in_old = date;
						}
					}
				} );

				el_tour_date_check_out.datepicker( {
					dateFormat: $._tour_date_format,
					minDate: date_min_can_book,
					yearRange: date_min_can_book.getFullYear() + ':' + $._max_year,
				} );

				el_tour_date_check_out.datepicker( 'option', 'onClose', function( date ) {
					$.fn._show_range_dates_choose();
					$.fn.update_tour_total_price();
				} );
			}
		}

		// Date checkin & checkout range
		el_tour_datepicker_range_checkin_checkout = el_tourBookingForm.find( '.tour-datepicker-range-checkin-checkout' );
		if ( el_tour_datepicker_range_checkin_checkout.length > 0 ) {
			el_input_tour_datepicker_range_checkin_checkout = el_tour_datepicker_range_checkin_checkout.find( 'input[name=tour_datepicker_range_checkin_checkout]' );

			const option_datepicker_range = {
				minDate: date_min_can_book,
				opens: 'left',
				drops: 'up',
				locale: {
					format: $._date_format_default_datepicker_range,
				},
			};

			el_input_tour_datepicker_range_checkin_checkout.daterangepicker( option_datepicker_range, function( start, end ) {
				//console.log( start, end );
			} );
		}

		if ( el_field_travel_booking.length > 0 ) {
			el_field_travel_booking.on( 'change keyup', function( i ) {
				$.fn.update_tour_total_price();
			} );
		}
	};

	$.fn.get_price_tours_default = function() {
		price_dates_tour.regular_price_dates = parseFloat( el_price_ticket.val() );

		if ( tour_have_children_ticket ) {
			price_dates_tour.child_price_dates = parseFloat( el_price_children.val() );
		}

		// Variation
		if ( tour_variation_options ) {
			$.each( tour_variation_options, function( k_variation, variation ) {
				if ( variation.set_price == 1 && variation.enable == 1 ) {
					if ( ! price_dates_tour[ k_variation ] ) {
						price_dates_tour[ k_variation ] = {};
					}

					$.each( variation.variation_attr, function( k_attr, attribute ) {
						price_dates_tour[ k_variation ][ k_attr ] = parseFloat( attribute.price );
					} );
				}
			} );
		}
	};

	$.fn.update_tour_total_price = function() {
		$.fn.get_price_tours_default();

		let subtotal = 0;
		const el_field_travel_booking = $( '.field-travel-booking' );
		let total_person = 0;
		const total_adult = parseInt( el_number_ticket.val() );
		total_person += total_adult;

		let total_child = 0;
		if ( el_number_children.length ) {
			total_child = parseInt( el_number_children.val() );
			total_person += total_child;
		}

		/*** Get price by dates ***/
		const isDateRangeFill = el_tour_date_check_in && el_tour_date_check_in.length && el_tour_date_check_out && el_tour_date_check_out.length &&
			el_tour_date_check_in.val() !== '' && el_tour_date_check_out.val() !== '';

		const hasPriceDateTourOption = $._tour_price_of_dates_option && $._tour_price_of_dates_option.price_dates_range && ! $.isEmptyObject( $._tour_price_of_dates_option.price_dates_range );

		let date_check_in;
		let date_check_out;
		if ( el_input_date_book.length && el_input_date_book.val() !== '' ) { // is Fixed date
			const duration = parseFloat( el_tour_duration_number.val() );
			date_check_in = new Date( $.fn._convert_date_to_format_default( el_input_date_book.val() ) );
			date_check_out = structuredClone(date_check_in);
			if ( duration > 1 ) {
				date_check_out.setDate( date_check_out.getDate() + duration );
			} else if ( duration === 1 ) {
				date_check_out.setDate( date_check_out.getDate() + 1 );
			}
		} else if ( isDateRangeFill ) {
			date_check_in = new Date( $.fn._convert_date_to_format_default( el_tour_date_check_in.val() ) );
			date_check_out = new Date( $.fn._convert_date_to_format_default( el_tour_date_check_out.val() ) );
		}

		if ( ! date_check_in ||  ! date_check_out ) {
			return;
		}

		let date_next = structuredClone(date_check_in);
		const price_dates_tour_tmp = {};

		// Get total days
		const total_night = $.fn._calculate_total_night( date_check_in, date_check_out );
		// let total_days = $.fn._calculate_total_days(date_check_in, date_check_out);
		const el_number_day = $( '.number-day' );
		el_number_day.text( total_night );

		while ( date_next < date_check_out ) {
			if ( hasPriceDateTourOption ) {
				const dateExistsOption = [];
				$.each( $._tour_price_of_dates_option.price_dates_range, function( k_price_dates_range, price_dates_range ) {
					const startDate = new Date( price_dates_range.start_date );
					const endDate = new Date( price_dates_range.end_date );

					if ( date_next >= startDate && date_next <= endDate ) {
						dateExistsOption.push( date_next );
						$.each( price_dates_range.prices, function( k_price, v_price ) {
							if ( price_dates_tour[ k_price ] ) {
								if ( k_price === 'regular_price_dates' || k_price === 'child_price_dates' ) {
									let price_attr = parseFloat( v_price.price );

									if ( isNaN( price_attr ) || price_attr <= 0 ) {
										price_attr = price_dates_tour[ k_price ];
									}

									if ( ! price_dates_tour_tmp[ k_price ] ) {
										price_dates_tour_tmp[ k_price ] = price_attr;
									} else {
										price_dates_tour_tmp[ k_price ] += price_attr;
									}
								} else {
									// Variations
									$.each( v_price, function( k_variation, variation_attr ) {
										if ( price_dates_tour[ k_price ][ k_variation ] ) {
											let price_attr = parseFloat( variation_attr.price );

											if ( isNaN( price_attr ) || price_attr <= 0 ) {
												price_attr = price_dates_tour[ k_price ][ k_variation ];
											}

											if ( ! price_dates_tour_tmp[ k_price ] ) {
												price_dates_tour_tmp[ k_price ] = {};
											}

											if ( ! price_dates_tour_tmp[ k_price ][ k_variation ] ) {
												price_dates_tour_tmp[ k_price ][ k_variation ] = price_attr;
											} else {
												price_dates_tour_tmp[ k_price ][ k_variation ] += price_attr;
											}
										}
									} );
								}
							}
						} );
					}
				} );

				// get price regular
				if ( dateExistsOption.indexOf( date_next ) === -1 ) {
					$.fn.getPriceDatesDefaultByDateRange( price_dates_tour_tmp, price_dates_tour );
				}
			} else {
				// get price regular
				$.fn.getPriceDatesDefaultByDateRange( price_dates_tour_tmp, price_dates_tour );
			}

			date_next.setDate( date_next.getDate() + 1 );
		}

		// console.log(price_dates_tour_tmp);

		/*** Set total price days ***/
		price_dates_tour.regular_price_dates = price_dates_tour_tmp.regular_price_dates;
		price_dates_tour.child_price_dates = price_dates_tour_tmp.child_price_dates == undefined ? 0 : price_dates_tour_tmp.child_price_dates;

		// Variations
		//console.log(price_dates_tour);
		$.each( price_dates_tour, function( k_price, v_price ) {
			if ( k_price !== 'regular_price_dates' && k_price !== 'child_price_dates' ) {
				$.each( v_price, function( k_attr, v_attr ) {
					if ( price_dates_tour_tmp[ k_price ] && price_dates_tour_tmp[ k_price ][ k_attr ] ) {
						price_dates_tour[ k_price ][ k_attr ] = price_dates_tour_tmp[ k_price ][ k_attr ];
					}
				} );
			}
		} );
		/*** End set total price days ***/

		// console.log(price_dates_tour);

		/*** Get subtotal ***/
		$.each( price_dates_tour, function( k, v ) {
			if ( k == 'regular_price_dates' ) {
				const totalPriceAdult = price_dates_tour[ k ] * total_adult;
				subtotal += totalPriceAdult;

				el_show_price_ticket.text( $.fn.tour_price_format( price_dates_tour[ k ] ) );
			} else if ( k == 'child_price_dates' ) {
				const totalPriceChild = price_dates_tour[ k ] * total_child;
				subtotal += totalPriceChild;

				el_show_price_children.text( $.fn.tour_price_format( price_dates_tour[ k ] ) );
			} else {
				$.each( v, function( k_variation, k_attr ) {
					var price_variation = price_dates_tour[ k ][ k_variation ];

					switch ( tour_variation_options[ k ].type_variation ) {
					case 'quantity':
						var qtyVariation = parseInt( $( 'input[name=' + k_variation + ']' ).val() );
						var totalPriceVariation = price_variation * qtyVariation;
						subtotal += totalPriceVariation;
						break;
					case 'checkbox':
						var elVariation = $( 'input[name=' + k_variation + ']' );

						if ( elVariation.is( ':checked' ) ) {
							subtotal += price_variation;
						}
						break;
					case 'radio':
						var elVariation = $( 'input[name=' + k + ']:checked' );

						if ( elVariation.val() == k_variation ) {
							var price_variation = price_dates_tour[ k ][ k_variation ];

							subtotal += price_variation;
						}
						break;
					case 'select':
						var k_attr_selected = $( 'select[name=' + k + ']' ).val();

						if ( k_attr_selected == k_variation && price_dates_tour[ k ][ k_attr_selected ] ) {
							subtotal += price_variation;
						}
						break;
					default:
						break;
					}

					$( '.price_' + k_variation ).text( $.fn.tour_price_format( price_variation ) );
				} );
			}
		} );

		// Group discount
		if ( tour_group_discount_data ) {
			const el_show_val_discount = el_label_tour_group_discount.find( '.val-discount' );
			const el_currency_group_discount = el_label_tour_group_discount.find( '.woocommerce-Price-currencySymbol' );
			const el_show_total_people = el_label_tour_group_discount.find( '.total-people' );

			try {
				let customer_number_match = 0;
				let discount_val = 0;

				$.each( tour_group_discount_data, function( k, v ) {
					const number_customer = parseInt( v.number_customer );

					if ( number_customer <= total_person && customer_number_match < number_customer ) {
						customer_number_match = number_customer;
						discount_val = v.discount;
					}
				} );

				if ( customer_number_match !== 0 ) {
					const patter = /\%$/;

					el_show_val_discount.text( discount_val );
					el_show_total_people.text( total_person );
					el_label_tour_group_discount.show();

					if ( patter.test( discount_val ) ) {
						el_currency_group_discount.hide();
						discount_val = parseFloat( discount_val );

						subtotal = subtotal - ( subtotal * discount_val / 100 );
					} else {
						discount_val = parseFloat( discount_val );
						el_show_val_discount.text( discount_val.toFixed( num_decimals ) );
						el_currency_group_discount.show();
						subtotal = subtotal - discount_val;
					}
				} else {
					el_label_tour_group_discount.hide();
				}
			} catch ( e ) {
				console.error( e );
			}
		}

		el_show_total_price.text( $.fn.tour_price_format( subtotal ) );
	};

	$.fn.getPriceDatesDefaultByDateRange = function( price_dates_tour_tmp, price_dates_tour ) {
		if ( ! price_dates_tour_tmp.regular_price_dates ) {
			price_dates_tour_tmp.regular_price_dates = price_dates_tour.regular_price_dates;
		} else {
			price_dates_tour_tmp.regular_price_dates += price_dates_tour.regular_price_dates;
		}

		if ( ! price_dates_tour_tmp.child_price_dates ) {
			price_dates_tour_tmp.child_price_dates = price_dates_tour.child_price_dates;
		} else {
			price_dates_tour_tmp.child_price_dates += price_dates_tour.child_price_dates;
		}

		// Variation
		$.each( price_dates_tour, function( k_price, v_price ) {
			if ( k_price != 'regular_price_dates' && k_price != 'child_price_dates' ) {
				$.each( v_price, function( k_attr, v_attr ) {
					if ( ! price_dates_tour_tmp[ k_price ] ) {
						price_dates_tour_tmp[ k_price ] = {};
					}

					if ( ! price_dates_tour_tmp[ k_price ][ k_attr ] ) {
						price_dates_tour_tmp[ k_price ][ k_attr ] = price_dates_tour[ k_price ][ k_attr ];
					} else {
						price_dates_tour_tmp[ k_price ][ k_attr ] += price_dates_tour[ k_price ][ k_attr ];
					}
				} );
			}
		} );
	};

	$.fn.tour_booking_submit = function() {
		$( '.btn-booking' ).on( 'click', function( e ) {
			e.preventDefault();
			const el_btn_booking = $( this );
			let flag_errors = false;
			const input_first_name = $( 'input[name=first_name]' );
			const input_last_name = $( 'input[name=last_name]' );
			const input_email = $( 'input[name=email_tour]' );
			const input_phone = $( 'input[name=phone]' );
			const input_qty = $( 'input[name=number_ticket]' );
			const qty_tour = parseInt( input_qty.val() );
			const tb_phys_ajax_url = $( 'input[name=url_home]' ).val();
			const el_deposit_radio = $( 'input[name=deposit_radio]' );
			const max_qty_per = parseInt( input_qty.attr( 'max' ) );
			const el_tour_variation_item = $( '.tour-variation-item' );
			// let el_tour_variations_options = $('input[name=tour_variations_options]');

			if ( input_first_name.length > 0 ) {
				if ( input_first_name.val().length == 0 ) {
					input_first_name.attr( 'placeholder', travel_booking.message_er_first_name );
					input_first_name.addClass( 'error' );
					return;
				}
			}

			if ( input_last_name.length > 0 ) {
				if ( input_last_name.val().length == 0 ) {
					input_last_name.attr( 'placeholder', travel_booking.message_er_last_name );
					input_last_name.addClass( 'error' );
					return;
				}
			}

			if ( input_email.length > 0 ) {
				if ( ! checkValidateEmail( input_email.val() ) ) {
					input_email.attr( 'placeholder', travel_booking.message_er_email );
					input_email.addClass( 'error' );
					input_email.val( '' );
					return;
				}
			}

			if ( input_phone.length > 0 ) {
				if ( input_phone.val().length == 0 ) {
					input_phone.attr( 'placeholder', travel_booking.message_er_phone );
					input_phone.addClass( 'error' );
					return;
				}
			}

			if ( el_input_date_book.length > 0 ) {
				if ( el_input_date_book.val().length == 0 ) {
					el_input_date_book.attr( 'placeholder', travel_booking.message_er_date );
					el_input_date_book.addClass( 'error' );
					return;
				}
			}

			if ( isNaN( qty_tour ) || qty_tour <= 0 ) {
				input_qty.val( 1 );
			} else if ( qty_tour > max_qty_per ) {
				return;
			}

			function checkValidateEmail( email ) {
				const regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				return regex.test( email );
			}

			// run ajax add to cart
			if ( ! el_btn_booking.hasClass( 'disable' ) ) {
				const data = {
					nonce: $( 'input[name=nonce]' ).val(),
					tour_id: $( 'input[name=tour_id]' ).val(),
					date_booking: el_input_date_book.val(),
				};

				data.number_ticket = input_qty.val();

				/*** Children ***/
				if ( el_number_children.length > 0 ) {
					let number_children = el_number_children.val();

					if ( isNaN( number_children ) || parseFloat( number_children ) < 0 ) {
						number_children = 0;
					}

					data.number_children = number_children;
				}

				/*** Variation ***/
				if ( el_tour_variations_options.length > 0 ) {
					data.tour_variations = {};

					$.each( el_tour_variation_item, function( i ) {
						const el_tour_variation_item = $( this );
						const variation_id = el_tour_variation_item.data( 'variation-id' );
						const variation_type = el_tour_variation_item.data( 'type_variation' );
						const variation_required = parseInt( el_tour_variation_item.data( 'required' ) );
						const el_variation_attr_field = el_tour_variation_item.find( '.field-travel-booking' );
						const total_el_variation_attr_field = el_variation_attr_field.length;
						let variation_attr_id = 0;

						if ( variation_type === 'quantity' && ! flag_errors ) {
							let flag_error_variation_quantity = true;

							$.each( el_variation_attr_field, function( i ) {
								const el_variation_attr_field = $( this );
								variation_attr_id = parseInt( el_variation_attr_field.attr( 'name' ) );

								if ( parseFloat( el_variation_attr_field.val() ) > 0 ) {
									if ( data.tour_variations[ variation_id ] === undefined ) {
										data.tour_variations[ variation_id ] = {};
									}
									data.tour_variations[ variation_id ][ variation_attr_id ] = {};
									data.tour_variations[ variation_id ][ variation_attr_id ].quantity = el_variation_attr_field.val();

									flag_error_variation_quantity = false;
								}

								if ( variation_required ) {
									if ( flag_error_variation_quantity && i === total_el_variation_attr_field - 1 ) {
										flag_errors = true;
										el_tour_variation_item.addClass( 'errors' );
										setTimeout( function() {
											el_tour_variation_item.removeClass( 'errors' );
										}, 2000 );
									}
								}
							} );
						} else if ( variation_type === 'select' && ! flag_errors ) {
							const el_variation_attr_field = el_tour_variation_item.find( '.field-travel-booking' );
							variation_attr_id = parseInt( el_variation_attr_field.val() );

							if ( variation_attr_id > 0 ) {
								if ( data.tour_variations[ variation_id ] === undefined ) {
									data.tour_variations[ variation_id ] = {};
								}
								data.tour_variations[ variation_id ][ variation_attr_id ] = {};
							} else if ( variation_required ) {
								flag_errors = true;
								el_variation_attr_field.addClass( 'errors' );
								setTimeout( function() {
									el_variation_attr_field.removeClass( 'errors' );
								}, 2000 );
							}
						} else if ( variation_type === 'checkbox' && ! flag_errors ) {
							let flag_errors_variation_checkbox = true;

							$.each( el_variation_attr_field, function( i ) {
								const el_variation_attr_field = $( this );
								if ( el_variation_attr_field.is( ':checked' ) ) {
									if ( data.tour_variations[ variation_id ] === undefined ) {
										data.tour_variations[ variation_id ] = {};
									}
									variation_attr_id = parseInt( el_variation_attr_field.val() );

									data.tour_variations[ variation_id ][ variation_attr_id ] = {};

									flag_errors_variation_checkbox = false;
									flag_errors = false;
								}

								if ( variation_required ) {
									if ( flag_errors_variation_checkbox && i === total_el_variation_attr_field - 1 ) {
										flag_errors = true;
										el_tour_variation_item.addClass( 'errors' );
										setTimeout( function() {
											el_tour_variation_item.removeClass( 'errors' );
										}, 2000 );
									}
								}
							} );
						} else if ( variation_type === 'radio' && ! flag_errors ) {
							let flag_errors_variation_radio = true;

							$.each( el_variation_attr_field, function( i ) {
								const el_variation_attr_field = $( this );

								if ( el_variation_attr_field.is( ':checked' ) ) {
									if ( data.tour_variations[ variation_id ] === undefined ) {
										data.tour_variations[ variation_id ] = {};
									}
									variation_attr_id = parseInt( el_variation_attr_field.val() );
									data.tour_variations[ variation_id ][ variation_attr_id ] = {};

									flag_errors_variation_radio = false;
									flag_errors = false;
								}

								if ( variation_required ) {
									if ( flag_errors_variation_radio && i === total_el_variation_attr_field - 1 ) {
										flag_errors = true;
										el_tour_variation_item.addClass( 'errors' );
										setTimeout( function() {
											el_tour_variation_item.removeClass( 'errors' );
										}, 2000 );
									}
								}
							} );
						}
					} );

					//console.log( data.tour_variations );

					data.tour_variations = JSON.stringify( ( data.tour_variations ) );
				}

				/*** Price dates ***/
				if ( el_tour_date_checkin_checkout.length > 0 ) {
					if ( el_tour_date_check_in.length > 0 && el_tour_date_check_out.length > 0 ) {
						if ( el_tour_date_check_in.val() === '' ) {
							el_tour_date_check_in.attr( 'placeholder', travel_booking.message_er_date_checkin );
							el_tour_date_check_in.addClass( 'error' );
							return;
						}

						if ( el_tour_date_check_out.val() === '' ) {
							el_tour_date_check_out.attr( 'placeholder', travel_booking.message_er_date_checkout );
							el_tour_date_check_out.addClass( 'error' );
							return;
						}

						data.date_check_in = el_tour_date_check_in.val();
						data.date_check_out = el_tour_date_check_out.val();
					}
				}

				/*** Deposit ***/
				if ( el_tour_deposit.length > 0 ) {
					$.each( el_tour_deposit, function( i ) {
						if ( $( this ).is( ':checked' ) ) {
							data.webtomizer_tour_deposit = $( this ).val();
						}
					} );
				}

				// console.log(data);
				// return;
				const custom_form_fields = el_tourBookingForm.find( '.form-field.custom-field *' );

				custom_form_fields.each(function() {
					data[$( this ).attr('name')] = $( this ).val();
				});

				if ( ! flag_errors ) {
					$( '.spinner' ).show();
					el_btn_booking.addClass( 'disable' );
					$.ajax( {
						url: tb_phys_ajax_url + '?tb-ajax=add_tour_to_cart_phys',
						type: 'post',
						data,
						dataType: 'json',
						success( result ) {
							if ( result.status === 'success' ) {
								const user_info = {
									first_name: input_first_name.val(),
									last_name: input_last_name.val(),
									email: input_email.val(),
									phone: input_phone.val(),
								};

								document.cookie = 'info_user=' + JSON.stringify( user_info ) + '; path=/';
								window.location = $( 'input[name=checkout_url]' ).val();
							} else {
								alert( result.message );
							}
						}, error( result ) {
							console.error( result );
						},
					} ).done( function() {
						$( '.spinner' ).hide();
						el_btn_booking.removeClass( 'disable' );
					} );
				}
			}
		} );
	};

	$.fn.tour_orderby = function() {
		$( '.tour-ordering' ).find( '.orderby' ).on( 'change', function() {
			$( '.tour-ordering' ).submit();
		} );
	};

	$.fn.load_ajax_notify = function() {
		load_notify = false;
		const limit = $( 'input[name=notify_limit]' ).val();
		const tb_phys_ajax_url = $( 'input[name=url_home]' ).val();

		$.ajax( {
			url: tb_phys_ajax_url + '?tb-ajax=notify_new_order',
			type: 'post',
			data: 'limit=' + limit,
			dataType: 'json',
			success( result ) {
				if ( result.status == 'success' ) {
					$( '.list-order-tour' ).html( result.html );
					load_notify = true;
				}
			},
		} );
	};

	$.fn.load_notify_products_of_order = function() {
		const notify_available = $( 'input[name=notify_available]' );
		if ( notify_available.length ) {
			load_notify = true;
			let refresh = $( 'input[name=notify_refresh]' ).val();
			if ( refresh == '' ) {
				refresh = 10000;
			}

			setInterval( function() {
				if ( load_notify ) {
					$.fn.load_ajax_notify();
				}
			}, refresh );
		}
	};

	$.fn._convert_date_to_format_default = function( date_string ) {
		let date_convert = '';
		let date = '';
		let month = '';
		let year = '';
		const separator_date = '/';

		if ( $._tour_date_format === 'yy/mm/dd' ) {
			const date_arr = date_string.split( '/' );
			date = date_arr[ 2 ];
			month = date_arr[ 1 ];
			year = date_arr[ 0 ];
		} else if ( $._tour_date_format === 'yy-mm-dd' ) {
			const date_arr = date_string.split( '-' );
			date = date_arr[ 2 ];
			month = date_arr[ 1 ];
			year = date_arr[ 0 ];
		} else if ( $._tour_date_format === 'dd/mm/yy' ) {
			const date_arr = date_string.split( '/' );
			date = date_arr[ 0 ];
			month = date_arr[ 1 ];
			year = date_arr[ 2 ];
		} else if ( $._tour_date_format === 'dd-mm-yy' ) {
			const date_arr = date_string.split( '-' );
			date = date_arr[ 0 ];
			month = date_arr[ 1 ];
			year = date_arr[ 2 ];
		} else if ( $._tour_date_format === 'mm/dd/yy' ) {
			const date_arr = date_string.split( '/' );
			date = date_arr[ 1 ];
			month = date_arr[ 0 ];
			year = date_arr[ 2 ];
		} else if ( $._tour_date_format === 'mm-dd-yy' ) {
			const date_arr = date_string.split( '-' );
			date = date_arr[ 1 ];
			month = date_arr[ 0 ];
			year = date_arr[ 2 ];
		} else if ( $._tour_date_format === 'DD, d MM, yy' ) {
			const date_arr = date_string.split( ',' );
			const date_month_arr = date_arr[ 1 ].trim().split( ' ' );
			date = date_month_arr[ 0 ];
			month = tour_month.indexOf( date_month_arr[ 1 ] ) + 1;

			if ( parseInt( month ) < 10 ) {
				month = '0' + month;
			}

			year = date_arr[ 2 ];
		}

		date_convert += year + separator_date + month + separator_date + date;

		// console.log(date_convert);

		return date_convert;
	};

	$.fn._convert_date_obj_by_format_default = function( date_obj ) {
		let date_convert = '';
		const year = date_obj.getFullYear();
		const month = ( '0' + ( date_obj.getMonth() + 1 ) ).slice( -2 );
		const date = ( '0' + date_obj.getDate() ).slice( -2 );

		date_convert += year + '/' + month + '/' + date;

		return date_convert;
	};

	$.fn._calculate_total_days = function( start_date, end_date ) {
		const start_date_obj = new Date( start_date );
		const end_date_obj = new Date( end_date );
		const date_next = start_date_obj;
		let total_day = 0;

		while ( date_next <= end_date_obj ) {
			date_next.setDate( date_next.getDate() + 1 );
			total_day += 1;
		}

		return total_day;
	};

	/**
	 * Total night
	 *
	 * @param string start_date
	 * @param start_date_obj
	 * @param end_date_obj
	 * @return {number}
	 */
	$.fn._calculate_total_night = function( start_date_obj, end_date_obj ) {
		const date_next = new Date( start_date_obj.getTime() );
		let total_day = 0;

		while ( date_next < end_date_obj ) {
			date_next.setDate( date_next.getDate() + 1 );
			total_day += 1;
		}

		return total_day;
	};

	$.fn._show_range_dates_choose = function() {
		const tour_date_check_in = el_tour_date_check_in.val();
		const tour_date_check_out = el_tour_date_check_out.val();

		if ( tour_date_check_in != '' && tour_date_check_out != '' ) {
			$._tour_dates_choose = [];
			const start_date = new Date( $.fn._convert_date_to_format_default( el_tour_date_check_in.val() ) );
			const end_date = new Date( $.fn._convert_date_to_format_default( el_tour_date_check_out.val() ) );
			const date_next = start_date;

			end_date.setDate( end_date.getDate() + 1 );

			while ( date_next < end_date ) {
				// console.log(date_next);
				const date_string_by_format_default = $.fn._convert_date_obj_by_format_default( date_next );
				$._tour_dates_choose.push( date_string_by_format_default );
				date_next.setDate( date_next.getDate() + 1 );
			}

			const function_anonymous_beforeShowDay = function( date ) {
				date = $.fn._convert_date_obj_by_format_default( date );
				// console.log(date);
				const indexOf = $._tour_dates_choose.indexOf( date );

				// console.log(indexOf, indexOfDatesDisable)
				if ( $._dates_disable !== null ) {
					const indexOfDatesDisable = $._dates_disable.indexOf( date );

					if ( indexOf > -1 ) {
						if ( indexOfDatesDisable > -1 ) {
							if ( date == start_date ) {
								return [ false, 'date-picked start-date', '' ];
							} else if ( date == end_date ) {
								return [ false, 'date-picked end-date', '' ];
							}
							return [ false, 'date-picked', '' ];
						}
						if ( date == start_date ) {
							return [ true, 'date-picked start-date', 'Available' ];
						} else if ( date == end_date ) {
							return [ true, 'date-picked end-date', 'Available' ];
						}
						return [ true, 'date-picked', 'Available' ];
					}
					if ( indexOfDatesDisable > -1 ) {
						return [ false, '', '' ];
					}
					return [ true, '', 'Available' ];
				}
				if ( indexOf > -1 ) {
					if ( date == start_date ) {
						return [ true, 'date-picked start-date', '' ];
					} else if ( date == end_date ) {
						return [ true, 'date-picked end-date', '' ];
					}
					return [ true, 'date-picked', '' ];
				}
				return [ true, '', '' ];
			};

			const function_anonymous_beforeShowDay_x = function( date ) {
				date = $.fn._convert_date_obj_by_format_default( date );
				// console.log(date);
				const indexOf = $._tour_dates_choose.indexOf( date );

				// console.log(indexOf, indexOfDatesDisable)

				if ( indexOf > -1 ) {
					if ( date == start_date ) {
						return [ true, 'date-picked start-date', '' ];
					} else if ( date == end_date ) {
						return [ true, 'date-picked end-date', '' ];
					}
					return [ true, 'date-picked', '' ];
				}
				return [ true, '', '' ];
			};

			if ( jQuery().datepicker ) {
				el_tour_date_check_in.datepicker( 'option', 'beforeShowDay', function_anonymous_beforeShowDay );

				el_tour_date_check_out.datepicker( 'option', 'beforeShowDay', function_anonymous_beforeShowDay_x );
			}
		}

		//console.log(start_date, end_date);
	};

	$.fn.filter_tour_price_widget = function() {
		const el_tour_price_range = document.getElementById( 'tour-price-range' );

		if ( el_tour_price_range ) {
			const el_tour_min_price_show = $( '.tour-min-price' );
			const el_tour_max_price_show = $( '.tour-max-price' );
			const tour_start_price_filter = parseFloat( $( 'input[name=tour_start_price_fitler]' ).val() );
			const tour_end_price_filter = parseFloat( $( 'input[name=tour_end_price_filter]' ).val() );
			const el_tour_max_price = $( 'input[name=tour_max_price]' );
			const el_tour_min_price = $( 'input[name=tour_min_price]' );
			const min_price = parseFloat( el_tour_min_price.val() );
			const max_price = parseFloat( el_tour_max_price.val() );

			// console.log(min_price, max_price);
			if ( min_price < max_price ) {
				if ( ! el_tour_price_range.noUiSlider ) {
					window.noUiSlider.create(el_tour_price_range, {
						start: [min_price, max_price],
						connect: true,
						range: {
							min: tour_start_price_filter,
							max: tour_end_price_filter,
						},
					});
				}

				el_tour_price_range.noUiSlider.on( 'update', function( values ) {
					// console.log(values);
					const min_price = values[ 0 ];
					const max_price = values[ 1 ];

					el_tour_min_price.val( min_price );
					el_tour_max_price.val( max_price );
					el_tour_min_price_show.text( min_price );
					el_tour_max_price_show.text( max_price );
				} );
			}
		}
	};
	$.fn.tour_price_format = function (val) { 
		val = val.toFixed(num_decimals);
		const parts = val.split('.');
		if (travel_booking.thousand_separator) {
			parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, travel_booking.thousand_separator);
		}
		return parts.join(travel_booking.decimal_separator || '.');
	};

	function getCookie( cname ) {
		const name = cname + '=';
		const decodedCookie = decodeURIComponent( document.cookie );
		const ca = decodedCookie.split( ';' );
		for ( let i = 0; i < ca.length; i++ ) {
			let c = ca[ i ];
			while ( c.charAt( 0 ) == ' ' ) {
				c = c.substring( 1 );
			}
			if ( c.indexOf( name ) == 0 ) {
				return c.substring( name.length, c.length );
			}
		}
		return '';
	}
}( jQuery, 'tour-booking-phys' ) );

jQuery( function( $ ) {
	'use strict';
	$.fn.tour_booking_init();
	$.fn.tour_booking_submit();
	// $.fn.tour_orderby();
	$.fn.load_notify_products_of_order();
	$.fn.filter_tour_price_widget();
	$(document).ready(function() {
        $.fn.update_tour_total_price();
    });

	if ( $( '#tour_rating' ).length ) {
		$( '#tour_rating' ).barrating( {
			theme: 'css-stars',
		} );
	}
} );
