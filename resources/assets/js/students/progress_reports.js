require('../bootstrap');
require('../modules/datatables/conditionalPaging');

import slideOutMenu from "../modules/slideOutMenu";
import shorten from "../modules/shorten";
import infoModal from "../components/infoModal";
import VdtnetTable from 'vue-datatables-net';
import teacherClipMixin from "../mixins/teacherClipMixin";

const app = new Vue({
    el: '#root',
    components: {infoModal, VdtnetTable},
    data() {
        return {
            reportsCnt: null,
            table_id: 'progress_reports_table'
        };
    },
    mixins: [ teacherClipMixin ],
    computed: {
        opts: function() {
            const self = this;
            return {
                ajax: async function (data, callback, settings) {
                    callback( await window.axios.get( ajax_url ) );
                },
                pageLength: 10,
                conditionalPaging: true,
                drawCallback: s => self.reportsCnt = this.table.page.info().recordsTotal,
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
                            row.language
                        )
                        : data
                },
                course: {label: this.sh.__('program')},
                evaluationDate: {label: this.sh.__('date'), render: ( data, type, row, meta ) => ( 'display' === type ) ? this.formatDate(new Date(data)) : data},
                speakingLevelTitle: {
                    label: this.sh.__('level') + ' <a href="#" class="badge info-modal">?</a>',
                    render:( data, type, row, meta ) => this.levelName(row)
                },
                speakingLevel: {label: this.sh.__('score'), render:( data, type, row, meta ) => this.score(row)},
                evaluationID: {label: ' ', render:( data, type, row, meta ) => ( 'display' === type ) ? '<a href="/students/progress-report/' + data + '" class="button">' + this.sh.__('view') + '</a>' : data}
            };
        }
    },
    methods: {
        formatDate: function (dt) {
            const opts = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Intl.DateTimeFormat( 'en-US', opts ).format( dt );
        },
        totalScore( row ) {
            return parseInt(row.speakingLevel) + parseInt(row.listeningLevel) + parseInt(row.writingLevel) + parseInt(row.readingLevel);
        },
        score( row ) {
            return this.totalScore( row ) / 4;
        },
        levelName( row ) {
            const total = this.totalScore( row );
            return this.sh.__( Object.keys(levels).reduce( (retval, score) => {
                if( total > levels[score] && levels[score] >= levels[retval] )
                    return score;

                return retval;
            }, 'beginner' ));

        },
        showLevelsInfo() {
            this.sh.$emit('info-modal', {
                header: 'll_level',
                info: 'll_levels'
            });
        }
    },
    created(){
        new shorten({
            more: this.sh.__('more'),
            less: this.sh.__('less'),
        });
    },
    mounted() {
        document.addEventListener(
            'click',
                e => {
                    if(e.target.classList.contains('info-modal')) {
                        e.preventDefault();
                        this.showLevelsInfo();
                    }
                }
            );
    }
});