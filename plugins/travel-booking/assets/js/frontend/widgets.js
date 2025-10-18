jQuery(function ($) {
    const tourFetchAPI = (url, data = {}, functions = {}) => {
        if ('function' === typeof functions.before) {
            functions.before();
        }

        fetch(url, {method: 'GET', ...data})
            .then((response) => response.json())
            .then((response) => {
                if ('function' === typeof functions.success) {
                    functions.success(response);
                }
            }).catch((err) => {
            if ('function' === typeof functions.error) {
                functions.error(err);
            }
        })
            .finally(() => {
                if ('function' === typeof functions.completed) {
                    functions.completed();
                }
            });
    };

    function widgetRestAPI() {
        const widgets = document.querySelectorAll('.tour-widget-wrapper');

        if (!widgets.length) {
            return;
        }

        const getResponse = (ele) => {
            const widgetData = ele.dataset.widget ? JSON.parse(ele.dataset.widget) : '';
            const url = tour_widget.rest_url + 'travel-tour/v1/widgets/api';
            const paramsFetch = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({...widgetData}),
            };

            const callBack = {
                before: () => {
                },
                success: (res) => {
                    const {data, status, message} = res;
                    if (data && status === 'success') {
                        ele.insertAdjacentHTML('afterbegin', data);
                    } else if (message) {
                        ele.insertAdjacentHTML('afterbegin', `<div class="tour-ajax-message error" style="display:block">${message}</div>`);
                    }
                },
                error: (error) => {

                },
                completed: () => {
                    //delete ele.dataset.widget;
                    ele.querySelector('.tour-skeleton-animation').remove();
                    tourWidgetSearch();
                    tourDateTime();
                },
            };

            // Call API load widget
            tourFetchAPI(url, paramsFetch, callBack);
        };

        widgets.forEach((ele) => {
            if (ele.classList.contains('tour-widget-wrapper__restapi')) {
                getResponse(ele);
            }
        });
    }

    const tourDateTime = () => {
        const daterange = $('.tour-search-field.date input');
        const removeDate = document.querySelector('#remove-date-range')

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
            });
        }
    }

    widgetRestAPI();
});
