import './image-modal.scss';
import $ from 'jquery';

/**
 * By Lazare Fortune
 * @class ImageModal
 */
export default class ImageModal {
    constructor() {
        this.createModalMarkup();
        this.$modal = $('.js-image-modal-container');
        this.$image = this.$modal.find('.js-image-modal__image');
        this.$close = this.$modal.find('.js-image-modal__close');
        this.$caption = this.$modal.find('.js-image-modal__caption');
        this.$overlay = this.$modal.find('.js-image-modal__overlay');

        this.$close.on('click', this.close.bind(this));
        this.$overlay.on('click', this.close.bind(this));
    }

    createModalMarkup() {
        const modalMarkup = `
            <div class="js-image-modal-container">
                <div class="js-image-modal__close"></div>
                <div class="js-image-modal__overlay"></div>
                <img class="js-image-modal__image" src="" alt="Modal image">
                <div class="js-image-modal__caption"></div>
            </div>
        `;
        $('body').append(modalMarkup);
    }

    open(src) {
        this.$image.attr('src', src);
        this.$modal.addClass('is-active');
    }

    close() {
        this.$modal.removeClass('is-active');
    }

    static init() {
        const imageModal = new ImageModal();

        const $images = $('img.js-image-modal');

        $images.on('click', function() {
            const src = $(this).attr('src');
            imageModal.open(src);
        });

        return imageModal;
    }
}
