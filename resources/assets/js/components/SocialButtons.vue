<template>
    <div class="columns mt-xs">
        <div v-for="(icon, provider) in sna" v-if="icon" class="column">
            <a :href="authLink(provider)"
               :title="btnTitle(provider)"
               :class="btnClass(provider)"
               v-html="btnHtml(icon, provider)"
            ></a>
        </div>
    </div>
</template>
<script>
    export default {
        name: "SocialButtons",
        props: [ 'networks', 'myAccounts', 'linkStub', 'providerStub' ],
        data() {
            return{
                sna: JSON.parse(this.networks),
                mySNA: JSON.parse(this.myAccounts)
            }
        },
        computed: {
            cls: function () {
                return "button ";
            },
        },
        methods: {
            getByProvider: function(p) {
                return this.mySNA.find( n => n.provider === p);
            },
            authLink: function (p) {
                return this.linkStub.replace(this.providerStub, p);
            },
            btnTitle: function (p) {
                return this.getByProvider(p)
                    ? this.sh.__('disable').replace(':p', this.sh.__(p))
                    : this.sh.__('enable').replace(':p', this.sh.__(p));
            },
            btnClass: function (p) {
                return 'button ' + p + (this.getByProvider(p) ? '' : ' secondary');
            },
            btnHtml: function (i, p) {
                return '<i class="fab ' + ((i === true) ? 'fa-' + p : i) + '"></i> ' +  this.sh.__(p);
            }
        }
    }
</script>