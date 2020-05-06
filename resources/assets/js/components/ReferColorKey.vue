<template>
    <h5>
        <slot></slot>
        <a v-for="row in data"
           href="#"
           class="badge"
           :style="'color: white;background-color: ' + row.bg"
           @click.prevent="$emit('colorize', row.title)"
        >{{ sh.__(row.title) }}</a>
    </h5>
</template>

<script>
    export default {
        name: "ReferColorKey",
        props: { data: { type: Array } }
    }
</script>