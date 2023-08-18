import $ from 'jquery';
// import 'flatpickr/dist/flatpickr.css';
// import 'flatpickr/dist/themes/material_blue.css';
import {French} from 'flatpickr/dist/l10n/fr.js';
import flatpickr from "flatpickr";

// import 'flatpickr/dist/themes/dark.css';

import 'flatpickr/dist/themes/light.css';
// import 'flatpickr/dist/themes/material_green.css';
// import 'flatpickr/dist/themes/material_red.css';
// import 'flatpickr/dist/themes/material_orange.css';
// import 'flatpickr/dist/themes/material_blue.css';
// import 'flatpickr/dist/themes/airbnb.css';


flatpickr.localize(French);

flatpickr(".flatpickr-date-input", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    disableMobile: true,
});

flatpickr(".flatpickr-date-input-today", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    disableMobile: true,
    minDate: "today",
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

