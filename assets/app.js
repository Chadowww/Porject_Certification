/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'tw-elements'
import './switch'
import './calendar'
// Initialization for ES Users
import {
    Modal,
    Collapse,
    Dropdown,
    initTE,
    Ripple,
    Input,

} from "tw-elements";

initTE({ Collapse, Dropdown, Ripple, Input, Modal });

const test = document.querySelectorAll('.bt-fav');

function addToFavorite(event) {
    event.preventDefault();

    const favLink = event.currentTarget;
    const link = favLink.href;

    try{
        fetch(link)
            .then(response => response.json())
            .then(data => {
                const favIcon = favLink.firstElementChild;
                if(data.isFavorite){
                    favIcon.classList.remove('bi-heart');
                    favIcon.classList.add('bi-heart-fill');
                }else {
                    favIcon.classList.remove('bi-heart-fill');
                    favIcon.classList.add('bi-heart');
                }
            });
    }catch(e){
        console.log(e);
    }

}
window.addToFavorite = addToFavorite;
