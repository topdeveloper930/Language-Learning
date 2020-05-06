require('../bootstrap');

require('../modules/KeepTokenAlive');

import slideOutMenu from "../modules/slideOutMenu";
import simpleModal from "../components/simpleModal";
import PricingBreakdown from "../components/PricingBreakdown";
import errorsMixin from "../mixins/errorsMixin";
import toaster from "../modules/toaster";

const app = new Vue({
    el: '#root',
    components: { simpleModal, PricingBreakdown },
    mixins: [errorsMixin],
    data() {
        return {
            toaster: new toaster,
            language: '',
            course: '',
            courses: [],
            numStudents: 1,
            hours: 10,
            coupon: coupon_code,
            giftCard: '',
            errorsBag: {},
            paymentMethod: defaultPaymentMethod || 'stripe',
            paymentData: {},
            timeout: false,
            loading: 0
        };
    },
    computed: {
        selectedCourse: {
            get: function() {
                return this.course ? this.course.courseType : '';
            },
            set: function(newVal) {
                this.course = this.courses.find(c => c.courseType === newVal);
                this.initUpdate();
            }
        },
        defaultHoursPurchased: () => document.querySelector('[data-hours]').dataset.hours,
        hoursPurchased: {
            get: function() {
                return this.hours;
            },
            set: function(newVal) {
                if (this.course && parseFloat(this.course.courseHours) > 0)
                    this.hours = this.course.courseHours;
                else if (newVal)
                    this.hours = newVal;
            }
        },
        submitText() {
            return ('paypal' === this.paymentMethod) ?
                this.sh.__('pay_paypal') :
                this.sh.__('purchase_classes');
        },
        purchaseItem() {
            return this.sh.__('itemDescription')
                .replace(':type', this.selectedCourse)
                .replace(':kind', (1 === parseInt(this.numStudents)) ? this.sh.__('private') : this.sh.__('group'));
        },
        total() {
            return this.paymentData.total || 0;
        },
        isGiftCard() {
            return !this.total && this.giftCard;
        },
        goodToQueryBalance() {
            return this.selectedCourse && this.hoursPurchased;
        }
    },
    methods: {
        reset() {
            this.paymentData = this.errorsBag = {};
        },
        pushError(er) {
            Object.assign(this.errorsBag, er);
        },
        isGoodToSubmit() {
            if (!this.sh.empty(this.errorsBag)) return false;

            const errors = {};
            if (!this.hoursPurchased || ('transfer' === this.paymentMethod && minTransferHours > this.hoursPurchased))
                errors.hoursPurchased = this.sh.__('hours_purchased');

            if (!this.selectedCourse)
                errors.program = this.sh.__('selected_course');

            this.errorsBag = errors;

            return this.sh.empty(this.errorsBag);
        },
        scrollToFirstError() {
            setTimeout(
                v => {
                    const target = document.querySelector('.field-invalid-label');
                    if (target) target.parentElement.scrollIntoView()
                },
                100
            );
        },
        async onLanguageChange() {
            this.reset();

            if (!this.language) return;

            const self = this,
                src = await axios.get('/ajax/' + self.language + '/courses');

            self.courses = src.data;
            self.selectedCourse = self.courses[0].courseType;
            self.initUpdate();
        },
        initUpdate: function(f) {
            if (f) delete this.errorsBag[f];

            clearTimeout(this.timeout);
            const pause = (this.coupon || this.giftCard) ? 2000 : 500;

            this.timeout = setTimeout(this.updateBalance, pause);
        },
        updateBalance: function() {
            const self = this;
            self.paymentData = {};

            if (!self.goodToQueryBalance) return;

            self.loading = 1;

            window.axios.post(ajax_url + '/balance', {
                    courseType: self.selectedCourse,
                    hours: self.hoursPurchased,
                    numStudents: self.numStudents,
                    coupon_code: self.coupon,
                    giftcard_code: self.giftCard
                })
                .then(
                    r => {
                        self.paymentData = r.data;
                        self.errorsBag = {};
                    },
                    er => {
                        self.errorsBag = er.response.data;
                        self.errors(self.errorsBag);

                        self.scrollToFirstError();
                    }
                )
                .finally(r => self.loading = 0);
        },
        setMethod: function(method) {
            this.paymentMethod = method;
        },
        sendPayment: function() {
            const self = this;

            self.isGoodToSubmit();

            if (!self.isGoodToSubmit()) {
                self.scrollToFirstError();
                return;
            }

            self.loading = 1;

            window.axios.post(ajax_url + '/send', {
                    paymentMethod: (self.isGiftCard) ? 'giftcard' : self.paymentMethod
                })
                .then(
                    r => {
                        if ('stripe' === self.paymentMethod) {
                            const stripe = Stripe(stripe_key);
                            stripe.redirectToCheckout({
                                    sessionId: r.data.sessionId
                                }).then(
                                    result => {
                                        self.errors(result.error.message);
                                        console.error('Stripe redirectToCheckout failed', result.error);
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
                        self.scrollToFirstError();
                    }
                )
                .finally(r => self.loading = 0);
        }
    },
    mounted() {
        const self = this;

        if (!self.sh.empty(old)) {
            self.language = old.language;
            self.hoursPurchased = old.hours;
            self.numStudents = old.numStudents;

            self.onLanguageChange()
                .then(v => self.selectedCourse = old.program);
        }
    }
});