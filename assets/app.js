import './scss/app.scss';

import {createIcons, icons} from 'lucide';

createIcons({icons});

/* Elements */
import {ScrollTop} from 'headless-elements'
import {Alert, FloatingAlert} from './elements/Alert'
/* Libs */
import './libs/faltpickr'

customElements.define('scroll-top', ScrollTop)
customElements.define('alert-message', Alert)
customElements.define('alert-floating', FloatingAlert)

/* Modules */
import './modules/header.js'
import './modules/scrollreveal.js'
// start the Stimulus application
// import './bootstrap';

