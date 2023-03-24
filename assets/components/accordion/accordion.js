import './accodion.scss'

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