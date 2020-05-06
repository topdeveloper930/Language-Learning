export default new Vue({
    data() {
        return {
            app_lang: document.documentElement.lang
            , lang: document.getElementById('translation')
                ? JSON.parse( document.getElementById('translation').value )
                : {}
        };
    }
    , methods: {
        isObject: o => !!(o && typeof o === 'object' && o.constructor === Object)
        , isString: s => (typeof s === 'string' || s instanceof String)
        , objectNotEmpty: o => !!Object.keys(o).length
        , empty: function(v){
            return !v || (this.isObject(v) && !this.objectNotEmpty(v)) || (Array.isArray(v) && !v.length);
        }
        , __: function (s) {
            return ( this.lang.hasOwnProperty( s )) ? this.lang[ s ] : s;
        }
    }
})