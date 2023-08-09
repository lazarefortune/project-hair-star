import $ from 'jquery';
// import 'flatpickr/dist/flatpickr.css';
// import 'flatpickr/dist/themes/material_blue.css';
import {French} from 'flatpickr/dist/l10n/fr.js';
import flatpickr from "flatpickr";

// import 'flatpickr/dist/themes/dark.css';

// import 'flatpickr/dist/themes/light.css';

import 'flatpickr/dist/themes/airbnb.css';

// $('.flatpickr-date-input').each(function () {
//     // // placer le this dans une div avec la classe flatpickr-date-wrap flatpickr-wrap
//     const $this = $(this);
//     $this.wrap('<div class="flatpickr-date-wrap flatpickr-wrap"></div>');
//     // // supprimer le this de la div
//     // // ajouter les boutons
//     // $this.after('<a class="input-button" title="toggle" data-toggle><i class="las la-calendar"></i></a><a class="input-button-clear" title="clear" data-clear><i class="las la-times-circle"></i></a>');
//     // // ajouter le data-input true à l'input
//     // // $this.attr('data-input', 'true');
//     //
//     // // $(this).before('<div class="flatpickr-date-wrap flatpickr-wrap"></div>');
//     // // déplacer l'élément dans la div
//     // // $(this).appendTo('.flatpickr-date-wrap');
//     // // ajouter les boutons
//     // $(this).after('<a class="input-button" title="toggle" data-toggle><i class="las la-calendar"></i></a><a class="input-button-clear" title="clear" data-clear><i class="las la-times-circle"></i></a>');
//     // // ajouter le data-input true à l'input
//     // $(this).attr('data-input', 'true');
// })


flatpickr.localize(French);

flatpickr(".flatpickr-date-input", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    disableMobile: true,
});

flatpickr(".flatpickr-date-birthday", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    maxDate: new Date().fp_incr(-16 * 365),
    disableMobile: true,
});

flatpickr(".flatpickr-date-realisation", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    minDate: new Date(2023, 0, 1),
    disableMobile: true,
});

flatpickr(".flatpickr-date", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    defaultDate: "today",
    disableMobile: true,
});

flatpickr(".flatpickr-date-wrap", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    wrap: true,
    disableMobile: true,
});

flatpickr(".flatpickr-time", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    locale: French,
    disableMobile: true,
});

flatpickr(".flatpickr-time-input", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    locale: French,
    defaultDate: "today",
    disableMobile: true,
});

flatpickr(".flatpickr-time-wrap", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    locale: French,
    wrap: true,
    disableMobile: true,
});

// $('.flatpickr-wrap').each(function () {
//     $(this).on('input', function () {
//         console.log($(this).find('input').val());
//         if ($(this).find('input').val() !== '') {
//             $(this).find('.input-button-clear').css('display', 'block');
//         } else {
//             $(this).find('.input-button-clear').css('display', 'none');
//         }
//     })
// })
//
// // on page load
// $(document).ready(function () {
//     $('.flatpickr-wrap').each(function () {
//         if ($(this).find('input').val() !== '') {
//             $(this).find('.input-button-clear').css('display', 'block');
//         } else {
//             $(this).find('.input-button-clear').css('display', 'none');
//         }
//     })
// })

