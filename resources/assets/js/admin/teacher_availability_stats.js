require('../bootstrap');

import VdtnetTable from 'vue-datatables-net';

new Vue({
    el: '#app',
    components:{ "vdtnet-table": VdtnetTable },
    data: {
        opts: {
            ajax: {
                url: '/ajax/teacher/availability-stats',
                type: 'GET',
                dataSrc: function ( json ) {
                    return json.data;
                }
            },
            dom: "Btr<'vdtnet-footer'<'span5'i><l><'span5 offset3'p>>",
        },
        fields: {
            id: { label: "ID" },
            name: { label: Shared.__('name'), searchable: true, sortable: true },
            email: { label: Shared.__('classes_hours') },
            availability: { label: Shared.__('availability') }
        }
    },
    methods: {

    },
    mounted() {

    }
});
