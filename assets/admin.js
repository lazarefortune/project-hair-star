import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import './styles/scss/admin.scss'
import './libs/flatpickr/index.js'
import './pages/index.js'

const accordionHeaders = document.querySelectorAll('.accordion-header')
accordionHeaders.forEach((accordionHeader) => {
    accordionHeader.addEventListener('click', (event) => {
        accordionHeader.classList.toggle('active')
        const accordionBody = accordionHeader.nextElementSibling
        if (accordionHeader.classList.contains('active')) {
            accordionBody.style.maxHeight = accordionBody.scrollHeight + 'px'
        } else {
            accordionBody.style.maxHeight = 0
        }
    })
})
// start the Stimulus application
import './bootstrap'

