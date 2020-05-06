require('../bootstrap');

import VdtnetTable from 'vue-datatables-net';
import Modal from "../components/Modal";
import errorsListMixin from "../mixins/errorsListMixin";

const app = new Vue({
    el: '#app',
    components:{ VdtnetTable, Modal },
    mixins:[ errorsListMixin ],
    data() {
        return {
            table_id: 'payments_dt',
            hideFooter: false,
            rowData: {},
            row: null,
            notification: {},
            action: '',
            modalVisible: false,
            loading: false,
            comment: ''
        };
    },
    computed: {
        opts() {
            const self = this;
            return {
                ajax: async function (data, callback, settings) {
                    callback( await window.axios.get( ajax_url ) );
                },
                pageLength: 25,
                dom: 'ftip',
                order: [[0, 'desc']]
            };
        },
        fields() {
            const self = this;

            return {
                id: {label: self.sh.__('id')},
                name: {label: self.sh.__('name')},
                gateway: {label: self.sh.__('gateway')},
                gateway_id: {label: self.sh.__('gateway_id')},
                course: {label: self.sh.__('for'), render: ( data, type, row, meta ) => {
                        if ( 'display' === type ) {
                            return self.sh.__('for_course').replace(':h', row.hours)
                                .replace(':c', data)
                                .replace(':t', 'x' + row.numStudents);
                        }

                        return data;
                    }},
                amount: {label: self.sh.__('amount')},
                date: {label: self.sh.__('date')},
                actions: {
                    label: self.sh.__('actions'),
                    defaultContent: '<span data-action="trigger-modal" data-method="view" class="btn btn-mini btn-info" title="' + self.sh.__('view') + '"><i class="icon-eye-open"></i></span>' +
                                    ' <span data-action="trigger-modal" data-method="approve" class="btn btn-mini btn-success"><i class="icon-ok"></i> ' + self.sh.__('approve') + '</span>' +
                                    ' <span data-action="trigger-modal" data-method="deny" class="btn btn-mini btn-danger"><i class="icon-remove"></i> ' + self.sh.__('deny') + '</span>'
                }
            };
        },
        label() {
            return ( 'deny' === this.action ) ? this.sh.__( 'reason' ) : this.sh.__( 'comment' );
        },
        modalBody: {
            get() {
                if( this.sh.empty( this.notification ) )
                    return '';

                const lvl = ( this.notification.lvl ) ? 'alert alert-' + this.notification.lvl : 'alert',
                    msg = ( typeof this.notification.msg === 'string' || this.notification.msg instanceof String )
                        ? this.notification.msg
                        : this.errorsList( this.notification.msg );

                return '<div class="' + lvl + '">' + msg + '</div>';

            },
            set( newVal ) {
                this.notification = newVal;
            }
        },
        modalHeader() {
            return this.sh.__( this.action + '_h' );
        },
        showForm(){
            return 'deny' === this.action || 'approve' === this.action;
        },
        okTxt() {
            if( this.loading )
                return '<i class="icon-spinner icon-spin"></i>';

            return ( 'view' === this.action ) ? '' : this.sh.__( this.action );
        },
        okCls() {
            return ( 'deny' === this.action ) ? 'btn btn-danger' : undefined;
        },
        cancelTxt() {
            return ( 'view' === this.action ) ? this.sh.__( 'close' ) : this.sh.__( 'return' );
        },
        modalDescription() {
            return (this.sh.empty( this.rowData ))
                ? ''
                : this.sh.__('purchase_of')[this.rowData.numStudents]
                    .replace(':h', this.rowData.hours)
                    .replace(':c', this.rowData.course);
        }
    },
    methods: {
        triggerModal(data, row, tr, target) {
            this.rowData = data;
            this.row = row;
            this.action = target.data( 'method' );
            this.modalVisible = 1;

            if( 'view' === this.action )
                this.view();
        },
        view() {
            window.axios.get( ajax_url + '/' + this.rowData.id )
                .then(
                    r => {
                        const msg = [
                            [this.sh.__('coupon')[(!!r.data.discount) * 1]
                                .replace(':d', '$' + r.data.discount.toFixed(2))
                                .replace(':c', r.data.coupon_code)
                                .replace(':v', r.data.coupon)],
                            [this.sh.__('referral_credits')[(!!r.data.referral_credits) * 1]
                                .replace(':r', '$' + r.data.referral_credits.toFixed(2))],
                            [this.sh.__('giftcard')[(!!r.data.giftcard) * 1]
                                .replace(':s', '$' + r.data.giftcard.toFixed(2))
                                .replace(':c', r.data.giftcard_code)]
                        ];

                        this.modalBody = {msg: msg, lvl: 'info'}
                    },
                    er => this.modalBody = {msg: er.response.data, lvl: 'danger'}
                );
        },
        approve() {
            const self = this;

            window.axios.patch( ajax_url, {
                transaction_id: self.rowData.id,
                comment: self.comment
            } )
                .then(
                    r => {
                        self.$refs.table.dataTable.row(self.row).remove().draw( false );
                        self.hideModal();
                    },
                    er => self.modalBody = {msg: er.response.data, lvl: 'danger'}
                )
                .finally(() => self.loading = false );
        },
        deny() {
            const self = this;
            window.axios.delete( ajax_url + '/' + self.rowData.id + '?comment=' + self.comment )
                .then(
                    r => {
                        self.$refs.table.dataTable.row(self.row).remove().draw( false );
                        self.hideModal();
                    },
                    er => self.modalBody = {msg: er.response.data, lvl: 'danger'}
                )
                .finally(() => self.loading = false );
        },
        hideModal() {
            this.modalVisible = false;
            this.rowData = {};
            this.comment = '';
            this.action = '';
            this.loading = false;
            this.modalBody = {};
        },
        process() {
            this.loading = true;
            this[this.action]();
        }
    }
});