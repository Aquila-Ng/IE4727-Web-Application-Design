decaf = document.getElementById('decaf')
decaf_price = document.getElementById('decaf_price')
decaf_error = document.getElementById('decaf_error')

CAL_single = document.getElementById('CAL_single')
CAL_double = document.getElementById('CAL_double')
CAL = document.getElementById('CAL')
CAL_price = document.getElementById('CAL_price')
CAL_error = document.getElementById('CAL_error')

IC_single = document.getElementById('IC_single')
IC_double = document.getElementById('IC_double')
IC = document.getElementById('IC')
IC_price = document.getElementById('IC_price')
IC_error = document.getElementById('IC_error')

price = document.getElementById('totalPrice')

function validateQuantity(inputElement, errorElement){
    inputElement.addEventListener('change', function(){
        quantity = parseFloat(inputElement.value)
        if (!quantity){
            // Insert error handling here
            

        }
        else if (quantity > 50){
            // Insert error handling for value greater than max order of 50
        }
        else {
            calculate_subTotal(inputElement, quantity)
        }
    })
}

function calculate_subTotal(inputElement, quantity){
    const prices = {
        decaf: 2.0,
        CAL_single: 2.0,
        CAL_double: 3.0,
        IC_single: 4.75,
        IC_double: 5.75
    };
    id = inputElement.getAttribute('id')
    console.log(inputElement.getAttribute('id'));
    if (id == 'decaf'){
        console.log(prices.decaf);
        decaf_total = parseFloat(prices[id] * quantity).toFixed(2)
        decaf_price.value = String('$' + decaf_total);
    }
    if (id == 'CAL'){
        element = document.querySelector('input[name="CAL"]:checked')
        CAL_total = parseFloat(prices[element.id] * quantity).toFixed(2)
        CAL_price.value = String('$' + CAL_total)
    }
    if (id == 'IC'){
        element = document.querySelector('input[name="IC"]:checked')
        IC_total = parseFloat(prices[element.id] * quantity).toFixed(2)
        IC_price.value = String('$' + IC_total)
    }
}

validateQuantity(decaf, decaf_error)
validateQuantity(CAL, CAL_error)
validateQuantity(IC, IC_error)