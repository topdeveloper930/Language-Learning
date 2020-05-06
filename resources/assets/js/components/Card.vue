<template>
    <div :class="classlist">
        <a :href="href" :class="linkcls">
            <img :class="imgClass" :src="src" :alt="imgalt" loading="lazy" @error="setStubSrc($event.target)">
            <span class="chip language"><img :src="countryFlag"> {{ countryName }}</span>
            <h4 class="card-title" v-if="header">{{ header }}</h4>
            <p class="card-subtitle"><slot></slot></p>
            <span class="button" v-if="profileBtn" v-text="profileBtn"></span>
        </a>
    </div>
</template>

<script>
    export default {
        name: "Card",
        props: {
            classlist: {
                default: "column col-4 col-6-lg col-12-md"
            }
            , href: String
            , linkcls: String
            , imgsrc: String
            , imgalt: String
            , imgcls: String
            , header: String
            , profileBtn: String
            , noImageStub: String
            , countryCode: String
            , countryName: String
        },
        data() {
            return {
                loadedCls: "loaded"
            };
        },
        computed: {
            img() {
                return '/' + this.imgsrc.replace(/^\//, '');
            },
            src() {
                return this.imgsrc ? this.img : this.noImageStub;
            },
            imgClass() {
                return this.isLazy ? this.imgcls : (this.imgcls + ' ' + this.loadedCls).trim();
            },
            countryFlag() {
                return '/public/images/flags/svg/' + this.countryCode + '.svg'
            }
        },
        methods: {
            setStubSrc(img) {
                img.src = this.noImageStub;
            }
        }
    }
</script>