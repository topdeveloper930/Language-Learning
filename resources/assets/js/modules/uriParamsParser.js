/* - Parser for encoded parameters (agesTaught, coursesTauht etc.) ---------------------------------------------- */
import initializable from "./initializable";
export default class uriParamsParser extends initializable{
    constructor( config ) {
        const defaultOpts = {
            urlStub: "http://s.co?"
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    parse( rawUri, paramName ) {
        const u = new URL( this.urlStub + decodeURI( rawUri ) ),
            sp = u.searchParams;
        return sp.getAll( paramName.replace('[]', '') + '[]' );
    }

    get urlStub() {
        return this._urlStub;
    }
}