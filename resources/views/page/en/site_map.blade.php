@php
    $tplConfig->page_meta_title = 'Site Map Page';
    !empty($language) OR $language = 'spanish';
@endphp

<main>
    <div class="container">

        <div class="page-content mt-lg mb-xxl">
            <header class="mb-lg">
                <h1 class="page-title">Sitemap</h1>
            </header>

            <div class="columns">
                <div class="column">
                    <span class="section-label">Front facing pages</span>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish']) }}">Spanish lessons</a></li>
                            <li><a href="{{  route('language', ['language' => $language, 'controller' => 'costs']) }}">Pricing</a></li>
                            <li><a href="{{  route('language', ['language' => $language, 'controller' => 'tutors']) }}">Tutors</a></li>
                            <li><a href="/pages-website/about.php">About us</a></li>
                            <li><a href="{{  route('language', ['language' => $language, 'controller' => 'courses']) }}">Course</a></li>
                            <li><a href="/pages-website/exams.php">Exam</a></li>
                            <li><a href="{{  route('language', ['language' => $language, 'controller' => 'specialized-courses']) }}">Specialized courses</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish', 'controller' => 'spanish-for-tourists']) }}">Spanish for tourists</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish', 'controller' => 'spanish-for-business']) }}">Spanish for business</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish', 'controller' => 'spanish-for-teachers']) }}">Spanish for teachers</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish', 'controller' => 'spanish-for-medical']) }}">Spanish for medical</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish', 'controller' => 'spanish-for-priests']) }}">Spanish for priests</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish', 'controller' => 'free-resources']) }}">Free resources</a></li>
                            <li><a href="/pages-website/tutor-profile.php">Tutor profile</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish', 'controller' => 'verbs']) }}">Spanish verbs</a></li>
                            <li><a href="/pages-website/spanish-verbs-list.php">Spanish verb list</a></li>
                            <li><a href="/pages-website/spanish-verbs-single.php">Spanish verbs (single)</a></li>
                            <li><a href="{{  route('page', ['controller' => 'blog']) }}">Blog</a></li>
                            <li><a href="{{  route('language', ['language' => 'spanish', 'controller' => 'free-courses']) }}">Free courses</a></li>
                            <li><a href="{{  route('page', ['controller' => 'blog']) }}">Quiz</a></li>
                            <li><a href="/pages-website/quiz-result.php">Quiz result</a></li>
                            <li><a href="{{  route('page', ['controller' => 'staff']) }}">Staff</a></li>
                            <li><a href="{{  route('page', ['controller' => 'faq']) }}">FAQ</a></li>
                            <li><a href="{{  route('page', ['controller' => 'gift-cards']) }}">Gift card</a></li>
                            <li><a href="{{  route('page', ['controller' => 'gift-card-checkout']) }}">Gift card payment</a></li>
                            <li><a href="/pages-website/gift-cards-claim.php">Gift card claim</a></li>
                            <li><a href="{{  route('page', ['controller' => 'affiliates']) }}">Affiliate program</a></li>
                            <li><a href="{{  route('page', ['controller' => 'contact-us']) }}">Contact us</a></li>
                            <li><a href="{{  route('page', ['controller' => 'work-with-us']) }}">Work with us</a></li>
                            <li><a href="/pages-website/work-with-us-single.php">Work with us (single)</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="column">
                    <span class="section-label">Supporting pages</span>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="/pages-website/login.php">Login</a></li>
                            <li><a href="/pages-website/register-free-lesson.php">Register / free lesson</a></li>
                            <li><a href="/pages-website/404.php">404</a></li>
                            <li><a href="/pages-website/terms-conditions.php">Terms &amp; Conditions</a></li>
                            <li><a href="/pages-website/privacy-policy.php">Privacy policy</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="column">
                    <span class="section-label">Student Dashboard</span>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="/pages-dashboard/student-dashboard.php">Dashboard / Lessons</a></li>
                            <li><a href="/pages-dashboard/student-purchase-classes.php">Purchase Classes</a></li>
                            <li><a href="/pages-dashboard/student-schedule-class.php">Schedule Class</a></li>
                            <li><a href="/pages-dashboard/student-profile.php">Profile</a></li>
                            <li><a href="/pages-dashboard/student-settings.php">Settings</a></li>
                            <li><a href="/pages-dashboard/student-activity.php">Activity</a></li>
                            <li><a href="/pages-dashboard/student-progress-reports.php">Progress Reports</a></li>
                            <li><a href="/pages-dashboard/student-progress-report.php">Progress Report</a></li>
                            <li><a href="/pages-dashboard/classroom.php">Classroom</a></li>
                            <li><a href="/pages-dashboard/student-transaction-history.php">Transaction history</a></li>
                            <li><a href="/pages-dashboard/student-assignments.php">Assignments</a></li>
                            <li><a href="/pages-dashboard/student-assignment.php">Assignment</a></li>
                            <li><a href="/pages-dashboard/auto-popup.php">Auto popup</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="column">
                    <span class="section-label">Teacher Dashboard</span>
                    <nav class="nav-menu">
                        <ul>
                            <li><a href="/pages-dashboard/teacher-dashboard.php">Dashboard</a></li>
                            <li><a href="/pages-dashboard/teacher-calendar.php">Calendar</a></li>
                            <li><a href="/pages-dashboard/teacher-my-students.php">My Students</a></li>
                            <li><a href="/pages-dashboard/teacher-balance.php">Balance sheet / payouts</a></li>
                            <li><a href="/pages-dashboard/teacher-profile.php">Profile</a></li>
                            <li><a href="/pages-dashboard/teacher-training-videos.php">Training videos</a></li>
                            <li><a href="/pages-dashboard/teacher-student-evaluation.php">Evaluation</a></li>
                            <li><a href="/pages-dashboard/teacher-student-profile.php">Student profile</a></li>
                            <li><a href="/pages-dashboard/teacher-resources.php">Resources</a></li>
                            <li><a href="/pages-dashboard/classroom.php">Classroom</a></li>
                            <li><a href="/pages-dashboard/teacher-assignments.php">Assignments</a></li>
                            <li><a href="/pages-dashboard/teacher-assignment-editor.php">Assignment editor</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>

    </div>
</main>