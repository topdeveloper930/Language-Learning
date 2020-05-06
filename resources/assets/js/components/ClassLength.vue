<template>
    <select :id="event_name" class="span3" v-model="$root.class_duration">
        <opt v-for="item in items"
             :value="item"
             :key="item"
        >{{ label(item) }}</opt>
    </select>
</template>

<script>
    export default {
        props: [ 'items', 'event_name', 'value' ]
        , methods: {
            label: function(m) {
                return Shared.lang.minutes.replace('xx', m);
            }
            , setSelected: function() {
                this.$root.class_duration = ( typeof this.items[1] !== 'undefined' )
                    ? this.items[1]
                    : ( ( typeof this.items[0] !== 'undefined' ) ? this.items[0] : null );
            }
        }
        , watch: {
            items: function() {
                this.setSelected();
            }
        }
        , mounted() {
            this.setSelected();
        }
    }
</script>
