document.addEventListener('DOMContentLoaded', function () {
    const inputDate = document.querySelector('.booking-date-choice');
    let excludeCreneaux = [];
    let excludeDays = [];
    let prestationId = null;

    let selectOptionsPrestationsElement = document.querySelector('#admin_add_booking_prestation');

    function updatePrestationId() {
        let selectedOptionPrestation = Array.from(selectOptionsPrestationsElement.options).find(option => option.selected);
        prestationId = selectedOptionPrestation.value;
        fetchAndUpdatePrestation(prestationId);
    }

    selectOptionsPrestationsElement.addEventListener('change', updatePrestationId);

    function fetchAndUpdatePrestation(prestationId) {
        return fetch('/api/prestations/' + prestationId, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                const minDate = data.dateStart;
                const maxDate = data.dateEnd;
                const minTime = data.timeStart;
                const maxTime = data.timeEnd;

                const bookingTimeElement = document.querySelector('#admin_add_booking_bookingTime');

                flatpickr(inputDate, {
                    altInput: true,
                    altFormat: "d/m/Y",
                    dateFormat: "Y-m-d",
                    // minDate: "today",
                    maxDate: maxDate,
                    disable: [
                        ...excludeCreneaux,
                        function (date) {
                            const adjustedDay = (date.getDay() === 0) ? "7" : date.getDay().toString();
                            return excludeDays.includes(adjustedDay);
                        }
                    ]
                });

                flatpickr(bookingTimeElement, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    minTime: minTime,
                    maxTime: maxTime,
                    disableMobile: true,
                });
            });
    }

    function fetchHolidays() {
        return fetch('/api/holidays/', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                data.forEach(creneaux => {
                    excludeCreneaux.push({
                        from: creneaux.startDate,
                        to: creneaux.endDate
                    });
                });
            });
    }

    function fetchOffDays() {
        return fetch('/api/off-days', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                excludeDays = data;
            });
    }

    Promise.all([fetchHolidays(), fetchOffDays()])
        .then(() => {
            updatePrestationId();
        })
        .catch(error => console.error(error));
});
