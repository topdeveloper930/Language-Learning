<template>
    <div :class="{'modal-box': true, xs: true, open: visible}" id="simple-message">
        <div class="modal-content">
            <img v-if="img.src" :src="img.src" :alt="img.alt" :class="img.cls">
            <h3 v-if="h3" class="modal-title" v-html="h3"></h3>
            <p v-html="text"></p>
            <a href="#" class="button primary outline modal-close" v-html="ok" @click.prevent="close"></a>
        </div>
    </div>
</template>

<script>
    import overlayMixin from "../mixins/overlayMixin";
    window.Vue = require('vue');
    Vue.mixin({
        data() {
            return {
                mod: '#mod_'
            };
        },
        methods: {
            isModalTrigger: function( n ) {
                return n.nodeName === 'A' && n.className === 'badge' && n.innerText === '?';
            },

            makeModal: function ( link ) {
                const self = this;
                Shared.$emit(
                    'simple_modal',
                    {
                        header: link.parentNode.innerText.replace('?', '').trim(),
                        text: self.sh.__(link.href.substr( link.href.indexOf(self.mod) + self.mod.length))
                    }
                );
            }
        },
        mounted() {
            const self = this;

            document.addEventListener( 'click', e => {
                if( self.isModalTrigger( e.target ) )
                    self.makeModal( e.target );
            }, false );
        }
    });
    export default {
        name: "simpleModal",
        props: {
            ok: {
                type: String,
                default: "Close"
            },
            header: String,
            img_src: String,
            img_alt: String,
            img_cls: {
                type: String,
                default: "rounded"
            }
        },
        data() {
            return {
                input: {},
                visible: false
            };
        },
        mixins: [overlayMixin],
        computed: {
            text: function () {
                return this.input.hasOwnProperty('text') ? this.input.text : '';
            },
            h3: function () {
                return this.input.hasOwnProperty('header') ? this.input.header : this.header;
            },
            img: function () {
                const img = { src: this.img_src, alt: this.img_alt, cls: this.img_cls };
                return this.input.hasOwnProperty('img')
                    ? Object.assign({}, img, this.input.img)
                    : img;
            }
        },
        methods: {
            close: function () {
                this.fade.out( this.overlay );
                this.visible = false;
            },
            show: function ( data ) {
                this.input = typeof data === 'object' ? data : {};
                this.fade.in( this.overlay );
                this.visible = true;
            }
        },
        mounted() {
            const self = this;

            self.overlay.addEventListener( 'click', self.close );

            Shared.$on('simple_modal', self.show);
        }
    }
</script>