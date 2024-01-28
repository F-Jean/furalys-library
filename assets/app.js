/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all';

// custom scripts 
// Generate a btn to load more post of homepage
import './js/load_more.js';
// Generate btn to add images/videos to Post Form
import './js/add_more_medias.js';

// Manage the display of category/artist dropdown of 
// post/_form.html.twig
import './js/category_dropdown.js';
import './js/artist_dropdown.js';

// for video/image field 'releasedThe'
import './js/dateTimeFields.js';