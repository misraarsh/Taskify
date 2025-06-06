document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('.form-details');
    const errorMsg = document.querySelector('.errorMsg');

    if (!form) return;

    form.addEventListener("submit", (e) => {
        const email = document.getElementById('email')?.value.trim();
        const password = document.getElementById('password')?.value;
        const nameField = document.getElementById('name');
        const cpasswordField = document.getElementById('cpassword');

        if (nameField && cpasswordField) {
            const name = nameField.value.trim();
            const cpassword = cpasswordField.value;

            if (!name || !email || !password || !cpassword) {
                e.preventDefault();
                errorMsg.textContent = "All fields are required";
                return;
            }

            if (password !== cpassword) {
                e.preventDefault();
                errorMsg.textContent = "Passwords do not match";
                return;
            }

        } else {
            if (!email || !password) {
                e.preventDefault();
                errorMsg.textContent = "Email and password are required";
                return;
            }
            
        }

        // setTimeout(() => {
        //     window.location.href = "/login";
        // }, 5000);
        
    });
});
