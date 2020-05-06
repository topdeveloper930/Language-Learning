<template>
    <a v-if="show">
        <slot></slot>
    </a>
</template>

<script>
    export default {
        name: "EnterClassRoomBtn",
        props: ['start'],
        computed: {
            show: function() {
                const nowMt = (typeof ltNow !== 'undefined') ? ltNow : new Date();

                return (typeof classRoomReady !== 'undefined')
                    && classRoomReady
                    && (Date.parse( this.start ) - nowMt) < 3.6E6;
            }
        }
    }
</script>

<style scoped>

</style>