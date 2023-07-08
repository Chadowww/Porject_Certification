const button = document.querySelector(".pc-button-clic");

let loginForm = document.querySelector(".pc-form-login-container");
let registerForm = document.querySelector(".pc-form-registration-container");


function toggleForm() {
console.log("toggleForm");
    if (loginForm.style.display === "none") {
        loginForm.style.display = "block";
        registerForm.style.display = "none";
    } else {
        loginForm.style.display = "none";
        registerForm.style.display = "block";
    }
}
button.addEventListener("click", () => {toggleForm()});
