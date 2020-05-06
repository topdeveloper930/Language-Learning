require('../bootstrap');
require("../modules/KeepTokenAlive"); // In case if this is authenticated teacher

import slideOutMenu from "../modules/slideOutMenu";
import RangeItem from "../components/RangeItem";
import toaster from "../modules/toaster";
import errorsMixin from "../mixins/errorsMixin";



const app = new Vue({
    el: '#root',
    components: { RangeItem },
    mixins: [errorsMixin],
    data: {
        isPrevButtonDisabled: false,
        limit: 6,
        page: 1,
        labelText: "this is label",
        questions: [],
        defaultAnswer: 5,
        isNextButtonDisabled: true,
        toaster: new toaster,
    },
    computed: {
        currentPageQuestions: function() {
            return this.questions.filter((question, index) => {
                return (index < this.limit * this.page && index >= (this.page - 1) * this.limit);
            })
        }
    },
    methods: {
        prevQuestions() {
            this.page = this.page > 1 ? this.page - 1 : this.page;
            this.scroll();
        },
        nextQuestions() {
            this.page = this.page < Math.ceil(this.questions.length / this.limit) ? this.page + 1 : this.page;
            this.scroll();
        },
        viewQuizResult() {
            const self = this;

            window.axios.post(ajax_url, self.questions)
                .then(
                    r => window.location.href = r.data.result_url,
                    err => self.errors(err.response.data)
                );
        },
        formatQuestion(q, id) {
            id += '';

            const t = (this.sh.lang.hasOwnProperty(id))
                ? this.sh.__(id).replace(':lang', quizLanguage)
                : quizLanguage;

            return q.replace('%s', t).replace('  ', ' ');
        },
        loadQuestions() {
            const self = this;
            window.axios.get(ajax_url)
                .then(
                    r => {
                        self.questions = r.data;
                        self.questions.forEach(q => q['answer'] = self.defaultAnswer);
                    },
                    e => self.errors(e.response.data)
                );
        },
        initRangeSlider() {
            const self = this;

            $('form input[type="range"]').rangeslider({
                polyfill: false,
                onInit() {},
                onSlide(position, value) {
                    const question = self.questions.find(q => q.id === parseInt($('.rangeslider--active').parents('[data-key]').data('key')));
                    question.answer = value;
                },
                onSlideEnd(position, value) {}
            });
        },
        scroll() {
            setTimeout(
                () => document.querySelector('.quiz-form').scrollIntoView(),
                100
            );
        }
    },
    mounted() {
        const self = this;

        Promise.all([
            self.loadQuestions(),
        ]);
    },
    updated() {
        this.initRangeSlider();
    }
});