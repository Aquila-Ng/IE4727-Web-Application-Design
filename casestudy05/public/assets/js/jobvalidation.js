// Function to handle validation
function validate(inputElement, regex, errorElement) {
    inputElement.addEventListener('blur', function() {
        let isValid = regex.test(inputElement.value.trim());

        if (!inputElement.value) {
            // Input is empty
            inputElement.classList.add('input-error');
            errorElement.textContent = "This field is required.";
            errors[inputElement.getAttribute('id')] = false;
        }
        else if (inputElement.getAttribute('id') === 'date' && !validateDate(inputElement)) {
            // Input is a date but invalid
            inputElement.classList.add('input-error');
            errorElement.textContent = "Invalid date. Date cannot be in the past.";
            errors[inputElement.getAttribute('id')] = false;
        } 
        else if (!isValid) {
            // Input does not match the regex
            inputElement.classList.add('input-error');
            errorElement.textContent = "Invalid input.";
            errors[inputElement.getAttribute('id')] = false;
        } else {
            // Input is valid
            inputElement.classList.remove('input-error');
            errorElement.textContent = "";
            errors[inputElement.getAttribute('id')] = true;
        }
    });
}

// Date validation: Date must be in the future or today
function validateDate(inputElement) {
    const inputDate = new Date(inputElement.value.trim());
    const currentDate = new Date();
    // Compare dates, make sure input date is not in the past
    return inputDate >= currentDate.setHours(0, 0, 0, 0);
}

// Check form status on any change
document.getElementsByName('jobForm')[0].addEventListener('change', function() {
    if (Object.values(errors).includes(false)) {
        document.getElementById('submit').disabled = true;
    } else {
        document.getElementById('submit').disabled = false;
    }
});

// Error tracking for each field
const errors = {
    "custName": false,
    "email": false,
    "date": false,
    "exp": false
};

// Get form elements and error elements
var custName = document.getElementById('custName');
var email = document.getElementById('email');
var date = document.getElementById('date');
var exp = document.getElementById('exp');

var custNameError = document.getElementById('custNameError');
var emailError = document.getElementById('emailError');
var dateError = document.getElementById('dateError');
var expError = document.getElementById('expError');

// Regular expressions for validation
let custNameRegex = /^[a-zA-Z\s]+$/; // Allow only alphabets and spaces
let emailRegex = /^[\w.-]+@(?:[\w-]+\.){1,3}[a-zA-Z]{2,3}$/; // Email format
let dateRegex = /^\d{4}-\d{2}-\d{2}$/; // Date in yyyy-mm-dd format
let expRegex = /\S+/; // Non-whitespace characters

// Validate each field
validate(custName, custNameRegex, custNameError);
validate(email, emailRegex, emailError);
validate(date, dateRegex, dateError);
validate(exp, expRegex, expError);