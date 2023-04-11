import 'bootstrap/dist/js/bootstrap.bundle.min.js'
/* ===== Styles ===== */
import './styles/scss/admin.scss'
/* ===== Libs ===== */
import './libs/flatpickr/index.js'
/* ===== Pages ===== */
import './pages/index.js'
/* ===== Components ===== */
import './components/index.js'

// start the Stimulus application
import './bootstrap'

function formater_numero_francais(numero) {
    const regex = /^(?:\+33|0)(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})$/;
    const format = '+33 $1 $2 $3 $4 $5';
    return numero.replace(regex, format);
}

const phoneInputs = document.getElementsByClassName('phone-input');

phoneInputs.forEach((phoneInput) => {
phoneInput.addEventListener('input', (event) => {
        const value = event.target.value;

        // Supprimez les espaces et les tirets pour faciliter la correspondance
        const cleanedValue = value.replace(/[-\s]/g, '');

        if (cleanedValue.match(/^(?:\+33|0)\d{9}$/)) {
            // Si le numéro correspond au format français, mettez à jour le champ
            const formattedValue = formater_numero_francais(cleanedValue);
            event.target.value = formattedValue;
        }
    });
});

// phoneInput.addEventListener('input', (event) => {
//     const value = event.target.value;
//
//     // Supprimez les espaces et les tirets pour faciliter la correspondance
//     const cleanedValue = value.replace(/[-\s]/g, '');
//
//     if (cleanedValue.match(/^(?:\+33|0)\d{9}$/)) {
//         // Si le numéro correspond au format français, mettez à jour le champ
//         const formattedValue = formater_numero_francais(cleanedValue);
//         event.target.value = formattedValue;
//     }
// });