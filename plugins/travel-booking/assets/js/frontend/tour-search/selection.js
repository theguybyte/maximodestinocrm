import {renderPriceFormat} from "./price";

let listNode, clearBtn;
const $ = jQuery;
const destinationSelect = document.querySelector('.tour-search-field.destination select.pa_destination');
const monthSelect = document.querySelector('.tour-search-field.destination select.pa_month');
const dateNode = document.querySelector('.tour-search-field.date input');
const durationNodes = document.querySelectorAll('.tour-search-field .duration-item input')
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
}

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
}

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
}
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
}


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
}

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
}

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
}
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
}

const updatePrice = () => { 
    const minPriceNode = document.querySelector('.tour-search-field.price').getAttribute('data-min');
    const maxPriceNode = document.querySelector('.tour-search-field.price').getAttribute('data-max');
    priceSliderNode.noUiSlider.on('update', function (values, handle, unencoded) {  
        const minPrice = parseInt(values[0]);
        const maxPrice = parseInt(values[1]);
        if (minPrice != minPriceNode || maxPrice != maxPriceNode) {
            changeSelectedField('price', minPrice + '-' + maxPrice, travel_booking_search.price + renderPriceFormat(minPrice) + '-' + renderPriceFormat(maxPrice));
        }
    });
}

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
}

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
}

const resetDestination = () => {
    if (!destinationSelect) {
        return;
    }
    destinationSelect.selectedIndex = 0
}
const resetMonth = () => {
    if (!monthSelect) {
        return;
    }
    monthSelect.selectedIndex = 0
}
const resetDate = () => {
    if (!dateNode) {
        return;
    }
    dateNode.value = '';
}

const resetDuration = (value = 'all') => {
    if (value === 'all') {
        [...durationNodes].map(durationNode => {
            durationNode.checked = false;
        })
    } else {
        const input = document.querySelector(`.tour-search-field .duration-item input[value="${value}"]`);
        input.checked = false;
    }
}

const resetRating = () => {
    [...ratingNodes].map(ratingNode => {
        ratingNode.checked = false;
    })
}
const resetTourtype = (value = 'all') => {
    if (value === 'all') {
        [...tourtypeNodes].map(tourtypeNode => {
            tourtypeNode.checked = false;
        })
    } else {
        const input = document.querySelector(`.tour-search-field.tour-type li input[value="${value}"]`);
        input.checked = false;
    }
}

const resetPrice = () => {
    priceSliderNode.noUiSlider.updateOptions({
        start: [parseInt(priceWrapper.getAttribute('data-min')), parseInt(priceWrapper.getAttribute('data-max'))],
    });
}
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
}
const tourSelectionInit = () => {
    tourSelection();
    resetAll();
}
export default tourSelectionInit;
