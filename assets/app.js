import './scss/app.scss';

import {createIcons, icons} from 'lucide';

createIcons({icons});

/* Elements */
import {ScrollTop} from 'headless-elements'

customElements.define('scroll-top', ScrollTop)

/* Modules */
import './modules/header.js'
import './modules/scrollreveal.js'
// start the Stimulus application
// import './bootstrap';

