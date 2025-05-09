email = document.getElementById('email')
password = document.getElementById('password')

email.addEventListener('blur', function() {
    const regex = /^[\w.-]+@(?:[\w-]+\.){1,3}[a-zA-Z]{2,3}$/
    const email_error = document.getElementById("email_error")
    let isValid = regex.test(email.value.trim());

    if (!email.value){
        email_error.textContent = "Please enter a password";
        email.className = "form-control is-invalid"
    }
    else if (email.value && !isValid) {
        email_error.textContent = "Invalid email";
        email.className = "form-control is-invalid"
    }
    else {
        email_error.textContent = "";
        email.className = "form-control is-valid"
    }
})

password.addEventListener('blur', function(){
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
    const password_error = document.getElementById('password_error')
    let isValid = regex.test(password.value.trim())
    let errors = '';
    if (password.value.length < 8) errors += '- 8 or more characters<br>';
    if (!/(?=.*?[A-Z])/.test(password.value)) errors += '- One upper case English letter<br>';
    if (!/(?=.*?[a-z])/.test(password.value)) errors += '- One lower case English letter<br>';
    if (!/(?=.*?[0-9])/.test(password.value)) errors += '- One digit<br>';
    if (!/(?=.*?[#?!@$%^&*-.])/.test(password.value)) errors += '- One special character<br>';

    if (!password.value){
        password_error.textContent = "Password required"
        password.className = "form-control is-invalid"
    } 
    else if (errors && !isValid){
        password.className = "form-control is-invalid"    
        if (errors) password_error.innerHTML = 'Password must contain the following:<br>' + errors;
    }
    else {
        password_error.textContent = ""
        password.className = 'form-control is-valid'
    }
})