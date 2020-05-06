if( typeof [].shuffle === 'undefined' ) {
    Array.prototype.shuffle = function () {
        const arr1 = this;
        for (let i = arr1.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr1[i], arr1[j]] = [arr1[j], arr1[i]];
        }

        return arr1;
    }
}