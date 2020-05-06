import initializable from "./initializable";
new class extends initializable{
    constructor( config ) {
        const defaultOpts = {
            interval: 1000 * 60 * 15
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        'use strict';

        super.init();

        setInterval(this.keepTokenAlive, this.interval);
    }

    keepTokenAlive() {
        axios.post('/keep-token-alive')
            .then( result => console.log( new Date() + ' ' + result.data ));
    }

    get interval () {
        return this._interval;
    }
};