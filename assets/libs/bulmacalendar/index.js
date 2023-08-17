import bulmaCalendar from 'bulma-calendar';
import 'bulma-calendar/dist/css/bulma-calendar.min.css';

// Initialize all input of id "bulma-calendar" with the bulmaCalendar plugin.
const options = {
    type: 'date',
    showHeader: false,
    maxDate: new Date(),
};

// whend page is loaded
document.addEventListener('DOMContentLoaded', () => {


    const calendars = bulmaCalendar.attach('#bulma-calendar', options);

    console.log(calendars);
// Loop on each calendar initialized
    for (let i = 0; i < calendars.length; i++) {
        // Add listener to select event
        calendars[i].on('select', date => {
            console.log(date);
        });
    }

});

// To access to bulmaCalendar instance of an element
// var element = document.querySelector('#my-element');
// if (element) {
//     // bulmaCalendar instance is available as element.bulmaCalendar
//     element.bulmaCalendar.on('select', function (datepicker) {
//         console.log(datepicker.data.value());
//     });
// }