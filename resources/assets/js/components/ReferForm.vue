<template>
    <form :id="formid" @submit.prevent="send">
        <fieldset v-if="todaySent < max">
            <legend>{{ sh.__('today_sent') }} <span class="badge">{{ todaySent + '/' + max }}</span></legend>
            <div class="form-group ruled">
                <label for="email">{{ sh.__('email') }}</label>
                <input type="email" id="email" name="email" v-model="email" :placeholder="sh.__('email_placeholder')" required @keyup="errBag.email = ''">
                <span class="field-invalid-label" v-if="errBag.hasOwnProperty('email')" v-text="errBag.email"></span>
            </div>

            <div class="form-group ruled">
                <label for="name">{{ sh.__('name') }}</label>
                <input type="text" id="name" name="name" v-model="name" :placeholder="sh.__('name_placeholder')" required @keyup="errBag.name = ''">
                <span class="field-invalid-label" v-if="errBag.hasOwnProperty('name')" v-text="errBag.name"></span>
            </div>

            <div class="form-group ruled">
                <label for="note">{{ sh.__('note') }}</label>
                <textarea id="note" name="note" v-model="note"  rows="4"></textarea>
                <span class="field-invalid-label" v-if="errBag.hasOwnProperty('note')" v-text="errBag.note" @keyup="errBag.note = ''"></span>
            </div>

            <button type="submit" class="button primary position-centered"><span v-if="sending"><i class="fas fa-spinner fa-spin"></i>&nbsp;</span>{{ sh.__('send') }}</button>
        </fieldset>
        <fieldset v-else>
            <span class="field-invalid-label">{{ sh.__('limit_reached').replace(':limit', max) }}</span>
        </fieldset>

    </form>
</template>

<script>
    export default {
        name: "ReferForm",
        props: {
            max: {
                type: Number
            },
            sent: {
                type: Number
            }
        },
        data() {
            return{
                formid: "invite_friend",
                todaySent: this.sent,
                sending: false,
                email: '',
                name: '',
                note: '',
                errBag: {}
            }
        },
        mounted() {},
        methods: {
            send: function (ev) {
                const self = this;

                self.sending = true;

                window.axios.post( ajax_url, { email: self.email, name: self.name, note: self.note } )
                    .then(
                        r => {
                            if( 200 === r.status && r.data.id ){
                                self.todaySent++;

                                self.$emit('success', r.data );

                                self.email = self.name = self.note = '';
                                ev.target.reset();
                                ev.target.scrollIntoView({behavior: "smooth"});
                            }
                            else {
                                self.$emit('err', {err: self.sh.__('something_wrong')});
                            }
                        }
                    )
                    .catch(e => {
                        /*
                         * Show validation errors in the error spans next to the input.
                         * Other errors to be emitted up to the parent component.
                         */
                        const validation_fields = ['email', 'name', 'note'];

                        let scrollUp = false;

                        validation_fields.forEach(f => {
                            if(e.response.data.hasOwnProperty(f)) {
                                scrollUp = true;
                                self.errBag[f] = e.response.data[f];
                            }
                        });

                        self.errBag = Object.assign({}, e.response.data);

                        const extra = Object.keys(e.response.data).filter(x => !validation_fields.includes(x));

                        if( extra.length ) {
                            const retObj = {};
                            extra.forEach(k => retObj[k] = e.response.data[k]);

                            self.$emit('err', retObj);
                        }

                        if( scrollUp )
                            setTimeout(() => document.querySelector('.field-invalid-label').parentNode.scrollIntoView({behavior: "smooth"}), 200);
                    })
                    .finally(() => self.sending = false)
            }
        }
    }
</script>

<style scoped>

</style>