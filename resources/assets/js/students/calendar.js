require('../bootstrap');
require('../modules/String.ucFirst');

import { DateTime } from 'luxon';
window.DT = DateTime;

// Globally used components
Vue.component('opt', require('../components/SelectOpt'));

const app = new Vue({
    el: '#app'
    , components: {
        'g-calendar':       require('../components/gCalendar'),
        'teacher-select':   require('../components/UserSelect.vue'),
        'timeslots':        require('../components/TimeSlots'),
        'class-confirm':    require('../components/ClassConfirm'),
        'js-cal':           require('../components/jsCalendar'),
        'ajax-errors':      require('../components/AjaxErrors')
    }
    , props: {
        active_indx: {
            default: 0
        }
        , source: {
            type: Object,
            default: function () { return {} }
        }
        , active_action: {
            default: 'select'
        }
    }
    , data: () => {
        return {
            teachers: JSON.parse( document.getElementById('teachers' ).value )
            , courses: JSON.parse( document.getElementById('courses' ).value )
            , simpleJsCalendar: require('simple-jscalendar')
            , schedule_advance: parseInt( document.getElementById('schedule_advance' ).value )
            , trial_class_length: parseFloat( document.getElementById('trial_length' ).value )
            , step: parseInt( document.getElementById('start_step' ).value )
            , student_tz: document.getElementById('student_tz' ).innerText
            , last_day: document.getElementById('last_day' ).value
            , selected_date: new Date()
            , selected_time: ''
            , selected_course: {hours: 0, creditsUsed: 0, scheduled: 0}
            , class_duration: 60
            , show_sync_button: false
            , ajax_errors: {}
        };
    }
    , computed: {
        selected_teacher: {
            get: function() {
                return this.teachers[ this.active_indx ];
            }
            , set: function (newValue) {
                this.active_indx = newValue;
            }
        }
        , pronoun: {
            get: function() {
                return ( this.teachers[ this.active_indx ].gender === 'female' )
                    ? 'she'
                    : 'he';
            }
        }
        , pronoun_possessive: {
            get: function() {
                return ( this.teachers[ this.active_indx ].gender === 'female' )
                    ? 'her'
                    : 'his';
            }
        }
        , teacher_languages: {
            get: function() {
                let langs = [];

                this.teachers[ this.active_indx ].languagesTaught.split('&').forEach(function (l, index) {
                    langs.push( l.substr( l.indexOf( '=' ) + 1 ) );
                });

                return langs;
            }
        }
        , teacher_profile_link: {
            get: function() {
                let alias = this.selected_teacher.firstName + '-' + this.selected_teacher.lastName;
                alias = alias.toLowerCase()
                    .replace(/\s/g, '-')
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, "");

                return '/' + this.teacher_languages[0].toLowerCase() + '/tutors/' + alias + '/';
            }
        }
        , teacher_tz: function() {
            return this.selected_teacher.timezone.substr( this.selected_teacher.timezone.indexOf(')') + 2 );
        }
        , time_slots: {
            get: function() {
                return this.source;
            }
            , set: function(newVal) {
                this.source = this.parseDataToTimeSlots(newVal);
            }
        }
        , selected_date_slots: {
            get: function () {
                const mo = this.simpleJsCalendar.prototype.languages[Shared.app_lang].months[this.selected_date.getMonth()];

                return ( mo in this.time_slots )
                    ? this.time_slots[ mo ][ this.selected_date.getDate() ]
                    : { "": 0 };
            }
            , set: function(cnt) {
                const mo = this.simpleJsCalendar.prototype.languages[Shared.app_lang].months[this.selected_date.getMonth()],
                    d = this.selected_date.getDate(),
                    keys = Object.keys( this.time_slots[ mo ][ d ] );
                let indx = keys.indexOf(this.selected_time);

                while( cnt-- ) {
                    this.time_slots[ mo ][ d ][ keys[ indx++ ] ] = 0;
                }
            }
        }
        , selected_with_next_day_slots: function () {
            let next_day = new Date(this.selected_date);
            next_day.setDate( next_day.getDate() + 1);

            const mo = this.simpleJsCalendar.prototype.languages[Shared.app_lang].months[next_day.getMonth()],
                d = next_day.getDate(),
                keys = Object.keys( this.time_slots[ mo ][ d ] );

            let retval = Object.assign({}, this.selected_date_slots );

            if(typeof this.time_slots[ mo ] !== 'undefined' && typeof this.time_slots[ mo ][ d ] !== 'undefined')
                for( let i = 0; i < 4; i++ ) // We need only 3 first timeslots of the next day to check availability just before midnight.
                    retval[keys[i].toString() + i.toString()] = this.time_slots[ mo ][ d ][ keys[i] ];

            return retval;
        }
        , calendar_end: function() {
            return DateTime.fromSQL( this.last_day, { zone: this.student_tz } );
        }
        , cursor_start: function() {
            let begin = DT.local().toUTC(),
                dif = 30 - (begin.minute % 30);
            return begin.plus({ minutes: dif, hours: this.schedule_advance });
        }
        , formatted_date: function () {
            const lang = ( Shared.app_lang === 'es' ) ? 'es-ME' : 'en-US',
                options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

            return this.selected_date.toLocaleDateString(lang, options);
        }
        , selected_course_credit: function() {
            return ( this.selected_course.hours - this.selected_course.creditsUsed - parseFloat( this.selected_course.scheduled ) ) * 60;
        }
    }
    , methods: {
        newTeacherSet: function(i) {
            this.active_action = 'select';
            this.selected_teacher = i;
            this.makeTimeSlots();
        }
        , coursesSummary: function() {
            // Replace last comma with "and"
            'string'.replace(/,(?=[^,]*$)/, ' and');
        }
        , hours_remaining: function( tplt ) {
            tplt = tplt.split('|');

            let parts = [];

            this.courses.forEach(function (course, i) {
                let mes = tplt[Number(course.numStudents > 1)].slice(0),
                    hrs = parseFloat(course.hours ) - parseFloat(course.creditsUsed ) - parseFloat(course.scheduled );
                parts.push( mes.replace(':xx', hrs ).replace( ':yy', course.course ) );
            });

            return ( parts.length > 1 )
                ? parts.join( ', ' ).replace(/,(?=[^,]*$)/, ' and')
                : parts.pop();
        }
        , makeTimeSlots: function() {
            let self = this,
                uri = '/students/ajax/teachers/'
                    + this.selected_teacher.teacherID
                    + '/schedule?start='
                    + this.cursor_start.toFormat('yyyy-LL-dd HH:mm:00')
                    + '&end=' + this.last_day;
            window.axios
                .get( encodeURI( uri ) )
                .then(response => (self.time_slots = response.data));
        }
        , checkUnavailability: function( un, t ) {
            let t_day = t.toFormat('cccc'),
                t_start = t.toFormat('HH:mm:00'),
                t_end = t.plus({ minutes: this.step }).toFormat('HH:mm:00');

            if( t_end === '00:00:00' ) t_end = '24:00:00'; // Fix midnight/new day issue.

            return !un.some(function(u){
                // "t" is same day and begins before timespan end and ends after timespan start.
                if(
                    u.day.toLowerCase() === t_day.toLowerCase() &&
                    t_start < u.endTime &&
                    t_end > u.startTime
                ) {
                    return 1;
                }

                return 0;
            });
        }
        , checkTimeOff: function( un, t ) {
            let f = t.toFormat('yyyy-LL-dd HH:mm:00');
            return !un.some(function(u){
                // t equal or greater than start and less than end.
                if( f < ( u.endDate + ' 24:00:00' ) && f >= ( u.startDate + ' 00:00:00' ) )
                    return 1;

                return 0;
            });
        }
        , checkTrials: function( un, t ) {
            let t_start = t.toFormat('yyyy-LL-dd HH:mm:00'),
                t_end = t.plus({ minutes: this.step }).toFormat('yyyy-LL-dd HH:mm:00');

            return !un.some(function(u){
                if( t_start < u.end && t_end > u.start )
                    return 1;

                return 0;
            });
        }
        , checkLessons: function( un, t ) {
            let t_start = t.toFormat('yyyy-LL-dd HH:mm:00'),
                t_end = t.plus({ minutes: this.step }).toFormat('yyyy-LL-dd HH:mm:00');

            return !un.some(function(u){
                if( t_start < u.eventEnd && t_end > u.eventStart )
                    return 1;

                return 0;
            });
        }
        , isSlotFree: function( d, t ) {
            let self = this;

            return this.checkUnavailability( d.unavailability, t ) &&
                this.checkTimeOff( d.timeoff, t ) &&
                this.checkTrials( d.trials, t ) &&
                this.checkLessons( d.lessons, t.toUTC() );
        }
        , parseDataToTimeSlots: function(data) {
            let self = this,
                cursor = self.cursor_start.setZone(self.student_tz),
                container = {};

            // Let's optimize the process by preparing some fields for use in loops.
            data.trials.forEach(function( u, i ) {
                data.trials[ i ][ 'end' ] = DT.fromSQL( u.start ).plus({ minutes: self.trial_class_length }).toFormat('yyyy-LL-dd HH:mm:ss');
            });

            do {
                this.addSlot(
                    container,
                    cursor.toFormat('LLLL'),
                    cursor.toFormat('d'),
                    cursor.toFormat('h:mm a'),
                    1 * self.isSlotFree( data, cursor.setZone(self.teacher_tz) )
                );

                cursor = cursor.plus({ minutes: self.step });
            }
            while ( self.calendar_end > cursor ) ;

            return container;
        }
        , addSlot: (base, month, day, t, val) => {
            base[ month ] = base[ month ] || {};
            base[ month ][ day ] = base[ month ][ day ] || {};
            base[ month ][ day ][ t ] = val;
        }
        , dateSelected: function (d) {
            this.selected_date = d;
            this.active_action = 'select';
        }
        , timeSelected: function (t) {
            this.selected_time = t;
            this.active_action = 'confirm';
        }
        , isCourseLanguage: function(course) {
            return this.teacher_languages.indexOf( course.language ) > -1;
        }
        , getLessonStart: function() {
            const tt = this.selected_time.split(':'),
                hrs = ( this.selected_time.toUpperCase().indexOf( 'AM' ) > -1 ) ? parseInt(tt[0]) % 12 : parseInt(tt[0]) % 12 + 12,
                min = parseInt(tt[1]),
                d = this.simpleJsCalendar.tools.dateToString(this.selected_date, "yyyy-MM-DD");

            return DT.fromSQL( d, { zone: this.student_tz } ).set({ hours: hrs, minutes: min }).toUTC();

        }
        , errors: function (errors) {
            this.ajax_errors = (errors && typeof errors === 'object' && errors.constructor === Object)
                ? errors
                : {err: Shared.lang.error};

            this.active_action = 'errors';
        }
        , createLesson: function() {
            let self = this,
                dt = this.getLessonStart().toUTC();

            window.axios
                .post( '/ajax/event', {
                    teacherID: self.selected_teacher.teacherID,
                    studentID: self.$el.querySelector('#studentID').value,
                    eventStart: dt.toFormat('yyyy-LL-dd HH:mm:00'),
                    eventEnd: dt.plus({ minutes: self.class_duration }).toFormat('yyyy-LL-dd HH:mm:00'),
                    course: self.selected_course.course,
                    numStudents: self.selected_course.numStudents
                } )
                .then( response => {
                    self.selected_course.scheduled = parseFloat( self.selected_course.scheduled ) + self.class_duration / 60;
                    self.selected_date_slots = self.class_duration / self.step;
                    self.active_action = 'success';

                    Shared.$emit( 'class_created', response.data );
                } )
                .catch(e => self.errors(e.response.data));
        }
    }
    , filters: {
        teacherCourse (courses, languages) {
            let retval = [];

            courses.reduce(function(retval, course){
                if( languages.indexOf( course.language ) > -1 )
                    retval.push( course );

                return retval;
            }, retval);

            return retval;
        }
    }
    , created() {
        const self = this;
        if( self.selected_teacher )
            self.makeTimeSlots();

        Shared.$on('date-selected', self.dateSelected);
        Shared.$on( 'time-selected', self.timeSelected );
        Shared.$on( 'class-confirm', self.createLesson );
    }
    , mounted() {}
});