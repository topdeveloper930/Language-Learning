<template>
    <div>
        <div id="com_modal" :class="{ in: visible, modal: true, fade: true }" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-header">
                <button type="button" class="close" @click="shutdown">X</button>
                <h3 id="myModalLabel" v-if="header" v-html="header"></h3>
            </div>
            <div class="modal-body">
                <slot></slot>
                <div v-html="body"></div>
            </div>
            <div class="modal-footer">
                <button :class="cancel_class" @click="shutdown" v-if="cancel_txt">{{ cancel_txt }}</button>
                <button :class="ok_class" v-if="ok_txt" @click="ok_handler" v-html="ok_txt"></button>
            </div>
        </div>
        <div id="com_bg" class="fade in modal-backdrop" v-if="visible" @click="shutdown"></div>
    </div>
</template>

<script>
    export default {
        name: "Modal"
        , props: {
            header: {
                default: ''
            }
            , body: {
                default: ''
            }
            , cancel_class: {
                default: 'btn'
            }
            , cancel_txt: {
                default: ''
            }
            , ok_class: {
                default: 'btn btn-primary'
            }
            , ok_txt: {
                default: ''
            }
            , ok_handler: {
                type: Function,
                default: () => null
            }
            , visible: {
                default: false
            }
            , in: {
                default: ' in'
            }
        }
        , data: function () {
            return {
                m: ''
                , bg: ''
            }
        }
        , methods: {
            shutdown() {
                this.$emit('modalclose');
            }
        }
        , mounted() {
            this.m = document.getElementById('com_modal');
            this.bg = document.getElementById('com_bg');
        }
    }
</script>