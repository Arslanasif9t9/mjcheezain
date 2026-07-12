const showPopupBtn = document.querySelectorAll(".login-btn");
const formPopup = document.querySelector(".form-popup");

if (formPopup) {
    const hidePopupBtn = formPopup.querySelector(".close-btn");
    const signupLoginLink = formPopup.querySelectorAll(".bottom-link a");

    // Hide login popup
    if (hidePopupBtn) {
        hidePopupBtn.addEventListener("click", () => {
            if (showPopupBtn.length > 0) showPopupBtn[0].click();
        });
    }

    // Show or hide signup form
    if (signupLoginLink) {
        signupLoginLink.forEach(link => {
            link.addEventListener("click", (e) => {
                e.preventDefault();
                formPopup.classList[link.id === 'signup-link' ? 'add' : 'remove']("show-signup");
            });
        });
    }
}

// Show login popup
if (showPopupBtn.length > 0 && formPopup) {
    showPopupBtn.forEach(btn => {
        btn.addEventListener("click", () => {
            document.body.classList.toggle("show-popup");
            if (btn.innerText.trim() === 'Customer Sign Up' || btn.innerText.trim() === 'Vendor Sign Up') {
                formPopup.classList.add('show-signup');
            } else {
                formPopup.classList.remove('show-signup');
            }
        });
    });
}