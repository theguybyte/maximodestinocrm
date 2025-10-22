jQuery(document).ready(function ($) {

    // Get the actual values from the select elements (browser may have cached them)
    const cachedLocationValue = $('#location-select').val();
    const cachedTransportationValue = $('#transportation-select').val();

    // Initialize Select2 for locations with local data
    $('#location-select').select2({
        placeholder: travelwpFilterData.strings.selectDestination,
        allowClear: true,
        data: travelwpFilterData.locations,
        dropdownAutoWidth: false,
        width: '100%',
        // width: 'resolve',
        dropdownCssClass: 'travelwp-main-dropdown',
        language: {
            noResults: function () {
                return 'No encontramos ese destino';
            }
        }
    });

    // Initialize Select2 for transportation with local data
    $('#transportation-select').select2({
        placeholder: travelwpFilterData.strings.selectTransportation,
        allowClear: true,
        data: travelwpFilterData.transportations,
        dropdownAutoWidth: false,
        width: '100%',
        // width: 'resolve',
        dropdownCssClass: 'travelwp-main-dropdown',
        language: {
            noResults: function () {
                return 'No encontramos ese transporte';
            }
        }
    });

    // Use setTimeout to ensure Select2 is fully rendered before setting values
    setTimeout(function () {
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
    $('#travelwp-search-form').on('submit', function (e) {
        // Show loading overlay
        showLoadingOverlay();
    });

    // Function to show loading overlay with Lottie
    function showLoadingOverlay() {

        // Prevent body scroll BEFORE adding overlay
        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });

        // Create overlay HTML
        const overlayHTML = `
            <div id="travelwp-loading-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.95);
                z-index: 99999;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                overflow: hidden;
                margin: 0;
                padding: 0;
            ">
                <lottie-player
                    src="https://lottie.host/231d8a57-c861-4da5-a41f-7197ea89fa12/u882jc7lt1.json"
                    background="transparent"
                    speed="1"
                    style="width: 500px; height: 500px;"
                    loop
                    autoplay>
                </lottie-player>
                <p style="
                    margin-top: 20px;
                    font-size: 35px;
                    color: #333;
                    font-weight: 600;
                    margin-bottom: 0;
                    position: absolute;
                    bottom: 30%;
                    font-weight: bold;
                ">Buscando tours...</p>
            </div>
        `;

        $('body').append(overlayHTML);

        // Load and play Lottie animation
        // Using a travel/search related animation





    }
});