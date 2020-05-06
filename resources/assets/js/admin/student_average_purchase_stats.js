require('../bootstrap');

import { DateTime } from 'luxon';
window.DT = DateTime;

import VdtnetTable from 'vue-datatables-net';

import LLLocalStorage from "../modules/LLLocalStorage";

const app = new Vue({
    el: '#app',
    components:{ "vdtnet-table": VdtnetTable },
    data: {
        table_id: 'stats_dt',
        hideFooter: false,
        years: {},
        year: new Date().getFullYear(),
        mode: 'months',
        per: 'transaction',
        months: [ '', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ]
    },
    computed: {
        table: function(){
            return $( '#' + this.table_id ).DataTable();
        },
        periodLbl: function() {
            return ('months' === this.mode) ? this.sh.__('month') : this.sh.__('week');
        },
        language_list: function () {
            return JSON.parse( this.$el.dataset.languages );
        },
        opts: function() {
            const self = this;
            return {
                ajax: async function (data, callback, settings) {
                    callback( await self.retrieveData() );
                },
                serverSide: true,
                    pageLength: -1,
                    dom: "t"
                };
        },
        fields: function () {
            const self = this,
                ret_val = {};

            ret_val[ 'period' ] = {
                label: self.sh.__( self.mode.slice(0, -1) ),
                // sortable: false,
                render: ( data, type, row, meta ) => {
                    if ( 'display' === type && !isNaN( row.period ) )
                        return ( 'months' === self.mode ) ? self.months[ meta.row + 1 ] : self.weekLabel( meta.row );

                    return row.period;
                }
            };

            $.each(self.language_list, (t,lang) => {
                ret_val[ lang ] = { label: lang }
            });

            ret_val[ 'all' ] = { label: self.sh.__('all') };

            return ret_val;
        }
    },
    methods: {
        retrieveData: async function() {
            // 1) Check local storage for required data
            // 2) Send ajax request to obtain missing data and store it to local storage
            // 3) Process the data (depending on configs) to format resulting object
            const self = this,
                key = self.year + '_' + self.mode + '_' + self.per;

            let src = LLLocalStorage.getItem( key );

            if( !src ) {
                if( 'active_student' === self.per )
                    src = await self.handleActiveStudentRequest();
                else if( 'transaction' === self.per )
                    src = await self.handleTransactionRequest();
                else if( 'new_student' === self.per )
                    src = await self.handleNewStudentRequest();
                else
                    src = [];

                if( src.length ) {
                    try {
                        LLLocalStorage.setItem( key, src, new Date().getTime() + 24 * 3600 * 1000 );
                    }
                    catch (e) {
                        if ( e == QUOTA_EXCEEDED_ERR )
                            alert('Local storage overwhelmed.');

                        console.log(e.name + ': ' + e.message);
                    }
                }
            }

            return { data: src };
        },
        handleTransactionRequest: async function() {
            const self = this,
                dataSet = await axios.get(
                    ajax_url + '?dataset=transactions&year=' + self.year + '&mode=' + self.mode.slice(0, -1)
                ),
                ret_val = [],
                max = ( 'months' === self.mode ) ? 12 : self.getIsoWeeksInYear(),
                sums = {period: self.sh.__('total'), all: 0},
                cnts = {period: self.sh.__('count'), all: 0},
                avgs = {period: self.sh.__('average'), all: 0};

            dataSet.data.reduce((t, value) => {
                sums.all += parseFloat( value.sum );
                cnts.all += parseFloat( value.transactions_cnt );
                return 0;
            }, 0);

            sums.all = sums.all.toFixed( 2 );

            if( cnts.all ) avgs.all = (sums.all / cnts.all).toFixed(2);

            $.each( self.language_list, (j, l) =>  sums[l] = cnts[l] = avgs[l] = 0 );

            for(let i = 0; i < max; i++) {
                let all = 0,
                    c = 0,
                    row = {period: i};

                $.each( self.language_list, (j, l) => {
                    let stats = dataSet.data.filter( lot => lot.language === l && parseInt( lot.date ) === ('months' === self.mode ? i + 1 : i) )[0],
                        v = (stats && stats.sum) ? parseFloat(stats.sum) : 0,
                        tc = (stats && stats.transactions_cnt) ? parseInt(stats.transactions_cnt) : 0;

                    sums[l] += v;
                    cnts[l] += tc;

                    all += v;
                    c += tc;

                    row[l] = ( tc ) ? (v / tc).toFixed(2) : 0;
                } );

                row['all'] = ( c ) ? (all / c).toFixed(2) : 0;

                ret_val.push( row );
            }

            $.each( self.language_list, (j, l) =>  {
                if(l) avgs[l] = cnts[l] ? (sums[l] / cnts[l]).toFixed(2) : 0;

                sums[l] = sums[l].toFixed(2) * 1;
            });

            ret_val.push( sums, cnts, avgs );

            return ret_val;
        },
        handleNewStudentRequest: async function() {
            const self = this,
                m = self.mode.slice(0, -1),
                dataSet = await Promise.all([
                    axios.get(
                        ajax_url + '?dataset=transactions&year=' + self.year + '&mode=' + m
                    ),
                    axios.get(
                        ajax_url + '?dataset=signups&year=' + self.year + '&mode=' + m
                    )
                ]),
                ret_val = [],
                max = ( 'months' === self.mode ) ? 12 : self.getIsoWeeksInYear(),
                sums = {period: self.sh.__('total'), all: 0},
                cnts = {period: self.sh.__('count'), all: 0},
                avgs = {period: self.sh.__('average'), all: 0};

            dataSet[0].data.reduce((t, value) =>  sums.all += parseFloat( value.sum ), 0);
            dataSet[1].data.reduce((t, value) =>  cnts.all += parseFloat( value.cnt ), 0);

            sums.all = sums.all.toFixed( 2 );

            if( cnts.all ) avgs.all = (sums.all / cnts.all).toFixed(2);

            $.each( self.language_list, (j, l) =>  sums[l] = cnts[l] = avgs[l] = 0 );

            for(let i = 0; i < max; i++) {
                let all = 0,
                    c = 0,
                    row = {period: i};

                $.each( self.language_list, (j, l) => {
                    let stats = dataSet[0].data.filter( lot => lot.language === l && parseInt( lot.date ) === ('months' === self.mode ? i + 1 : i) )[0],
                        newStds = dataSet[1].data.filter( lot => lot.language === l && parseInt( lot.date ) === ('months' === self.mode ? i + 1 : i) )[0],
                        v = (stats && stats.sum) ? parseFloat(stats.sum) : 0,
                        tc = (newStds && newStds.cnt) ? parseInt(newStds.cnt) : 0;

                    sums[l] += v;
                    cnts[l] += tc;

                    all += v;
                    c += tc;

                    row[l] = ( tc ) ? (v / tc).toFixed(2) : 0;
                } );

                row['all'] = ( c ) ? (all / c).toFixed(2) : 0;

                ret_val.push( row );
            }

            $.each( self.language_list, (j, l) =>  {
                if(l) avgs[l] = cnts[l] ? (sums[l] / cnts[l]).toFixed(2) : 0;

                sums[l] = sums[l].toFixed(2) * 1;
            });

            ret_val.push( sums, cnts, avgs );

            return ret_val;

        },
        handleActiveStudentRequest: async function() { // Only monthly stats
            const self = this,
                dataSet = await Promise.all([
                    axios.get(
                        ajax_url + '?dataset=transactions&mode=month&year=' + self.year
                    ),
                    axios.get(
                        ajax_url + '?dataset=active&year=' + self.year
                    )
                ]),
                ret_val = [],
                sums = {period: self.sh.__('total'), all: 0},
                cnts = {period: self.sh.__('count'), all: 0},
                avgs = {period: self.sh.__('average'), all: 0};

            dataSet[0].data.reduce((t, value) =>  sums.all += parseFloat( value.sum ), 0);
            dataSet[1].data.reduce((t, value) =>  cnts.all += parseFloat( value.cnt ), 0);

            sums.all = sums.all.toFixed( 2 );

            if( cnts.all ) avgs.all = (sums.all / cnts.all).toFixed(2);

            $.each( self.language_list, (j, l) =>  sums[l] = cnts[l] = avgs[l] = 0 );

            for(let i = 0; i < 12; i++) {
                let all = 0,
                    c = 0,
                    row = {period: i};

                $.each( self.language_list, (j, l) => {
                    let stats = dataSet[0].data.filter( lot => lot.language === l && parseInt( lot.date ) === i + 1 )[0],
                        actStds = dataSet[1].data.filter( lot => lot.language === l && parseInt( lot.date ) === i + 1 )[0],
                        v = (stats && stats.sum) ? parseFloat(stats.sum) : 0,
                        tc = (actStds && actStds.cnt) ? parseInt(actStds.cnt) : 0;

                    sums[l] += v;
                    cnts[l] += tc;

                    all += v;
                    c += tc;

                    row[l] = ( tc ) ? (v / tc).toFixed(2) : 0;
                } );

                row['all'] = ( c ) ? (all / c).toFixed(2) : 0;

                ret_val.push( row );
            }

            $.each( self.language_list, (j, l) =>  {
                if(l) avgs[l] = cnts[l] ? (sums[l] / cnts[l]).toFixed(2) : 0;

                sums[l] = sums[l].toFixed(2) * 1;
            });

            ret_val.push( sums, cnts, avgs );

            return ret_val;
        },
        toggleMode: function (event) {
            if( this.mode === event.target.value ) return;

            this.mode = event.target.value;
            this.setPeriodLabel();
            this.reload();
        },
        togglePer: function (event) {
            if( this.per === event.target.value ) return;

            this.per = event.target.value;

            // Only monthly stats available on active students
            if( 'active_student' === this.per ) {
                this.mode = 'months';
                this.setPeriodLabel();
            }

            this.reload();
        },
        sum: obj => Object.values(obj).reduce((t, value) => parseFloat(t) + parseFloat(value), 0).toFixed(2),
        weekLabel: function( w ) {
            w++;
            return DateTime.fromISO( this.year + '-W' + ("0" + w).slice(-2) + '-1' ).toFormat('LLL dd - ') +
                DateTime.fromISO( this.year + '-W' + ("0" + w).slice(-2) + '-7' ).toFormat('LLL dd');
        },
        extractForLanguage: function ( data, type, row, meta ) {
            const self = this;
            return row.getParam( self.language_list[ meta.col - 1 ] ) || 0;
        },
        getIsoWeeksInYear: function() {
            const self = this,
                lux = DateTime.fromISO( this.year + '-W53-1' );

            return !!lux.invalid ? 52 : 53;
        },
        reload: function () {
            this.table.ajax.reload();
        },
        tfoot: function ( tds ) {
            $('#' + this.table_id + ' > tfoot').remove();
            $(
                '<tfoot></tfoot>',
                {html: "<tr>" + tds + "</tr>"}
            ).appendTo($('#' + this.table_id));
        },
        setPeriodLabel: function () {
            const self = this;
            $('#' + self.table_id).find('thead th:first-child').text(self.periodLbl);
        }
    },
    mounted() {
        let $div, $spinner;

        if( document.getElementById( "spinner" ) ) {
            $spinner = $('#spinner');
        }
        else {
            $div = $('<div></div>', {class: 'spinner-container'});
            $spinner = $('<div></div>', {class: 'lds-ripple hidden', id: 'spinner'});

            $spinner.appendTo($div);
            $('body').prepend($div);
        }

        $('#' + this.table_id)
            .on( 'processing.dt', ( e, settings, processing ) => processing ? $spinner.removeClass('hidden') : $spinner.addClass('hidden') );
    }
});
