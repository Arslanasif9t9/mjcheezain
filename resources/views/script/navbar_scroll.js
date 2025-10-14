let navbar = document.querySelector('.header-heo');
let logoImg = document.querySelector('.logo-img');

window.addEventListener('scroll', () => {
    let scroll = window.scrollY;
    let isMobile = window.innerWidth <= 768; // You can adjust breakpoint
    logoImg.style.filter = "brightness(2)";

    if (scroll > 0) {
        // Scrolled state
        navbar.style.background = "black";
        navbar.style.height = "120px";
        logoImg.style.height = "120px";
        logoImg.style.position = "absolute";
        logoImg.style.top = "1px";

        if (isMobile) {
            // Center on mobile
            logoImg.style.left = "5%";
            // logoImg.style.transform = "translateX(-50%)";
        } else {
            // Left side 15% on larger screens
            logoImg.style.left = "15%";
            logoImg.style.transform = "translateX(0)";
        }
    } else {
        // Top of page state
        navbar.style.background = "transparent";
        navbar.style.height = "100px";
        logoImg.style.height = "120px";
        logoImg.style.position = "absolute";
        logoImg.style.top = "100px";
        logoImg.style.left = "100px";
        logoImg.style.transform = "none";
    }
});
    let scroll = window.scrollY;
    let isMobile = window.innerWidth <= 768; // You can adjust breakpoint
    logoImg.style.filter = "brightness(2)";

    if (scroll > 0) {
        // Scrolled state
        navbar.style.background = "black";
        navbar.style.height = "120px";
        logoImg.style.height = "120px";
        logoImg.style.position = "absolute";
        logoImg.style.top = "1px";

        if (isMobile) {
            // Center on mobile
            logoImg.style.left = "5%";
            // logoImg.style.transform = "translateX(-50%)";
        } else {
            // Left side 15% on larger screens
            logoImg.style.left = "15%";
            logoImg.style.transform = "translateX(0)";
        }
    } else {
        // Top of page state
        navbar.style.background = "transparent";
        navbar.style.height = "100px";
        logoImg.style.height = "120px";
        logoImg.style.position = "absolute";
        logoImg.style.top = "100px";
        logoImg.style.left = "100px";
        logoImg.style.transform = "none";
    }