@if(config('app.env') == 'local') {{--Локальная разработка--}}

    @if(!empty($js)) {{-- основные --}}
        @foreach($js as $key=>$value)
            <script src="{{ mix($value) }}"></script>
        @endforeach
    @endif

    @if(!empty($custom)) {{-- кастомные включения --}}
        @foreach($custom as $key=>$value)
            {!! $value !!}
        @endforeach
    @endif

@else {{-- Продакшн --}}

    @if(!empty($js))
        @foreach($js as $key=>$value)
            <script src="{{ asset($value) }}"></script>
        @endforeach
    @endif

    @if(!empty($custom))
        @foreach($custom as $key=>$value)
            {!! $value !!}
        @endforeach
    @endif
@endif