export default {
    methods: {
        errorsList: function (errors) {
            let retval = '<ul>';

            if ( typeof errors === 'object' && errors.constructor === Object )
                Object.keys(errors).forEach(k => retval += '<li>' + errors[k] + '</li>');
            else if ( typeof errors === 'string' || errors instanceof String )
                retval += '<li>' + errors + '</li>';
            else if( Array.isArray( errors ) )
                retval += '<li>' + errors.reduce((prev, curr) => prev.concat( curr )).join('</li><li>') + '</li>';

            return retval + '</ul>';
        }
    }
};