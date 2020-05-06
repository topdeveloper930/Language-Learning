import shorten from "../modules/shorten";

require("../bootstrap");
require("../modules/KeepTokenAlive");

import slideOutMenu from "../modules/slideOutMenu";

const app = new Vue({
    el: "#root",
    methods: {
        removeInvalid: e => e.target.classList.remove('invalid')
    },
    created(){
        new shorten({
            more: this.sh.__('more'),
            less: this.sh.__('less'),
        });
    }
});