@php($isSuperAdmin = $user->isSuperAdmin())
<ul class="nav nav-pills nav-main" id="mainMenu">
    <li @if ( $tplConfig->main_menu_active == 'dashboard' ) class="active" @endif>
        <a href="{{ url( '/admin/admin-dashboard.php' ) }}">@lang( 'admin.Dashboard' )</a>
    </li>
    <li class="dropdown @if ( $tplConfig->main_menu_active == 'trial' ) active @endif">
        <a href="{{ url( '/admin/trial-classes.php' ) }}" title="@lang('admin.manage_trials')">@lang('admin.trials') <i class="icon-angle-down"></i></a>

        <ul class="dropdown-menu">
            <li><a href="{{ url( '/admin/trial-classes-add.php' ) }}" title="@lang('admin.add_trial_title')">@lang('admin.add_trial')</a></li>
            <li><a href="{{ url( '/admin/trial-classes.php' ) }}" title="@lang('admin.unassigned_trial_title')">@lang('admin.unassigned_trial')</a></li>
            <li><a href="{{ url( '/admin/trial-classes-pending.php' ) }}" title="@lang('admin.pending_trial_title')">@lang('admin.pending_trial')</a></li>
            <li><a href="{{ url( '/admin/trial-classes-history.php' ) }}" title="@lang('admin.history_trial_title')">@lang('admin.history_trial')</a></li>
            <li><a href="{{ url( '/admin/trial-classes-statistics.php' ) }}" title="@lang('admin.statistics_trial_title')">@lang('admin.statistics_trial')</a></li>
        </ul>
    </li>
    <li class="dropdown @if ( $tplConfig->main_menu_active == 'students' ) active @endif">
        <a href="{{ url( '/admin/manage_students.php' ) }}">@lang('admin.students') <i class="icon-angle-down"></i></a>

        <ul class="dropdown-menu">
            <li><a href="{{ url( '/admin/manage_students.php' ) }}">@lang('admin.manage_students')</a></li>
            <li><a href="{{ url( '/admin/edit-student.php' ) }}">@lang('admin.add_student')</a></li>
            <li><a href="{{ url( '/admin/student-payments.php' ) }}" title="@lang('admin.payment_log')">@lang('admin.student_payments')</a></li>
            <li><a href="{{ url( '/admin/student-gift-cards.php' ) }}" title="@lang('admin.gift_cards_record')">@lang('admin.gift_cards')</a></li>
            <li><a href="{{ url( '/admin/coupons.php' ) }}">@lang('admin.coupons')</a></li>
            <li><a href="{{ url( '/admin/manage_referrals.php' ) }}">@lang('admin.manage_referrals')</a></li>
        </ul>
    </li>
    <li class="dropdown @if ( $tplConfig->main_menu_active == 'teachers' ) active @endif">
        <a href="{{ url( '/admin/manage_teachers.php' ) }}">@lang('admin.teachers') <i class="icon-angle-down"></i></a>

        <ul class="dropdown-menu">
            <li><a href="{{ url( '/admin/manage_teachers.php' ) }}" title="@lang('admin.active_teachers')">@lang('admin.manage_teachers')</a></li>
            <li><a href="{{ url( '/admin/manage-teachers-on-break.php' ) }}" title="@lang('admin.manage_teachers_on_break')">@lang('admin.teachers_on_break')</a></li>
            <li><a href="{{ url( '/admin/manage-teachers-inactive.php' ) }}" title="@lang('admin.manage_teachers_inactive')">@lang('admin.teachers_inactive')</a></li>
            <li><a href="{{ url( '/admin/teacher-findbyrange.php' ) }}" title="@lang('admin.teacher_findbyrange')">@lang('admin.teachers_available')</a></li>
            <li><a href="{{ url( '/admin/edit-teacher.php' ) }}">@lang('admin.add_teacher')</a></li>
            <li><a href="{{ url( '/admin/teacher-statistics.php' ) }}">@lang('admin.teacher_statistics')</a></li>
        </ul>
    </li>
    <li class="dropdown @if ( $tplConfig->main_menu_active == 'statistics' ) active @endif">
        <a href="{{ url( '/admin/statistics-dashboard.php' ) }}">@lang('admin.statistics_dashboard') <i class="icon-angle-down"></i></a>

        <ul class="dropdown-menu">
            <li><a href="{{ url( '/admin/student-payments.php' ) }}" title="@lang('admin.payment_log')">@lang('admin.student_payments')</a></li>
            <li><a href="{{ url( '/admin/trial-classes-statistics-monthly.php' ) }}" title="@lang('admin.statistics_trial_overview')">@lang('admin.statistics_trial')</a></li>
            <li><a href="{{ url( '/admin/stats-sign-up-trial' ) }}" title="@lang('admin.statistics_trial2signup')">@lang('admin.trial2signup')</a></li>
            <li><a href="{{ url( '/admin/stats-student-average-purchase' ) }}" title="@lang('admin.statistics_average_purchase')">@lang('admin.average_purchase')</a></li>
            <li><a href="{{ url( '/admin/enrollment-statistics.php' ) }}" title="@lang('admin.enrollment_statistics')">@lang('admin.enrollment')</a></li>
            <li><a href="{{ url( '/admin/ad-statistics.php' ) }}">@lang('admin.ad_statistics')</a></li>
            <li><a href="{{ url( '/admin/report-sign-up-reason.php' ) }}">@lang('admin.signup_reason')</a></li>
            @if( $isSuperAdmin )
                <li><a href="{{ url( '/admin/cronjobs.php' ) }}">@lang('admin.cron_job_logs')</a></li>
            @endif
        </ul>
    </li>
</ul>