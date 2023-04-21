import 'bootstrap/dist/js/bootstrap.bundle.min.js'
/* ===== Styles ===== */
import './styles/scss/admin.scss'
/* ===== Libs ===== */
import './libs/flatpickr/index.js'
import ImageModal from "./libs/lf/image-modal"
document.addEventListener('DOMContentLoaded', () => {
    ImageModal.init();
});
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
if(phoneInputs.length > 0) {
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
}

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

let imagesModals = document.querySelectorAll('.image-modal');
if( imagesModals.length > 0 ) {
    imagesModals.forEach((imageModal) => {
        imageModal.addEventListener('click', (event) => {
            console.log('click');
            const img = event.target;
            // get attribute id
            const id = img.getAttribute('data-image-modal-id');
            console.log(id);
            if (id === null) return;
            // get attribute src
            const src = img.getAttribute('src');
            console.log(src);
            // this modal by is attribute data-image-modal-container-id
            const modal = document.querySelector(`[data-image-modal-container-id="${id}"]`);
            //
            if( modal === null ) return;
            modal.style.display = "block";
            const modalImg = modal.querySelector('img');



            modalImg.src = src;


        });
    });
}



// modal img
// Get the modal
// var modal = document.getElementById("myModal");
//
// // Get the image and insert it inside the modal - use its "alt" text as a caption
// var img = document.getElementById("myImg");
// var modalImg = document.getElementById("img01");
// var captionText = document.getElementById("caption");
// img.onclick = function(){
//     modal.style.display = "block";
//     modalImg.src = this.src;
//     captionText.innerHTML = this.alt;
// }
//
// // Get the <span> element that closes the modal
// var span = document.getElementsByClassName("close")[0];
//
// // When the user clicks on <span> (x), close the modal
// span.onclick = function() {
//     modal.style.display = "none";
// }