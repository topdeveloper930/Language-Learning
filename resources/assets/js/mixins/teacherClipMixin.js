export default {
    computed: {
        table() {
            return $( '#' + this.table_id ).DataTable();
        },
    },
    methods: {
        imageSrc(src) {
            src = src || 'img/profiles/no-profile-image.jpg';

            if ( src.indexOf('http') === 0 || src.indexOf('/') === 0) return src;

            return '/' + src;
        },
        teacherClip( id, ttl, name, photo, lang ) {
            return '<a class="chip" href="' + this.tutorUrl( lang, name, id ) + '"><img alt="' + name + '" src="' + this.imageSrc(photo) + '" class="avatar"> ' + ttl + ' ' + name + '</a>';
        },
        tutorUrl( lang, name, id ) {
            return '/' + lang.toLowerCase()
                + '/tutors/' + name.toLowerCase().replace(/\s/, '-')
                + '/' + id;
        }
    }
};