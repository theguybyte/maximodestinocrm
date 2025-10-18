import Sortable from 'sortablejs';
import { on } from 'delegated-events';

const tourReviewImages = () => {
    const { __ } = wp.i18n;
    const mediaElNodes = document.querySelectorAll( '.tour-image-info' );
    if ( ! mediaElNodes ) {
        return;
    }

    on( 'click', '.tour-gallery-add', function( event ) {
        event.preventDefault();
        const mediaElNode = this.closest( '.tour-image-info' );

        const tourUploader = wp.media( {
            title: __( 'Select Images', 'travel-booking' ), button: {
                text: __( 'Use these images', 'travel-booking' ),
            }, multiple:true, library: {
                type: 'image',
            },
        } );

        tourUploader.on( 'select', function() {
            const selection = tourUploader.state().get( 'selection' );

            let attachments = selection.filter( function( item ) {
                return item.toJSON().type === 'image';
            } ).map( function( item ) {
                return item.toJSON();
            } );
            attachments = attachments.filter( ( item ) => {
                return validateFile( item, mediaElNode );
            } );


            let attachmentIds = attachments.map( function( item ) {
                return item.id;
            } );

            const number = mediaElNode.querySelector( 'input[type="hidden"]' ).getAttribute( 'data-number' );
            attachmentIds = attachmentIds.slice( 0, number );
            mediaElNode.querySelector( 'input' ).value = attachmentIds.join();

            //Gallery preview
            let galleryPreviewHtml = '';

            for ( let i = 0; i < attachmentIds.length; i++ ) {
                let src = '';
                let dataId = '';
                if ( !! attachmentIds[ i ] ) {
                    dataId = attachmentIds[ i ];
                    if ( !! attachments[ i ].sizes && !! attachments[ i ].sizes.thumbnail ) {
                        src = attachments[ i ].sizes.thumbnail.url;
                    } else {
                        src = attachments[ i ].url;
                    }
                }
                galleryPreviewHtml += `<div class="tour-gallery-preview" data-id="${ dataId }">
						<div class="tour-gallery-centered"><img src="${ src }" alt="#">
						<span class="tour-gallery-remove dashicons dashicons dashicons-no-alt"></span>
						</div></div>`;
            }

            if ( !! galleryPreviewHtml ) {
                mediaElNode.querySelectorAll( '.tour-gallery-preview' ).forEach( ( e ) => e.remove() );
                mediaElNode.querySelector( '.tour-gallery-add' ).insertAdjacentHTML( 'beforebegin', galleryPreviewHtml );
            }
        } );
        tourUploader.on( 'open', function() {
            const selection = tourUploader.state().get( 'selection' );
            let attachmentIds = mediaElNode.querySelector( 'input' ).value;

            if ( attachmentIds.length > 0 ) {
                attachmentIds = attachmentIds.split( ',' );
                attachmentIds.forEach( function( id ) {
                    const attachment = wp.media.attachment( id );
                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                } );
            }
        } );

        tourUploader.open();
    } );

    const sortElNode = document.querySelector( '.tour-image-info .tour-gallery-inner' );
    if ( sortElNode ) {
        Sortable.create( sortElNode, {
            handle: '.tour-gallery-preview',
            draggable: '.tour-gallery-preview',
            animation: 150,
            onEnd() {
                const mediaElNode = this.el.closest( '.tour-image-info' );
                reorderIds( mediaElNode );
            },
        } );
    }

    on( 'click', '.tour-image-info .tour-gallery-remove', function( event ) {
        event.preventDefault();
        const imageInfo = this.closest( '.tour-image-info' );
        this.closest( '.tour-gallery-preview' ).remove();
        reorderIds( imageInfo );
    } );

    //Remove image
    for ( let i = 0; i < mediaElNodes.length; i++ ) {
        const mediaElNode = mediaElNodes[ i ];
        const removeButtonNode = mediaElNode.querySelector( 'button.tour-image-remove' );
        if ( ! removeButtonNode ) {
            return;
        }
        removeButtonNode.addEventListener( 'click', function() {
            mediaElNode.querySelector( 'input[type=text]' ).value = '';
            mediaElNode.querySelector( 'input[type=hidden]' ).value = '';
            mediaElNode.querySelector( '.tour-image-preview img' ).style.display = 'none';
        } );
    }
    //Function
};

const reorderIds = ( mediaElNode ) => {
    const previewGalleries = mediaElNode.querySelectorAll( '.tour-gallery-preview' );
    let dataIds = [];
    for ( let i = 0; i < previewGalleries.length; i++ ) {
        dataIds.push( previewGalleries[ i ].getAttribute( 'data-id' ) );
    }

    dataIds = dataIds.filter( function( el ) {
        return !! el;
    } );
    mediaElNode.querySelector( 'input' ).value = dataIds.join();
};

const validateFile = ( item, mediaElNode ) => {
    const maxFileSize = mediaElNode.getAttribute( 'data-max-file-size' );
    const itemFileSize =  item.filesizeInBytes ; //KB

    if ( maxFileSize && (maxFileSize * 1024) < itemFileSize ) {
        return false;
    }

    return true;
};

export default tourReviewImages;
