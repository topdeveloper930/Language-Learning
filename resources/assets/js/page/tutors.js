require('../bootstrap');

require('../modules/Array.shuffle');

import slideOutMenu from "../modules/slideOutMenu";
import Card from "../components/Card";
import errorsMixin from "../mixins/errorsMixin";
import uriParamsParser from "../modules/uriParamsParser";
import Pagination from "../components/Pagination";
import toaster from "../modules/toaster";

const tutorsApp = new Vue({
    el: '#root',
    components: { Card, Pagination },
    data: {
        teachers: [],
        filters: filters,
        toaster: new toaster,
        limit: 9,
        profileImageStub: "/img/profiles/no-profile-image.jpg",
        lazy: ('loading' in document.createElement('img')),
        parser: new uriParamsParser,
        searchStr: '',
        page: 1,
        coursesTaught: []
    },
    mixins: [ errorsMixin ],
    computed: {
        teachersFiltered() {
            const self = this;

            return this.teachers.filter(
                (t, indx) => (!self.filters.location.length || self.filters.location.indexOf(t.countryName) > -1)
                && (!self.filters.lang.length || -1 < t.languagesSpoken.indexOf(self.filters.lang))
                && (!self.filters.course.length || -1 < t.coursesTaught.indexOf(self.filters.course))
                && (self.searchStr.length < 3 || -1 < JSON.stringify( t ).indexOf(self.searchStr) )
            );
        },
        locations() {
            return this.sh.empty(this.teachers)
                ? []
                : this.removeDuplicates(
                    this.teachers.reduce( (res, t) => { res.push(t.countryName); return res; }, [] ),
                    true
                );
        },
        languagesSpoken() {
            return this.sh.empty(this.teachers)
                ? []
                : this.removeDuplicates(
                    this.teachers.reduce( (res, t) => { res = res.concat(t.languagesSpoken); return res; }, [] ),
                    true
                ).filter((item, pos) => item.toLowerCase() !== language.toLowerCase() );
        }
    },
    watch: {
        teachersFiltered(newVal) {
            if( this.page > Math.ceil(newVal.length / this.limit) )
                this.$refs.pagination.active = this.page = 1;
        }
    },
    methods: {
        loadTeachers() {
            const self = this;

            window.axios.get( ajax_url )
                .then(
                    r => {
                        r.data.forEach( t => {
                            t.agesTaught = self.parser.parse(t.agesTaught, 'ages');
                            t.coursesTaught = self.parser.parse(t.coursesTaught, 'courses');
                            t.languagesSpoken = self.parseLanguageString(t.languagesSpoken);
                            self.teachers.push(t);
                        });

                        self.teachers = self.teachers.shuffle()
                            .sort((x,y) => x.profileImage === self.profileImageStub.slice(1) ? 1 : y.profileImage === self.profileImageStub.slice(1) ? -1 : 0 );
                    },
                    e => self.errors(e.response.data)
                );
        },
        loadCourses() {
            const self = this;

            window.axios.get( ajax_url + '/courses' )
                .then(
                    r => self.coursesTaught = r.data,
                    e => self.errors(e.response.data)
                );
        },
        removeDuplicates: function (arr, removeEmpty) {
            const self = this;
            removeEmpty = typeof removeEmpty !== 'undefined' ? removeEmpty : false;

            return arr.filter(function(item, pos) {
                return (!removeEmpty || !self.sh.empty(item)) && arr.indexOf(item) === pos;
            });
        },
        initFilter() {
            if( this.sh.empty(this.filters)) {
                this.filters = {lang: '', location: [], course: ''};
                return;
            }

            if( !this.filters.hasOwnProperty('lang') )
                this.filters['lang'] = '';

            if( !this.filters.hasOwnProperty('location') )
                this.filters['location'] = [];
            else if( this.sh.isString(this.filters.location))
                this.filters['location'] = [this.filters.location];

            if( !this.filters.hasOwnProperty('course') )
                this.filters['course'] = '';
        },
        teacherUrl(t) {
            const n = (t.firstName + '-' + t.lastName).trim().replace(/\s/g, '-').toLowerCase();
            return tutorUrl.replace(':n', n).replace(':id', t.teacherID);
        },
        parseLanguageString( str ) {
            return str.replace(/(?:\sand\s|\)\s)/g, ',')                    // Replace " and " and ") " with "," - i.e. adds missing comma or replaces "and" with comma.
                .replace(/(?:\.|\s?\([^)]*\) *|\s{1}[A-C]{1}\d{1})/g, "")   // Removes . (dots), parenthesis with text inside, and A1 and alike level designations.
                .replace(/\s/g, '')                 // Removes spaces
                .split(',');
        }
    },
    created() {
    },
    mounted() {
        const self = this;
        Promise.all([
            self.initFilter(),
            self.loadTeachers(),
            self.loadCourses()
        ]);
    }
});