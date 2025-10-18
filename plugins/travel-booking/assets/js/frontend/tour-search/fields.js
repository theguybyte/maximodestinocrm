const productFilterNode = document.querySelector( '.travel-product-filter ' );
const travelProductFilterField = () => {
	if ( ! productFilterNode ) {
		return;
	}
	toggleAllFields(); 
	toggleField();
        
}; 

const toggleAllFields = () => { 
	const toggleBtn = document.querySelector( '.filter-button-toggle-wp' );  
	const el = document.querySelector('.travel-product-filter');
	const heading1 = document.querySelectorAll( '.item-filter-heading' );
	const filterTitleToggles1 = productFilterNode.querySelectorAll( '.filter-title-toggle' );
	const close_form = document.querySelector('.close-filter-product');
	
	if ( ! toggleBtn ) {
		return;
	}
	toggleBtn.addEventListener( 'click', function() {
		productFilterNode.classList.toggle('disabled');
		document.body.classList.add('overlay-filter');
	} );
	if(toggleBtn.classList.contains('button-hidden-desktop')){
		el.classList.remove("show-filter-toggle");
	}
	if (window.innerWidth < 768 && toggleBtn.classList.contains('button-hidden-desktop')) {
		el.classList.add("show-filter-toggle");
		filterTitleToggles1.forEach((filterTitleToggle1) => {
			if (window.innerWidth < 768 ) {
				filterTitleToggle1.classList.remove("filter-item-dropdown");
				filterTitleToggle1.classList.add("open");
			}
		});
	}
	window.onresize = function(event) {
		if (window.innerWidth < 768 && toggleBtn.classList.contains('button-hidden-desktop')) {
			el.classList.add("show-filter-toggle");
			filterTitleToggles1.forEach((filterTitleToggle1) => {
				filterTitleToggle1.classList.remove("filter-item-dropdown");
				filterTitleToggle1.classList.add("open");
			});
		}else{
			if(toggleBtn.classList.contains('button-hidden-desktop')){
				el.classList.remove("show-filter-toggle");
			}
			filterTitleToggles1.forEach((filterTitleToggle1) => {
				const toggletypes = productFilterNode.querySelectorAll(".toggle-type-dropdown");
				toggletypes.forEach((toggletype) => {
					toggletype.classList.add("filter-item-dropdown");
				});
				filterTitleToggle1.classList.remove("open");
			});
		}
	}
	document.addEventListener('click', function (event) {
		if (event.target != toggleBtn && !event.target.closest('#search_tour_form')) {  
			productFilterNode.classList.remove('disabled');
			document.body.classList.remove('overlay-filter');
		}
	})
	close_form.addEventListener( 'click', function( event ) {
		productFilterNode.classList.remove('disabled');
		document.body.classList.remove('overlay-filter');
	})
};

const toggleField = () => {
	const filterTitleToggles = productFilterNode.querySelectorAll( '.filter-title-toggle' );
	[ ...filterTitleToggles ].map( ( filterTitleToggle ) => {
		const heading = filterTitleToggle.querySelector( '.item-filter-heading' );
		heading.addEventListener( 'click', function() {
			if((window.innerWidth > 767 && filterTitleToggle.classList.contains('toggle-type-dropdown')) ||
			(window.innerWidth < 767 && !filterTitleToggle.classList.contains('has-btn-filter-show_mobile'))){
				if (filterTitleToggle.classList.contains("open")) {
					filterTitleToggle.classList.remove("open");
				} else {
					const toggleTitlesWithIsOpen = productFilterNode.querySelectorAll(".open");
					toggleTitlesWithIsOpen.forEach((toggleTitleWithIsOpen) => {
						toggleTitleWithIsOpen.classList.remove("open");
					});
					filterTitleToggle.classList.toggle( 'open' );
				}
			}else{
				filterTitleToggle.classList.toggle( 'open' );
			}
			
		} );
		document.addEventListener( 'click', function( event ) {
			if(!event.target.classList.contains('item-filter-heading') && filterTitleToggle.classList.contains('toggle-type-dropdown')){
				if(filterTitleToggle.classList.contains("open") && filterTitleToggle.classList.contains('toggle-type-dropdown')){
					filterTitleToggle.classList.remove( 'open' );
				}
				
			}
		})
	} );
};

export default travelProductFilterField;
