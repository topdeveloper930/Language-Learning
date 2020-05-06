require('../bootstrap');

require ('../modules/KeepTokenAlive');
require ('../modules/accordion');

import slideOutMenu from "../modules/slideOutMenu";
import errorsMixin from "../mixins/errorsMixin";
import toaster from "../modules/toaster";
import confirmModal from "../components/confirmModal";

const app = initVue ? new Vue({
    el: '#root',
    components: { confirmModal },
    mixins: [errorsMixin],
    data() {
        return {
            toaster: new toaster,
            loading: false,
            modalVisible: false
        };
    },
    methods: {
        returnToPayment(gateway, idOrUrl) {
            const self = this;

            if( 'stripe' === gateway ) {
                const stripe = Stripe( stripe_key );
                stripe.redirectToCheckout({
                    sessionId: idOrUrl
                }).then(
                    result => {
                        self.errors( result.error.message );
                        console.log('Stripe redirectToCheckout failed', result.error );
                    }
                )
                    .catch(e => self.errors(e.error.message));
            }
            else if ( 'paypal' === gateway ){
                window.location.href = idOrUrl;
            }
        },
        cancelTransaction( transactionId ) {
            const self = this;

            self.modalVisible = false;
            self.loading = true;

            window.axios.delete( ajax_url )
                .then(
                    r => window.location.reload(),
                    er => {
                        self.loading = false;
                        self.errors(er.response.data);
                    }
                );
        }
    }
}) : null;