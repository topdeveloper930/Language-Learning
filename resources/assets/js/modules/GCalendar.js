export default class GCalendar {

    constructor( config ) {
        const defaultOpts = {
            calendarID: "",
            apiKey: "",
            clientId: "",
            gapi_token: false,
            discoveryDocs: ['https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest'],
            scope: "https://www.googleapis.com/auth/calendar.readonly https://www.googleapis.com/auth/calendar.events",
            confirmed: "confirmed",
            signInBtn: '',
            callback: () => null
        };

        config = Object.assign( {}, defaultOpts , config);

        for ( let prop in config )
            if( config.hasOwnProperty( prop ) )
                this[ '_' + prop ] = config[ prop ];

    }

    /*
        Methods
    */

    loadClient ( cb, el ) {
        const self = this;

        self._callback = ( typeof cb === 'function' ) ? cb : self._callback;
        self.signInBtn = el || self.signInBtn;

            this._signInBtn = el;

            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                if( !self.initiated ){
                    self._client = gapi.client.init( self.opts );
                    self._auth2 = gapi.auth2.getAuthInstance();
                }

                if( typeof self.signInBtn === 'object' && self.signInBtn.nodeName.toUpperCase() === 'BUTTON' ) {
                    self._auth2.attachClickHandler(self.signInBtn, {}
                        , r => Promise.resolve( self.onAuth() )
                        , function (resp) {
                            throw new Error(resp.error);
                        }
                    );
                }
                else {
                    self.onAuth();
                }
            });
    }

    authExecute( cb ) {
        const self = this;
        return new Promise(function (resolve, reject) {
            if( !self.isAuthenticated ) {
                self.auth2.isSignedIn.listen(function () {
                    resolve(cb());
                });

                self.auth2.signIn();
            }
            else {
                resolve( cb() );
            }
        });
    }

    obtainOfflineAccess () {
        const self = this;

        return self.auth2.grantOfflineAccess({
            client_id: self._clientId,
            scope: self._scope,
            response_type: 'permission'
        });
    }

    listAvailableCalendars () {
        // Get list of user's calendars
        const request = gapi.client.calendar.calendarList.list();

        /**
         * Format the list into array of objects, containing the calendar id and name (summary).
         */

        return new Promise(function( resolve, reject ) {
            request.execute( function( resp ) {
                let ls = [];

                if( resp.hasOwnProperty('items') && resp.items.length ) {
                    ls = resp.items.map(function (cal) {
                        const id = (cal.primary) ? 'primary' : cal.id;

                        return {
                            id: id,
                            name: ('primary' === id) ? cal.summary + ' (Primary)' : cal.summary
                        }
                    });
                }

                ( !ls.length ) ? reject( 'No calendar found' ) : resolve ( ls );
            });
        });
    }

    listUpcomingEvents ( search, from, till ) {
        const self = this;
        let params = {
            'calendarId': self.calendarID,
            'singleEvents': true,
            'orderBy': 'startTime'
        };

        if( search ) params.q = search;
        if( from ) params.timeMin = from;
        if( till ) params.timeMax = till;

        return gapi.client.calendar.events.list( params )
            .then(function(response) {
                return response.result.items; // Promise.resolve( response.result.items );
            });
    }

    deleteLLEvents( ev_list, filter_ll ) {

        if( !ev_list.length ) return true;

        const self = this,
            batch = gapi.client.newBatch();

        if( typeof filter_ll === 'undefined' ) filter_ll = false;

        ev_list.map( function( gg_ev ) {
            if( !filter_ll || gg_ev.location === self.ll_location ) {
                batch.add(gapi.client.calendar.events.delete(
                    { calendarId: self.calendarID, eventId: gg_ev.id }
                ));
            }
        } );

        return batch.execute();
    }

    reset () {
        gapi.auth2.getAuthInstance().disconnect();
        this._calendarID = '';
        this._gapi_token = false;
    }

    /*
        Getters and Setters
    */

    get opts () {
        return {
            apiKey: this._apiKey,
            clientId: this._clientId,
            discoveryDocs: this._discoveryDocs,
            scope: this._scope
        }
    }

    get calendarID () {
        return this._calendarID;
    }

    set calendarID ( calendarID ) {
        this._calendarID = calendarID;
    }

    get clientId () {
        return this._clientId;
    }

    set clientId ( client_id ) {
        this._clientId = client_id;
    }

    get initiated() {
        return ( typeof this.client !== 'undefined' );
    }

    get hasToken() {
        return this._gapi_token;
    }

    set hasToken( v ) {
        this._gapi_token = v;
    }

    get isAuthenticated() {
        return this.initiated && this.auth2.isSignedIn.get();
    }

    get auth2() {
        return this._auth2;
    }

    get client () {
        return this._client;
    }

    get ll_location () {
        return this._ll_location;
    }

    get onAuth() {
        return this._callback;
    }

    set onAuth ( cb ) {
        if( typeof cb === 'function' )
            this._callback = cb;
    }

    get signInBtn() {
        return this._signInBtn;
    }

    set signInBtn( el ) {
        this._signInBtn = el;
    }
}