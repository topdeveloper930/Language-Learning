<template>
    <div id="paypal-button-container"
         class="text-align-center"
    ></div>
</template>

<script>
    export default {
        name: "Paypal",
        props: {
            payment: Object,
            description: String,
            brand_name: String,
            notify_url: String
        },
        methods: {
            createOrder: async function(data, actions) {
                const self = this;

                return window.axios.post(ajax_url + '/send', { paymentMethod: 'paypal' })
                .then(
                    r => {
                        return actions.order.create({
                            intent: 'CAPTURE',
                            purchase_units: [{
                                description: self.description,
                                invoice: r.data.transactionID,
                                amount: {
                                    value: self.payment.total.toFixed(2),
                                    currency_code: 'USD'
                                },
                                reference_id: r.data.transactionID,
                                notify_url: self.notify_url
                            }],
                            application_context : {
                                brand_name: self.brand_name,
                                shipping_preference: "NO_SHIPPING"
                            }
                        });
                    },
                    err => self.$emit('fail', err.response.data)
                );
            },
            onApprove: function(data) {
                const self = this; self.$emit('paid', data);
                // window.axios.post('/students/purchase/paypal', data)
                //     .then(
                //         res => self.$emit('paid', res.data),
                //         err => {
                //             //TODO: We get here only if something wrong happens on our server. The payment is OK. No need to make the client to worry.
                //             console.error( err.response.data );
                //         }
                //     );
            }
        },
        mounted() {
            paypal.Buttons({
                createOrder: this.createOrder
                , onApprove: this.onApprove
            }).render('#paypal-button-container');
        }
    }
</script>

<style scoped>
    #paypal-button-container {
        width: 100%;
    }
</style>