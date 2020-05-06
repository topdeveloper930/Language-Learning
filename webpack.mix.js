/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 | As an option: require('dotenv').config(); permits usage of .env variables for configuring the output.
 |      my_root = ( 'local' === process.env.MIX_APP_ENV.toLowerCase() ) ? 'goodvybs' : 'livelingua'; - use MIX_ prefix for nodejs vars.
 |
 | Please, create a MIX_PUBLIC_PATH constant in your .env file with your specific public path relative to the root.
 */

require('dotenv').config();

const mix = require('laravel-mix');

// mix.styles(['resources/assets/css/framework.css'], process.env.MIX_PUBLIC_PATH + 'public/css/vendor.css')
//     .options({
//         postCss: [
//             require('cssnano')({
//                 preset: 'default',
//             })
//         ]
//     });

mix.setPublicPath(process.env.MIX_PUBLIC_PATH)
    // .js('resources/assets/js/students/calendar.js', 'public/js/calendar.js')
    // .js('resources/assets/js/students/upcoming-classes.js', 'public/js/upcoming-classes.js')
    // .js('resources/assets/js/admin/teacher_availability_stats.js', 'public/js/teacher_availability_stats.js')
    // .js('resources/assets/js/admin/student_average_purchase_stats.js', 'public/js/student_average_purchase_stats.js')
    // .sass('resources/assets/sass/js-calendar.scss', 'public/css/calendar.css')
    // .js('resources/assets/js/admin/stats_signup_trial.js', 'public/js/stats_signup_trial.js')
    // .js('resources/assets/js/students/student_dashboard.js', 'public/js/student_dashboard.js')
    // .js('resources/assets/js/page/tutor.js', 'public/js/tutor.js')
    // .js('resources/assets/js/page/tutors.js', 'public/js/tutors.js')
    // .js('resources/assets/js/modules/toaster.js', 'public/js/toaster.js')
    // .js('resources/assets/js/modules/slideOutMenu.js', 'public/js/slideOutMenu.js')
    // .js('resources/assets/js/students/student_profile.js', 'public/js/student_profile.js')
    // .js('resources/assets/js/page/login.js', 'public/js/login.js')
    // .js('resources/assets/js/students/student_schedule_class.js', 'public/js/student_schedule_class.js')
    // .js('resources/assets/js/page/authentication_with_social_networks.js', 'public/js/sn_authentication.js')
    // .js('resources/assets/js/students/student_refer.js', 'public/js/student_refer.js')
    // .js('resources/assets/js/students/student_transactions.js', 'public/js/student_transactions.js')
    // .js('resources/assets/js/students/student_purchase.js', 'public/js/student_purchase.js')
    // .js('resources/assets/js/admin/payments_pending.js', 'public/js/admin_payments_pending.js')
    // .js('resources/assets/js/students/purchase_result.js', 'public/js/purchase_result.js')
    // .js('resources/assets/js/students/progress_reports.js', 'public/js/student_progress_reports.js')
    // .js('resources/assets/js/students/progress_report.js', 'public/js/student_progress_report.js')
    // .js('resources/assets/js/students/credits.js', 'public/js/student_credits.js')
    // .js('resources/assets/js/teachers/teacher_profile.js', 'public/js/teacher_profile.js')
    // .js('resources/assets/js/teachers/teacher_dashboard.js', 'public/js/teacher_dashboard.js')
    // .js('resources/assets/js/teachers/teacher_balance.js', 'public/js/teacher_balance.js')
    // .js('resources/assets/js/page/pricing.js', 'public/js/pricing.js')
    // .js('resources/assets/js/page/faq.js', 'public/js/faq.js')
    // .js('resources/assets/js/page/affiliates.js', 'public/js/affiliates.js')
    // .js('resources/assets/js/page/gift_cards.js', 'public/js/gift_cards.js')
    // .js('resources/assets/js/page/gift_card_claim.js', 'public/js/gift_card_claim.js')
    // .js('resources/assets/js/page/gift_card_checkout.js', 'public/js/gift_card_checkout.js')
    // .js('resources/assets/js/teachers/teacher_trial_class_report.js', 'public/js/teacher_trial_class_report.js')
    // .js('resources/assets/js/page/flexslider_component.js', 'public/js/flexslider_component.js')
    // .js('resources/assets/js/page/quiz.js', 'public/js/quiz.js')
    // .js('resources/assets/js/page/quiz_result.js', 'public/js/quiz_result.js')
    // .js('resources/assets/js/page/home.js', 'public/js/home.js')
    // .js('resources/assets/js/page/trial_lesson.js', 'public/js/trial_lesson.js')
    // .js('resources/assets/js/page/course.js', 'public/js/course.js')
    .js('resources/assets/js/teachers/teacher_class_log.js', 'public/js/teacher_class_log.js')
    .js('resources/assets/js/teachers/teacher_student_evaluation.js', 'public/js/teacher_student_evaluation.js')
;