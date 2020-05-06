/**
 * Base class. Just extended with overridden config and init method.
 */
export default class initializable {
    constructor( config ) {
        const defaultOpts = {
            callback: this.init
        };

        config = Object.assign( {}, defaultOpts , config);

        for ( let prop in config )
            if( config.hasOwnProperty( prop ) )
                this[ '_' + prop ] = config[ prop ];

        this._callback();
    }

    init() {}
}