if (document.querySelector(".pc-form-login-container")) {
    const button = document.querySelector(".pc-button-clic");
    const button2 = document.querySelector(".pc-button-clic2");

    let loginForm = document.querySelector(".pc-form-login-container");
    let registerForm = document.querySelector(".pc-form-registration-container");


    function toggleForm() {
        if (loginForm.style.display === "none") {
            loginForm.style.display = "block";
            registerForm.style.display = "none";
        } else {
            loginForm.style.display = "none";
            registerForm.style.display = "block";
        }
    }

    button.addEventListener("click", () => {
        toggleForm()
    });
    button2.addEventListener("click", () => {
        toggleForm()
    });
}