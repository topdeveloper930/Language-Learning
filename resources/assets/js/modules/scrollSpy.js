/**
 * Not tested. "for" loop of the init method needs double check.
 */
import initializable from "./initializable";

export default class scrollSpy extends initializable{
    constructor( config ) {
        const defaultOpts = {
            sectionSelector: ".section",
            classActive: "active"
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        'use strict';

        super.init();

        const section = document.querySelectorAll( this._sectionSelector ),
            sections = {};

        let i = 0;

        Array.prototype.forEach.call(section, function(e) {
            sections[e.id] = e.offsetTop;
        });

        window.onscroll = function() {
            const scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;

            for (i in sections) {
                if (sections.hasOwnProperty(i) && sections[i] <= scrollPosition) {
                    document.querySelector( '.' + this._classActive ).classList.remove( this._classActive );
                    document.querySelector('a[href*=' + i + ']').classList.add( this._classActive );
                }
            }
        };
    }
}