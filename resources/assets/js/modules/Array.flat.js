if( typeof [].flat === 'undefined' ) {
    Array.prototype.flat = function () {
        const arr1 = this;
        return arr1.reduce((acc, val) => Array.isArray(val) ? acc.concat(val.flat()) : acc.concat(val), []);
    }
}