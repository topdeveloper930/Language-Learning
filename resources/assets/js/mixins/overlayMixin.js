import fading from "../modules/fading";

export default {
    props: {
        overlay_cls: {
            type: String,
            default: "modal-box-overlay"
        }
    },
    data() {
        return {
            fade: new fading({}),
            overlay: null
        };
    },
    methods: {
        makeOverlay: function () {
            this.overlay =  document.createElement('DIV');
            this.overlay.className = this.overlay_cls;
            document.body.appendChild( this.overlay );
        }
    },
    mounted() {
        const self = this;

        ( document.getElementsByClassName( self.overlay_cls ).length )
            ? self.overlay = document.getElementsByClassName( self.overlay_cls )[0]
            : self.makeOverlay();
    }
};