<template>
    <span style="display: inline-block">
        <button type="button"
                :class="btnCls"
                @click="showGModal"
                v-html="btnTxt"
        ></button>
        <g-modal ref="gmodal"
                 @do_unlink="doUnlink"
                 @on_sign_in="signIn"
                 @save_calendar="saveCalendar"
        ></g-modal>
    </span>
</template>

<script>
    import GCalendar from '../modules/GCalendar';
    import GModal from './GModal';
    export default {
        name: "gCalendar"
        , components: { 'g-modal': GModal }
        , props: {
            calendar_id: {
                default: ''
            }
            , btnCls: {
                type: String,
                default: 'button google'
            }
            , client_id: String
            , api_key: String
            , gapi_token: String
            , ll_location: { required: true }
            , translate: { required: true }
        }
        , data() {
            return {
                authentication_code: "",
                gCal: new GCalendar({
                    calendarID: this.calendar_id,
                    clientId: this.client_id,
                    apiKey: this.api_key,
                    gapi_token: !!this.gapi_token,
                    ll_location: this.ll_location,
                    signinBtnID: 'signin-button'
                })
            }
        }
        , computed: {
            cal_id: function () {
                return this.gCal.calendarID;
            },
            btnTxt: function() {
                return this.cal_id ? this.lang.unlink_btn : this.lang.sync_btn;
            }
        }
        , methods: {
            signIn() {
                const self = this;

                self.gCal.obtainOfflineAccess()
                    .then(
                        res => {
                            if (res.error) throw new Error( res.error );

                            self.authentication_code = res.code;
                            return self.gCal.authExecute( () => true ); // Just to ensure authentication;
                        },
                        self.handleError
                    )
                    .then(
                        t => (self.gCal.listAvailableCalendars()
                                .then(
                                    ls => {
                                        self.$refs.gmodal.c_list = ls;
                                        self.$refs.gmodal.show('calendars');
                                    }
                                )),
                        self.handleError
                    );
            }
            , saveCalendar: function( provider_cal_id ) {
                const self = this;
                axios.post('/ajax/calendar-external', {
                    "provider": "google",
                    "provider_cal_id": provider_cal_id,
                    "authentication_code": self.authentication_code
                })
                    .then(
                        (r) => { // r here is id of the calendar in the DB table (not Google calendarId)
                            self.gCal.calendarID = provider_cal_id;
                            self.gCal.hasToken = true;
                            self.authentication_code = '';
                            self.$refs.gmodal.show('link_success');
                        }
                    )
            }
            , handleError: err => {
                Shared.$emit( 'g_error', JSON.stringify( err ) ); //TODO: Remove JSON formatting?
            }
            , doUnlink( remove_events ) {
                const self = this;
                let errors_arr = [];

                if( remove_events ) {
                    const dd = new Date(),
                        tMin = dd.toISOString();
                    self.gCal.authExecute(()=> self.gCal.listUpcomingEvents( self.ll_location, tMin ))
                        .then(
                            evts => self.gCal.deleteLLEvents( evts, true ),
                            err => errors_arr.push( self.lang.cant_delete, err )
                        )
                        .then(
                            res => {
                                if( !Shared.empty( errors_arr ) ) {
                                    self.handleError( errors_arr );
                                }
                                else {
                                    self.deleteCal();
                                }
                            },
                            err => self.handleError( errors_arr.push( err ) )
                        );
                }
                else {
                    self.deleteCal();
                }
            }
            , deleteCal() {
                const self = this;
                axios.delete( '/ajax/calendar-external/0' )
                    .then(
                        res => {
                            self.gCal.reset();
                            self.$refs.gmodal.show('unlink_success');
                        }
                    )
                    .catch( e => self.handleError(e.response.data) );
            }
            , renewAuthCode: function() {
                const self = this;

                self.gCal.obtainOfflineAccess()
                    .then(
                        r => axios.patch( '/ajax/calendar-external/0', {
                            authentication_code: r.code
                        }),
                        self.handleError
                    )
                    .then(
                        r => self.$refs.gmodal.show('token_success')
                    )
                    .catch( e => self.handleError(e.response.data) );
            }
            , showGModal: function () {
                this.$refs.gmodal.show((this.cal_id) ? 'confirm_unlink' : 'login');
            }
        }
        , created() {
            Object.assign(this.lang, JSON.parse(this.translate));
        }
        , mounted() {
            /**
             * Set callback Get auth code only if calendarID provided and access_token is false.
             */
            const cb = ( this.cal_id && !this.gapi_token )
                ? this.renewAuthCode
                : undefined;

            this.gCal.loadClient( cb );
        }
    }
</script>

<style scoped>

</style>