<template>
    <div class="row">
        <div class="span3">
            <p>{{ dateStart }}</p>
        </div>

        <div class="span2">
            {{ timeStart }} - {{ timeEnd }}
        </div>

        <div class="span4">
            <b>{{ courseTitle }}</b> {{ lang.with }} <br>
            <button type="button" class="btn btn-link" @click="openInNewTab(teacher_id)">{{ teacher }}</button>
        </div>

        <div class="span1">
            <button type="button" class="btn btn-danger" @click="$root.selected_class = $root.classes[indx]" v-if="!isLate">{{ lang.cancel_btn }}</button>
        </div>
        <br class="clearfix">
        <hr class="span10 mt0">
    </div>
</template>

<script>
    export default {
        name: "Lesson"
        , props: [ 'start', 'end', 'teacher_id', 'teacher', 'course', 'numstudents', 'indx' ]
        , computed: {
            eventStart: function() {
                return DT.fromISO( this.start.replace(' ', 'T') + 'Z', { zone: this.$root.timezone } );
            }
            , eventEnd: function() {
                return DT.fromISO( this.end.replace(' ', 'T') + 'Z', { zone: this.$root.timezone } );
            }
            , courseTitle: function() {
                const type = Shared.lang.type.split('|');

                return type[ ( parseInt( this.numstudents ) > 1 ) * 1 ]
                    .replace( ':cc', this.course.substring( this.course.indexOf('(') + 1, this.course.indexOf(')') ) );
            }
            , dateStart: function () {
                return this.eventStart.toLocaleString( Object.assign( DT.DATE_HUGE, { locale: Shared.app_lang } ) );
            }
            , timeStart: function () {
                return this.eventStart.toLocaleString( Object.assign( DT.TIME_SIMPLE, { locale: Shared.app_lang } ) );
            }
            , timeEnd: function () {
                return this.eventEnd.toLocaleString( Object.assign( DT.TIME_SIMPLE, { locale: Shared.app_lang } ) );
            }
            , isLate: function () {
                return this.eventStart < DT.fromObject({ zone: this.$root.timezone }).plus({ "hours": this.$root.advance_hrs });
            }
        }
        , methods: {
            openInNewTab: function(teacherID) {
                const self = this;
                window.axios
                    .get( '/students/ajax/teachers/' + self.teacher_id + '/language' )
                    .then(response => {
                        const alias = this.teacher.toLowerCase()
                            .replace(/\s/g, '-')
                            .normalize('NFD').replace(/[\u0300-\u036f]/g, ""),
                            win = window.open( '/' + response.data.toLowerCase() + '/tutors/' + alias + '/', '_blank' );
                        win.focus();
                    } )
                    .catch(e => console.error(e.response.data));
            }
        }
    }
</script>

<style scoped>
    .mt0 {
        margin-top: 0;
    }
</style>