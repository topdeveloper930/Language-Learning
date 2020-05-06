<template>
    <button type="button" :class="classname" @click="horn($event.target.innerText)"><slot></slot></button>
</template>

<script>
    export default {
        props: [ 'classname' ]
        , methods: {
            horn: t => Shared.$emit( 'time-selected', t )
        }
    }
</script>
