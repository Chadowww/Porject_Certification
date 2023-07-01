/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import 'tw-elements'
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


