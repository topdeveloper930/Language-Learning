require('../bootstrap');

import VdtnetTable from 'vue-datatables-net';
import { GChart } from 'vue-google-charts'

const app = new Vue({
    el: '#app',
    components:{ "vdtnet-table": VdtnetTable, "gchart": GChart },
    data: {
        table_id: 'stats_dt',
        hideFooter: true,
        years: {},
        year: new Date().getFullYear(),
        language: 'Spanish',
        country: 'USA',
        age: 'All ages',
        age_list: [
            'All ages',
            'Unknown',
            'Child (4-10 years old)',
            'Adolescent (11-17 years old)',
            'University (18-22 years old)',
            'Adult (23-59 years old)',
            'Senior (60+ years old)'
        ],
        gender: 0,
        gender_list: [
            'All', 'Mr.', 'Ms.', 'Mrs.', 'Dr.', 'Prof.', 'Ms. & Mrs.', 'Dr. & Prof.'
        ],
        row_labels: [
            Shared.__( 'signups' ), Shared.__( 'trials' ), Shared.__( 'percent' )
        ],
        months: [ '', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
        chartData: [],
        chartOptions: {
            title: Shared.__('chart_title'),
            legend: { position: "none" },
            hAxis: {
                title: Shared.__('h_axis_title')
            }
        }
    },
    computed: {
        table: function(){
            return $( '#' + this.table_id ).DataTable();
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
                // drawCallback: self.setChartData,
                columnDefs: [
                    { "orderable": false, "targets": "_all" }
                ],
                serverSide: true,
                    pageLength: -1,
                    dom: "t"
                };
        },
        fields: function () {
            const self = this,
                ret_val = {
                    "0": {
                        label: self.sh.__( 'value' ),
                        render: ( data, type, row, meta ) => {
                            if ( 'display' === type )
                                return self.row_labels[ meta.row ];

                            return '';
                        }
                    }
                };

            $.each(self.months, (t,m) => {
                if( t )
                    ret_val[ t ] = { label: m }
            });

            ret_val[ 'year' ] = { label: self.sh.__('year') };

            return ret_val;
        }
    },
    methods: {
        retrieveData: async function() {
            const self = this,
                y = {year: 0},
                age = ( 'All ages' === self.age ) ? '' : self.age,
                dataset = await axios.get(
                    ajax_url + '?dataset=stats&year=' + self.year
                    + '&country=' + self.country + '&language=' + self.language
                    + '&age=' + age + '&gender=' + self.gender
                );

            self.months.forEach((value, t) => { if( t ) y[t] = 0 });

            let src = [y, Object.assign({}, y), Object.assign({year: "N/A"}, y)],
                year_signups = 0,
                year_trials = 0;

            dataset.data.forEach((value, t) => {
                if( 'signups' === value.field ) {
                    src[ 0 ][ value.month ] = value.count;
                    src[ 0 ].year += parseInt( value.count );
                }
                else {
                    src[ 1 ][ value.month ] = value.count;
                    src[ 1 ].year += parseInt( value.count );
                }
            });

            if( src[1].year )
                src[2].year = (src[0].year * 100 / src[1].year).toFixed(2);

            self.months.forEach( (m, i) => {
                if( i && src[1][i] )
                    src[2][i] = (src[0][i] * 100 / src[1][i]).toFixed(2);
            });

            return { data: src };
        },
        reload: function () {
            this.table.ajax.reload();
        },
        countriesTypeahead: function () {
            const self = this,
                countries = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    // url points to a json file that contains an array of country names, see
                    // https://github.com/twitter/typeahead.js/blob/gh-pages/data/countries.json
                    prefetch: ajax_url + '?dataset=countries'
                });

            // passing in `null` for the `options` arguments will result in the default options being used
            $('#country').typeahead( null, {
                name: 'countries',
                source: countries
            })
                .on('typeahead:selected', function(evt, item) {
                    self.country = item;
                    self.reload();
            });
        },
        initSpinner: function () {
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
        },
        setChartData: function () {
            const self = this,
                tbl = $( '#' + self.table_id ).DataTable(),
                rowObj = self.table.data().toArray()[2],
                rows = [[self.sh.__('h_axis_title'), self.sh.__('percent'), { role: 'annotation' } ]];

            for( let m in rowObj ) {
                if( rowObj.hasOwnProperty(m) ) {
                    !isNaN( m )
                        ? rows.push([ m, parseFloat(rowObj[m]), self.months[ parseInt(m) ]])
                        : rows.push([ m, parseFloat(rowObj[m]), self.sh.__('year')]);
                }
            }

            self.chartData = google.visualization.arrayToDataTable( rows );
        },
    },
    mounted() {
        const self = this;

        this.initSpinner();
        this.countriesTypeahead();
        $( '#' + self.table_id ).on('draw.dt', self.setChartData);
    }
});
