require('../bootstrap');

import slideOutMenu from "../modules/slideOutMenu";
import infoModal from "../components/infoModal";

const app = new Vue({
    el: '#root',
    components: {infoModal},
    methods: {
        setModal( level ) {
            const self = this;

            window.axios.get( ajax_url + '?level=' + level )
                .then(
                    r => self.sh.$emit( 'info-modal', {sizeCls: 'lg', header: r.data.title, info: r.data.description}),
                    er => self.sh.$emit( 'info-modal', {header: 'fail', info: 'error'})
                );
        }
    }
});