export default {
    methods: {
        setValidatorTranslations: function () {
            const self = this;
            for( let k of Object.keys( self.sh.lang.errors ) ) {
                if( ! self.sh.lang.errors.hasOwnProperty(k) ) continue;
                if( typeof self.sh.lang.errors[k] === 'string' )
                    self.validate.validators[k]['message'] = self.sh.lang.errors[k];
                else if( typeof self.sh.lang.errors[k] === 'object' ) {
                    for( let j of Object.keys( self.sh.lang.errors[k] ) ) {
                        self.validate.validators[k][j] = self.sh.lang.errors[k][j];
                    }
                }
            }
        }
    },
    mounted() {
        this.setValidatorTranslations();
    }
};