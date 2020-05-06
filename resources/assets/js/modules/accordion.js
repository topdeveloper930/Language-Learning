import initializable from "./initializable";
new class extends initializable{
    constructor( config ) {
        const defaultOpts = {
            linkSelector: ".accordion-title",
            selector: ".accordion",
            classActive: "active",
            iconUp: "fa-angle-up",
            iconDown: "fa-angle-down",
            icon: "i",
            iconCls: "far"
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        'use strict';

        super.init();

        const self = this,
            section = document.querySelectorAll( this._sectionSelector ),
            sections = {};

        document.addEventListener('click', e => self.eventHandler(e), false);

        [].forEach.call(
            document.querySelectorAll( this._selector ),
                div => {
                    const i = document.createElement('I');

                    if( div.classList.contains( self._classActive )) {
                        i.className = self._iconCls + ' ' + self._iconUp;
                        setTimeout( c => div.querySelector(self._icon).click(), 100 );
                    }
                    else {
                        i.className = self._iconCls + ' ' + self._iconDown;
                    }

                    div.querySelector(self._linkSelector).appendChild(i);
                }
        );

    }

    eventHandler( e ) {
        if( !e.target.classList.contains( this._linkSelector.slice(1) ) )
            return;

        e.preventDefault();

        const i = e.target.querySelector( this._icon ),
            p = e.target.parentNode,
            isActive = p.classList.contains( this._classActive );

        document.querySelectorAll(this._selector + '.' + this._classActive).forEach(ac => {
            ac.classList.remove( this._classActive );
            const icon = ac.querySelector('.' + this._iconUp);
            icon.classList.remove(this._iconUp);
            icon.classList.add(this._iconDown);
        });

        if( !isActive ) {
            p.classList.add( this._classActive );
            i.classList.add(this._iconUp);
            i.classList.remove(this._iconDown);
        }
    }
};