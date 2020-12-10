// assets/js/app.js
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// register.css
import './Frontend/css/common.scss';
import './Frontend/css/pdf.scss';


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
 
require('./Frontend/js/bilan.js');
require('./Frontend/js/compte_resultat.js');
require('./Frontend/js/marge_brute_MS.js');

import noUiSlider from 'nouislider';
import 'nouislider/distribute/nouislider.css';
window.noUiSlider = noUiSlider
