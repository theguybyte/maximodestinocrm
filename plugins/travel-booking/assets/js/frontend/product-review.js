(function () {
    const openFomBtn = document.querySelector('#tour-add-new-review');
    const closeFormBtn = document.querySelector('.close-form-btn');

    const reviewFormPopup = document.querySelector('#tour-booking-review-form-popup');
    let background, reviewForm
    let imageData = [];

    let isAjax;

    const init = () => {
        if (!reviewFormPopup) {
            return;
        }

        background = reviewFormPopup.querySelector('.bg-overlay');
        reviewForm = reviewFormPopup.querySelector('#tour-booking-submit-review-form');
        if (PRODUCT_REVIEW_GALLERY && PRODUCT_REVIEW_GALLERY.is_enable_ajax) {
            isAjax = PRODUCT_REVIEW_GALLERY.is_enable_ajax === 'yes';
        }

        openForm();
        closeForm();
        ratingReview();
        uploadImages();
        submitReview();
        sortReview();
        ajax();
    };

    jQuery(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/tours-widget-comment.default', init);
    });

    const ajax = () => {

        if (!isAjax) {
            return;
        }

        document.addEventListener('click', function (event) {
            const target = event.target;

            if (!target.closest('#comments')) {
                return;
            }

            if (target.tagName !== 'A') {
                return;
            }

            if (target.closest('.gallery-filter') || target.closest('#tour-sort-by') || target.closest('.woocommerce-pagination')) {
                event.preventDefault();

                getProductReview(target.href);
            }
        })
        // const filterNodes = document.querySelectorAll('.gallery-filter a');
        //
        // [...filterNodes].map(filterNode => {
        //     filterNode.addEventListener('click', function (event) {
        //         event.preventDefault();
        //         getProductReview(this.href)
        //     });
        // });
    }

    const getProductReview = (url = '') => {
        if (!isAjax) {
            return;
        }

        const oldComment = document.querySelector('#comments');
        oldComment.style.opacity = 0.6;

        fetch(url, {method: 'GET'}
        ).then((res) => res.text()
        ).then((res) => {
            const bodyText = res.substring(
                res.indexOf('<body>') + 1,
                res.lastIndexOf('</body>')
            );

            const bodyNode = document.createElement('body');
            bodyNode.innerHTML = bodyText;

            const newComment = bodyNode.querySelector('#comments');

            if (oldComment && newComment) {
                oldComment.innerHTML = newComment.innerHTML;
            }

        }).catch((err) => {
            console.log(err);
        }).finally(() => {
            window.history.pushState('', '', url);
            oldComment.style.opacity = 1;
        });
    };

    const sortReview = () => {
        document.addEventListener('click', function (event) {
            const sortByNode = document.querySelector('#tour-sort-by');

            if (!sortByNode) {
                return;
            }

            const target = event.target;
            if (target.classList.contains('toggle') && target.closest('.tour-commentlist-sort-filter')) {
                sortByNode.classList.add('is-open')
            } else if (!target.classList.contains('tour-sort-by-option')) {
                sortByNode.classList.remove('is-open')
            }
        });
    }

    const openForm = () => {
        if (!openFomBtn) {
            return;
        }

        openFomBtn.addEventListener('click', function () {
            background.style.display = 'block';
            reviewForm.style.display = 'block';
        });
    }

    const closeForm = () => {
        closeFormBtn.addEventListener('click', function () {
            background.style.display = 'none';
            reviewForm.style.display = 'none';
        })

        document.addEventListener('click', function (event) {
            const target = event.target;

            if (target.getAttribute('id') === 'tour-add-new-review') {
                return;
            }

            const formNode = target.closest('#tour-booking-submit-review-form');

            if (!formNode && target.getAttribute('id') !== 'tour-booking-submit-review-form') {
                background.style.display = 'none';
                reviewForm.style.display = 'none';
            }
        });
    }

    const ratingReview = () => {
        const reviewRatingNode = reviewForm.querySelector('#tour-booking-submit-review-form input[name="review-rating"]');
        const ratingNode = document.querySelector('#tour-booking-submit-review-form .rating-star');
        const ratingStarItems = ratingNode.querySelectorAll('#tour-booking-submit-review-form .rating-star-item');

        [...ratingStarItems].map(ratingStarItem => {
            ratingStarItem.addEventListener('mouseover', function (event) {
                const actived = this.closest('.rating-star.active');
                if (actived) {
                    return;
                }

                ratingNode.classList.add('selected');
                ratingStarItem.classList.add('selected');
            });

            ratingStarItem.addEventListener('mouseleave', function (event) {
                const actived = this.closest('.rating-star.active');
                if (actived) {
                    return;
                }

                ratingNode.classList.remove('selected');
                ratingStarItem.classList.remove('selected');
            });

            ratingStarItem.addEventListener('click', function (event) {
                event.preventDefault();
                reviewRatingNode.value = this.getAttribute('data-star-rating');

                const activeRatingStarNode = document.querySelector('#tour-booking-submit-review-form .rating-star-item.active');

                if (activeRatingStarNode) {
                    activeRatingStarNode.classList.remove('active');
                }

                ratingNode.classList.remove('selected');
                ratingNode.classList.add('active');
                ratingStarItem.classList.remove('selected');
                ratingStarItem.classList.add('active');
            });
        });
    }

    const uploadImages = () => {
        const galleryReview = document.querySelector('.tour-gallery-review');
        if (!galleryReview) {
            return;
        }

        if (!PRODUCT_REVIEW_GALLERY) {
            return;
        }

        const tourId = galleryReview.getAttribute('data-tour-id');
        const uploadImage = galleryReview.querySelector('#tour-review-image');
        const preview = galleryReview.querySelector('.gallery-preview');
        const reviewNotice = galleryReview.querySelector('.review-notice');

        const maxImages = PRODUCT_REVIEW_GALLERY.max_images || 0;

        uploadImage.addEventListener('change', function () {
            removeNotice();

            const count = uploadImage.files.length;
            let uploadedCount = 0;
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

            //Validate image number
            if (count + uploadedCount > maxImages) {
                handleUploadError();
                displayNotice(PRODUCT_REVIEW_GALLERY.max_image_error, 'error');
                return;
            }

            for (let i = 0; i < count; i++) {
                if (!allowedTypes.includes(uploadImage.files[i].type)) {
                    handleUploadError();
                    displayNotice(PRODUCT_REVIEW_GALLERY.file_type_error, 'error');
                    return;
                } else if (uploadImage.files[i].size && uploadImage.files[i].size > (PRODUCT_REVIEW_GALLERY.max_file_size) * 1024) {
                    handleUploadError();
                    displayNotice(PRODUCT_REVIEW_GALLERY.max_file_size_error, 'error');
                    return;
                }
            }

            let previewItemHtml = '';
            [...uploadImage.files].map(file => {
                const previewImage = window.URL.createObjectURL(file);
                previewItemHtml += `<div class="preview-item"><img src="${previewImage}" alt="#preview"></div>`
            });

            if (previewItemHtml) {
                preview.innerHTML = previewItemHtml;
            }

            imageData = uploadImage.files;
        });

        const handleUploadError = () => {
            const notUploadedPreview = galleryReview.querySelectorAll('.cr-upload-images-preview .tour-upload-images-containers:not(.tour-upload-ok)');
            [...notUploadedPreview].map(el => {
                el.remove();
            });

            uploadImage.value = '';
        }

        const removeNotice = () => {
            reviewNotice.innerHTML = '';
        }

        const displayNotice = (message, status = 'success') => {
            if (status === 'success') {
                reviewNotice.classList.remove('error');
                reviewNotice.classList.add('success');
            } else {
                reviewNotice.classList.remove('success');
                reviewNotice.classList.add('error');
            }

            reviewNotice.innerHTML = message;
        }
    }

    const submitReview = () => {
        const submitBtnNode = reviewFormPopup.querySelector('footer button');
        submitBtnNode.addEventListener('click', async function () {
            submitBtnNode.disabled = true;
            const spinnerNode = reviewFormPopup.querySelector('.tour-spinner');
            spinnerNode.classList.add('active');
            const nameNode = reviewFormPopup.querySelector('#review-name');
            const emailNode = reviewFormPopup.querySelector('#review-email');
            const noticeNode = reviewFormPopup.querySelector('p.notice');
            const ratingNode = reviewFormPopup.querySelector('input[name="review-rating"]');
            const contentNode = reviewFormPopup.querySelector('#review-content');
            const titleNode = reviewFormPopup.querySelector('#review-title');

            const name = nameNode?.value;
            const email = emailNode?.value;
            const rating = parseInt(ratingNode.value);
            const content = contentNode.value;
            const title = titleNode.value;

            const base64Images = await handleBase64();

            for (let i = 0; i < base64Images.length; i++) {
                base64Images[i].name = imageData[i].name;
                base64Images[i].type = imageData[i].type;
            }

            const productId = reviewForm.getAttribute('data-product-id');

            let data = {
                "product_id": productId
            }

            if (name) {
                data = {...data, name};
            }

            if (email) {
                data = {...data, email};
            }

            if (rating) {
                data = {...data, rating};
            }

            if (title) {
                data = {...data, title};
            }

            if (content) {
                data = {...data, content};
            }

            if (base64Images) {
                data = {...data, "base64_images": base64Images};
            }

            wp.apiFetch({
                path: '/travel-tour/v1/update-review', method: 'POST', data,
            }).then((res) => {
                if (res.status === 'success') {
                    noticeNode.classList.remove('failed');
                    noticeNode.classList.add('success');

                    let mgs = res.msg;
                    if (mgs) {
                        if (res?.data?.require_approved_by_admin) {
                            mgs += `<span class="approve-mgs">${res?.data?.approve_mgs}</span>`;
                        }

                        noticeNode.innerHTML = mgs;
                    }

                    if (res.data.redirect_url) {
                        setTimeout(function () {
                            window.location.href = res.data.redirect_url;
                            location.reload();
                        }, 3000);
                        return false;
                    }
                } else {
                    noticeNode.classList.remove('success');
                    noticeNode.classList.add('failed');

                    if (res.msg) {
                        noticeNode.innerHTML = res.msg;
                    }
                }
            }).catch((err) => {
                console.log(err);
            }).finally(() => {
                submitBtnNode.disabled = false;
                spinnerNode.classList.remove('active');
            });
        });
    }

    const toBase64 = (file) => {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
    };

    const handleBase64 = async () => {
        const filePathsPromises = [];
        [...imageData].map(file => {
            filePathsPromises.push(toBase64(file));
        });
        const filePaths = await Promise.all(filePathsPromises);
        return filePaths.map((base64File) => ({"base64": base64File}));
    }

    init();
})();

// add_action('template_redirect', 'custom_domain_redirect');
//
// function custom_domain_redirect() {
//     if ( $_SERVER['HTTP_HOST'] === 'bbb.com' ) { // Thay 'bbb.com' bằng domain bbb của bạn
//         wp_redirect( 'http://aaa.com' . $_SERVER['REQUEST_URI'] );
//         exit;
//     }
// }
