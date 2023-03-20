import $ from 'jquery';
import 'flatpickr/dist/flatpickr.css';
// import 'flatpickr/dist/themes/material_blue.css';
import { French } from 'flatpickr/dist/l10n/fr.js';
import flatpickr from "flatpickr";

// import 'flatpickr/dist/themes/dark.css';

// import 'flatpickr/dist/themes/light.css';

import 'flatpickr/dist/themes/airbnb.css';


// flatpickr.localize(French);

flatpickr(".flatpickr-date", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    locale: French,
    defaultDate: "today",
});

flatpickr(".flatpickr-date-wrap", {
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "Y-m-d",
    locale: French,
    wrap: true,
});

flatpickr(".flatpickr-time", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    locale: French,
});

flatpickr(".flatpickr-time-wrap", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    locale: French,
    wrap: true,
});

$('.flatpickr-wrap').each(function() {
    $(this).on('input', function() {
        console.log($(this).find('input').val());
        if ($(this).find('input').val() !== '') {
            $(this).find('.input-button-clear').css('display', 'block');
        } else {
            $(this).find('.input-button-clear').css('display', 'none');
        }
    })
})

