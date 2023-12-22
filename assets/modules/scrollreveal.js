const ratio = 0.02
const options = {
    root: null,
    rootMargin: '0px',
    threshold: ratio
}

const handleIntersect = function (entries, observer) {
    entries.forEach(function (entry) {
        if (entry.intersectionRatio > ratio) {
            entry.target.classList.add('reveal-visible')
            console.log(entry.target.dataset.delay)
            if (entry.target.dataset.delay) {
                entry.target.style.transitionDelay = `${entry.target.dataset.delay}s`
            }
            observer.unobserve(entry.target)
        }
    })
}

const observer = new IntersectionObserver(handleIntersect, options)

document.querySelectorAll('.reveal').forEach(function (r) {
    console.log(r)
    observer.observe(r)
})

