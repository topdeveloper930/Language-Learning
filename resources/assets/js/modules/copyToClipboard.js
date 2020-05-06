import initializable from "./initializable";
export default class CopyToClipboard extends initializable{
    constructor( config ) {
        const defaultOpts = {
            selectClass: "copy_to_cb",
            afterCopyCB: a => null
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        super.init();

        const self = this;

        document.addEventListener('click', event => {
            if( event.target.classList.contains(self._selectClass) ) {
                document.execCommand("copy");
            }
        });

        document.addEventListener('copy', event => {
            if( event.target.classList.contains(self._selectClass) ) {
                event.preventDefault();
                if (event.clipboardData) {
                    event.clipboardData.setData("text/plain", event.target.textContent);
                    self._afterCopyCB(event.target);
                }
            }
        });
    }
}