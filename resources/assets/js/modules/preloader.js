/* -- VUE PAGE PRELOADER ---------------------------------------------- */
import initializable from "./initializable";
export default class Preloader extends initializable{
    constructor( config ) {
        const defaultOpts = {
            rootId: "root",
            positionCls: 'text-align-center',
            spinnerCls: 'fa fa-spinner fa-spin fa-2x'
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        super.init();

        this._preloader = document.createElement('DIV');
        this._preloader.className = this._positionCls;
        this._preloader.innerHTML = '<i class="' + this._spinnerCls + '"></i>';
        root.parentNode.insertBefore(this._preloader, root);
    }

    stop() {
        this._preloader.remove();
    }
}