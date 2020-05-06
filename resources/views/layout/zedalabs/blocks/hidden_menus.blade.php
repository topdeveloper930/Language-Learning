@if(auth('student')->check())
    @include('layout.zedalabs.blocks.student_mobile_menu')
@elseif(auth('teacher')->check())
    @include('layout.zedalabs.blocks.teacher_mobile_menu')
@else
    @include('layout.zedalabs.blocks.guest_mobile_menu')
@endif

<div class="hidden-menu-overlay"></div>