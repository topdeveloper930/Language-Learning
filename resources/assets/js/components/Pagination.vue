<template>
    <nav class="pagination mt-xl" v-if="totalPages > 1">
        <a href="#0" :class="{'prev paging': true, disabled: 1 === active}" v-html="prev"></a>

        <a v-for="i in pagesLeft" :href="'#' + i"  :class="{paging: true, active: i === active}" v-text="i"></a>

        <span class="spacer" v-if="showSpacer" v-html="spacer"></span>

        <a v-for="i in pagesRight" :href="'#' + i"  :class="{paging: true, active: i === active}" v-text="i"></a>

        <a href="#00" :class="{'next paging': true, disabled: totalPages === active}" v-html="next"></a>
    </nav>
</template>

<script>
    export default {
        name: "Pagination",
        props: {
            prev: {
                type: String,
                default: "Prev"
            },
            next: {
                type: String,
                default: "Next"
            },
            spacer: {
                type: String,
                default: "..."
            },
            items: {
                type: Array,
                required: true
            },
            limit: {
                type: Number,
                default: 9
            },
            start: {
                type: Number,
                default: 1
            }
        },
        data() {
            return {
                active: this.start
            }
        },
        computed: {
            totalPages() {
                return Math.ceil(this.items.length / this.limit);
            },
            pagesLeft() {
                if( this.totalPages <= 7 )
                    return [...Array(this.totalPages + 1).keys()].slice(1);

                if( this.active > Math.floor(this.totalPages / 2 ) )
                    return [1, 2];

                const start = (this.active < 4) ? 1 : this.active - 2,
                    retVal = [];

                for(let i = 0; i < 4; i++)
                    retVal.push( start + i );

                return retVal;
            },
            pagesRight() {
                if( this.totalPages <= 7 )
                    return [];

                if( this.pagesLeft.length > 2 )
                    return [this.totalPages - 1, this.totalPages];

                const end = (this.totalPages - this.active < 3) ? this.totalPages : this.active + 2,
                    retVal = [];

                for(let i = 4; i >= 0; i--)
                    retVal.push( end - i );

                return retVal;
            },
            showSpacer() {
                return this.spacer && (document.querySelector('.hide-md').offsetParent === null || this.pagesRight.length);
            }
        },
        methods: {
            onClick( e ) {
                const p = e.target.href.split('#').pop(),
                ignore = e.target.classList.contains('disabled') || parseInt( p ) === this.active;

                if( !ignore ) {
                    this.active = (parseInt( p ))
                        ? parseInt( p )
                        : ((p === '00') ? this.active + 1 : this.active - 1 );

                    this.$emit('input', this.active);
                }
            }
        },
        mounted() {
            const self = this;

            document.addEventListener('click', e => {
                if(e.target.classList.contains('paging')) {
                    e.preventDefault();
                    self.onClick(e);
                }
            });
        }
    }
</script>

<style scoped>
    .disabled, .disabled:hover {
        opacity: .5;
        background: white;
    }
    .disabled:hover {
        cursor: text;
    }

</style>