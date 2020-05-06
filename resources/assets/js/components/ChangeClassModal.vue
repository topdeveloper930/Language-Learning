<template>
    <span>
        <div :class="{'modal-box': true, open: visible}" id="base-modal">
            <div class="modal-header">
                <a href="#" class="fal fa-times" @click.prevent="close"></a>
                <h4 class="modal-title" v-html="h3">
                </h4>
            </div>
            <div class="modal-content">
                <div v-if="cancel" v-html="lesson.areYouSure">
                    Are you sure?
                </div>
                <div v-else class="columns">
                    <div class="column">
                        <button type="button" class="button caution" @click="cancel = true">
                            {{ sh.__('cancel_class') }}
                        </button>
                    </div>
                    <div class="column">
                        <a :href="editLink" class="button primary">
                            {{ sh.__('reschedule') }}
                        </a>
                    </div>
                </div>
            </div>
            <div v-if="cancel" class="modal-footer">
                <a href="#" class="secondary" @click.prevent="cancel = false">{{ quitTxt }}</a>
                <a href="#" class="caution" @click.prevent="cancelClass"><i v-if="loading" class="fa fa-spinner fa-spin"></i> {{ okTxt }}</a>
            </div>
        </div>
    </span>
</template>

<script>
    import overlayMixin from "../mixins/overlayMixin";
    export default {
        name: "ChangeClassModal",
        props: {
            quitTxt: {
                type: String,
                default: "Cancel"
            },
            okTxt: {
                type: String,
                default: "OK"
            },
            deleteHref: {
                type: String,
                required: true
            },
            editHref: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                lesson: {},
                visible: false,
                cancel: false,
                loading: false
            };
        },
        mixins:[overlayMixin],
        computed: {
            h3: function() {
                return this.cancel ? this.sh.__('cancel_class') : this.sh.__('change_class');
            },
            href: function () {
                return this.deleteHref + '/' + this.lesson.calendarID;
            },
            editLink: function () {
                return this.editHref + '/' + this.lesson.calendarID;
            }
        },
        methods: {
            close: function () {
                this.fade.out( this.overlay );
                this.visible = false;
                this.cancel = false;
                this.lesson = {};
            },
            show: function ( classID, ttl ) {
                this.lesson = {calendarID: classID, areYouSure: ttl};
                this.fade.in( this.overlay );
                this.visible = true;
            },
            cancelClass: function () {
                const self = this;

                self.loading = true;

                window.axios
                    .delete( self.href )
                    .then( response => {
                        if( 202 === response.status && response.data )
                            self.$emit('delete', self.lesson.calendarID);
                        else
                            self.$emit('err' );

                        self.close();
                    } )
                    .catch(e => self.$emit('err', e.response.data))
                    .finally(() => self.loading = false);
            }
        },
        mounted() {
            const self = this;

            self.overlay.addEventListener( 'click', self.close );
        }
    }
</script>
<style scoped>
    .modal-header > a.fal {
        margin-top: -14px;
        color: white;
    }
</style>