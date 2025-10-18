jQuery(document).ready(function($) {
    
    // Get the actual values from the select elements (browser may have cached them)
    const cachedLocationValue = $('#location-select').val();
    const cachedTransportationValue = $('#transportation-select').val();
    
    // Initialize Select2 for locations with local data
    $('#location-select').select2({
        placeholder: travelwpFilterData.strings.selectDestination,
        allowClear: true,
        data: travelwpFilterData.locations
    });
    
    // Initialize Select2 for transportation with local data
    $('#transportation-select').select2({
        placeholder: travelwpFilterData.strings.selectTransportation,
        allowClear: true,
        data: travelwpFilterData.transportations
    });

    // Use setTimeout to ensure Select2 is fully rendered before setting values
    setTimeout(function() {
        // If browser cached a value, restore it in Select2
        if (cachedLocationValue) {
            $('#location-select').val(cachedLocationValue).trigger('change');
        } else {
            $('#location-select').val(null).trigger('change');
        }
        
        if (cachedTransportationValue) {
            $('#transportation-select').val(cachedTransportationValue).trigger('change');
        } else {
            $('#transportation-select').val(null).trigger('change');
        }
    }, 100);
    
    // Show loading animation on form submit
    $('#travelwp-search-form').on('submit', function(e) {
        // Show loading overlay
        showLoadingOverlay();
    });
    
    // Function to show loading overlay with Lottie
    function showLoadingOverlay() {
        // Create overlay HTML
        const overlayHTML = `
            <div id="travelwp-loading-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.95);
                z-index: 99999;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            ">
                <div id="lottie-animation" style="width: 300px; height: 300px;"></div>
                <p style="
                    margin-top: 20px;
                    font-size: 18px;
                    color: #333;
                    font-weight: 600;
                ">Buscando tours...</p>
            </div>
        `;
        
        $('body').append(overlayHTML);
        
        // Load and play Lottie animation
        // Using a travel/search related animation
        lottie.loadAnimation({
            container: document.getElementById('lottie-animation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://lottie.host/4db68bbd-31f6-4008-a6d3-095ba977d36c/OQkPVjBJyq.json' // Travel search animation
        });
    }
});