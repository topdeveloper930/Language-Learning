export default {
    methods: {
        setErrors( errBag ) {
            const self = this;
            self.errors = errBag;
            Object.keys( errBag ).map( k => {
                const el = document.getElementById(k);

                if( el ) {
                    el.classList.add('invalid');
                    self.addListners( el );
                }
            } );
        },
        clearError( id ) {
            const self = this;
            if( self.errors.hasOwnProperty( id ) ) {
                self.errors[ id ] = '';
                const el = document.getElementById( id );

                if( el ) {
                    el.classList.remove( 'invalid' );
                    self.clearListners( el );
                }
            }
        },
        addListners( el ) {
            const self = this;
            el.addEventListener('input', e => self.clearError(el.id), false );
            el.addEventListener('change', e => self.clearError(el.id), false );
        },
        clearListners( el ) {
            const self = this;
            el.removeEventListener('input', e => self.clearError(el.id), false );
            el.removeEventListener('change', e => self.clearError(el.id), false );
        }
    }
};