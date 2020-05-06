<template>
    <select id="userOther" v-model="selected" @change="$emit( 'user-change', selected )">
        <opt v-for="(user, indx) in users"
             :key="user.firstName"
             :value="indx"
        >{{ user.title + ' ' + user.firstName + ' ' + user.lastName }}</opt>
    </select>
</template>

<script>
    export default {
        props: [ 'users', 'active' ],
        data: function() {
            return {
                selected: this.active
            };
        }
    }
</script>
