require("../bootstrap");
require("../modules/KeepTokenAlive");

import slideOutMenu from "../modules/slideOutMenu";
import toaster from "../modules/toaster";
import errorsMixin from "../mixins/errorsMixin";

const app = new Vue({
    el: "#root",
    data() {
        return {
            toaster: new toaster,
            courses: null,
            indx: null,
            classDate: '',
            classLength: 1,
            whatWasStudied: '',
            internalNotes: '',
            lessons: {
                // "0.25": Shared.__('n_minutes').replace(':n', 15),
                "0.5": Shared.__('n_minutes').replace(':n', 30),
                "0.75": Shared.__('n_minutes').replace(':n', 45),
                "1": Shared.__('n_minutes').replace(':n', 60),
                "1.5": Shared.__('n_minutes').replace(':n', 90),
                "2": Shared.__('n_minutes').replace(':n', 120),
            }
        }
    },
    mixins: [ errorsMixin ],
    computed: {
        creditedCourses() {
            return (this.courses && this.courses.length)
                ? this.courses.filter( c => c.hours - c.creditsUsed > .5 )
                : [];
        },
        lessonsLengths() {
            return (this.courses && this.indx > -1)
                ? Object.keys(this.lessons).filter(j => parseFloat(j) <= (this.creditedCourses[this.indx].hours - this.creditedCourses[this.indx].creditsUsed)).sort()
                : [];
        }
    },
    methods: {
        optText(i) {
            const c = this.creditedCourses[i],
                type = 1 === c.numStudents
                    ? this.sh.__('num_students')[0]
                    : this.sh.__('num_students')[1].replace(':n', c.numStudents);

            return this.sh.__('opt_text')
                        .replace(':c', c.course)
                        .replace(':t', type)
                        .replace(':h', c.hours - c.creditsUsed);
        },
        getCourses() {
            const self = this;

            window.axios.get(ajax_url)
                .then(
                    r => {
                        self.courses = r.data;

                        if(self.courses.length === 1)
                            self.indx = 0;
                        else if(self.courses.length)
                            self.indx = self.courses.findIndex(c => c.course == selectedCourse && c.numStudents == numStudents);

                        if( self.indx > -1 && self.courses[self.indx].hours - self.courses[self.indx].creditsUsed < 1)
                            self.classLength = self.courses[self.indx].hours - self.courses[self.indx].creditsUsed;
                    },
                    er => self.errors(er.response.data)
                )
        },
        logClass() {
            const self = this;

            window.axios.post(ajax_url, {
                numStudents: self.creditedCourses[self.indx].numStudents,
                course: self.creditedCourses[self.indx].course,
                creditsUsed: self.classLength,
                whatWasStudied: self.whatWasStudied,
                internalNotes: self.internalNotes,
                classDate: self.classDate
            })
                .then(
                    r => window.location.href = r.data,
                    er => self.errors(er.response.data)
                )
        }
    },
    mounted() {
        this.getCourses();
    }
});