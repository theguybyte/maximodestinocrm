// jQuery(function ($) {
// 	'use strict';
// 	$('.tour-ordering').find('.orderby').on('change', function () {
// 		$('.tour-ordering').submit();
// 	});
// });
document.addEventListener('DOMContentLoaded', (event) => {
    let formTourOrder = document.querySelector( '.tour-ordering' ),
    	searchTour = document.querySelector( '#search_tour_form' );
    if ( ! formTourOrder ) {
    	return;
    }
    formTourOrder.addEventListener( 'change', (e) => {
    	let target = e.target;
    	// console.log( target );
    	if ( target.name == 'orderbyt') {
    		if ( ! searchTour ) {
    			formTourOrder.submit();
    		} else {
    			let input = searchTour.querySelector( 'input[name="orderbyt"]' );
    			input.value = target.value;
    			searchTour.submit();
    		}
    	}
    } );
});
