require('../bootstrap');

Vue.component('ajax-errors', require('../components/AjaxErrors'));

Vue.component('ll-modal', require('../components/Modal'));

import { DateTime } from 'luxon';
window.DT = DateTime;

const app = new Vue({
    el: '#root'
    , components: {
        'g-calendar':   require('../components/gCalendar'),
        'lesson':       require('../components/Lesson')
    }
    , data: function () {
        return {
            classes: JSON.parse( document.getElementById('upcoming_classes' ).value )
            , timezone: document.querySelector('h5#timezone > span').textContent
            , selected_class: {}
            , advance_hrs: document.getElementById('schedule_advance' ).value
            , ajax_errors: false
        }
    }
    , computed: {
        class_body: function () {
            if( Shared.empty( this.selected_class ) )
                return '';

            const dt = DT.fromISO( this.selected_class.eventStart.replace(' ', 'T') + 'Z', { zone: this.timezone } );

            return  '<p>' + Shared.lang.cancel_ask.replace(':tt', this.selected_class.teacher_name ) + '</p>'
                + '<h5 class="text-black">' + dt.toLocaleString( Object.assign( DT.DATE_HUGE, { locale: Shared.app_lang } ) )
                + '<br>' + dt.toLocaleString(Object.assign( DT.TIME_SIMPLE, { locale: Shared.app_lang } ) ) + ' - '
                + DT.fromISO( this.selected_class.eventEnd.replace(' ', 'T') + 'Z', { zone: this.timezone } )
                    .toLocaleString( Object.assign( DT.TIME_SIMPLE, { locale: Shared.app_lang } ) )+ '</h5>';
        }
    }
    , methods: {
        cancelClass: function() {
            const self = this,
                indx = self.classes.indexOf( self.selected_class );

            window.axios
                .delete( '/ajax/event/' + self.selected_class.calendarID )
                .then(response => {
                    self.classes = self.classes.filter( c => c.calendarID !== self.selected_class.calendarID );
                    self.modalClose();
                } )
                .catch(e => self.ajax_errors = Shared.isObject(e.response.data) ? e.response.data : {err: Shared.lang.error});
        }
        , modalClose: function () {
            this.selected_class = {};
            this.ajax_errors = false;
        }
    }
});