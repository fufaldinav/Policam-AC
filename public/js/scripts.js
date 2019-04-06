let slides = document.querySelectorAll(`#slides .slide`);
let currentSlide = 0;

function nextSlide() {
    goToSlide(currentSlide + 1);
}

function previousSlide() {
    goToSlide(currentSlide - 1);
}

function goToSlide(n) {
    slides[currentSlide].classList.remove(`showing`);
    currentSlide = (n + slides.length) % slides.length;
    slides[currentSlide].classList.add(`showing`);
}

let next = document.getElementById(`next`);
let previous = document.getElementById(`previous`);

next.onclick = function () {
    nextSlide();
};
previous.onclick = function () {
    previousSlide();
};

$(`#slides`).swipe({
    swipeLeft: leftSwipe,
    swipeRight: rightSwipe,
    threshold: 0
});

function leftSwipe() {
    nextSlide();
}

function rightSwipe() {
    previousSlide();
}

window.tree_toggle = function (event) {
    event = event || window.event;
    let clickedElem = event.target || event.srcElement;
    if (!hasClass(clickedElem, `tree-expand`)) {
        return;
    }
    let node = clickedElem.parentNode;
    if (hasClass(node, `tree-expand-leaf`)) {
        return;
    }
    let newClass = hasClass(node, `tree-expand-open`) ? `tree-expand-closed` : `tree-expand-open`;
    let re = /(^|\s)(tree-expand-open|tree-expand-closed)(\s|$)/;
    node.className = node.className.replace(re, `$1` + newClass + `$3`);
}

function hasClass(elem, className) {
    return new RegExp("(^|\\s)" + className + "(\\s|$)").test(elem.className);
}
