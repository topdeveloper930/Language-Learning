/* - SHORTEN TEXT SNIPPETS ---------------------------------------------- */
import initializable from "./initializable";
export default class Shorten extends initializable{
    constructor( config ) {
        const defaultOpts = {
            nodeSelector: ".shorten",
            strlength: 150,
            more: "More",
            less: "Less",
            cls: "ellipsis",
            clsShort: "short",
            clsMore: "short-long",
            clsLong: 'long'
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    makeShortHtml( txt, len ) {
        const shortNeedle = '%',
            longNeedle = '%%';

        if( typeof this._spanShort === 'undefined' ) {
            this._spanShort = '<span class="' + this._clsShort + '">' + shortNeedle + '<span class="ellipsis">... </span></span><span class="' + this._clsLong + '" hidden="1">' + longNeedle + '</span>';
        }

        return this._spanShort.replace(shortNeedle, txt.substring(0, len)).replace(longNeedle, txt);
    }

    init() {
        super.init();

        document.removeEventListener('click', e => this.clickHandler(e), false );
        document.addEventListener('click', e => this.clickHandler(e), false);

        this.run();
    }

    run() {
        const self = this;

        document.querySelectorAll(self._nodeSelector).forEach(n => {
            let strlength = n.dataset.length;
            if ( !strlength ) strlength = self._strlength; // default
            if(n.innerHTML.length > strlength){
                n.innerHTML = self.makeShortHtml( n.innerHTML, strlength );
                n.appendChild( self.moreBtn );
            }
        });
    }

    clickHandler(e) {
        const self = this;
        if( !e.target.classList.contains( self._clsMore ) ) return;

        const target = e.target.closest( self._nodeSelector );

        if( target.querySelector( 'span.' + self._clsLong ).getAttribute( 'hidden' ) ) {
            self.show( target.querySelector( 'span.' + self._clsLong ) );
            self.hide( target.querySelector( 'span.' + self._clsShort ) );
            e.target.innerHTML = '&nbsp;' + self._less;
        } else {
            self.show( target.querySelector( 'span.' + self._clsShort ) );
            self.hide( target.querySelector( 'span.' + self._clsLong ) );
            e.target.innerHTML = self._more;
        }
    }

    hide(n) {
        n.setAttribute( 'hidden', 1 );
    }

    show(n) {
        n.removeAttribute( 'hidden' );
    }

    get moreBtn() {
        if( typeof this._moreBtn === 'undefined' ) {
            this['_moreBtn'] = document.createElement('A');
            this._moreBtn.innerHTML = this._more;
            this._moreBtn.className = this._clsMore;
            this._moreBtn.href='javascript:void(0);'
        }

        return this._moreBtn.cloneNode(true);
    }
}