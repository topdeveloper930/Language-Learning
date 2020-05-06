require("../bootstrap");
require("../modules/KeepTokenAlive");

import slideOutMenu from "../modules/slideOutMenu";
import shorten from "../modules/shorten";
import confirmModal from "../components/confirmModal";
import infoModal from "../components/infoModal";
import errorsMixin from "../mixins/errorsMixin";

const app = new Vue({
    el: "#root",
    data() {
        return {
            noShowMod: false,
            iWaited: false,
            iLeftMessage: false,
            iWaitedErr: 0,
            iLeftMessageErr: 0,
            loading: false,
            evaluation: {
                speakingLevel: null,
                listeningLevel: null,
                readingLevel: null,
                writingLevel: null,
                comments: ''
            }
        };
    },
    components: {
        confirmModal, infoModal
    },
    mixins: [ errorsMixin ],
    methods:{
        resetNoShow() {
            this.loading = this.noShowMod = this.iWaited = this.iLeftMessage = this.iWaitedErr = this.iLeftMessageErr = false;
        },
        setNoShow() {
            const self = this;

            if(!self.iWaited)
                self.iWaitedErr = 1;

            if(!self.iLeftMessage)
                self.iLeftMessageErr = 1;

            if(self.iWaitedErr || self.iLeftMessageErr)
                return;

            self.loading = true;

            window.axios.post(dashboard_url + '/trial_noshow', {id: trialId})
                .then(
                    res => {
                        if(!self.sh.empty(res.data))
                            window.location.href = dashboard_url;
                        else
                            self.toaster.caution(self.sh.__('no_response'));
                    },
                    er => self.errors(er.response.data)
                )
        },
        removeInvalid: e => e.target.classList.remove('invalid')
    },
    created(){
        new shorten({
            more: this.sh.__('more'),
            less: this.sh.__('less'),
        });
    }
});