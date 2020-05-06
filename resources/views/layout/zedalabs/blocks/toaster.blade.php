@php($haveToasts = false)

@if($errors->any())
    @foreach( $errors->all() as $error )
        @push('toasts')
            <div class="toast caution">{!! $error !!}</div>
        @endpush
    @endforeach
    @php($haveToasts = true)
@endif

@if(Session::has('notification'))
    @push('toasts')
        <div class="toast {{ Session::get('notification-class')  }}">{!! Session::get('notification') !!}</div>
    @endpush
    @php($haveToasts = true)
@endif

@if($haveToasts)
    <div class="toaster">
        @stack('toasts')
    </div>
    <script>
        document.querySelectorAll('.toast').forEach(function(t){
            t.addEventListener('animationend', e => t.parentNode.removeChild(t), false);
        });
    </script>
@endif