require('../bootstrap');
require('../modules/tabsNavigation');
require('../modules/KeepTokenAlive');
const validate = require("validate.js");
import toaster from "../modules/toaster";

import errorsMixin from "../mixins/errorsMixin";


const app = new Vue({
    el: '#root',
    mixins: [errorsMixin],
    data() {
        return {
            validate: validate,
            toaster: new toaster({}),
            country: 'USA',
            state: 'New York',
            city: '',
            paymentType: '',
            paymentMethod: 'stripe',
            loading: 0
        }
    },
    computed: {
        submitText() {
            return ('paypal' === this.paymentMethod) ?
                this.sh.__('pay_paypal') :
                this.sh.__('purchase_classes');
        },
    },
    methods: {

        // These functions are not needed but leave that for the emergency case
        ///////////////////////////////////////////////////////
        countriesTypeahead: function() {
            const self = this,
                countries = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: '/pub/location/country?plucked=1'
                });

            // passing in `null` for the `options` arguments will result in the default options being used
            $('#country').typeahead(null, {
                    name: 'countries',
                    source: countries
                })
                .on('typeahead:selected', function(evt, item) {
                    self.country = item;
                    self.state = '';
                    $('#state').typeahead('destroy');
                    self.regionsTypeahead();
                });
        },

        regionsTypeahead: function() {
            const self = this,
                regions = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: '/pub/location/region?plucked=1&country=' + self.country
                });

            // passing in `null` for the `options` arguments will result in the default options being used
            $('#state').typeahead(null, {
                    name: 'regions',
                    source: regions
                })
                .on('typeahead:selected', function(evt, item) {
                    self.state = item;
                });
        },

        ///////////////////////////////////////////////////////



        setMethod: function(method) {
            this.paymentMethod = method;
        },

        isGoodToSubmit() {
            if (!this.sh.empty(this.errorsBag)) return false;

            const errors = {};


            this.errorsBag = errors;

            return this.sh.empty(this.errorsBag);
        },

        sendPayment: function() {
            const self = this;

            self.isGoodToSubmit();

            if (!self.isGoodToSubmit()) {
                return;
            }

            self.loading = 1;

            window.axios.post(ajax_url, {
                    paymentMethod: self.paymentMethod
                })
                .then(
                    r => {
                        debugger;
                        if ('stripe' === self.paymentMethod) {
                            const stripe = Stripe(stripe_key);
                            stripe.redirectToCheckout({
                                    sessionId: r.data.sessionId
                                }).then(
                                    result => {
                                        self.errors(result.error.message);

                                    }
                                )
                                .catch(e => self.errors(e.error.message));
                        } else if (r.data.redirectUrl) {
                            window.location.href = r.data.redirectUrl;
                        } else {
                            //Can we somehow get down here?
                            self.errors(self.sh.__('something_wrong'));
                            console.error(r.data);
                        }
                    },
                    er => {
                        self.errorsBag = er.response.data;
                        self.errors(self.errorsBag);

                    }
                )
                .finally(r => self.loading = 0);
        }

    },
    mounted() {
        this.countriesTypeahead();

        if (this.country)
            this.regionsTypeahead();
    }
});