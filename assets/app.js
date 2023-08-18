/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import './bootstrap.js';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'tw-elements'
import './Scripts/add-favorite'
import './Scripts/switch'
import './Scripts/calendar'
import './Scripts/js-form-validator'
import './Scripts/js-form-validator.min.js'
import './Scripts/validator'
import './Scripts/flowbite.min.js'
// Initialization for ES Users
import {
    Modal,
    Collapse,
    Dropdown,
    initTE,
    Ripple,
    Input,
    Sidenav,
    Alert,

} from "tw-elements";

initTE({ Modal, Collapse, Dropdown ,initTE , Ripple, Input, Sidenav, Alert });


// assets/app.js
import zoomPlugin from 'chartjs-plugin-zoom';

// register globally for all charts
document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    Chart.register(zoomPlugin);
});

// register globally for all charts
document.addEventListener('chartjs:init', function (event) {
    const Chart = event.detail.Chart;
    const Tooltip = Chart.registry.plugins.get('tooltip');
    Tooltip.positioners.bottom = function(items) {
    };
});