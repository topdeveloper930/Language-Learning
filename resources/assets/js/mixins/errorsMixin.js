export default {
    methods: {
        errors: function (errors) {
            const self = this;

            if ( typeof errors === 'object' && errors.constructor === Object )
                Object.keys(errors).forEach(k => self.toaster.caution(errors[k]));
            else if (typeof errors === 'string' || errors instanceof String)
                self.toaster.caution( errors );
            else
                self.toaster.caution( self.sh.__('error') );
        }
    }
};