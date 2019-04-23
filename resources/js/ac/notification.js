let slides = document.querySelectorAll(`#slides .slide`)
let currentSlide = 0

function nextSlide() {
    goToSlide(currentSlide + 1)
}

function previousSlide() {
    goToSlide(currentSlide - 1)
}

function goToSlide(n) {
    slides[currentSlide].classList.remove(`showing`)
    currentSlide = (n + slides.length) % slides.length
    slides[currentSlide].classList.add(`showing`)
}

let next = document.getElementById(`next`)
let previous = document.getElementById(`previous`)

next.onclick = function () {
    nextSlide()
}
previous.onclick = function () {
    previousSlide()
}

$(`#slides`).swipe({
    swipeLeft: leftSwipe,
    swipeRight: rightSwipe,
    threshold: 0
})

function leftSwipe() {
    nextSlide()
}

function rightSwipe() {
    previousSlide()
}
