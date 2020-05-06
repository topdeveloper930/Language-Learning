import initializable from "./initializable";
import fading from "../modules/fading";
export default class slideOutMenu extends initializable{
    constructor( config ) {
        const defaultOpts = {
            fade: new fading({}),
            menuSelector: ".hidden-menu",
            linkSelector: ".hidden-menu",
            overlaySelector: ".hidden-menu-overlay",
            classActive: "open"
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );

        this._overlay = document.querySelector( this._overlaySelector );
    }

    init() {
        'use strict';

        super.init();

        const self = this,
            section = document.querySelectorAll( this._sectionSelector ),
            sections = {};

        document.addEventListener('click', e => self.eventHandler(e), false);
    }

    eventHandler( e ) {
        if( this.overlay && e.target === this.overlay ) {
            this.closeMenu();
            return;
        }

        const href = this.getHrefAttr( e.target );

        if( false !== href ) {
            e.preventDefault();
            this.openMenu( href );
        }
    }

    closeMenu (){
        document.querySelector( this._menuSelector + '.' + this._classActive ).classList.remove( this._classActive );

        this._fade.out( this.overlay );
    }
    openMenu ( target ){
        const el = document.querySelector('.hidden-menu'+target);

        if( el ) {
            el.classList.add( this._classActive );

            this._fade.in( this.overlay );
        }
    }

    getHrefAttr ( a ) {
        if( 'A' !== a.nodeName || !a.getAttribute("href") || a.getAttribute("href").charAt(0) !== "#" || a.getAttribute("href").length < 2) return false;

        const href = a.getAttribute("href");

        return document.getElementById( href.slice(1) ) ? href : false;
    }

    get overlay() {
        return this._overlay;
    }

    set overlay( ol ) {
        this._overlay = ol;
    }
}

new slideOutMenu();