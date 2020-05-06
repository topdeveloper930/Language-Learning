<template>
    <div>
        <div v-if="nocredits">
            <h5>{{ lang.no_creds }}</h5>

            <p>{{ lang.no_creds_text }}</p>

            <a href="/students/purchase-lessons.php" class="btn btn-danger">{{ lang.buy }}</a>
        </div>
        <div v-else>
            <h5 v-html="header"></h5>
            <label for="course-selected">{{ lang.lesson_type }}</label>
            <course-select event_name="course-selected"
                           :items="courses"
                           v-if="courses.length > 1"
            ></course-select>
            <input v-else class="span3" type="text" :value="class_type" disabled="disabled">

            <label for="class-duration">{{ lang.class_length }}</label>
            <class-length event_name="class-duration"
                          ref="durs"
                          :items="durations_filtered"
            ></class-length>
            <p class="text-error remark" v-show="limited">*{{ lang.limit }}</p>

            <p><slot></slot></p>

            <button class="btn btn-default" type="button" @click="$root.active_action = 'select'">{{ lang.back }}</button>
            <button class="btn btn-success" type="button" @click="horn">{{ lang.confirm }}</button>

        </div>
    </div>

</template>

<script>
    import CourseSelect from './CourseSelect';
    import ClassLength from './ClassLength';
    export default {
        props: [ 'courses', 'header_tplt', 'date', 'time' ]
        , data: function() {
            return {
                durations: [ 30, 60, 90, 120 ]
                , limited: false
            }
        }
        , components: {
            "course-select": CourseSelect,
            "class-length": ClassLength
        }
        , computed: {
            header: function () {
                return this.header_tplt.replace(':date', this.date).replace(':time', this.time);
            }
            , class_type: function(){
                const c = Shared.lang.class_type.split('|'),
                    i = ( this.courses[0].numStudents !== 1 ) * 1;
                return this.courses[0].course + ' (' + c[i] + ')';
            }
            , nocredits: function() {
                return this.courses.reduce((sum, course)=> sum + ( course.hours - course.creditsUsed - parseFloat(course.scheduled)), 0) < 0.5;
            }
            , durations_filtered: function() {
                /**
                 * Filter out those class length options that are not relevant due to lack of the remaining credit
                 * or taken next time slot (teacher has another event scheduled.
                 *
                 * @type {int[]}
                 */
                const keys = Object.keys( this.$root.selected_with_next_day_slots );

                let self = this,
                    retval = [],
                    check_next_slot = function( start, cnt ) {
                        // Check that all "cnt" time slots from "start" are not 0 (taken).
                        let sum = 0, j = start;
                        do{
                            sum += self.$root.selected_with_next_day_slots[keys[j++]];
                        } while (j < start + cnt);

                        return sum === cnt;
                    };

                self.durations.some(function(dur){
                    if( self.$root.selected_course_credit >= dur && check_next_slot( keys.indexOf( self.$root.selected_time ), dur / self.$root.step ) ) {
                        retval.push( dur );
                        return 0;
                    }

                    return 1;
                });

                return retval;
            }
        }
        , methods: {
            horn: function () {
                Shared.$emit('class-confirm');
            }
            , checkLimited: function() {
                let self = this;
                setTimeout(function() {
                    if( typeof self.$refs.durs !== 'undefined' ) self.limited = self.$refs.durs.$children.length < self.durations.length;
                }, 500);
            }
        }
        , created() {
            if( this.courses.length === 1 )
                this.$root.selected_course = this.courses[0];
        }
        , mounted() {
            this.checkLimited();

            Shared.$on('selected_course', this.checkLimited );
        }
    }
</script>
