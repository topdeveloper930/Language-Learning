require('../modules/tabsNavigation');

import Cookies from 'js-cookie';

import initializable from "../modules/initializable";

class Login extends initializable{
    constructor( config ) {
        const defaultOpts = {
            ckName: 'login_teacher',
            teachers_link: document.querySelector('a[href="#teachers"]'),
            students_link: document.querySelector('a[href="#students"]')
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        super.init();
        const self = this;

        self._teachers_link.addEventListener('click', () => self._cookie.set(self._ckName, 1, {expires: 365}), false);
        self._students_link.addEventListener('click', () => self._cookie.remove(self._ckName), false);

        document.addEventListener('DOMContentLoaded', ()=> self.setTab() );

        setTimeout(
            () => document.querySelectorAll('.shaking').forEach(l => l.classList.remove('shaking')),
            2000
        );
    }

    click( elem ) {
        const evt = new MouseEvent('click', {
                bubbles: true,
                cancelable: true,
                view: window
            }),
            canceled = !elem.dispatchEvent(evt);
    }

    setTab() {
        if(this._cookie.get(this._ckName))
            this.click( this._teachers_link );
    }
}

new Login({cookie: Cookies});
