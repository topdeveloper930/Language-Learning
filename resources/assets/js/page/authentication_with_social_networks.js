import initializable from "../modules/initializable";

class SNAuth extends initializable{
    constructor( config ) {
        const defaultOpts = {
            link_stub: '/auth/:provider/:role',
            class_select: 'sn-link',
            common_classes: 'button'
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        super.init();
        const self = this;

        document.addEventListener('click', e => {
            if( e.target.classList.contains( self._class_select ))
                window.location.href = self._link_stub.replace(':role', self.role).replace(':provider', self.getProvider(e.target));
        } );
    }

    getProvider( elem ) {
        return elem.className
            .replace(this._class_select, '')
            .replace(this._common_classes, '')
            .trim();
    }

    get role() {
        const tabLink = document.querySelector('.tab-navigation a.active'),
            retval = tabLink
                ? tabLink.href.substr( tabLink.href.indexOf('#') + 1 )
                : document.querySelector('[name="role"]').value;

        return retval.endsWith('s') ? retval.slice(0, -1) : retval;
    }
}

new SNAuth();