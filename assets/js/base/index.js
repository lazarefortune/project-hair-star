document.addEventListener("DOMContentLoaded", function () {
    const switchesBox = document.querySelectorAll('.form-switch');

    switchesBox.forEach(switchElementBox => {
        // get input element switchElementBox
        const switchElement = switchElementBox.querySelector('.form-switch-input');

        // get label element switchElementBox
        const label = switchElementBox.querySelector('.form-switch-label');
        const labelOn = switchElement.getAttribute('data-label-on');
        const labelOff = switchElement.getAttribute('data-label-off');

        // Initialisation du label au chargement de la page
        label.textContent = switchElement.checked ? labelOn : labelOff;

        // change check state
        switchElement.value = switchElement.checked ? 1 : 0;

        switchElement.addEventListener('change', function () {
                label.textContent = this.checked ? labelOn : labelOff;
                this.value = this.checked ? 1 : 0;
            }
        );
    });
});