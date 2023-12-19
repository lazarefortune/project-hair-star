import './scss/admin.scss'

import {createIcons, icons} from 'lucide';
createIcons({icons});
/* Elements */
import {NavTabs} from 'headless-elements'
import {Spotlight} from "./elements/admin/Spotlight";
import '@grafikart/drop-files-element'
/* Libs */
import './libs/faltpickr'
import './libs/select2'
/* Modules */
import './modules/modal.js'
import './modules/scrollreveal.js'
/* ===== Pages ===== */
import './pages/index.js'

// start the Stimulus application
// import './bootstrap'

customElements.define('spotlight-bar', Spotlight)
customElements.define('nav-tabs', NavTabs)