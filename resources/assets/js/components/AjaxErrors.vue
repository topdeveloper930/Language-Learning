<template>
    <div>
        <h4 class="text-error"><slot></slot></h4>
        <ul class="text-left">
            <li v-for="message in messages">{{ message }}</li>
        </ul>
    </div>
</template>

<script>
    require('../modules/Array.flat'); // For the case if browser does not natively support the function.
    export default {
        props: [ 'errors' ],
        computed: {
            messages: function() {
                return Object.values(this.errors).flat();
            }
        }
    }
</script>
