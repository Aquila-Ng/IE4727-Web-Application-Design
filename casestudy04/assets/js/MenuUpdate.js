// Get all the necessary elements
let decaf = document.getElementById('decaf');
let decaf_price = document.getElementById('decaf_price');
let decaf_error = document.getElementById('decaf_error')

let CAL_single = document.getElementById('CAL_single');
let CAL_double = document.getElementById('CAL_double');
let CAL = document.getElementById('CAL');
let CAL_price = document.getElementById('CAL_price');
let CAL_error = document.getElementById('CAL_error')

let IC_single = document.getElementById('IC_single');
let IC_double = document.getElementById('IC_double');
let IC = document.getElementById('IC');
let IC_price = document.getElementById('IC_price');
let IC_error = document.getElementById('IC_error')

let price = document.getElementById('totalPrice');

// Prices object
const prices = {
    decaf: 2.0,
    CAL_single: 2.0,
    CAL_double: 3.0,
    IC_single: 4.75,
    IC_double: 5.75
};

// Function to validate quantity input
function validateQuantity(inputElement, errorElement) {
    inputElement.addEventListener('change', function () {
        let quantity = parseFloat(inputElement.value);
        if (!quantity || quantity < 1) {
            // Insert error handling here for invalid quantity
            inputElement.classList.add('input-error')
            errorElement.textContent = "Required.";
        } else if (quantity > 50) {
            // Insert error handling for value greater than max order of 50
            inputElement.classList.add('input-error')
            errorElement.textContent = "Invalid";
            
        } else {
            inputElement.classList.remove('input-error')
            errorElement.textContent = ''
            calculate_subTotal(inputElement, quantity);
        }
    });
}

// Function to calculate subtotal
function calculate_subTotal(inputElement, quantity) {
    let id = inputElement.getAttribute('id');
    
    if (id === 'decaf') {
        // Calculate decaf subtotal
        let decaf_total = (prices.decaf * quantity).toFixed(2);
        decaf_price.value = '$' + decaf_total;
    } 
    
    if (id === 'CAL') {
        // Calculate CAL subtotal based on selected radio button
        let element = document.querySelector('input[name="CAL"]:checked');
        let CAL_total = (prices[element.id] * quantity).toFixed(2);
        CAL_price.value = '$' + CAL_total;
    } 
    
    if (id === 'IC') {
        // Calculate IC subtotal based on selected radio button
        let element = document.querySelector('input[name="IC"]:checked');
        let IC_total = (prices[element.id] * quantity).toFixed(2);
        IC_price.value = '$' + IC_total;
    }
    
    // Update total price
    updateTotalPrice();
}

// Function to update the total price
function updateTotalPrice() {
    let total = (
        parseFloat(decaf_price.value.replace('$', '')) +
        parseFloat(CAL_price.value.replace('$', '')) +
        parseFloat(IC_price.value.replace('$', ''))
    ).toFixed(2);
    if (total > 0 ){
        price.value = '$' + total;   
    }
}

// Add event listeners for quantity inputs
validateQuantity(decaf, decaf_error);
validateQuantity(CAL, CAL_error);
validateQuantity(IC, IC_error);

// Add event listeners for radio buttons
document.querySelectorAll('input[name="CAL"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
        calculate_subTotal(CAL, CAL.value); // Use the current quantity value
    });
});

document.querySelectorAll('input[name="IC"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
        calculate_subTotal(IC, IC.value); // Use the current quantity value
    });
});