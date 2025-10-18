const $ = jQuery;

const tourDateTime = () => {
    const daterange = $('.tour-search-field.date input');
    const removeDate = document.querySelector('#remove-date-range') 
    const selectionWrapper = document.querySelector('.tour-search-field.selection');
    
    
    if (!daterange) {
        return;
    }

    const value = daterange.val();
    daterange.daterangepicker({
        opens: 'center',
        locale: {
            // autoUpdateInput: false,
            format: 'DD/MM/YYYY',
            // cancelLabel: 'Clear'
        }
    }, function (start, end, label) {
    });


    if (!value) {
        daterange.val('');
    }

    if (removeDate) {
        removeDate.addEventListener('click', function (event) {
            daterange.val('');
            if (selectionWrapper) {
                const listNode = selectionWrapper.querySelector('.list');
                const fieldNode = listNode.querySelector(`li[data-field="date"]`);
                fieldNode.remove()
            }
        });
    }
}

export default tourDateTime;
