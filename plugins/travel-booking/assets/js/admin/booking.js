/**
 * Travel booking js
 *
 * @author physcode
 * @version 2.0.4
 */

;(function ($) {
	'use strict';

	$._default_date_format = 'yy/mm/dd';
	$._default_date_format_datepicker_range = 'YYYY/MM/DD';

	var date_arr = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	$.fn.init_booking = function () {
		var el_regular_price = $('input[name=_regular_price]');
		var el_sale_price = $('input[name=_sale_price]');
		var el_phys_tour_regular_price = $('input[name=phys_tour_regular_price]');
		var el_phys_tour_sale_price = $('input[name=phys_tour_sale_price]');

		// set value for input name = "_regular_price"
		el_phys_tour_regular_price.on('keyup', function () {
			el_regular_price.val($(this).val());
		});

		// set value for input name = "_sale_price"
		el_phys_tour_sale_price.on('keyup', function () {
			el_sale_price.val($(this).val());
		});
	};

	$.fn.js_product_list_woo = function () {
		$('.wp-submenu').find('.wp-first-item').addClass('current');
		$('.wp-submenu').find('li.current').removeClass('current');
	};

	$.fn.js_custom_tab = function () {
		$('ul.tabs li').click(function () {

			var tab_id = $(this).attr('data-tab');
			console.log(tab_id);
			$('ul.tabs li').removeClass('current');
			$('.tab-content').removeClass('current');
			$(this).addClass('current');
			$("#" + tab_id).addClass('current');
		})
	};

	$.fn.active_menu_tour = function () {
    var $flag_set_current_tour = false
    var $select_product_type = $('select[name=product-type]')
    var urlCurrent = new URL(window.location.href)
    var param_product_type = urlCurrent.searchParams.get('product_type')

    if ('tour_phys' === param_product_type) {
      $flag_set_current_tour = true
    }
		if ($select_product_type.length > 0 && $select_product_type.val() == 'tour_phys') {
			$flag_set_current_tour = true;
		}

		if ($flag_set_current_tour) {
			var pattern = 'edit.php?post_type=product&product_type=tour_phys';
			$('li.current').removeClass('current');
			$('a[href$="' + pattern + '"]').parent().addClass('current')
		}
	};

	$.fn.show_inventory = function () {
		var el_product_type = $('#product-type');

		if (el_product_type.val() == 'tour_phys') {
			$('.inventory_options').addClass('show_if_tour_phys');
			$('._manage_stock_field').addClass('show_if_tour_phys');
		}
	};

	let el_tour_variants_data = null;
	$.fn.tour_variation = function () {
		var wrapper_tour_variation_item = $('.wrapper-tour-variant-item');
		var tour_variation_phys = $('#tour_variation_phys');
		el_tour_variants_data = $('input[name=_tour_variations_options]');

		if (tour_variation_phys.length > 0) {
			let el_wrapper_tour_variants = tour_variation_phys.find('.wrapper-tour-variants');
			let el_wrapper_tour_variation_attribute = tour_variation_phys.find('.wrapper-tour-variation-attribute');
			let tour_variation_item_structure_fields = $('input[name=tour_variation_item_structure_fields]').val();
			let tour_variation_item_structure_fields_obj = JSON.parse(tour_variation_item_structure_fields);

			try {
				// console.log(tour_variation_item_structure_fields_obj);

				/*** Add new variation ***/
				$('#new-tour-variations').on('click', function (e) {
					e.preventDefault();
					let el_parent = $(this).parent();
					let date = new Date();
					let time = date.getTime();

					let el_variation_item_content = '';

					el_variation_item_content += '<div class="wrapper-tour-variant-item open">';
					el_variation_item_content += '<div class="header-tour-variation-item">' +
						'<h3>Item new</h3>' +
						'<span class="toggle-variation-item toggle-up" aria-hidden="true"></span>' +
						'</div>';
					el_variation_item_content += '<div class="tour-variant-item" data-variation-id="' + time + '">';

					$.each(tour_variation_item_structure_fields_obj, function (k, v) {

						if (k !== 'variation_attr') {
							el_variation_item_content += '<p class="variation-field">';
							el_variation_item_content += '<span>' + v.label + '</span>';
							if (typeof v.types === 'object') {
								el_variation_item_content += '<select class="field"  name="' + k + '">';
								$.each(v.types, function (k_type, v_type) {
									el_variation_item_content += '<option value="' + k_type + '">' + v_type.label + '</option>';
								});
								el_variation_item_content += '</select>';
							} else if (v.types === 'input_text') {
								el_variation_item_content += '<input type="text" name="' + k + '" class="field" >';
							}
							el_variation_item_content += '</p>';
						} else {
							el_variation_item_content += '<div class="variation-field-attrs" name="' + k + '">';
							el_variation_item_content += '<button class="new-variation-attr btn">Add new attribute</button>';
							el_variation_item_content += '<div class="wrapper-tour-variation-attribute">';
							el_variation_item_content += $.fn.set_tour_variation_attr_content(v);
							el_variation_item_content += '</div>';
							el_variation_item_content += '</div>';

						}
					});
					el_variation_item_content += '<span class="remove-variant dashicons dashicons-no"></span>';
					el_variation_item_content += '</div>';
					el_variation_item_content += '</div>';

					if (el_wrapper_tour_variants.find('.wrapper-tour-variant-item:first-child').length > 0) {
						el_wrapper_tour_variants.find('.wrapper-tour-variant-item:first-child').before(el_variation_item_content);
					} else {
						el_wrapper_tour_variants.append(el_variation_item_content);
					}
				});

				/*** Remove variation ***/
				tour_variation_phys.on('click', '.remove-variant', function (e) {
					e.preventDefault();
					if (confirm('Do you want remove this Variant?')) {
						// Remove it on Dates and Prices
						let variation_id = $(this).closest('.tour-variant-item').data('variation-id');
						if (el_phys_price_of_dates_option.length > 0 && el_phys_price_of_dates_option.val() !== '') {
							let price_dates_option = JSON.parse(el_phys_price_of_dates_option.val());
							let price_dates_ranges = price_dates_option['price_dates_range'];

							$.each(price_dates_ranges, function (i, v) {
								let item_prices = v.prices;

								delete item_prices[variation_id];

								price_dates_ranges[i].prices = item_prices;

								price_dates_option['price_dates_range'] = price_dates_ranges;

								console.log(price_dates_option);

								el_phys_price_of_dates_option.val(JSON.stringify(price_dates_option));
							});
						}

						$(this).closest('.wrapper-tour-variant-item').remove();
						$.fn.save_tour_variants();
					}
				});

				/*** Add new variation attribute ***/
				tour_variation_phys.on('click', '.new-variation-attr', function (e) {
					e.preventDefault();
					let el_parent = $(this).closest('.wrapper-tour-variant-item');

					let tour_variation_attr_item_html = $.fn.set_tour_variation_attr_content(tour_variation_item_structure_fields_obj.variation_attr);

					el_parent.find('.wrapper-tour-variation-attribute').find('.tour-variation-attribute:first-child').before(tour_variation_attr_item_html);

					/*** Sort ***/
					el_parent.find('.wrapper-tour-variation-attribute').sortable({
						update: $.fn.save_tour_variants
					});
				});

				/*** Remove variation attribute ***/
				tour_variation_phys.on('click', '.remove-variant-attr', function (e) {
					if (confirm('Do you want remove this Variant Attribute?')) {
						e.preventDefault();

						// Remove it on Dates and Prices
						let variation_id = $(this).closest('.tour-variant-item').data('variation-id');
						let attr_id = $(this).parent().data('attr-id');
						if (el_phys_price_of_dates_option.length > 0 && el_phys_price_of_dates_option.val() !== '') {
							let price_dates_option = JSON.parse(el_phys_price_of_dates_option.val());
							let price_dates_ranges = price_dates_option['price_dates_range'];


							$.each(price_dates_ranges, function (i, v) {
								let item_prices = v.prices;

								delete item_prices[variation_id][attr_id];

								price_dates_ranges[i].prices = item_prices;

								price_dates_option['price_dates_range'] = price_dates_ranges;

								console.log(price_dates_option);

								el_phys_price_of_dates_option.val(JSON.stringify(price_dates_option));
							});
						}

						// Save
						$(this).parent().remove();
						$.fn.save_tour_variants();
					}
				});

				/*** Save variation ***/
				tour_variation_phys.on('change keyup', '.field', function (e) {
					$.fn.save_tour_variants();
				});

				/*** Toggle variation item ***/
				tour_variation_phys.on('click', '.toggle-variation-item', function () {
					let el = $(this);

				});
				tour_variation_phys.on('click', '.header-tour-variation-item', function () {
					let el = $(this);
					let wrapper_tour_variation_item = el.closest('.wrapper-tour-variant-item');
					let el_toggle_variation_item = el.find('.toggle-variation-item');

					if (wrapper_tour_variation_item.hasClass('open')) {
						el_toggle_variation_item.removeClass('toggle-up');
						el_toggle_variation_item.addClass('toggle-down');
						wrapper_tour_variation_item.removeClass('open');
						wrapper_tour_variation_item.find('.tour-variant-item').hide();
					} else {
						el_toggle_variation_item.removeClass('toggle-down');
						el_toggle_variation_item.addClass('toggle-up');
						wrapper_tour_variation_item.addClass('open');
						wrapper_tour_variation_item.find('.tour-variant-item').show();
					}
				});

				/*** Sort Variation item***/
				el_wrapper_tour_variants.sortable({

					update: $.fn.save_tour_variants,
					cancel: "div.tour-variant-item",
				});

				/*** Sort Variation attribute item***/
				el_wrapper_tour_variation_attribute.sortable({
					update: $.fn.save_tour_variants
				});
			} catch (e) {
				console.error(e);
			}
		}
	};

	$.fn.set_tour_variation_attr_content = function (structure_variation_attr) {
		let tour_variation_attr_item_html = '';
		let date = new Date();
		let time = date.getTime();

		tour_variation_attr_item_html += '<ul class="tour-variation-attribute el-new" data-attr-id="' + time + '">';
		tour_variation_attr_item_html += '<li><h4><strong>Attribute:</strong></h4></li>';

		$.each(structure_variation_attr, function (k_attr, v_attr) {
			tour_variation_attr_item_html += '<li>';
			tour_variation_attr_item_html += '<span>' + v_attr.label + '</span>';
			tour_variation_attr_item_html += '<input class="field" type="text" name="' + k_attr + '" >';
			tour_variation_attr_item_html += '</li>';
		});
		tour_variation_attr_item_html += '<span class="remove-variant-attr dashicons dashicons-no-alt"></span>';
		tour_variation_attr_item_html += '</ul>';

		setTimeout(function () {
			$('.tour-variation-attribute.el-new').removeClass('el-new');
		}, 500);

		return tour_variation_attr_item_html;
	};

	$.fn.save_tour_variants = function () {
		let tour_variations_options = {};

		$('.tour-variant-item').each(function (i) {
			let el_tour_variant = $(this);
			let variation_id = el_tour_variant.data('variation-id');

			tour_variations_options[variation_id] = {};

			$.each(el_tour_variant.find('.variation-field'), function () {
				let el_variation_field = $(this);
				let el_field = el_variation_field.find('.field');
				let name_field = el_field.attr('name');

				tour_variations_options[variation_id][name_field] = el_field.val();

				// For toggle change header variation
				if (name_field === 'label_variation') {
					let el_h3 = el_variation_field.closest('.wrapper-tour-variant-item').find('.header-tour-variation-item').find('h3')
					if (el_field.val() === '') {
						el_h3.text('Item new');
					} else {
						el_h3.text(el_field.val());
					}
				}
			});

			/*** Attributes ***/
			let el_tour_variation_field_attrs = el_tour_variant.find('.variation-field-attrs');
			let name_field_attr = el_tour_variation_field_attrs.attr('name');

			tour_variations_options[variation_id][name_field_attr] = {};

			$.each(el_tour_variation_field_attrs.find('.tour-variation-attribute'), function () {
				let el_variation_attrs = $(this);
				let variation_attr_id = el_variation_attrs.data('attr-id');
				let el_variation_attr_fields = el_variation_attrs.filter('[data-attr-id="' + variation_attr_id + '"]');

				tour_variations_options[variation_id][name_field_attr][variation_attr_id] = {};

				$.each(el_variation_attr_fields.find('.field'), function () {
					let el_field = $(this);
					let name_field = el_field.attr('name');

					tour_variations_options[variation_id][name_field_attr][variation_attr_id][name_field] = el_field.val();

					// Change label on Tab "Dates and Price"
					if (name_field === 'label') {
						if (el_phys_price_of_dates_option.length > 0 && el_phys_price_of_dates_option.val() !== '') {
							let price_dates_option = JSON.parse(el_phys_price_of_dates_option.val());
							let price_dates_ranges = price_dates_option['price_dates_range'];

							$.each(price_dates_ranges, function (i, v) {
								let item_prices = v.prices;

								if (item_prices !== undefined &&
									item_prices[variation_id] !== undefined &&
									item_prices[variation_id][variation_attr_id] !== undefined &&
									item_prices[variation_id][variation_attr_id][name_field] !== undefined
								) {
									item_prices[variation_id][variation_attr_id][name_field] = el_field.val();
								} else {
									if (item_prices[variation_id] === undefined) {
										item_prices[variation_id] = {};
									}

									if (item_prices[variation_id][variation_attr_id] === undefined) {
										item_prices[variation_id][variation_attr_id] = {};

										item_prices[variation_id][variation_attr_id]['label'] = el_field.val();
										item_prices[variation_id][variation_attr_id]['price'] = '';
									}
								}

								price_dates_ranges[i].prices = item_prices;

								price_dates_option['price_dates_range'] = price_dates_ranges;

								console.log(price_dates_option);

								el_phys_price_of_dates_option.val(JSON.stringify(price_dates_option));
							});
						}
					}
				});
			});
		});

		console.log(tour_variations_options);
		el_tour_variants_data.val(JSON.stringify(tour_variations_options));
	};

    $.fn.tour_faq = function () {
        const physTourFaq = document.querySelector('#phys_tour_faq');

        if (physTourFaq) {
            let wrapperTourFaqs = physTourFaq.querySelector('.wrapper-tour-faqs');
            const itemStructureFields = physTourFaq.querySelector('input[name=tour_faq_item_structure_fields]').value;
            let itemStructureFieldsObj = JSON.parse(itemStructureFields);
            const addNewBtn = physTourFaq.querySelector('#new-tour-faq');
            try {
                /*** Add new faq ***/
                addNewBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    let date = new Date();
                    let time = date.getTime();

                    let html = '';

                    html += `<div class="wrapper-tour-faq-item open">
								<div class="header-tour-faq-item">
									<h3>Item new</h3>
										<span class="toggle-faq-item toggle-up" aria-hidden="true"></span>
								</div>
								<div class="tour-faq-item" data-faq-id="${time}">`;

                    for (const key in itemStructureFieldsObj) {
                        const value = itemStructureFieldsObj[key];

                        html += `<p class="faq-field">
						            <span>${value.label}</span>`;
                        if (typeof value.types === 'object') {
                            html += `<select class="field"  name="${key}">`;

                            for (const k_type in value.types) {
                                const v_type = value.types[k_type];
                                html += `<option value="${k_type}">${v_type.label}</option>`;
                            }
                            html += '</select>';
                        } else if (value.types === 'input_text') {
                            html += `<input type="text" name="${key}" class="field" >`;
                        }else if (value.types === 'textarea') {
                            html += `<textarea type="text" name="${key}" class="field" ></textarea>`;
                        }

                        html += '</p>';
                    }
                    html += `<span class="remove-faq dashicons dashicons-no"></span>
                         </div>
                    </div>`;

                    if (wrapperTourFaqs.querySelector('.wrapper-tour-faq-item')) {
                        wrapperTourFaqs.insertAdjacentHTML('afterbegin', html);
                    } else {
                        wrapperTourFaqs.innerHTML = html;
                    }
                });

                //Toggle header
                document.addEventListener('click', function(event){
                    const target = event.target;
                    if(!target.closest('.header-tour-faq-item') && ! target.classList.contains('header-tour-faq-item')){
                        return;
                    }
                    const faqItemWrapper = target.closest('.wrapper-tour-faq-item ');
                    const toggleFaqItem = faqItemWrapper.querySelector('.toggle-faq-item');

                    if (faqItemWrapper.classList.contains('open')) {
                        toggleFaqItem.classList.remove('toggle-up');
                        toggleFaqItem.classList.add('toggle-down');
                        faqItemWrapper.classList.remove('open');
                        faqItemWrapper.querySelector('.tour-faq-item').style.display = 'none';
                    } else {
                        toggleFaqItem.classList.remove('toggle-down');
                        toggleFaqItem.classList.add('toggle-up');
                        faqItemWrapper.classList.add('open');
                        faqItemWrapper.querySelector('.tour-faq-item').style.display = 'block';
                    }
                });
                /*** Remove faq ***/
                document.addEventListener('click', function (event) {
                    const target = event.target;

                    if (!target.classList.contains('remove-faq')) {
                        return;
                    }

                    const faqItemNode = target.closest('.tour-faq-item');

                    if (!faqItemNode) {
                        return;
                    }

                    if (confirm('Do you want remove this faq?')) {
                        const faqId = faqItemNode.getAttribute('faq-id');
                        faqItemNode.closest('.wrapper-tour-faq-item').remove();
                        $.fn.save_tour_faqs();
                    }
                });

                /*** Save faq ***/
                document.addEventListener('keyup', function (event) {
                    const target = event.target;
                    if (!target.closest('.faq-field')) {
                        return;
                    }
                    $.fn.save_tour_faqs();
                })

                //Textarea
                document.addEventListener('input', function (event) {
                    const target = event.target;
                    if (!target.closest('.faq-field')) {
                        return;
                    }
                    $.fn.save_tour_faqs();
                })

                //Change
                document.addEventListener('keyup', function (event) {
                    const target = event.target;
                    if (!target.closest('.header-tour-faq-item') && !target.classList.contains('header-tour-faq-item')) {
                        return;
                    }

                    $.fn.save_tour_faqs();
                })


                /*** Sort faq item***/
                $('.wrapper-tour-faqs').sortable({
                    update: $.fn.save_tour_faqs,
                    cancel: "div.tour-faq-item",
                });
            } catch (e) {
                console.error(e);
            }
        }
    };

    $.fn.save_tour_faqs = function () {
        let tourFaqOptions = {};

        const faqItemNodes = document.querySelectorAll('.tour-faq-item');
        if (faqItemNodes) {
            [...faqItemNodes].map(faqItemNode => {
                const faqId = faqItemNode.getAttribute('data-faq-id');
                tourFaqOptions[faqId] = {};

                const faqFields = faqItemNode.querySelectorAll('.faq-field');
                [...faqFields].map(faqField => {
                    const field = faqField.querySelector('.field');
                    const fieldName = field.name;
                    tourFaqOptions[faqId][fieldName] = field.value;
                });
            });
        }

        document.querySelector('input[name="phys_tour_faq_options"]').value = JSON.stringify(tourFaqOptions);
	};
	$.fn.tour_hightlight = function () {
		const physTourHightlight = document.querySelector('#phys_tour_hightlight');

		if (physTourHightlight) {
			let wrapperTourHightlights = physTourHightlight.querySelector('.wrapper-tour-hightlights');
			const itemStructureFields = physTourHightlight.querySelector('input[name=tour_hightlight_item_structure_fields]').value;
			let itemStructureFieldsObj = JSON.parse(itemStructureFields);
			const addNewBtn = physTourHightlight.querySelector('#new-tour-hightlight');

			try {
				addNewBtn.addEventListener('click', function (e) {
					e.preventDefault();
					let date = new Date();
					let time = date.getTime();

					let html = '';

					html += `<div class="wrapper-tour-hightlight-item open">
                            <div class="header-tour-hightlight-item">
                                <h3>Item new</h3>
                                <span class="toggle-hightlight-item toggle-up" aria-hidden="true"></span>
                            </div>
                            <div class="tour-hightlight-item" data-hightlight-id="${time}">`;

					for (const key in itemStructureFieldsObj) {
						const value = itemStructureFieldsObj[key];
						html += `<div class="hightlight-field">
                                <span>${value.label}</span>`;
						if (typeof value.types === 'object') {
							html += `<select class="field" name="${key}">`;

							for (const k_type in value.types) {
								const v_type = value.types[k_type];
								html += `<option value="${k_type}">${v_type.label}</option>`;
							}
							html += '</select>';
						} else if (value.types === 'input_text') {
							html += `<input type="text" name="${key}" class="field">`;
						} else if (value.types === 'textarea') {
							html += `<textarea rows="3" name="${key}" class="field"></textarea>`;
						} else if (value.types === 'image') {
							const uniqueId = `${key}_${time}`;
							html += `<div class="hightlight-field-image">
							<img id="${uniqueId}_preview" src="" alt="" style="max-width: 100px; height: auto; margin-top: 10px; display: none;">
							<button type="button" class="button upload_image_button" data-target="#${uniqueId}">Select Image</button>
							<input type="hidden" id="${uniqueId}" name="${key}" class="field"></div>`;
						}
						html += '</div>';
					}
					html += `<span class="remove-hightlight dashicons dashicons-no"></span>
                        </div>
                    </div>`;

					if (wrapperTourHightlights.querySelector('.wrapper-tour-hightlight-item')) {
						wrapperTourHightlights.insertAdjacentHTML('afterbegin', html);
					} else {
						wrapperTourHightlights.innerHTML = html;
					}

					$('.upload_image_button').off('click').on('click', handleImageUploadButtonClick);
				});
				$('.upload_image_button').off('click').on('click', handleImageUploadButtonClick);
				function handleImageUploadButtonClick(e) {
					e.preventDefault();

					var button = $(this);
					var target = $(button.data('target'));
					var preview = $(`#${target.attr('id')}_preview`);

					var frame = wp.media({
						title: 'Select or Upload an Image',
						button: {
							text: 'Use this image'
						},
						multiple: false
					});

					frame.on('select', function () {
						var attachment = frame.state().get('selection').first().toJSON();
						target.val(attachment.id);
						preview.attr('src', attachment.sizes.thumbnail.url).show();
						$.fn.save_tour_hightlights();
					});

					frame.open();
				}


				document.addEventListener('click', function (event) {
					const target = event.target;
					if (!target.closest('.header-tour-hightlight-item') && !target.classList.contains('header-tour-hightlight-item')) {
						return;
					}
					const HightlightItemWrapper = target.closest('.wrapper-tour-hightlight-item');
					const toggleHightlightItem = HightlightItemWrapper.querySelector('.toggle-hightlight-item');

					if (HightlightItemWrapper.classList.contains('open')) {
						toggleHightlightItem.classList.remove('toggle-up');
						toggleHightlightItem.classList.add('toggle-down');
						HightlightItemWrapper.classList.remove('open');
						HightlightItemWrapper.querySelector('.tour-hightlight-item').style.display = 'none';
					} else {
						toggleHightlightItem.classList.remove('toggle-down');
						toggleHightlightItem.classList.add('toggle-up');
						HightlightItemWrapper.classList.add('open');
						HightlightItemWrapper.querySelector('.tour-hightlight-item').style.display = 'block';
					}
				});

				document.addEventListener('click', function (event) {
					const target = event.target;

					if (!target.classList.contains('remove-hightlight')) {
						return;
					}

					const HightlightItemNode = target.closest('.tour-hightlight-item');

					if (!HightlightItemNode) {
						return;
					}

					if (confirm('Do you want remove this hightlight?')) {
						HightlightItemNode.closest('.wrapper-tour-hightlight-item').remove();
						$.fn.save_tour_hightlights();
					}
				});

				document.addEventListener('keyup', function (event) {
					const target = event.target;
					if (!target.closest('.hightlight-field')) {
						return;
					}
					$.fn.save_tour_hightlights();
				});

				document.addEventListener('input', function (event) {
					const target = event.target;
					if (!target.closest('.hightlight-field')) {
						return;
					}
					$.fn.save_tour_hightlights();
				});

				document.addEventListener('keyup', function (event) {
					const target = event.target;
					if (!target.closest('.header-tour-hightlight-item') && !target.classList.contains('header-tour-hightlight-item')) {
						return;
					}

					$.fn.save_tour_hightlights();
				});

				$('.wrapper-tour-hightlights').sortable({
					update: $.fn.save_tour_hightlights,
					cancel: "div.tour-hightlight-item",
				});
			} catch (e) {
				console.error(e);
			}
		}
	};

	$.fn.save_tour_hightlights = function () {
		let tourHightlightOptions = {};

		const HightlightItemNodes = document.querySelectorAll('.tour-hightlight-item');
		if (HightlightItemNodes) {
			[...HightlightItemNodes].forEach(HightlightItemNode => {
				const HightlightId = HightlightItemNode.getAttribute('data-hightlight-id');
				tourHightlightOptions[HightlightId] = {};

				const HightlightFields = HightlightItemNode.querySelectorAll('.hightlight-field');
				[...HightlightFields].forEach(HightlightField => {
					const field = HightlightField.querySelector('.field');
					const fieldName = field.name;
					console.log(field)
					tourHightlightOptions[HightlightId][fieldName] = field.value;
				});
			});
		}

		document.querySelector('input[name="phys_tour_hightlight_options"]').value = JSON.stringify(tourHightlightOptions);
	};


	
	/*** Travel Dates and prices ***/
	let el_phys_tour_dates_price = null;
	let el_phys_datepicker_dates_disable = null;
	let el_btn_disable_all_dates = null;
	let el_btn_enable_all_dates = null;
	let el_phys_dates_disable = null;
	let el_multiple_dates = null;
	let el_phys_price_of_dates_option = null;
	let el_dates_range = null;
	let el_phys_enable_tour_fixed_duration = null;
	$._dates_disable = [];
	$._years_enable = [];
	$._max_year = '';
	$._price_dates_option = {};

	$.fn._phys_tour_dates_and_price = function () {
		el_phys_tour_dates_price = $('#phys_tour_dates_price');

		if (el_phys_tour_dates_price.length > 0) {
			let now = new Date();
			el_phys_enable_tour_fixed_duration = el_phys_tour_dates_price.find('select[name=phys_enable_tour_fixed_duration]');
			var el_wrapper_phys_price_dates = el_phys_tour_dates_price.find('#wrapper-phys-price-dates');
			let el_phys_max_year_enable = el_phys_tour_dates_price.find('#phys_max_year_enable');
			el_phys_datepicker_dates_disable = el_phys_tour_dates_price.find('#phys_datepicker_dates_disable');
			el_phys_dates_disable = el_phys_tour_dates_price.find('#phys_dates_disable');
			el_btn_disable_all_dates = el_phys_tour_dates_price.find('.disable-all-dates');
			el_btn_enable_all_dates = el_phys_tour_dates_price.find('.enable-all-dates');
			el_multiple_dates = el_phys_tour_dates_price.find('#multiple-dates');
			el_dates_range = el_phys_tour_dates_price.find('#dates-range');
			el_phys_price_of_dates_option = el_phys_tour_dates_price.find('input[name=phys_price_of_dates_option]');
			let el_add_new_price_multiple_dates = el_phys_tour_dates_price.find('#add-new-price-multiple-dates');
			let el_add_new_price_dates_range = el_phys_tour_dates_price.find('#add-new-price-dates-range');
			var el_tour_duration = el_phys_tour_dates_price.find('input[name=tour_duration]');
			var el_tour_option_duration = el_phys_tour_dates_price.find('.tour-option-duration');

			if (el_phys_price_of_dates_option.length > 0 && el_phys_price_of_dates_option.val() !== '') {
				$._price_dates_option = JSON.parse(el_phys_price_of_dates_option.val());
			}

			$._max_year = el_phys_max_year_enable.val();
			if (el_phys_dates_disable.length > 0 && el_phys_dates_disable.val() !== '') {
				$._dates_disable = JSON.parse(el_phys_dates_disable.val());
			}

			/*** Fixed duration ***/
			if (el_phys_enable_tour_fixed_duration.val() == 0) {
				el_tour_option_duration.closest('p').hide();
				el_tour_duration.closest('p').hide();
			}

			el_phys_enable_tour_fixed_duration.on('change', function (e) {
				if ($(this).val() == 0) {
					el_tour_option_duration.closest('p').hide();
					// el_tour_duration.closest('p').hide();
				} else {
					el_tour_option_duration.closest('p').show();
					// el_tour_duration.closest('p').show();
				}
			});

			/*** On change Max year enable ***/
			el_phys_max_year_enable.on('change keyup', function (e) {
				$._max_year = $(this).val();

				el_phys_datepicker_dates_disable.datepicker("destroy");
				$.fn._phys_tour_datepicker_disable_dates();
			});

			/*** Disable dates ***/
			$.fn._phys_tour_datepicker_disable_dates();

			//<editor-fold desc="Set Price for Dates">

			/*** Multiple dates ***/
			if (el_multiple_dates !== null && el_multiple_dates.length > 0) {
				$.fn._phys_tour_datepicker_price_multiple_dates();
			}

			/*** Add new price multiple dates ***/
			el_add_new_price_multiple_dates.on('click', function (e) {
				e.preventDefault();

				let el = $(this);
				let el_wrapper_price_multiple_dates_item = el_multiple_dates.find('.wrapper-price-multiple-dates').find('.wrapper-price-multiple-dates-item');

				let content_item_price_multiples_date = $.fn._content_item_price_multiples_date();

				if (el_wrapper_price_multiple_dates_item.length > 0) {
					el_multiple_dates.find('.wrapper-price-multiple-dates').find('.wrapper-price-multiple-dates-item:first-child').before(content_item_price_multiples_date);
					$.fn._phys_tour_datepicker_price_multiple_dates();
				} else {
					el_multiple_dates.find('.wrapper-price-multiple-dates').append(content_item_price_multiples_date);
					$.fn._phys_tour_datepicker_price_multiple_dates();
				}
			});

			/*** Save price multiple dates ***/
			el_multiple_dates.on('change keyup', '.field_price_multiple_dates', function () {
				$.fn._save_tour_price_multiple_dates();
			});

			/*** Dates range ***/
			if (el_dates_range !== null && el_dates_range.length > 0) {
				$.fn._phys_tour_datepicker_price_dates_range();
			}

			/*** Add new price dates range ***/
			el_add_new_price_dates_range.on('click', function (e) {
				e.preventDefault();

				let el = $(this);
				let el_wrapper_price_dates_range_item = el_dates_range.find('.wrapper-price-dates-range').find('.wrapper-price-dates-range-item');

				let content_item_price_dates_range = $.fn._content_item_price_dates_range();

				if (el_wrapper_price_dates_range_item.length > 0) {
					el_dates_range.find('.wrapper-price-dates-range').find('.wrapper-price-dates-range-item:first-child').before(content_item_price_dates_range);
					$.fn._phys_tour_datepicker_price_dates_range();
				} else {
					el_dates_range.find('.wrapper-price-dates-range').append(content_item_price_dates_range);
					$.fn._phys_tour_datepicker_price_dates_range();
				}
			});

			/*** Remove item price dates range ***/
			el_dates_range.on('click', '.remove-item-price-dates', function () {
				if (confirm('Are you want remove item?')) {
					$(this).closest('.wrapper-price-dates-range-item').remove();
					$.fn._save_tour_price_dates_range();
				}
			});

			/*** Save price dates range ***/
			el_dates_range.on('change keyup', '.field_price_dates_range', function () {
				$.fn._save_tour_price_dates_range();
			});

			//</editor-fold>
		}
	};

	$.fn._phys_tour_datepicker_disable_dates = function () {
		let now = new Date();

		el_phys_datepicker_dates_disable.datepicker({
			changeMonth    : true,
			changeYear     : true,
			yearRange      : now.getFullYear() + ':' + $._max_year,
			showButtonPanel: true,
			beforeShowDay  : function (date) {
				let month = ("0" + (date.getMonth() + 1)).slice(-2);
				let day = ("0" + date.getDate()).slice(-2);

				date = date.getFullYear() + '/' + month + '/' + day;
				let indexOf = $._dates_disable.indexOf(date);

				if (indexOf > -1) {
					return [true, "date-disable", ""];
				} else {
					return [true, "date-enable", ""];
				}
			},
			dateFormat     : $._default_date_format,
			minDate        : now,
			onSelect       : function (selectedDate) {
				let indexOf = $._dates_disable.indexOf(selectedDate);

				if (indexOf === -1) {
					$._dates_disable.push(selectedDate);

					console.log($._dates_disable);

					el_phys_dates_disable.val(JSON.stringify($._dates_disable));
					return [true, "date-disable", ""];
				} else {
					$._dates_disable.splice(indexOf, 1);

					el_phys_dates_disable.val(JSON.stringify($._dates_disable));
					return [true, "date-enable", ""];
				}
			}
		});

		el_btn_disable_all_dates.on('click', function (e) {
			e.preventDefault();

			var dateNext = new Date();
			var dateEnd = new Date($._max_year + '/12/31');

			while (dateNext < dateEnd) {
				let month = ("0" + (dateNext.getMonth() + 1)).slice(-2);
				let day = ("0" + dateNext.getDate()).slice(-2);
				var dateStr = dateNext.getFullYear() + '/' + month + '/' + day;

				let indexOf = $._dates_disable.indexOf(dateStr);

				if (indexOf == -1) {
					$._dates_disable.push(dateStr);
				}

				dateNext.setDate(dateNext.getDate() + 1);
			}

			el_phys_dates_disable.val(JSON.stringify($._dates_disable));

			el_phys_datepicker_dates_disable.datepicker('option', 'beforeShowDay',
				function (date) {
					let month = ("0" + (date.getMonth() + 1)).slice(-2);
					let day = ("0" + date.getDate()).slice(-2);

					date = date.getFullYear() + '/' + month + '/' + day;
					let indexOf = $._dates_disable.indexOf(date);

					if (indexOf > -1) {
						return [true, "date-disable", ""];
					} else {
						return [true, "date-enable", ""];
					}
				}
			);
		});

		el_btn_enable_all_dates.on('click', function (e) {
			e.preventDefault();

			$._dates_disable = [];

			el_phys_dates_disable.val(JSON.stringify($._dates_disable));

			el_phys_datepicker_dates_disable.datepicker('option', 'beforeShowDay',
				function (date) {
					let month = ("0" + (date.getMonth() + 1)).slice(-2);
					let day = ("0" + date.getDate()).slice(-2);

					date = date.getFullYear() + '/' + month + '/' + day;
					let indexOf = $._dates_disable.indexOf(date);

					if (indexOf > -1) {
						return [true, "date-disable", ""];
					} else {
						return [true, "date-enable", ""];
					}
				}
			);
		});
	};

	$.fn._content_item_price_multiples_date = function () {
		let date = new Date();
		let time = date.getTime();
		let class_field = 'field_price_multiple_dates';

		let html = '<div class="wrapper-price-multiple-dates-item" data-item-id="' + time + '">' +
			'<div class="header-price-dates-item">' +
			'<h3>Multiple dates item</h3>' +
			'<span class="toggle-variation-item toggle-down" aria-hidden="true"></span>' +
			'</div>' +
			'<div class="set_date"></div>' +
			'<div class="price-dates-item">' +
			'<p><span>Regular Price</span><input type="text" name="regular_price" class="' + class_field + '"></p>' +
			'</div>' +
			'</div>';

		return html;
	};

	$.fn._phys_tour_datepicker_price_multiple_dates = function () {
		let now = new Date();

		el_multiple_dates.find('.set_date').datepicker({
			changeMonth    : true,
			changeYear     : true,
			yearRange      : now.getFullYear() + ':' + $._max_year,
			showButtonPanel: true,
			dateFormat     : $._default_date_format,
			minDate        : now,
			onSelect       : function (selectedDate) {
				let datepicker_id = $(this).attr('id');

				let el_wrapper_price_multiple_dates_item = $('#' + datepicker_id).closest('.wrapper-price-multiple-dates-item');
				let item_id = el_wrapper_price_multiple_dates_item.data('item-id');

				if ($._price_dates_option.price_multiple_dates === undefined) {
					$._price_dates_option.price_multiple_dates = {};
				}

				if ($._price_dates_option.price_multiple_dates[item_id] === undefined) {
					$._price_dates_option.price_multiple_dates[item_id] = {};
				}

				if ($._price_dates_option.price_multiple_dates[item_id].dates === undefined) {
					$._price_dates_option.price_multiple_dates[item_id].dates = [];
				}

				let indexOf = $._price_dates_option.price_multiple_dates[item_id].dates.indexOf(selectedDate);

				if (indexOf > -1) {
					$._price_dates_option.price_multiple_dates[item_id].dates.splice(indexOf, 1);
					$.fn._save_tour_price_multiple_dates();

					return [true, "", ""];
				} else {
					$._price_dates_option.price_multiple_dates[item_id].dates.push(selectedDate);
					$.fn._save_tour_price_multiple_dates();

					return [true, "datepicker-set-price", ""];
				}
			}
		});

		el_multiple_dates.find('.set_date').datepicker("option", "beforeShowDay", function (date) {
			let el = $(this);
			let el_wrapper_price_multiple_dates_item = el.closest('.wrapper-price-multiple-dates-item');
			let item_id = el_wrapper_price_multiple_dates_item.data('item-id');

			if ($._price_dates_option.price_multiple_dates !== undefined) {
				if ($._price_dates_option.price_multiple_dates[item_id] !== undefined && $._price_dates_option.price_multiple_dates[item_id].dates !== undefined) {
					let month = ("0" + (date.getMonth() + 1)).slice(-2);
					let day = ("0" + date.getDate()).slice(-2);

					date = date.getFullYear() + '/' + month + '/' + day;
					let indexOf = $._price_dates_option.price_multiple_dates[item_id].dates.indexOf(date);

					if (indexOf > -1) {
						return [true, "datepicker-set-price", ""];
					} else {
						return [true, "", ""];
					}
				} else {
					return [true, "", ""];
				}
			} else {
				return [true, "", ""];
			}
		});
	};

	let class_field_price_dates_listen_change = 'field_price_dates_range';
	$.fn._content_item_price_dates_range = function () {
		let date = new Date();
		let time = date.getTime();
		let html_content_price_dates_variation = '';

		/*** Content Price dates variation ***/
		html_content_price_dates_variation += $.fn._content_item_variation_price_dates_range();

		let html = '<div class="wrapper-price-dates-range-item" data-item-id="' + time + '">' +
			'<div class="header-price-dates-item">' +
			'<h4>Dates range</h4>' +
			'<span class="toggle-variation-item toggle-down" aria-hidden="true"></span>' +
			'</div>' +
			'<div class="set_date">' +
			'<input type="text" class="' + class_field_price_dates_listen_change + '" name="start_date" placeholder="Start date" readonly>' +
			'<input type="text" class="' + class_field_price_dates_listen_change + '" name="end_date" placeholder="End date" readonly>' +
			'</div>' +
			'<div class="price-dates-item">' +
			'<p class="normal"><span>Regular Price</span><input type="text" name="regular_price_dates" class="' + class_field_price_dates_listen_change + '"></p>' +
			'<p class="normal"><span>Child Price</span><input type="text" name="child_price_dates" class="' + class_field_price_dates_listen_change + '"></p>' +
			html_content_price_dates_variation +
			'</div>' +
			'<span class="remove-item-price-dates dashicons dashicons-no-alt" title="Remove item"></span>' +
			'</div>';

		return html;
	};

	$.fn._phys_tour_datepicker_price_dates_range = function () {
		let now = new Date();

		let start_date = el_dates_range.find('input[name=start_date]');
		let end_date = el_dates_range.find('input[name=end_date]');

		start_date.datepicker({
			changeMonth    : true,
			changeYear     : true,
			yearRange      : now.getFullYear() + ':' + $._max_year,
			showButtonPanel: true,
			dateFormat     : $._default_date_format,
			minDate        : now,
			onSelect       : function (selectedDate) {
				let el = $(this);
				let el_wrapper_price_dates_range_item = el.closest('.wrapper-price-dates-range-item');
				let item_id = el_wrapper_price_dates_range_item.data('item-id');

				let date_next = new Date(selectedDate);
				date_next.setDate(date_next.getDate() + 1);
				el_wrapper_price_dates_range_item.find('.set_date').find('input[name=end_date]').datepicker('option', 'minDate', date_next);

				$.fn._save_tour_price_dates_range();
			}
		});

		end_date.datepicker({
			changeMonth    : true,
			changeYear     : true,
			yearRange      : now.getFullYear() + ':' + $._max_year,
			showButtonPanel: true,
			dateFormat     : $._default_date_format,
			minDate        : now,
			onSelect       : function (selectedDate) {
				let el = $(this);
				let el_wrapper_price_dates_range_item = el.closest('.wrapper-price-dates-range-item');
				let item_id = el_wrapper_price_dates_range_item.data('item-id');

				$.fn._save_tour_price_dates_range();
			}
		});
	};

	$.fn._content_item_variation_price_dates_range = function () {
		let html_content = '';

		if (el_tour_variants_data !== null && el_tour_variants_data.length > 0 && el_tour_variants_data.val() !== '') {
			let tour_variations_options = JSON.parse(el_tour_variants_data.val());

			$.each(tour_variations_options, function (k, v) {
				let html_content_attribute_item = '';

				$.each(v.variation_attr, function (k_attr, v_attr) {
					html_content_attribute_item += '<p class="variation-attr-item">' +
						'<span>' + v_attr.label + '</span>' +
						'<input type="number" name="' + k_attr + '" class="' + class_field_price_dates_listen_change + '" >' +
						'</p>';
				});

				html_content += '<div class="price-dates-variation-item" data-variation-id="' + k + '">' +
					// '<span>' + v.label_variation + '</span>' +
					html_content_attribute_item +
					'</div>';
			});
		}

		return html_content;
	};

	$.fn._save_tour_price_multiple_dates = function () {
		if ($._price_dates_option.price_multiple_dates === undefined) {
			$._price_dates_option.price_multiple_dates = {};
		}

		$.each(el_multiple_dates.find('.field_price_multiple_dates'), function () {
			let item_id = $(this).closest('.wrapper-price-multiple-dates-item').data('item-id');
			let name_field = $(this).attr('name');

			if ($._price_dates_option.price_multiple_dates[item_id] === undefined) {
				$._price_dates_option.price_multiple_dates[item_id] = {};
			}

			if ($._price_dates_option.price_multiple_dates[item_id].prices === undefined) {
				$._price_dates_option.price_multiple_dates[item_id].prices = {};
			}

			$._price_dates_option.price_multiple_dates[item_id].prices[name_field] = {};
			$._price_dates_option.price_multiple_dates[item_id].prices[name_field] = {
				'label': $(this).closest('p').find('span').text(),
				'price': $(this).val()
			};
		});

		/*** Sort ***/
		let price_multiple_dates_tmp = {};

		Object.keys($._price_dates_option.price_multiple_dates).sort(function (a, b) {
			return b - a
		}).forEach(function (k, i) {
			// console.log(k, i);
			if (price_multiple_dates_tmp[k] === undefined) {
				price_multiple_dates_tmp[k] = {};
			}

			price_multiple_dates_tmp[k] = $._price_dates_option.price_multiple_dates[k];
		});

		$._price_dates_option.price_multiple_dates = price_multiple_dates_tmp;
		/*** End Sort ***/

		el_phys_price_of_dates_option.val(JSON.stringify($._price_dates_option));
	};

	$.fn._save_tour_price_dates_range = function () {

		$._price_dates_option.price_dates_range = {};

		$.each(el_dates_range.find('.field_price_dates_range'), function () {
			let item_id = $(this).closest('.wrapper-price-dates-range-item').data('item-id');
			let name_field = $(this).attr('name');

			if ($._price_dates_option.price_dates_range[item_id] === undefined) {
				$._price_dates_option.price_dates_range[item_id] = {};
			}

			if (name_field === 'start_date' || name_field === 'end_date') {
				$._price_dates_option.price_dates_range[item_id][name_field] = $(this).val();
			} else {
				if ($._price_dates_option.price_dates_range[item_id].prices === undefined) {
					$._price_dates_option.price_dates_range[item_id].prices = {};
				}


				let parent_p = $(this).closest('p');

				if (parent_p.hasClass('normal')) {
					$._price_dates_option.price_dates_range[item_id].prices[name_field] = {};
					$._price_dates_option.price_dates_range[item_id].prices[name_field] = {
						'label': $(this).closest('p').find('span').text(),
						'price': $(this).val()
					};
				} else if (parent_p.hasClass('variation-attr-item')) {
					let parent_variation_item = $(this).closest('.price-dates-variation-item');
					let variation_id = parent_variation_item.data('variation-id');

					if ($._price_dates_option.price_dates_range[item_id].prices[variation_id] === undefined) {
						$._price_dates_option.price_dates_range[item_id].prices[variation_id] = {};
					}

					$._price_dates_option.price_dates_range[item_id].prices[variation_id][name_field] = {};

					$._price_dates_option.price_dates_range[item_id].prices[variation_id][name_field] = {
						'label': $(this).closest('p').find('span').text(),
						'price': $(this).val()
					};
				}
			}
		});

		/*** Sort ***/
		let price_dates_range_tmp = {};

		Object.keys($._price_dates_option.price_dates_range).sort(function (a, b) {
			return b - a
		}).forEach(function (k, i) {
			// console.log(k, i);
			if (price_dates_range_tmp[k] === undefined) {
				price_dates_range_tmp[k] = {};
			}

			price_dates_range_tmp[k] = $._price_dates_option.price_dates_range[k];
		});

		$._price_dates_option.price_dates_range = price_dates_range_tmp;
		/*** End Sort ***/

		console.log($._price_dates_option);

		el_phys_price_of_dates_option.val(JSON.stringify($._price_dates_option));
	};

	/*** Tour group discount ***/
	let el_phys_tour_group_discount = null;
	let el_list_group_discount = null;
	let el_tour_group_discount_data = null;

	$.fn.tour_group_discount_phys = function () {
		el_phys_tour_group_discount = $('#phys_tour_group_discount');

		if (el_phys_tour_group_discount.length > 0) {
			let el_new_tour_group_discount = el_phys_tour_group_discount.find('#new-tour-group-discount');
			el_list_group_discount = el_phys_tour_group_discount.find('#list-group-discount');
			let el_tour_group_discount_meta_field = el_phys_tour_group_discount.find('input[name=tour_group_discount_meta_field]');
			let tour_group_discount_meta_field = JSON.parse(el_tour_group_discount_meta_field.val());
			el_tour_group_discount_data = el_phys_tour_group_discount.find('input[name=tour_group_discount_data]');

			// Add new item
			el_new_tour_group_discount.on('click', function (e) {
				e.preventDefault();

				let el_content_item_discount = '';
				$.each(tour_group_discount_meta_field, function (k, v) {
					if (k !== 'description') {
						el_content_item_discount += '<div class="label-name" data-key-field="' + k + '">' + v.label + '</div>';
						el_content_item_discount += '<div class="value"><input class="form-control" type="' + v.type + '" name="' + k + '" value="' + v.value + '"></div>';
					} else {
						el_content_item_discount += '<div class="des"><div class="tip" data-key-field="' + k + '">' + v.value + '</i></div></div>';
					}
				});

				let html_content_item_discount = '';
				html_content_item_discount += '<li class="item-group-discount">' +
					el_content_item_discount +
					'<div class="remove-item"><span class="dashicons dashicons-no-alt"></span></div>' +
					'</li>';

				if (el_list_group_discount.find('li').length > 0) {
					el_list_group_discount.find('li:first-child').before(html_content_item_discount);

				} else {
					el_list_group_discount.append(html_content_item_discount);
				}
			});

			// Change value item
			$(document).delegate('.value>input', 'change keyup', function () {
				$.fn._save_tour_group_discount_phys();
			});
			// Remove item
			$(document).delegate('.item-group-discount .remove-item', 'click', function () {
				let item = $(this).closest('.item-group-discount');

				if (confirm("Are you want remove item?")) {
					item.remove();
					$.fn._save_tour_group_discount_phys();
				}
			});
		}
	};

	$.fn._save_tour_group_discount_phys = function () {
		let data_save = [];
		let el_item = el_list_group_discount.find('.item-group-discount');

		$.each(el_item, function () {
			let data_item_group_discount = {};
			let el_this_item = $(this);
			$.each(el_this_item.find('.value>input'), function () {
				let name_field = $(this).attr('name');
				let val = $(this).val();

				data_item_group_discount[name_field] = val;
			});

			data_item_group_discount['description'] = el_this_item.find('div[data-key-field="description"]').text();

			data_save.push(data_item_group_discount);
		});
		console.log(data_save);
		el_tour_group_discount_data.val(JSON.stringify(data_save));
	};

	/*** Travel Personal Information ***/
	$.fn.travel_personal_information = function () {
		let el_table_travel_setting_personal_information = $('#table-travel-setting-personal-information');

		if (el_table_travel_setting_personal_information.length > 0) {
			let el_field_personal_information = el_table_travel_setting_personal_information.find('.field-personal-information');
			let el_travel_personal_information_structure_fields = $('input[name=travel_personal_information_structure_fields]');
			let travel_personal_information_structure_fields_obj = JSON.parse(el_travel_personal_information_structure_fields.val());
			let el_add_travel_personal_info = $('#add-travel-personal-info');
			let class_field_personal_information = $('input[name=class-field-personal-information]').val();

			$('.form-table').remove();

			/*** Sort Attribute Personal Info ***/
			el_table_travel_setting_personal_information.find('tbody').sortable({
				update: $.fn._save_travel_personal_information
			});
			// el_table_travel_setting_personal_information.disableSelection();

			/*** Add Attribute P I ***/
			el_add_travel_personal_info.on('click', function (e) {
				e.preventDefault();
				let date = new Date();
				let time = date.getTime();
				let el_item = '';

				el_item += '<tr id="' + time + '">';
				$.each(travel_personal_information_structure_fields_obj, function (k, v) {
					el_item += '<td>';

					if (k !== 'attribute') {
						if (typeof v.types === 'object') {
							el_item += '<select name="' + k + '" class="' + class_field_personal_information + '">';
							$.each(v.types, function (i, type) {
								el_item += '<option value="' + i + '">' + type.label + '</option>';
							});
							el_item += '</select>';
						} else if (v.types === 'text') {
							el_item += '<input name="' + k + '" class="' + class_field_personal_information + '" type="text"></td>';

						}
					} else {
						el_item += '<input name="' + k + '" class="' + class_field_personal_information + '" type="text" placeholder="value 1|value 2"></td>';
					}
					el_item += '</td>';
				});
				el_item += '<td><span class="dashicons dashicons-move"></span></td>';
				el_item += '<td><span class="dashicons dashicons-no-alt remove-item-personal-information"></span></td>';
				el_item += '</tr>';

				el_table_travel_setting_personal_information.find('tbody').find('tr:first-child').before(el_item);
			});

			/*** Remove Attribute P I ***/
			el_table_travel_setting_personal_information.on('click', '.remove-item-personal-information', function () {
				let el = $(this);
				let el_tr = el.closest('tr');
				if (confirm('Are you want remove item?')) {
					el_tr.remove();
					$.fn._save_travel_personal_information();
				}

			});

			/*** Save Attribute P I ***/
			el_table_travel_setting_personal_information.on('change keyup', el_field_personal_information, function () {
				$.fn._save_travel_personal_information();
			});
		}
	};

	$.fn._save_travel_personal_information = function () {
		let el_field_personal_information = $('.field-personal-information');
		let el_travel_personal_information_option = $('input[name=travel_personal_information_option]');

		let travel_personal_information_data = {};

		$.each(el_field_personal_information, function () {
			let el_field_personal_information = $(this);
			let name_field = el_field_personal_information.attr('name');
			let el_tr_parent = el_field_personal_information.closest('tr');
			let id_field = el_tr_parent.attr('id');

			console.log(name_field);

			if (travel_personal_information_data[id_field] === undefined) {
				travel_personal_information_data[id_field] = {};
			}

			travel_personal_information_data[id_field][name_field] = el_field_personal_information.val();

			// if(name_field === 'label') {
			// 	key_field = el_field_personal_information.val().split(' ').join('-');
			// 	travel_personal_information_data[key_field] = {};
			// } else {
			// 	if(travel_personal_information_data[key_field] !== undefined) {
			// 		travel_personal_information_data[key_field][name_field] = {};
			// 		travel_personal_information_data[key_field][name_field] = el_field_personal_information.val();
			// 	}
			// }
		});

		console.log(travel_personal_information_data);
		el_travel_personal_information_option.val(JSON.stringify(travel_personal_information_data));
	};

	$.fn._active_tab_tour = function () {
		let el_product_type = $('select[name=product-type]');
		let el_product_data_tabs = $('.product_data_tabs');

		if (el_product_type.val() === 'tour_phys') {
			// set cookie tab active
			let d = new Date();
			d.setTime(d.getTime() + (24 * 60 * 60 * 1000));
			let expires = "expires=" + d.toUTCString();

			el_product_data_tabs.find('li').on('click', function () {
				let id_tab_active = $(this).find('a').attr('href');
				document.cookie = 'tab_active=' + id_tab_active + ';expires=' + expires;
			});

			// get cookie tab active
			let tab_active = getCookie('tab_active');
			if (tab_active !== '') {
				$('a[href="' + tab_active + '"]').ready(function (e) {
					$('a[href="' + tab_active + '"]').click();
				});
			}
		}
	};

	$.fn._hide_category_product = function () {
		var el_product_type = $('#product-type');
		var el_product_cat = $('#product_catdiv');
		var el_product_tag = $('#tagsdiv-product_tag');
		var el_tour_cat = $('#tour_physdiv');

		if (el_product_type.val() == 'tour_phys') {
			el_product_cat.hide();
			el_product_tag.hide();
			el_tour_cat.show();
		} else {
			el_product_cat.show();
			el_product_tag.show();
			el_tour_cat.hide();
		}

		el_product_type.on('change', function (e) {
			if ($(this).val() == 'tour_phys') {
				el_product_cat.hide();
				el_product_tag.hide();
				el_tour_cat.show();
			} else {
				el_product_cat.show();
				el_product_tag.show();
				el_tour_cat.hide();
			}
		});
	};

	$.fn.show_yith_subscription = function () {
		var el_product_type = $('#product-type');

		if (el_product_type.val() == 'tour_phys') {
			$('label').filter("[for='_ywsbs_subscription']").addClass('show_if_tour_phys');
			$('.ywsbs_price_is_per').next().addClass('show_if_tour_phys');
		}
	};

	function getCookie(cname) {
		let name = cname + "=";
		let ca = document.cookie.split(';');
		for (let i = 0; i < ca.length; i++) {
			let c = ca[i];
			while (c.charAt(0) === ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) === 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

	$.fn._location_option = function () {
		$('#location_option').on('change', function () {
			if ($(this).val() == 'google_api') {
				$(this).parent().parent().next().slideDown('normal', "swing");
			} else {
				$(this).parent().parent().next().slideUp('normal', "swing");
			}
		}).trigger('change');
	};

})(jQuery, 'tour-booking-admin-phys');

jQuery(function ($) {
	'use strict';
	$.fn.init_booking();
	$.fn.js_custom_tab();
	$.fn.active_menu_tour();
	$.fn._active_tab_tour();
	$.fn._phys_tour_dates_and_price();
	// $.fn.show_inventory();
	$.fn.tour_variation();
	$.fn.tour_faq();
	$.fn.tour_hightlight();
	$.fn.tour_group_discount_phys();
	$.fn.travel_personal_information();
	$.fn._hide_category_product();
	$.fn.show_yith_subscription();
	$.fn._location_option();
});
