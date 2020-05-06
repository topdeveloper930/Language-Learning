import initializable from "./initializable";
export default class toaster extends initializable {
    constructor(config) {
        const defaultOpts = {
            toasterSelector: ".toaster",
            toastSelector: ".toast",
            hideClass: "toast-out",
            delay: 4000,
            fadeTime: 200,
            yBottom: 200,
            autoHide: true
        };

        config = Object.assign({}, defaultOpts, config);

        super(config);
    }

    init() {
        'use strict';

        super.init();

        const self = this,
            fadeOut = (e) => {
                if(e.target.classList.contains(self._toastSelector.substr(1)))
                    e.target.classList.add(self._hideClass);
            };

        this.addToaster();

        document.addEventListener('click', fadeOut, false);
    }

    toast( mes, type ) {
        const self = this,
            toast = document.createElement('DIV');
        toast.innerHTML = mes;
        toast.className = this._toastSelector.substr(1);
        if( typeof type !== 'undefined' ) toast.classList.add( type );
        self.toaster.appendChild( toast );
        toast.addEventListener('animationend', e => self.toaster.removeChild(toast), false)
    }

    default( mes ) {
        this.toast( mes );
    }

    success( mes ) {
        this.toast( mes, 'success' );
    }

    caution( mes ) {
        this.toast( mes, 'caution' );
    }

    notify( mes ) {
        this.toast( mes, 'notify' );
    }

    addToaster() {
        if( !this._toaster ) {
            this._toaster = document.createElement('DIV');
            this._toaster.className = this._toasterSelector.substr(1);
            document.body.appendChild( this._toaster );
        }
    }

    get toaster() {
        return this._toaster;
    }
}