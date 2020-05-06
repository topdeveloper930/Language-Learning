import initializable from "./initializable";
export default class LazyImage extends initializable{
    constructor( config ) {
        const defaultOpts = {
            selector: "[loading='lazy']",
            rootSelector: "#root",
            rootMargin: "0px 0px 200px 0px",
            loadedClass: "loaded",
            observer: null
        };

        config = Object.assign( {}, defaultOpts , config);

        super( config );
    }

    init() {
        super.init();
        const self = this,
            onIntersection = imageEntites => {
                const self = this;
                imageEntites.forEach(image => {
                    if (image.isIntersecting) {
                        observer.unobserve(image.target);
                        image.target.src = image.target.dataset.src;
                        image.target.onload = () => image.target.classList.add( self._loadedClass )
                    }
                })
            },
            observer = new IntersectionObserver(onIntersection, self.interactSettings);

        self.images.forEach(image => observer.observe( image ));
    }

    get images() {
        return [...document.querySelectorAll(this._selector)];
    }

    get interactSettings () {
        return {
            rootMargin: this._rootMargin
        };
    }
}
