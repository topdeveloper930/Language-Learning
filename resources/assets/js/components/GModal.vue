<template>
    <div>
        <div id="g_modal" :class="{ open: step, 'modal-box': true, fade: true }" tabindex="-1" role="dialog" aria-labelledby="gModalLabel">
            <div v-if="headers[step]" class="modal-header">
                <a href="#" class="modal-close fal fa-times " style="color: #fff; top: inherit;" @click.prevent="shutdown"></a>
                <span id="gModalLabel" class="modal-title">{{ headers[step] }}</span>
            </div>
            <div class="modal-content">
                <div :class="alert_class" v-if="show_alert" v-html="alert_text"></div>

                <dl v-if="'calendars' === step">
                    <dt v-for="cal in c_list">
                        <label><input type="radio" name="choose_cal" v-model="selected_cal" :value="cal.id">{{ cal.name }}</label>
                    </dt>
                </dl>

                <button id="signin-button" class="button google" v-show="'login' === step" @click="signIn">
                    <span class="fab fa-google"></span>
                    <span class="buttonText">{{ lang.signin }}</span>
                </button>
            </div>
            <div class="modal-footer" v-if="cancel_txt || ok_txt">
                <a href="#" :class="cancel_class" @click.prevent="shutdown" v-if="cancel_txt" v-text="cancel_txt"></a>
                <a href="#" :class="ok_class" v-if="ok_txt" @click.prevent="callback()()" v-text="ok_txt"></a>
            </div>
        </div>
        <div id="g_bg" class="fade modal-box-overlay"></div>
    </div>
</template>
<script>
    import fading from "../modules/fading";
    export default {
        name: "GModal"
        , data: function () {
            return {
                fade: new fading({})
                , step: 0
                , m: ''
                , bg: ''
                , c_list: []
                , selected_cal: 'primary'
                , error_mes: ''
            }
        }
        , computed: {
            cancel_class: () => 'secondary'
            , cancel_txt: function() {
                switch (this.step) {
                    case 'calendars':
                    case 'error':
                    case 'unlink_success':
                    case 'token_success':
                        return this.lang.close;
                    case 'confirm_unlink':
                        return this.lang.cancel;
                    default:
                        return '';
                }
            }
            , ok_class: () => 'primary'
            , ok_txt: function() {
                switch (this.step) {
                    case 'calendars':
                        return this.lang.save;
                    case 'confirm_unlink':
                        return this.lang.yes;
                    default:
                        return '';
                }
            }
            , in: () => ' in'
            , headers: function() {
                return {
                    login: this.lang.auth_header,
                    calendars: this.lang.select_calendar,
                    error: this.lang.error,
                    confirm_unlink: this.lang.confirm_unlink_header,
                    unlink_success: this.lang.unlink_success_header,
                    token_success: this.lang.token_success_header,
                    link_success: this.lang.link_success_header
                };
            }
            , show_alert: function () {
                return ['error', 'confirm_unlink', 'unlink_success', 'token_success', 'link_success']
                        .indexOf( this.step ) >= 0;
            }
            , alert_text: function () {
                switch (this.step) {
                    case 'error':
                        return this.error_mes;
                    case 'confirm_unlink':
                        return this.lang.confirm_unlink;
                    case 'unlink_success':
                        return this.lang.unlink_success;
                    case 'token_success':
                        return this.lang.token_success;
                    case 'link_success':
                        return this.lang.link_success;
                    default:
                        return '';
                }
            }
            , alert_class: function () {
                if( 'error' === this.step ) return 'alert alert-danger';
                else if( 'confirm_unlink' === this.step ) return 'alert alert-warning';
                else return 'alert alert-success';
            }
        }
        , methods: {
            close: function () {
                this.fade.out( this.bg );
                this.step = 0;
            }
            , show: function(s) {
                this.step = s;
                this.fade.in( this.bg );
            }
            , shutdown: function() {
                this.error_mes = '';
                this.close();
            }
            , saveCalendar: function () {
                this.$emit( 'save_calendar', this.selected_cal );
                this.step = 0;
            }
            , unlinkCalendar: function () {
                this.$emit( 'do_unlink', document.getElementById("remove_events_cbx").checked );
            }
            , signIn: function () {
                this.shutdown();
                this.$emit( 'on_sign_in' );
            }
            , callback: function () {
                switch (this.step) {
                    case 'calendars':
                        return this.saveCalendar;
                    case 'confirm_unlink':
                        return this.unlinkCalendar;
                    default:
                        return ()=>null;
                }
            }
        }
        ,created() {
            const self = this;
            Shared.$on( 'google_login', () => self.step = 'login' );
            Shared.$on( 'g_error', err => { self.error_mes = err; self.step = 'error'; });
            // Shared.$on( 'calendars', (ls) => { self.c_list = ls; self.step = 'calendars';} )
        }
        , mounted() {
            this.m = document.getElementById('g_modal');
            this.bg = document.getElementById('g_bg');
            this.bg.addEventListener( 'click', this.shutdown );
        }
    }
</script>
<style scoped>
    #signin-button {
        display: block;
        background: #fff;
        width: 190px;
        border-radius: 5px;
        border: thin solid #888;
        box-shadow: 1px 1px 1px grey;
        white-space: nowrap;
        margin: auto;
    }
    #signin-button span.buttonText {
        display: inline-block;
        vertical-align: middle;
        padding-left: 42px;
        padding-right: 42px;
        font-size: 14px;
        font-weight: 700;
        font-family: Roboto,sans-serif;
    }
    dl {
        text-align: left;
    }
    dl input {
        margin-right: 10px;
    }
</style>