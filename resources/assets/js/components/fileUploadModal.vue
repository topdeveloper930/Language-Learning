<template>
    <div :class="{'modal-box': true, xs: true, open: visible}" id="file_upload">
        <div class="modal-content">
            <a href="#" class="modal-close fal fa-times" @click.prevent="close"></a>
            <h3 v-if="header" class="modal-title" v-html="header"></h3>
            <p v-if="!file"><slot></slot></p>
            <img v-else-if="preview && img_src" :src="img_src" alt="" :style="imgStyle">
            <div v-if="errors.length">
                <span v-for="error of errors" class="field-invalid-label">{{ error }}</span>
            </div>
            <input type="file" id="file" ref="file" @change="handleFileUpload" hidden/>
            <a v-if="!file" href="#" class="button" v-html="btn_select" @click.prevent="fileClick"></a>
            <div v-else class="modal-footer">
                <a href="#" :class="cancelBtnClass" v-html="cancelBtnTxt" @click.prevent="clear"></a>
                <a id="uploadFileSubmitBtn" href="#" :class="submitBtnClass" @click.prevent="submit" :disabled="!isValid">
                    {{ submitBtnTxt }}
                    <span v-if="spinner && load" v-html="spinner"></span>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import fading from "../modules/fading";
    export default {
        name: "fileUploadModal",
        props: {
            btn_select: {
                type: String,
                default: "Select image"
            },
            header: String,
            overlay_cls: {
                type: String,
                default: "modal-box-overlay"
            },
            maxSize: {
                type: Number, // kB
                default: 0
            },
            minSize: {
                type: Number, // kB
                default: 0
            },
            types: String,
            imgStyle: String,
            preview: Boolean,
            submitBtnClass: {
                type: String,
                default: 'primary'
            },
            submitBtnTxt: {
                type: String,
                default: 'Submit'
            },
            cancelBtnClass: {
                type: String,
                default: 'caution'
            },
            cancelBtnTxt: {
                type: String,
                default: 'Cancel'
            },
            spinner: String
        },
        data() {
            return {
                fade: new fading({}),
                file: "",
                img_src: "",
                overlay: null,
                visible: false,
                errors: [],
                load: false
            };
        },
        computed: {
            isValid: function () {
                return this.file && !this.errors.length;
            }
        },
        methods: {
            fileClick: function() {
                const elem = this.$refs.file,
                    evt = new MouseEvent('click', {
                        bubbles: true,
                        cancelable: true,
                        view: window
                    }),
                    canceled = !elem.dispatchEvent(evt);
            },
            fileValidate: function() {
                if ( this.types && (!this.file.type || this.types.indexOf( this.file.type ) === -1))
                    this.errors.push( Shared.__( 'image_type_error' ));

                if ( this.minSize && ( this.file.size < ( this.minSize * 1024 )))
                    this.errors.push( Shared.__( 'image_minsize_error' ));

                if( this.maxSize && ( this.file.size > ( this.maxSize * 1024 )))
                    this.errors.push( Shared.__( 'image_maxsize_error' ));
            },
            clear: function() {
                this.load = false;
                this.errors = [];
                this.img_src = '';
                this.file = '';
            },
            close: function () {
                this.clear();
                this.fade.out( this.overlay );
                this.visible = false;
            },
            show: function () {
                this.fade.in( this.overlay );
                this.visible = true;
            },
            makeOverlay: function () {
                this.overlay =  document.createElement('DIV');
                this.overlay.className = this.overlay_cls;
                document.body.appendChild( this.overlay );
            },
            handleFileUpload: function () {
                this.file = this.$refs.file.files[0];
                this.setImg();

                if( this.file ) this.fileValidate();
                else            this.clear();
            },
            setImg: function () {
                const self = this;
                if (self.file && self.file.type.indexOf('image/') === 0 ) {
                    const reader = new FileReader();
                    reader.onload = e => self.img_src = e.target.result;

                    reader.readAsDataURL(self.file);
                }
            },
            submit: function () {
                if( this.isValid ) {
                    this.load = true;
                    this.$emit('file_selected', this.file);
                }
            }
        },
        mounted() {
            const self = this;

            ( document.getElementsByClassName( self.overlay_cls ).length )
                ? self.overlay = document.getElementsByClassName( self.overlay_cls )[0]
                : self.makeOverlay();

            self.overlay.addEventListener( 'click', self.close );

            Shared.$on('file_upload_show', self.show);
            Shared.$on('file_upload_close', self.close);
            Shared.$on('file_upload_error', errors => {
                self.clear;
                self.errors = errors;
            });
        }
    }
</script>

<style scoped>
    #uploadFileSubmitBtn[disabled] {
        opacity: .5;
        cursor: default;
        color: grey;
    }
</style>