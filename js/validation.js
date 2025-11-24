
//applicants creates an account first
function registrationValidate() {

    const form = document.getElementById("regForm");
    const submitForm = document.getElementById("id");

    submitForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const name = form.name.value;
        const username = form.username.value;
        const password = form.password.value;
        const confirmPassword = form.confpass.value;


        if (name === "" || username === "" || password === "") {
            alert("Please fill in all fields");
            return false;
        }

    });

    return false;
} 

//applicants log in to their account first
function logintValidation() {

}


function adminValidate() {

}