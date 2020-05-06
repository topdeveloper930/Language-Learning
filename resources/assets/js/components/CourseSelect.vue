<template>
    <select :id="event_name" class="span3" v-model="selected" @change="horn">
        <opt v-for="(item, indx) in items"
             :value="indx"
             :key="item.course + ':' + item.numStudents"
        >{{ class_type(item.course, item.numStudents) }}</opt>
    </select>
</template>

<script>
    export default {
        props: [ 'items', 'event_name' ],
        data: function() {
            return {
                selected: 0
            };
        }
        , methods: {
            class_type: function(course, numStudents){
                const c = Shared.lang.class_type.split('|'),
                    i = ( numStudents !== 1 ) * 1;
                return course + ' (' + c[i] + ')';
            }
            , horn: function() {
                this.$root.selected_course = this.items[ this.selected ];
                Shared.$emit('selected_course');
            }
        }
        , watch: {
            items: function() {
                this.horn();
            }
        }
        , mounted() {
            this.horn();
        }
    }
</script>
