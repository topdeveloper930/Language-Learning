<template>
    <div>
        <h5 v-text="dt"></h5>
        <time-slot v-for="(visible, ts) in slots"
                   v-show="visible"
                   classname="btn btn-large btn-calendar"
                   :key="ts"
        >{{ ts }}</time-slot>

        <p v-if="!Object.keys(slots).length"><i>
            {{ Shared.lang.no_slot }}
        </i></p>
    </div>
</template>

<script>
    import TimeSlot from './TimeSlot.vue';
    export default {
        props: [ 'slots', 'selected' ]
        , components: {
            "time-slot": TimeSlot
        }
        , computed: {
            dt: function() {
                const lang = ( Shared.app_lang === 'es' ) ? 'es-ME' : 'en-US',
                    options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

                return this.selected.toLocaleDateString(lang, options);
            }
        }
    }
</script>
