require('../bootstrap');
require('../modules/tabsNavigation');
require('../modules/datatables/conditionalPaging');

import slideOutMenu from "../modules/slideOutMenu";
import shorten from "../modules/shorten";

import EnterClassRoomBtn from "../components/EnterClassRoomBtn";
import VdtnetTable from 'vue-datatables-net';
import ChangeClassModal from "../components/ChangeClassModal";
import toaster from "../modules/toaster";
import errorsMixin from "../mixins/errorsMixin";
import teacherClipMixin from "../mixins/teacherClipMixin";

const app = new Vue({
    el: '#root'
    , components: {
        "enter-btn": EnterClassRoomBtn, "vdtnet-table": VdtnetTable, "change-class": ChangeClassModal
    },
    mixins: [ errorsMixin, teacherClipMixin ],
    data() {
        return {
            table_id: 'previous_classes_table',
            toaster: new toaster,
            shortener: null,
            timezone: document.getElementById('timezone').value,
            cntPreviousClasses: 0
        }
    },
    computed: {
        opts: function() {
            const self = this;
            return {
                ajax: async function (data, callback, settings) {
                    callback( await self.getPreviousClasses() );
                },
                columnDefs: [
                    { "orderable": false, "targets": "_all" }
                ],
                pageLength: 10,
                conditionalPaging: true,
                drawCallback: () => self.shortener.run(),
                dom: "t<'pagination mv-lg'p>"
            };
        },
        fields: function () {
            return {
                teacher: {
                    label: this.sh.__('teacher'),
                    render: ( data, type, row, meta ) => ( 'display' === type )
                        ? this.teacherClip(
                            row.teacherID,
                            row.title,
                            data,
                            row.photo,
                            row.course.substr(0, row.course.indexOf('-'))
                        )
                        : data
                },
                date: {label: this.sh.__('date_time'), render: ( data, type, row, meta ) => ( 'display' === type ) ? this.formatDate(new Date(data)) : data},
                course: {label: this.sh.__('course'), render:( data, type, row, meta ) => ( 'display' === type ) ? data.substr(data.indexOf('-') + 1) : data},
                creditsUsed: {label: this.sh.__('length'), render: ( data, type, row, meta ) => ( 'display' === type ) ? this.sh.__('n_minutes').replace(':n', data * 60) : data},
                topics: {label: this.sh.__('topics'), className: "shorten"}
            };
        }
    },
    methods: {
        getPreviousClasses: async function() {
            const self = this,
                src = await axios.get( ajax_url );

            self.cntPreviousClasses = src.data.length;

            return src;
        },
        formatDate: function (dt) {
            const opts = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Intl.DateTimeFormat( 'en-US', opts ).format( dt );
        },
        adjustWidth: function (e) {
            const self = this;
            if( $(e.target).is('[href="#previous-classes"]') ) {
                $('#' + self.table_id).css({width:"100%"});
                setTimeout(()=>$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust(), 10);
                $('.tab-navigation a').off( 'click', self.adjustWidth );
            }
        },
        deleteRow: function (id) {
            const row = document.getElementById('lesson_' + id),
                badge = document.querySelector('[href="#upcoming-classes"] > .badge');

            row.parentNode.removeChild(row);
            badge.innerText = parseInt( badge.innerText ) - 1;
        }
    },
    created() {
        this.shortener = new shorten({strlength: 60, more: this.sh.__('more'), less: this.sh.__('less')});
    },
    mounted() {
        const self = this;
        new slideOutMenu();
        $('.tab-navigation a').on( 'click', self.adjustWidth );
    }
});