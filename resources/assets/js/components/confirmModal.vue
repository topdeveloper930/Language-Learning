<template>
    <div :class="{'modal-box':true, xs: true, open: visible}" id="modal-header">
        <div class="modal-header">
            <h4 class="modal-title" v-html="header"></h4>
        </div>
        <div class="modal-content">
            <slot></slot>
        </div>
        <div class="modal-footer">
            <a href="#" v-if="no" :class="noCls" @click.prevent="$emit('close')" v-html="no"></a>
            <a href="#" :class="okCls" @click.prevent="$emit('confirm')" v-html="ok"></a>
        </div>
    </div>
</template>

<script>
    import overlayMixin from "../mixins/overlayMixin";

    export default {
        name: "confirmModal",
        props: {
            ok: {
                type: String,
                default: "OK"
            },
            no: {
                type: String
            },
            okCls: {
                default: "primary"
            },
            noCls: {
                type: String,
                default: "secondary"
            },
            visible: {
                type: Boolean,
                default: false
            },
            header: String
        },
        mixins: [overlayMixin],
        watch: {
            visible(newVal, oldVal) {
                ( newVal ) ? this.fade.in( this.overlay ) : this.fade.out( this.overlay );
            }
        },
        mounted() {
            const self = this;
            self.overlay.addEventListener( 'click', e => self.$emit('close') );
        }
    }
</script>