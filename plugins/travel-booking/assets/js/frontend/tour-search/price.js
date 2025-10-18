import 'nouislider';

// const numberDecimals = TOUR_SEARCH_OBJ.decimals || 0;
// const thousandsSeparator = TOUR_SEARCH_OBJ.thousand_separator || '';
const numberDecimals =  0;
const thousandsSeparator =  '';

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
            min: parseInt(minPrice), max: parseInt(maxPrice),
        },
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
                start: [parseInt(start), parseInt(end)],
            });
        });
    }
}

const renderPriceNumber = (price) => {
    price = (price / 1).toFixed(numberDecimals);
    price = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);

    // if (TOUR_SEARCH_OBJ.price_format === 'left-space') {
    //     price = ' ' + price;
    // } else if (TOUR_SEARCH_OBJ.price_format === 'right-space') {
        price = price + ' ';
    // }

    return price;
};

const renderPriceFormat = (price) => {
    price = renderPriceNumber(price);

    // if (['left', 'left-space'].includes(TOUR_SEARCH_OBJ.price_format)) {
    //     price = TOUR_SEARCH_OBJ.currency_symbol + price;
    // } else {
        // price = price + TOUR_SEARCH_OBJ.currency_symbol;
    // }

    return price;
}

export {renderPriceFormat}
export default tourPrice;
