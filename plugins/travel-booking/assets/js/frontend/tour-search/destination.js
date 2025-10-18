import TomSelect from "tom-select";

const tourDestination = () => {
    const destinationWrapper = document.querySelector('.tour-search-field-inner.pa_destination');
    const selectionWrapper = document.querySelector('.tour-search-field.selection');
    
    if (!destinationWrapper) {
        return;
    } else {
        const destinationSelect = destinationWrapper.querySelector('select.pa_destination'); 
        const destinationRemove = destinationWrapper.querySelector('.remove-attr'); 
        destinationRemove.addEventListener('click', function (event) { 
            destinationSelect.selectedIndex = 0    
            if (destinationRemove) {
                const listNode = selectionWrapper.querySelector('.list');
                const fieldNode = listNode.querySelector(`li[data-field="destination"]`);
                fieldNode.remove()
            }
        })

    }
    const mothWrapper = document.querySelector('.tour-search-field-inner.pa_month');
    if (!mothWrapper) {
        return;
    } else {
        const mothSelect = mothWrapper.querySelector('select.pa_month'); 
        const mothRemove = mothWrapper.querySelector('.remove-attr');
        mothRemove.addEventListener('click', function (event) {
            mothSelect.selectedIndex = 0
            if (destinationRemove) {
                const listNode = selectionWrapper.querySelector('.list');
                const fieldNode = listNode.querySelector(`li[data-field="month"]`);
                fieldNode.remove()
            }
        })
    } 

  
}

export default tourDestination;
