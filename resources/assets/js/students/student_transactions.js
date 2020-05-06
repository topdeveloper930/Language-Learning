import slideOutMenu from "../modules/slideOutMenu";

require('../bootstrap');
require('../modules/datatables/conditionalPaging');
import VdtnetTable from 'vue-datatables-net';

const app = new Vue({
    el: '#root',
    components: {VdtnetTable},
    data() {
        return {
            'tbl_id': 'transactions_table'
        };
    },
    computed: {
        DTapi: function() {
            return $('#' + this.tbl_id).DataTable();
        },
        opts: function() {
            const self = this;
            return {
                ajax: {
                    url: ajax_url,
                    dataSrc: json => json
                },
                pageLength: 10,
                conditionalPaging: true,
                dom: 'tip',
                order: [[0, 'desc']]
            };
        },
        fields: function () {
            return {
                transactionDate: { label: this.sh.__('date') },
                paymentGateway: {label: this.sh.__('payment_method')},
                paymentAmount: {label: this.sh.__('amount')},
                paymentFor: {label: this.sh.__('course')},
                hours: {label: this.sh.__('hours')},
                gatewayTransID: {label: this.sh.__('transaction_id')}
            };
        }
    },
    methods: {
        search: function (e) {
            this.DTapi.search( e.target.value ).draw();
        }
    }
});