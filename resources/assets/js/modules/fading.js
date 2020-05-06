import initializable from "./initializable";
export default class fading extends initializable{
    constructor( config ) {
        const defaultOpts = {
            duration: 200
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        super.init();
    }

    fade ( fadeTarget, duration, op ) {
        op = typeof op !== 'undefined' ? op : 0;
        duration = typeof duration !== 'undefined' ? duration : this._duration;

        fadeTarget.style.transitionProperty = 'opacity';
        fadeTarget.style.transitionDuration = (duration / 1000) + 's';

        if( 0 === parseFloat( op ) ) {
            setTimeout( () => fadeTarget.style.display = null, duration );
        }
        else if( 0 < parseFloat( op ) ) {
            if( fadeTarget.hasAttribute('hidden') )
                fadeTarget.removeAttribute('hidden');
            if( !fadeTarget.style.display || fadeTarget.style.display === 'none')
                fadeTarget.style.display = 'block';
        }

        setTimeout( () => fadeTarget.style.opacity = op, 100 );
    }

    in ( fadeTarget, duration ) {
        this.fade( fadeTarget, duration, 1 );
    }

    out ( fadeTarget, duration ) {
        this.fade( fadeTarget, duration, 0 );
    }
}

window.fadeInOut = new fading({});