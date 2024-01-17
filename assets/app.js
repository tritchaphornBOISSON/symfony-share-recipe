/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';


let myDeleteIngredientEvent = document.querySelector('.delete-form')

myDeleteIngredientEvent.addEventListener('click', function (event) {
    let confirmed = confirm('Are you sure you want to delete this ingredient?');
    if (!confirmed) {
        event.preventDefault();
    }
});