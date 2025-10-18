import tourDateTime from './date-time';
import tourDestination from './destination';
import tourPrice from './price';
import tourSelection from './selection';
import tourHandleSearch from './handle-search';
import travelProductFilterField from './fields';

document.addEventListener('DOMContentLoaded', (event) => {
    travelProductFilterField();
    tourDateTime();
    tourDestination();
    tourPrice();
    tourSelection();
    tourHandleSearch();
});
