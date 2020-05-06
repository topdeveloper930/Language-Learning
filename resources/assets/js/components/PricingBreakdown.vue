<template>
    <div class="form-pricing mt-lg mb-md">
        <span class="pricing-label">{{ sh.__('balance_due' )}}</span>
        <span class="pricing-breakdown mv-md">
            {{ sh.__('cost' ) }} <component :is="deducted ? 's' : 'span'" :class="{'text-caution': deducted, 'text-success': !deducted}">{{ '$' + cost.toFixed(2) }}</component><br>
            <span v-if="discount">{{ sh.__('coupon_for' )}} <span v-text="coupon"> </span>: <span class="text-success">{{ '-$' + discount.toFixed(2) }}</span><br></span>
            <span v-if="referral_credits">{{ sh.__('referral_credits' )}} <span class="text-success">{{ '-$' + referral_credits.toFixed(2) }}</span><br></span>
            <span v-if="giftcard">{{ sh.__('giftcard' )}} <span class="text-success">{{ '-$' + giftcard.toFixed(2) }}</span><br></span>
        </span>
        <span class="pricing-amount">{{ '$' + total.toFixed(2) }}</span>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: "PricingBreakdown",
        props: {
            cost: {
                type: Number,
                default: 0
            },
            discount: {
                type: Number,
                default: 0
            },
            coupon: {
                type: String,
                default: ''
            },
            giftcard: {
                type: Number,
                default: 0
            },
            referral_credits: {
                type: Number,
                default: 0
            },
            total: {
                type: Number,
                default: 0
            }
        },
        computed: {
            deducted: function () {
                return this.discount || this.giftcard || this.referral_credits;
            }
        }
    }
</script>