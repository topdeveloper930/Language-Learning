import slideOutMenu from "../modules/slideOutMenu";

require('../bootstrap');
require('../modules/datatables/conditionalPaging');

import CopyToClipboard from "../modules/copyToClipboard";
import toaster from "../modules/toaster";
import ReferForm from "../components/ReferForm";
import errorsMixin from "../mixins/errorsMixin";
import colorsMixin from "../mixins/colorsMixin";
import ReferColorKey from "../components/ReferColorKey";
import VdtnetTable from 'vue-datatables-net';

const app = new Vue({
    el: '#root',
    components: { ReferForm, ReferColorKey, VdtnetTable },
    mixins: [ errorsMixin, colorsMixin ],
    data() {
        return {
            toaster: new toaster({}),
            activeTab: 'home',
            referralsTableId: 'referrals_table',
            creditsTableId: 'credits_table',
            invitationsTableId: 'invitations_table',
            credits: 0
        };
    },
    computed: {
        remainingCredits: function() {
            return this.sh.__('remaining_credits').replace(':credits', this.credits.toFixed(2));
        },
        referralColorKey: function () {
            return [
                {bg: this.colors.green, title: 'user_made_purchase'},
                {bg: this.colors.yellow, title: 'user_sign_up'}
            ]
        },
        creditsColorKey: function () {
            return [
                {bg: this.colors.green, title: 'credit_record'},
                {bg: this.colors.red, title: 'debit_record'}
            ]
        },
        invitationsColorKey: function() {
            return this.referralColorKey;
        },
        referralsOpts: function() {
            const self = this;
            return {
                ajax: {
                    url: ajax_url + '/referrals'
                    , dataSrc: json => json
                },
                pageLength: 10,
                conditionalPaging: true,
                createdRow: function (row, data, index) {
                    // if the user made purchase, add success class
                    if( parseFloat( data.bonus ) > 0 ) $(row).addClass('text-success');
                    else $(row).addClass('text-notify');
                },
                dom: '<"float-right"f>tip'
            };
        },
        referralsFields: function () {
            return {
                referral: { label: this.sh.__('name') },
                bonus: {label: this.sh.__('bonus')}
            };
        },
        creditsOpts: function() {
            const self = this;
            return {
                ajax: {
                    url: ajax_url + '/credits',
                    dataSrc: json => {
                        self.credits = json.reduce( (a, b) => a + parseFloat(b.amount), 0 );
                        return json;
                    }
                },
                pageLength: 10,
                order: [[ 0, "desc" ]],
                conditionalPaging: true,
                createdRow: function (row, data, index) {
                    // if the user made purchase, add success class
                    if( parseFloat( data.amount ) > 0 ) $(row).addClass('text-success');
                    else $(row).addClass('text-caution');
                },
                dom: '<"float-right"f>tip',
                footerCallback: function ( row, data, start, end, display ) {
                    if(!$(this).find('tfoot').length)
                        $(this).append("<tfoot><tr><td>" + self.sh.__('total') + "</td><td colspan='3'></td></tr></tfoot>");

                    const sum1 = display.reduce( (a, b) => a + parseFloat(data[b].amount), 0 );

                    // Update footer
                    $(this).find('tfoot td[colspan]').html( ' ' + sum1.toFixed(2) + ' USD' );
                }
            };
        },
        creditsFields: function () {
            return {
                created_on: { label: this.sh.__('date') },
                amount: {label: this.sh.__('amount')},
                course: {label: this.sh.__('for'), render: ( data, type, row, meta ) => data || row.referral_name },
                note: {label: this.sh.__('remark')}
            };
        },
        invitationsOpts: function() {
            const self = this;
            return {
                ajax: {
                    url: ajax_url + '/invitations',
                    dataSrc: json => json
                },
                pageLength: 10,
                conditionalPaging: true,
                order: [[ 2, "desc" ]],
                createdRow: function (row, data, index) {
                    if( parseInt( data.has_purchase ) ) $(row).addClass('text-success');
                    else if( parseInt( data.signed_up ) ) $(row).addClass('text-notify');
                },
                dom: '<"float-right"f>tip'
            };
        },
        invitationsFields: function () {
            return {
                name: { label: this.sh.__('name') },
                email: {label: this.sh.__('email')},
                date: {label: this.sh.__('date')},
                has_purchase: {visible: false},
                signed_up: {visible: false}
            };
        }
    },
    methods: {
        sentSuccess: function ( invite ) {
            this.toaster.success(this.sh.__('sent_success').replace(':name', invite.name));
        },
        setActiveTab: function (tab) {
            document.querySelector('h1.dashboard-title').innerText = this.sh.__('h1')[tab];

            // To force reload VdtnetTable
            const self = this;
            self.activeTab = '';
            setTimeout(() => self.activeTab = tab, 10);
        },
        filterReferralsTable: function ( title ) {
            const regX = ('user_sign_up' === title) ? '^$' : '.+';
            $('#' + this.referralsTableId ).DataTable()
                .column( 1 )
                .search( regX, true, false )
                .draw();
        },
        filterCreditsTable: function ( title ) {
            const regX = ('debit_record' === title) ? '^-{1}\\d+([.]\\d+)?$' : '^[+]?\\d+([.]\\d+)?$';

            $('#' + this.creditsTableId ).DataTable()
                .column( 1 )
                .search( regX, true, false )
                .draw();
        },
        filterInvitationsTable: function ( title ) {
            const col = ('user_sign_up' === title) ? 4 : 3,
                table = $('#' + this.invitationsTableId ).DataTable();

            table.search( '' ).columns([3,4]).search( '' );
            table.column( col )
                .search( 1, false )
                .draw();
        }
    },
    mounted() {
        const self = this;
        new CopyToClipboard({
            afterCopyCB: () => self.toaster.success(self.sh.__('copied'))
        });

        $(document).on( 'draw.dt', function ( e, settings ) {
            $('#' + self.referralsTableId + ', #' + self.creditsTableId + ', #' + self.invitationsTableId).css({width:"100%"});
            setTimeout(() => $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust(), 10);
        } );
    }
});