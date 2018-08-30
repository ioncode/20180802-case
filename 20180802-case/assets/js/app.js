/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');
require('bootstrap');

console.log('Webpack Encore, jquery, bootstrap libs connected, edit in assets/js/app.js');

$(document).ready(function() {

    console.log('Document ready');

    if ($('#filter_nav')){
         // process filter
        let filter_form = $('#filter_form');
        let action = filter_form.attr('action');
        // console.log(['Filter', filter_form, action]);
        filter_form.submit(function (event) {
            let filter_input = $('#filter_nav');

            // console.log([event, action, filter_input.val(), action+filter_input.val()]);
            filter_form.attr('action', action+filter_input.val());


            //event.preventDefault();


        });

    }

});


