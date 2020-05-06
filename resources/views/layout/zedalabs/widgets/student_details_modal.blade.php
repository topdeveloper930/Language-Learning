<div class="text-align-left">

    <b>@lang('teacher_dashboard.student.age_range')</b> {{ $student->ageRange }}<br>

    @if($student->currentLevel)
        <b>@lang('teacher_dashboard.student.course')</b> {{ $student->course }}<br>
        <b>@lang('teacher_dashboard.student.Level')</b> {{ $student->currentLevel }}<br>
    @endif

    <b>@lang('teacher_dashboard.student.email')</b> <a href="mailto:{{ $student->email }}">{{ $student->email }}</a><br>
    <b>@lang('teacher_dashboard.student.skype')</b> <a href="skype:{{ $student->skype }}">{{ $student->skype }}</a><br>
    <b>@lang('teacher_dashboard.student.location')</b> {{ $student->city }}, {{ $student->country }}<br>
    <b>@lang('teacher_dashboard.student.timezone')</b> {{ $student->timezone }}<br><br>

    @isset($studentTime)
        <b>@lang('teacher_dashboard.student.studentDT')</b> {{ $studentTime }}<br>
        <b>@lang('teacher_dashboard.student.details')</b> {{ $student->comments }}<br>
        <cite>@lang('teacher_dashboard.student.review')</cite>
    @endisset

</div>