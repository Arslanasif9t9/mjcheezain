const showPopupBtn = document.querySelectorAll(".login-btn");
const formPopup = document.querySelector(".form-popup");
const hidePopupBtn = formPopup.querySelector(".close-btn");
const signupLoginLink = formPopup.querySelectorAll(".bottom-link a");
// console.log(showPopupBtn)

// function showSignupPopup() {
//     document.body.classList.toggle("show-popup");
//     // formPopup.classList['add']("show-signup");
// }

// Show login popup
showPopupBtn.forEach(btn => {
    btn.addEventListener("click", () => {
        document.body.classList.toggle("show-popup");
        if (btn.innerText == 'Customer Sign Up' || btn.innerText == 'Vendor Sign Up') {
            // console.log('signup')
            formPopup.classList.add('show-signup')
        }
        else {
            formPopup.classList.remove('show-signup')
        }
        // console.log(btn.innerText.trim())
    });
})

// Hide login popup
hidePopupBtn.addEventListener("click", () => showPopupBtn[0].click());

// Show or hide signup form
signupLoginLink.forEach(link => {
    link.addEventListener("click", (e) => {
        e.preventDefault();
        formPopup.classList[link.id === 'signup-link' ? 'add' : 'remove']("show-signup");
    });
});