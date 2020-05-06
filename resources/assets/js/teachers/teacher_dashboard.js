require("../bootstrap");
require("../modules/KeepTokenAlive");
require("../modules/tabsNavigation");
require('../modules/datatables/conditionalPaging');

import slideOutMenu from "../modules/slideOutMenu";
import toaster from "../modules/toaster";
import errorsMixin from "../mixins/errorsMixin";
import VdtnetTable from 'vue-datatables-net';
import EnterClassRoomBtn from "../components/EnterClassRoomBtn";
import confirmModal from "../components/confirmModal";
import infoModal from "../components/infoModal";

const app = new Vue({
    el: "#root",
    data() {
        return {
            upcoming_id: 'upcoming_classes_table',
            trials_id: 'trial_classes_table',
            toaster: new toaster,
            cntUpcomingClasses: 0,
            cntTrialClasses: 0,
            rowData: {},
            iWaited: false,
            iLeftMessage: false,
            iWaitedErr: 0,
            iLeftMessageErr: 0,
            confirmNoShow: false,
            showTrialReport: false,
            loading: false,
            infoBadge: '<span data-action="info" class="button secondary"><i class="fad fa-info"></i></span>',
            studentInfo: '',
            students: [],
            showClassLog: false,
            studentsLoaded: 0,
            selectedStudent: ''
        }
    },
    components: {
        VdtnetTable, "enter-btn": EnterClassRoomBtn, confirmModal, infoModal
    },
    mixins: [ errorsMixin ],
    computed: {
        upcoming_opts() {
            const self = this;
            return {
                ajax: async function (data, callback, settings) {
                    callback( await window.axios.get( ajax_url + '/upcoming' ) );
                },
                columnDefs: [
                    { "orderable": false, "targets": "_all" }
                ],
                order: [[0, 'asc']],
                pageLength: 10,
                conditionalPaging: true,
                dom: "t<'pagination mv-lg'p>",
                drawCallback: function ( settings ) {
                    const api = this.api(),
                        rows = api.rows( {page:'current'} ).nodes();

                    self.cntUpcomingClasses = api.rows().count();

                    let last=null;

                    api.column(0, {page:'current'} ).data().each( ( group, i ) => {
                        const d = group.substr(0, group.indexOf(' '));
                        if ( last !== d ) {
                            const dtObj = new Date( group ),
                                ttl = ( self.isToday( dtObj ) ) ? self.sh.__('today_').replace(':d', self.formatDate( dtObj )) : self.formatDate( dtObj );

                            $(rows).eq( i ).before(
                                '<tr class="group table-label"><td colspan="6">' + ttl + '</td></tr>'
                            );

                            last = d;
                        }
                    } );
                }
            };
        },
        upcoming_fields() {
            const self = this;

            return {
                start: {label: self.sh.__('time'), render: ( data, type, row, meta ) => ( 'display' === type ) ? self.formatTime(new Date( data )) : data},
                studentID: {
                    label: self.sh.__('student'),
                    render: ( data, type, row, meta ) => ( 'display' !== type ) ? data : (data ? '<a href="/teachers/student-profile/' + data + '">' + self.nameFromTitle( row.entryTitle ) + '</a> ' + self.infoBadge : self.nameFromTitle( row.entryTitle ) + ' ' + self.infoBadge)
                },
                entryTitle: {label: self.sh.__('course'), render:( data, type, row, meta ) => ( 'display' === type ) ? self.courseFromTitle( data ) : data},
                end: {label: self.sh.__('length'), render: ( data, type, row, meta ) => ( 'display' === type ) ? self.sh.__('n_minutes').replace(':n', (Date.parse(data) - Date.parse(row.start)) / 60 / 1000) : data},
                id: {label: self.sh.__('type'), render: ( data, type, row, meta ) => ( 'display' === type ) ? self.sh.__('types')[ 1 * (!!row.studentID) ] : data},
                actions: {
                    label: '',
                    render: self.actionButtons
                }

            };
        },
        trial_opts() {
            const self = this;
            return {
                ajax: async function (data, callback, settings) {
                    callback( await window.axios.get( ajax_url + '/trials' ) );
                },
                columnDefs: [
                    { "orderable": false, "targets": "_all" }
                ],
                order: [[0, 'desc']],
                pageLength: 10,
                conditionalPaging: true,
                dom: "t<'pagination mv-lg'p>",
                drawCallback: function(settings) {
                    self.cntTrialClasses = this.api().rows().count();
                }
            };
        },
        trial_fields() {
            const self = this;

            return {
                start: {label: self.sh.__('time'), render: ( data, type, row, meta ) => ( 'display' === type ) ? self.formatDateTime(new Date( data )) : data},
                student: {label: self.sh.__('student')},
                course: {label: self.sh.__('course')},
                actions: {label: '', render: ( data, type, row, meta ) => ( 'display' === type )
                        ? '<a href="/teachers/trial-class-report/' + row.id + '" class="button primary">' + self.sh.__('completed') + '</a> <span data-action="log-noshow" class="button secondary">' + self.sh.__('no_show') + '</span>'
                        : ''
                }

            };
        },
        logHeader() {
            return this.sh.empty( this.rowData )
                ? ''
                : this.sh.__('trial_header')
                    .replace(':student', this.rowData.data.student)
                    .replace(':date', this.rowData.data.start.slice(0, -3));
        },
        classLogUrl() {
            const q = encodeURI('?course=' + this.students[this.selectedStudent].course + '&n=' + this.students[this.selectedStudent].n);
            return classLogUrl.replace(':id', this.students[this.selectedStudent].id) + q;
        }
    },
    methods: {
        formatTime(dt) {
            const opts = { hour: "numeric", minute:"2-digit", hour12: true, timeZoneName: "short" };
            return new Intl.DateTimeFormat( 'en-US', opts ).format( dt );
        },
        formatDate(dt) {
            const opts = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Intl.DateTimeFormat( 'en-US', opts ).format( dt );
        },
        formatDateTime(dt) {
            const opts = { month: 'short', day: 'numeric', hour: "numeric", minute:"2-digit", hour12: true };
            return new Intl.DateTimeFormat( 'en-US', opts ).format( dt );
        },
        isToday(someDate) {
            const today = new Date();
            return  someDate.getDate() === today.getDate() &&
                    someDate.getMonth() === today.getMonth() &&
                    someDate.getFullYear() === today.getFullYear();
        },
        nameFromTitle( ttl ) {
            const f = ttl.indexOf(' (');
            return (f > -1) ? ttl.substring(0, ttl.indexOf(' (')).trim() : ttl;
        },
        courseFromTitle( ttl ) {
            return ttl.substr(ttl.indexOf('(') + 1).slice(0, -1);
        },
        actionButtons( data, type, row, meta ) {
            const room = (
                (typeof classRoomReady !== 'undefined') && classRoomReady
                && ltNow < Date.parse( row.end ) && ltNow > (Date.parse( row.start ) - 60 * 60 * 1000)
            )   ? '<a href="/teachers/classroom/' + '" class="button primary">' + this.sh.__('start_class') + '</a>'
                : '';

            return  (room + ' <span data-action="trigger-modal" data-method="deny" class="button secondary">' + this.sh.__('reschedule') + '</span>').trim();
        },
        triggerModal(data, row, tr, target) { console.log(data);
            this.rowData = data;
            this.row = row;
            this.action = target.data( 'method' );
            this.modalVisible = 1;

            if( 'view' === this.action )
                this.view();
        },
        adjustWidth (e) {
            const self = this;
            setTimeout(() => {
                    $('#' + self.trials_id).css({width: '100%'});
                    self.$refs[self.trials_id].dataTable.columns.adjust();

                    $('#' + self.upcoming_id).css({width: '100%'});
                    self.$refs[self.upcoming_id].dataTable.columns.adjust();
                },
                50
            );
        },
        logCompleted(data, row, tr, target) {
            this.rowData = {data: data, row: row, tr: tr, target: target};
            this.showTrialReport = true;
        },
        showInfo(data, row, tr, target) {
            const self = this;

            self.studentInfo = '<i class="fa fa-spinner fa-spin"></i>';

            new Promise(((resolve, reject) => {
                self.sh.$emit( 'info-modal', {header: self.nameFromTitle(data.entryTitle)});

                const uri = (data.studentID)
                    ? '/student?id=' + data.studentID
                    : '/trial?id=' + data.id;

                resolve(uri);
            }))
                .then(
                    uri => window.axios.get(ajax_url + uri)
                )
                .then(
                    r => self.studentInfo = r.data.html,
                    er => {
                        self.$refs.infoModal.close();
                        self.errors(er.response.data);
                    }
                );
        },
        logNoshow(data, row, tr, target) {
            this.rowData = {data: data, row: row, tr: tr, target: target};
            this.confirmNoShow = true;
        },
        resetNoShow() {
            // this.closeLogClass();
            this.rowData = {};
            this.loading = this.confirmNoShow = this.iWaited = this.iLeftMessage = this.iWaitedErr = this.iLeftMessageErr = false;
        },
        setNoShow() {
            const self = this;

            if(!self.iWaited)
                self.iWaitedErr = 1;

            if(!self.iLeftMessage)
                self.iLeftMessageErr = 1;

            if(self.iWaitedErr || self.iLeftMessageErr)
                return;

            self.loading = true;

            window.axios.post(ajax_url + '/trial_noshow', {id: self.rowData.data.id})
                .then( res => {
                    if(!self.sh.empty(res.data)) {
                        self.$refs[self.trials_id]
                            .dataTable
                            .row( self.rowData.row )
                            .remove()
                            .draw();

                        self.toaster.success(self.sh.__('report_saved'));
                    }
                    else {
                        self.toaster.caution(self.sh.__('no_response'));
                    }
                } )
                .catch(er => self.errors(er.response.data))
                .finally(self.resetNoShow)
        },
        printStudentLine(s) {
            return this.sh.__('student_opt')
                .replace(':s', s.student)
                .replace(':c', s.course)
                .replace(':n', this.sh.__('class_type')[parseInt(s.n)])
                .replace(':h', s.credits);
        },
        logClassWizard() {
            const self = this;

            self.showClassLog = true;

            if( self.studentsLoaded ) return;

            window.axios.get(ajax_url + '/students')
                .then(
                    r => {
                        self.students = r.data;
                        self.studentsLoaded = true;
                    },

                );
        },
        goClassLog() {
            if(this.selectedStudent)
                window.location.href = this.classLogUrl;
        }
    },
    mounted() {
        const self = this;
        $('.tab-navigation a').on( 'click', self.adjustWidth );

        window.onresize = self.adjustWidth;
    }
});