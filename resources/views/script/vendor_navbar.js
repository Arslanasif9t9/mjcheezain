const aside = document.getElementById('aside');
function navbarToggle(elem) {
    elem.firstElementChild.classList.toggle('text-white');
    elem.firstElementChild.classList.toggle('fa-bars');
    elem.firstElementChild.classList.toggle('fa-times');
    aside.classList.toggle('show');
    // aside.classList.toggle('hide');
}