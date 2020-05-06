require('../bootstrap');
require('../modules/Date.formatStd');

import slideOutMenu from "../modules/slideOutMenu";
import gCalendar from "../components/gCalendar";
import toaster from "../modules/toaster";
import errorsMixin from "../mixins/errorsMixin";

import { DateTime } from 'luxon';
window.DT = DateTime;

const app = new Vue({
    el: '#root'
    , components: {
        gCalendar
    }
    , mixins: [ errorsMixin ]
    , data() {
        return {
            selectedTeacher: null,
            selectedCourse: null,
            pickedDate: null,
            selectedSlot: null,
            minDate: schedule_advance ? schedule_advance / 24 : 1,
            maxDate: last_day,
            lengths: [30, 60, 90, 120],
            length: 0,
            courses: courseList,
            calendarRawData: {},
            slotsSource: {},
            teachers: teacherList,
            toaster: new toaster({}),
            stepInstruction: '',
            loading: false,
            teacher_courses: []
        };
    }
    , computed: {
        isReschedule: function() {
            return !this.sh.empty(editClass);
        },
        defaultDate: function() {
            if( this.isReschedule ) {
                return DT.fromSQL( editClass.eventStart, { zone: "UTC" }).setZone(timezone).toFormat('LL/dd/yyyy');
            }

            return schedule_advance/24;
        },
        maxClassLength: function () {
            return this.selectedCourse
                ? this.remainingCredits( this.selectedCourse ) * 60
                : 0;
        },
        selectedDate: {
            get: function () {
                const retval = (this.pickedDate instanceof Date)
                    ? DT.fromJSDate(this.pickedDate)
                    : this.pickedDate
                        ? DT.fromFormat(this.pickedDate, 'LL/dd/yyyy', { zone: timezone })
                        : this.pickedDate;

                return ( retval && retval > this.cursorStart ) ? retval : this.cursorStart;

            },
            set: function ( newVal ) {
                this.pickedDate = newVal;
            }
        },
        cursorStart: function() {
            const begin = DT.local(),
                dif = 30 - (begin.minute % 30);

            begin.setZone(timezone);

            return begin.plus({ minutes: dif, seconds: -1 * begin.second, hours: schedule_advance });
        },
        calendar_end: function() {
            return DT.fromJSDate( last_day, { zone: timezone } );
        },
        time_slots: {
            get: function() {
                let compareArr = [];
                const self= this,
                    retval = [],
                    selection = self.sh.empty( this.slotsSource )
                    ? []
                    : self.slotsSource[self.selectedDate.toFormat('L')]
                        ? self.slotsSource[self.selectedDate.toFormat('L')][self.selectedDate.toFormat('d')] || []
                        : [];

                if (!selection.length || self.length === start_step) {
                    return selection;
                }
                else {
                    let p = self.length / start_step;

                    selection.forEach( o => {
                        retval.push($.extend({}, o));
                        compareArr.push($.extend({}, o));
                    });

                    // If last time slot is cross midnight, then add few time slots of the next day for checking availability.
                    if( retval[retval.length - 1].status && retval[retval.length - 1].t.setZone(timezone).toFormat('HH:mm') === '23:30') {
                        const tomorrow = self.selectedDate.plus({ days: 1 }),
                            m = tomorrow.toFormat('L'),
                            d = tomorrow.toFormat('d'),
                            nextDaySlots = (self.slotsSource.hasOwnProperty(m) && self.slotsSource[m].hasOwnProperty(d))
                                ? self.slotsSource[m][d]
                                : [];

                        // No use to extend the array, if the first slot of the next day starts later, than midnight, or unavailable.
                        if( nextDaySlots.length && nextDaySlots[0].t.toFormat('HH:mm') === '00:00' && nextDaySlots[0].status )
                            compareArr = compareArr.concat(nextDaySlots.slice(0,p));
                    }

                    return retval.map((el, indx) => {
                        if(!el.status) return el;

                        let i = p,
                            clone = el.t;

                        // Check every next slot for the class duration exists, has true status and has continuous time.
                        while ( --i ) {
                            indx++;
                            clone = clone.plus({minutes: start_step});

                            if( !compareArr[indx] || !compareArr[indx].status || +clone !== +compareArr[indx].t ){
                                el.status = 0;
                                break;
                            }
                        }

                        return el;
                    });
                }
            }
            , set: function(newVal) {
                // Let's optimize the process by preparing some fields for use in loops.
                newVal.trials.forEach(function (u, i) {
                    newVal.trials[i]['end'] = DT.fromSQL(u.start).plus({minutes: trial_class_length}).toFormat('yyyy-LL-dd HH:mm:ss');
                });

                if( this.isReschedule )
                    newVal.lessons.splice( newVal.lessons.findIndex(l => l.eventStart === editClass.eventStart && l.eventEnd === editClass.eventEnd ), 1);

                this.calendarRawData = newVal;

                this.parseDataToTimeSlots();
            }
        },
        teacher_tz: function() {
            return this.selectedTeacher.timezone.substr( this.selectedTeacher.timezone.indexOf(')') + 2 );
        },
        selectedDT: function () {
            return this.selectedSlot.setZone(timezone).toLocaleString(DateTime.DATETIME_FULL);
        },
        classLength: function() {
            return this.lengthText();
        },
        program: function() {
            return this.selectedCourse.course + ' (' + this.lang[this.selectedCourse.numStudents] + ')';
        },
        teacherCourses: function () {
            if (!courseList.length) return [];

            return courseList.filter( c => this.teacher_courses.indexOf(c.course) > -1 );
        }
    }
    , methods: {
        toUTC: function( dt ) {
            return dt.toISOString().substr(0, 17).replace('T', ' ') + '00';
        },
        studentTime: slot => slot.t.setZone( timezone ).toFormat('h:mm a'),
        remainingCredits: function(course){
            return parseFloat(course.hours) - parseFloat(course.creditsUsed) - parseFloat(course.scheduled);
        },
        getInstruction: function () {
            return Array.from(document.querySelectorAll(".step.activated"))
                .pop().dataset.instruction;
        },
        lengthText: function(l) {
            if(typeof l === 'undefined')
                return this.sh.__('n_minute_class').replace('%d', this.length);
            else
                return this.sh.__('n_minutes').replace('%d', l);
        },
        fullname: function (user) {
            return (user.firstName + ' ' + user.lastName).trim();
        },
        accost: function (user) {
            return (user.title + ' ' + this.fullname(user)).trim();
        },
        imgSrc: function (user) {
            const src = user.profileImage ? user.profileImage : 'https://placehold.it/300x300';
            return (src.indexOf('/') === 0 || src.indexOf('http') === 0) ? src : '/' + src;
        },
        teacherLanguages: function (teacher) {
            return teacher.languagesTaught.toLowerCase().replace(/specialty%5b%5d=/g, '').split('&').filter( l => l);
        },
        courseDescription: function (c) {
            return this.lang[c.numStudents]
                + this.lang.course_credits.replace('%d', this.remainingCredits(c).toFixed(2))
                                          .replace('%s', parseFloat(c.scheduled).toFixed(2));
        },
        selectTeacher: function(teacher){
            this.teacher_courses = [];
            this.selectCourse(null);
            this.selectedTeacher = teacher;
            this.toLastActivatedStep();
            this.getTeacherCalendarData();

            const self = this;

            window.axios.get( ajax_url + '?teacherID=' + teacher.teacherID )
                .then( r => self.teacher_courses = r.data );
        },
        selectCourse: function (course) {
            this.setLength(0);

            if( course && this.remainingCredits( course ) < this.lengths[0] / 60 )
                this.toaster.default( this.sh.__('not_enough_credits') );
            else
                this.selectedCourse = course;

            this.toLastActivatedStep();
        },
        setLength: function (length) {
            this.onDateSelect();

            if( length > this.maxClassLength )
                this.toaster.default( this.sh.__('not_enough_credits') );
            else
                this.length = length;

            this.toLastActivatedStep();

        },
        selectTime: function(slot) {
            if( !slot.status ){
                this.toaster.caution( this.sh.__('slot_unavailable') );
                return;
            }

            this.selectedSlot = slot.t;
            this.toLastActivatedStep();
        },
        toLastActivatedStep: function () {
            setTimeout(
                () => {
                    const nodes = document.querySelectorAll('.step.activated');
                    nodes[nodes.length - 1].scrollIntoView({behavior: "smooth"});
                    this.stepInstruction = this.getInstruction();
                },
                200
            );
        },
        onDateSelect: function() {
            this.selectedSlot = null;
        },
        initDatePicker: function () {
            const self = this,
                $dp = $("#datepicker");

            if( $dp.datepicker ) $dp.datepicker( 'destroy' );

            $dp.datepicker({
                minDate: self.minDate,
                maxDate: self.maxDate,
                defaultDate: self.defaultDate,
                onSelect: function( dateText, inst ) {
                    self.selectedDate = dateText;
                    self.onDateSelect();
                },
                beforeShowDay : date => self.classForDate( date )
            });

            self.selectedDate = $dp.datepicker( "getDate" );
        },
        submitLesson: function(ev) {
            this.isReschedule
                ? this.rescheduleLesson()
                : this.createLesson();
        },
        createLesson: function() {
            let self = this,
                dt = self.selectedSlot.toUTC();

            self.loading = true;

            window.axios
                .post( '/ajax/event', {
                    teacherID: self.selectedTeacher.teacherID,
                    studentID: studentID,
                    eventStart: dt.toFormat('yyyy-LL-dd HH:mm:00'),
                    eventEnd: dt.plus({ minutes: self.length }).toFormat('yyyy-LL-dd HH:mm:00'),
                    course: self.selectedCourse.course,
                    numStudents: self.selectedCourse.numStudents
                } )
                .then( response => {
                    // Update scheduled credits for the course and reset
                    self.selectedCourse.scheduled = parseFloat( self.selectedCourse.scheduled ) + self.length / 60;

                    // Reset page.
                    if(self.teachers.length > 1) self.selectedTeacher = null;
                    else self.selectTeacher(self.teachers[0]);

                    if(self.courses.length > 1) self.selectedCourse = null;

                    self.length = 0;
                    self.selectedSlot = null;

                    self.toaster.success( self.sh.__('schedule_class_success') );
                } )
                .catch(e => self.errors(e.response.data))
                .finally(() => self.loading = false);
        },
        rescheduleLesson: function() {
            let self = this,
                dt = self.selectedSlot.toUTC();

            self.loading = true;

            window.axios
                .put( '/ajax/event/' + editClass.calendarID, {
                    id: editClass.calendarID,
                    eventStart: dt.toFormat('yyyy-LL-dd HH:mm:00'),
                    eventEnd: dt.plus({ minutes: self.length }).toFormat('yyyy-LL-dd HH:mm:00')
                } )
                .then( response => window.location.href = '/students/dashboard' )
                .catch(e => self.errors(e.response.data))
                .finally(() => self.loading = false);
        },
        getTeacherCalendarData: function() {
            let self = this,
                uri = '/students/ajax/teachers/'
                    + self.selectedTeacher.teacherID
                    + '/schedule?start='
                    + self.cursorStart.toUTC().toFormat('yyyy-LL-dd HH:mm:00')
                    + '&end=' + last_day.formatStd();
            window.axios
                .get( encodeURI( uri ) )
                .then(response => {
                    self.time_slots = response.data;
                    self.initDatePicker();
                });
        },
        parseDataToTimeSlots: function() {
            let self = this,
                cursor = self.cursorStart;
            
            self.slotsSource = {};

            do {
                self.addSlot( cursor, 1 * self.slotStatus(cursor.setZone(self.teacher_tz)) );

                cursor = cursor.plus({ minutes: start_step });
            }

            while ( self.calendar_end > cursor ) ;
        },
        addSlot: function( dt, status ) {
            const month = dt.toFormat('L'),
                day = dt.toFormat('d');

            if( -1 === status ) return;

            this.slotsSource[ month ] = this.slotsSource[ month ] || {};
            this.slotsSource[ month ][ day ] = this.slotsSource[ month ][ day ] || [];
            this.slotsSource[ month ][ day ].push({t: dt, status: status});
        },
        slotStatus: function( t ) {
            if(
                !this.checkTimeOff( t )
                || !this.checkUnavailability( t )
            ) return -1;

            return this.checkTrials( t )
                && this.checkLessons( t.toUTC() );
        },
        checkUnavailability: function( t ) {
            if(!this.calendarRawData.hasOwnProperty('unavailability')) return false;

            let t_day = t.toFormat('cccc'),
                t_start = t.toFormat('HH:mm:00'),
                t_end = t.plus({ minutes: start_step }).toFormat('HH:mm:00');

            if( t_end === '00:00:00' ) t_end = '24:00:00'; // Fix midnight/new day issue.

            return !this.calendarRawData.unavailability.some(function(u){
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
        },
        checkTimeOff: function( t ) {
            if(!this.calendarRawData.hasOwnProperty('timeoff')) return false;

            let f = t.toFormat('yyyy-LL-dd HH:mm:00');
            return !this.calendarRawData.timeoff.some(function(u){
                // t equal or greater than start and less than end.
                if( f < ( u.endDate + ' 24:00:00' ) && f >= ( u.startDate + ' 00:00:00' ) )
                    return 1;

                return 0;
            });
        },
        checkTrials: function( t ) {
            if(!this.calendarRawData.hasOwnProperty( 'trials' )) return false;

            let t_start = t.toFormat('yyyy-LL-dd HH:mm:00'),
                t_end = t.plus({ minutes: start_step }).toFormat('yyyy-LL-dd HH:mm:00');

            return !this.calendarRawData.trials.some(function(u){
                if( t_start < u.end && t_end > u.start )
                    return 1;

                return 0;
            });
        },
        checkLessons: function( t ) {
            if(!this.calendarRawData.hasOwnProperty( 'lessons' )) return false;

            let t_start = t.toFormat('yyyy-LL-dd HH:mm:00'),
                t_end = t.plus({ minutes: start_step }).toFormat('yyyy-LL-dd HH:mm:00');

            return !this.calendarRawData.lessons.some(function(u){
                if( t_start < u.eventEnd && t_end > u.eventStart )
                    return 1;

                return 0;
            });
        },
        classForDate: function(d) {
            const self = this,
                month = d.getMonth() + 1,
                day = d.getDate();

            return (self.sh.empty(self.slotsSource)
                    || !self.slotsSource.hasOwnProperty(month.toString())
                    || !self.slotsSource[month.toString()].hasOwnProperty(day.toString())
                    || !self.slotsSource[month.toString()][day.toString()].length
                ) ? '' : 'ui-datepicker-unselectable ui-state-disabled';
        },
        isDisabledSlot: function (indx) {
            return !this.time_slots[indx].status;
        },
        initSetUp: function() {
            if( this.isReschedule )  {
                this.selectTeacher(this.teachers.find(t => t.teacherID === editClass.teacherID));
            }
            else if( this.teachers.length === 1 ) {
                this.selectTeacher(this.teachers[0]);
            }

            if( this.isReschedule ) {
                const course = editClass.entryTitle.match(/\(([^)]+)\)/)[1];
                this.selectCourse(this.courses.find(c => c.course === course && parseInt(editClass.numStudents) === parseInt(c.numStudents) ));

                this.setLength(
                    DT.fromSQL(editClass.eventEnd).diff( DT.fromSQL(editClass.eventStart) ).as('minutes')
                );
            }
            else if( this.teacherCourses.length === 1 && this.remainingCredits(this.teacherCourses[0]) >= .5 ) {
                this.selectCourse(this.teacherCourses[0]);
            }

            const instructions = document.querySelectorAll(".step.activated");

            if(instructions.length)
                this.stepInstruction = Array.from(instructions).pop().dataset.instruction;

        }
    },
    mounted() {
        const self = this;

        self.initSetUp();

        $(document).on('click', '.ui-datepicker-unselectable', e => self.toaster.caution( self.sh.__('date_unavailable') ));
    }
});