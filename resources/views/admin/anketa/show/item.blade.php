<div class="question-answer-item @isset($class) {{$class}} @endisset">
    <span class="anketa-question">{{$label}}</span>
    @if(!empty($question))
    <div class="anketa-answer">

        {!! @$question !!}

    </div>
    @endif
</div>
