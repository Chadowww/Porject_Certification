import Validator from './js-form-validator';

const firstname = document.querySelector('#user_firstname');
const lastname = document.querySelector('#user_lastname');
const email = document.querySelector('#user_email');
const password = document.querySelector('#password');
const passwordConfirm = document.querySelector('#confirm-password');

// Get form handle
 let formHandle = document.querySelector('.form-user-edit');

if(document.querySelector('.form-user-edit')){
 new Validator(document.querySelector('.form-user-edit'), function (err, res) {
  return res;
 });
}