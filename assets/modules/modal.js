document.addEventListener('DOMContentLoaded', function () {
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Modales
    const modalButtons = document.querySelectorAll('.modal-button');
    const modalCloses = document.querySelectorAll('.modal-close');


    modalButtons.forEach(button => {
        button.addEventListener('click', function () {
            const modalID = button.getAttribute('data-modal-id');
            const modal = document.getElementById(modalID);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    modalCloses.forEach(close => {
        close.addEventListener('click', function () {
            const modal = close.closest('.modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });


});