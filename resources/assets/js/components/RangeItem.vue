<template>
    <div class="form-group">
        <div class="range-labels">
            <label v-text="label"></label>
            <span v-text="minLabel"></span>
            <span v-text="maxLabel"></span>
        </div>
        <input type="range"
               :min="minValue"
               :max="maxValue"
               :value="value"
        >
    </div>
</template>

<script>
    export default {
        name : "RangeItem",
        props : {
            label : {
                type: String,
                required: true
            },
            minLabel : {
                type: String,
                required: true
            },
            maxLabel : {
                type: String,
                required: true
            },
            value : {
                type : Number,
                default :  5
            },
            minValue : {
                type : Number,
                default :  0
            },
            maxValue : {
                type : Number,
                default :  10
            }
        }
    }
</script>