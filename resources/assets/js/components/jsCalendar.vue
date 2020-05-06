<template>
    <div :class="classname"
         :data-date="selected"
         :data-min="start"
         :data-max="end"
         :data-language="language"
         ref="l"
    ></div>
</template>

<script>
    export default {
        props: {
            driver: {
                required: true
            }
            , selected: {
                required: true
            }
            , start: {
                required: true
            }
            , end: {
                required: true
            }
            , classname: {
                default: 'green'
            }
            , l: {
                default: 'auto-jsCalendar'
            }
            , language: {
                default: 'es'
            }
            , m_format: {
                default: 'month YYYY'
            }
            , time_slots: {
                type: Object,
                default: () => {}
            }
        }
        , data: () => {
            return {
                cal: {}
            };
        }
        , computed: {}
        , watch: {
            time_slots: function() {
                this.cal.refresh();
            }
        }
        , mounted() {
            let self = this;
            self.cal = self.driver.new( self.$refs.l, self.selected, { "monthFormat": self.m_format } );


            self.cal.onDateRender(function(date, element, info) {
                element.className += ' ' + self.getDateClass(date);
            });

            self.cal.onDateClick(function( event, date ){
                if( event.target.className.indexOf( 'jsCalendar-dimmed' ) !== -1 || event.target.className.indexOf( 'jsCalendar-closed' ) !== -1 ) return;

                self.cal.set( date );
                Shared.$emit( 'date-selected', date );
            });

            Shared.$emit( 'date-selected', self.cal._now );
        }
        , methods: {
            getDateClass: function( date ) {
                const month = this.cal.language.months[date.getMonth()],
                    dd = date.getDate();

                if( !(month in this.time_slots) || !(dd in this.time_slots[month]) )
                    return 'jsCalendar-dimmed';

                return ( Object.values(this.time_slots[month][dd]).reduce((a, b) => a + b) === 0 )
                    ? 'jsCalendar-closed'
                    : '';
            }
        }
    }
</script>
