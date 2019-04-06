`use strict`;

function tree_toggle(event) {
    event = event || window.event
    let clickedElem = event.target || event.srcElement
    if (!hasClass(clickedElem, `tree-expand`)) {
        return
    }
    let node = clickedElem.parentNode
    if (hasClass(node, `tree-expand-leaf`)) {
        return
    }
    let newClass = hasClass(node, `tree-expand-open`) ? `tree-expand-closed` : `tree-expand-open`
    let re = /(^|\s)(tree-expand-open|tree-expand-closed)(\s|$)/
    node.className = node.className.replace(re, `$1` + newClass + `$3`)
}

function hasClass(elem, className) {
    return new RegExp("(^|\\s)" + className + "(\\s|$)").test(elem.className)
}
