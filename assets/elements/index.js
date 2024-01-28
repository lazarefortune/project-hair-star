import {NavTabs, ScrollTop, ModalDialog} from 'headless-elements'
import {Spotlight} from './admin/Spotlight'
import {Alert, FloatingAlert} from "./Alert";

customElements.define('spotlight-bar', Spotlight)
customElements.define('nav-tabs', NavTabs)
customElements.define('scroll-top', ScrollTop)
customElements.define('alert-message', Alert)
customElements.define('alert-floating', FloatingAlert)
customElements.define('modal-dialog', ModalDialog)