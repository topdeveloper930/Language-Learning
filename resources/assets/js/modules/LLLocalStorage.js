class LLLocalStorage {
    getItem( key ) {
        let src = localStorage.getItem( key );

        if( !src ) return src;

        if( this.isValidJSON( src ) ) src = JSON.parse( src );

        if( !src.hasOwnProperty('meta') )
            return src;
        else if( !src.meta.hasOwnProperty('valid_till') || ( new Date().getTime() > parseInt( src.meta.valid_till ) ) )
            return null;

        return src.data;
    }

    setItem( key, data, valid_till ) {

        if( typeof valid_till === 'undefined' )
            localStorage.setItem( key, ( this.needsConvertionToJSON( data ) ? JSON.stringify( data ) : data ) );
        else
            localStorage.setItem( key, JSON.stringify( { data: data, meta: {valid_till: valid_till } } ) );

    }

    needsConvertionToJSON( value ) {
        return Array.isArray(value) || ( value && typeof value === 'object' && value.constructor === Object );
    }

    isValidJSON( text ) {
        return ( typeof text === 'string' || text instanceof String) && (/^[\],:{}\s]*$/.test(text.replace(/\\["\\\/bfnrtu]/g, '@').
        replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
        replace(/(?:^|:|,)(?:\s*\[)+/g, '')));
    }
}

export default new LLLocalStorage();