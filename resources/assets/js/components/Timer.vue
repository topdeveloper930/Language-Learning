<template>
    <span>
        <span v-if="days">{{ days }}d </span>{{ formatTime(hours) }}:{{ formatTime(minutes) }}:{{ formatTime(seconds) }}
    </span>
</template>

<script>
    export default {
        name: "Timer",
        props: ['starttime', 'endtime'],
        data() {
            return{
                start: "",
                end: "",
                interval: "",
                days: "",
                minutes: "",
                hours: "",
                seconds: "",
                statusType: ""
            }
        },
        mounted() {
            const self = this;
            self.start = new Date(self.starttime).getTime();
            self.end = new Date(self.endtime).getTime();
            self.interval = setInterval( () => self.timerCount( self.start, self.end ), 1000 );
        },
        methods: {
            timerCount: function(start, end) {
                const now = new Date().getTime(),
                    distance = start - now,
                    passTime =  end - now;

                if(distance < 0 && passTime < 0){
                    //TODO: any notification? Hide?
                    this.statusType = -1;
                    clearInterval(this.interval);
                    return;

                }else if(distance < 0 && passTime > 0){
                    //TODO: any notification?
                    this.calcTime(passTime);
                    this.statusType = 0;

                } else if( distance > 0 && passTime > 0 ){
                    this.calcTime(distance);
                    this.statusType = 1;
                }
            },
            calcTime: function(dist){
                this.days = Math.floor(dist / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((dist % (1000 * 60)) / 1000);
            },
            formatTime: t => ('00' + t).slice(-2)
        }
    }
</script>

<style scoped>

</style>