<template>
    <div :class="boxCls" id="info-modal">
        <div class="modal-header" v-if="h2">
            <h2 class="modal-title" v-html="h2"></h2>
        </div>
        <div class="modal-content">
            <div v-if="info" v-html="info"></div>
            <slot name="info"></slot>
            <a href="#" class="button primary outline modal-close" v-if="btnTxt" v-html="btnTxt" @click.prevent="close"></a>
        </div>
    </div>
</template>

<script>
    import overlayMixin from "../mixins/overlayMixin";
    export default {
        name: "infoModal",
        props: {
            ok: {
                type: String,
                default: "Close"
            },
            header: String,
            text: String,
        },
        data() {
            return {
                sizeCls: "",
                visible: false,
                btnTxt: "",
                info: "",
                h2: ""
            };
        },
        mixins: [overlayMixin],
        computed: {
            boxCls() {
                let cls = 'modal-box';
                if ( this.sizeCls ) cls += ' ' + this.sizeCls;
                if ( this.visible ) cls += ' open';

                return cls;
            }
        },
        methods: {
            close: function () {
                this.fade.out( this.overlay );
                this.visible = false;
            },
            show: function ( data ) {
                this.sizeCls = data.sizeCls;
                this.btnTxt = data.btnTxt ? this.sh.__( data.btnTxt ) : this.ok;
                this.h2 = data.header ? this.sh.__( data.header ) : this.header;
                this.info = data.info ? this.sh.__( data.info ) : this.text;
                this.fade.in( this.overlay );
                this.visible = true;
            },
            isModalTrigger( el ) {
                return el.classList.contains('info-modal');
            }
        },
        mounted() {
            const self = this;

            self.sh.$on('info-modal', self.show );
            self.overlay.addEventListener( 'click', self.close );
        }
    }
</script>