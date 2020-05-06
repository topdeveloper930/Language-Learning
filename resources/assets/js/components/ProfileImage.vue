<template>
    <aside class="profile column col-3 col-12-md">
        <div class="card profile">
            <a href="#" :class="{'profile-avatar-placeholder': !isValidImage}" @click.prevent="sh.$emit('file_upload_show')" v-html="profileImage">
            </a>

            <span class="profile-name" v-text="fullname"></span>
            <span class="profile-email" v-text="email"></span>
        </div>
    </aside>
</template>

<script>
    export default {
        name: "ProfileImage",
        props: {
            image: String,
            fullname: {
                type: String,
                required: true
            },
            email: {
                type: String,
                required: true
            },
            slug: {
                type: String,
                required: true
            }
        },
        computed: {
            isValidImage() {
                return this.image && this.slug !== this.image;
            },
            profileImage() {
                return this.isValidImage
                    ? '<img class="profile-image" src="' + this.image + '" alt=""/>'
                    : '<i class="fal fa-image"></i>';
            }
        }
    }
</script>

<style scoped>
    .profile.column {
        padding-right: 3rem;
    }
</style>