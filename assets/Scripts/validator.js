// import Validator from './js-form-validator.js';
// const Validator = require('./js-form-validator.min.js');
import { Validator } from './js-form-validator.min.js';


//////////////
console.log(Validator)
const firstname = document.querySelector('#user_firstname');
const lastname = document.querySelector('#user_lastname');
const email = document.querySelector('#user_email');
const password = document.querySelector('#password');
const passwordConfirm = document.querySelector('#confirm-password');



// Get form handle

if(document.querySelector('.form-user-edit')){
    let formHandle = document.querySelector('.form-user-edit');

    new Validator(formHandle, function (err, res) {

        // some code of success of validation
        return res;
    }, {
        // set auto tracking forcibly
        autoTracking: true
    });
// Got to validation
}

if (document.querySelector('.form-user-login')){
    let formHandleLogin = document.querySelector('.form-user-login');

    new Validator(formHandleLogin, function (err, res) {

        // some code of success of validation
        return res;
    }, {
        // set auto tracking forcibly
        autoTracking: true
    });
// Got to validation
}
if (document.querySelector('.form-user-register')) {
    console.log('register')
    let formHandleRegister = document.querySelector('.form-user-register');

    new Validator(formHandleRegister, function (err, res) {

        // some code of success of validation
        return res;
    }, {
        // set auto tracking forcibly
        autoTracking: true
    });
}

