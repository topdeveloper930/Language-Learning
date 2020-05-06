require("../bootstrap");
require("../modules/KeepTokenAlive");

import slideOutMenu from "../modules/slideOutMenu";
import shorten from "../modules/shorten";
import VdtnetTable from "vue-datatables-net";
import toaster from "../modules/toaster";
import errorsMixin from "../mixins/errorsMixin";

const app = new Vue({
    el: "#root",
    data() {
        return {
            selectedDate: {year:currentDate.year, month: currentDate.month},
            table_ref: "salary_table",
            toaster: new toaster,
            summaryTblHours: {
                base: 0,
                group2: 0,
                group3: 0,
                specialized1: 0,
                specialized2: 0,
                specialized3: 0
            },
            summaryTblSum: {
                base: 0,
                group2: 0,
                group3: 0,
                specialized1: 0,
                specialized2: 0,
                specialized3: 0
            },
            studentsTaught: 0,
            admin_change: {},
            spin: false
        };
    },
    components: { VdtnetTable },
    mixins: [ errorsMixin ],
    computed: {
        showAdminChange() {
            return !this.sh.empty(this.admin_change) && this.isCurrentMonthSelected;
        },
        hoursTaught() {
            const self = this;
            let retVal = 0;
            Object.keys(self.summaryTblHours).forEach(
                index => (retVal += self.summaryTblHours[index])
            );

            return retVal;
        },
        income() {
            const self = this;
            let retVal = 0;
            Object.keys(self.summaryTblSum).forEach(
                index => retVal += self.summaryTblSum[index]
            );

            if( self.showAdminChange )
                retVal += parseFloat(self.admin_change.totalPay);

            return retVal.toFixed(2);
        },
        isCurrentMonthSelected() {
            return (
                this.sYear === currentDate.year &&
                this.sMonth === currentDate.month
            );
        },
        sMonth: {
            get() {
                return this.selectedDate.month;
            },
            set(newVal) {
                this.selectedDate.month = newVal;

                this.refresh();
            }
        },
        sYear: {
            get() {
                return this.selectedDate.year;
            },
            set(newVal) {
                this.selectedDate.year = parseInt(newVal);

                this.refresh();
            }
        },
        opts() {
            const self = this;
            return {
                ajax: {
                    url:
                        ajax_url +
                        "/" +
                        self.selectedDate.year +
                        "-" +
                        self.selectedDate.month,
                    type: "GET",
                    dataSrc: null,
                    headers: {
                        CSRFToken: window.axios.defaults.headers.common["X-CSRF-TOKEN"]
                    }
                },
                columnDefs: [{ orderable: false, targets: "_all" }],
                pageLength: -1,
                dom: "t",
                footerCallback(row, data, start, end, display) {
                    Object.keys(self.summaryTblHours).forEach(
                        index => {
                            self.summaryTblHours[index] = 0;
                            self.summaryTblSum[index] = 0;
                        }
                    );

                    self.studentsTaught = end;

                    data.forEach(
                        r => {
                            const col = self.whatColumn(r.numStudents, r.payGrade);
                            self.summaryTblHours[col] += parseFloat(r.hours);

                            if( !self.isCurrentMonthSelected )
                                self.summaryTblSum[col] += parseFloat( r.totalPay );
                        }
                    );

                    if( self.isCurrentMonthSelected ) {
                        Object.keys(self.summaryTblHours)
                            .forEach(
                                col => self.summaryTblSum[col] = self.summaryTblHours[col] * teacherSalary[col]
                            );
                    }
                }
            };
        },
        fields() {
            const self = this;

            return {
                student: {
                    label: self.sh.__("students"),
                    render: (data, type, row, meta) => data ? data : ( row.hasOwnProperty('totalPay') ? row.course + ' ($' + row.totalPay + ')' : '-')
                },
                base: {
                    label: "SD",
                    render: (data, type, row, meta) =>
                        "base" === self.whatColumn(row.numStudents, row.payGrade)
                            ? row.hours
                            : 0
                },
                group2: {
                    label: "G2",
                    render: (data, type, row, meta) =>
                        "group2" === self.whatColumn(row.numStudents, row.payGrade)
                            ? row.hours
                            : 0
                },
                group3: {
                    label: "G3",
                    render: (data, type, row, meta) =>
                        "group3" === self.whatColumn(row.numStudents, row.payGrade)
                            ? row.hours
                            : 0
                },
                specialized1: {
                    label: "S1",
                    render: (data, type, row, meta) =>
                        "specialized1" === self.whatColumn(row.numStudents, row.payGrade)
                            ? row.hours
                            : 0
                },
                specialized2: {
                    label: "S2",
                    render: (data, type, row, meta) =>
                        "specialized2" === self.whatColumn(row.numStudents, row.payGrade)
                            ? row.hours
                            : 0
                },
                specialized3: {
                    label: "S3",
                    render: (data, type, row, meta) =>
                        "specialized3" === self.whatColumn(row.numStudents, row.payGrade)
                            ? row.hours
                            : 0
                }
            };
        }
    },
    methods: {
        whatColumn(ns, pg) {
            if (3 === parseInt(ns)) return "group3";
            else if (2 === parseInt(ns)) return "group2";
            else return pg ? pg : "base";
        },
        setYearMonth(ymObj) {
            this.selectedDate = ymObj;
            this.refresh();
        },
        refresh() {
            if(
                ( this.sYear === currentDate.year && this.sh.__('months').indexOf(this.sMonth) > this.sh.__('months').indexOf(currentDate.month) )
                || ( this.sYear === teacherStart.year && this.sh.__('months').indexOf(this.sMonth) < teacherStart.month )
            ) return;

            this.$refs[this.table_ref].dataTable.ajax.url(
                ajax_url + "/" + this.sYear + "-" + this.sMonth
            );

            this.$refs[this.table_ref].dataTable.ajax.reload();
        },
        submitComment() {
            const self = this;
            self.spin = true;
            window.axios.post(ajax_url, {comments: document.getElementById('salaryComments').value})
                .then(
                    r => self.toaster.success( r.data ),
                    er => self.errors( er.response.data )
                )
                .finally(() => self.spin = false);
        }
    },
    created() {
        const self = this;

        new shorten({
            more: self.sh.__("more"),
            less: self.sh.__("less")
        });

        window.axios.get( ajax_url )
            .then( r => self.admin_change = r.data );
    }
});