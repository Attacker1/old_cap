@if(config('app.env') == 'local') {{--Локальная разработка--}}

    @if($css) {{-- основные стили --}}
        @foreach($css as $key=>$value)
            <link rel="stylesheet" href="{{ mix($value) }}"/>
        @endforeach
    @endif

    @if(!empty($custom)) {{-- кастомные включения --}}
        @foreach($custom as $key=>$value)
            {!! $value !!}
        @endforeach
    @endif

@else {{-- Продакшн --}}

    @if($css)
        @foreach($css as $key=>$value)
            <link rel="stylesheet" href="{{ asset($value) }}"/>
        @endforeach
    @endif

    @if(!empty($custom))
        @foreach($custom as $key=>$value)
            {!! $value !!}
        @endforeach
    @endif
@endif