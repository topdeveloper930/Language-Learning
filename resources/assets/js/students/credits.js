require('../bootstrap');
require('../modules/datatables/conditionalPaging');

import slideOutMenu from "../modules/slideOutMenu";
import VdtnetTable from 'vue-datatables-net';

const app = new Vue({
    el: '#root',
    components: {VdtnetTable},
    data() {
        return {
            table_id: 'credits_table'
        };
    },
    computed: {
        opts: function() {
            const self = this;
            return {
                ajax: async function (data, callback, settings) {
                    callback( await window.axios.get( ajax_url ) );
                },
                pageLength: 10,
                conditionalPaging: true,
                order: [[ 0, "desc" ]],
                dom: "t<'pagination mv-lg'p>"
            };
        },
        fields: function () {
            return {
                transactionDate: { label: this.sh.__('date_time') },
                paymentType: {label: this.sh.__('payment_method'), render: ( data, type, row, meta ) => ( 'display' === type ) ? this.sh.__(data.toLowerCase()) : data},
                paymentAmount: {label: this.sh.__('amount'), render: ( data, type, row, meta ) => ( 'display' === type ) ? parseFloat(data).toFixed(2) : data},
                paymentFor: { label: this.sh.__('course') },
                hours: {label: this.sh.__('hours') },
                gatewayTransID: {label: this.sh.__('transaction_n') }
            };
        }
    }
});