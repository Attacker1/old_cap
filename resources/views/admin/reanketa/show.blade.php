@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('content')
    <div class="card card-custom col-12 mb-6">
        <div class="card-body pt-4">

            {{--@if(env('APP_ENV')!='production')
                @foreach($anketas as $key => $anketa)
                    <a href="{{ route('admin.reanketa.show.test', $key) }}" target="_blank">{{ $key }}</a><br>
                @endforeach
                <a href="{{ route('admin.reanketa.show.testQuestions') }}" target="_blank">Вопросы</a><br><br>
            @endif--}}

            @include('admin.reanketa.show.top')

            @if($anketas)

                @include('admin.reanketa.show.block-strogie-net')
                @include('admin.reanketa.show.block-params')
                @include('admin.reanketa.show.block-capsula-style')
                @include('admin.reanketa.show.obrasy-colors')
                @include('admin.reanketa.show.block-history')
            @else <b>нет данных по анкете</b>
            @endif

        </div><!--card-body-->
    </div><!--card-->

    {{--@include('admin.reanketa.comments')--}}
@endsection

@section('styles')
<style>
.anketa-head {
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 3px;
}

.question-answer-wrapper {
    background-color: #f6f6f9;
    border-radius: 3px;
    padding: 10px 10px 20px 10px;
    margin-top: 10px;
    min-width: 350px;
}

.question-answer-wrapper .top-0 {
    margin-top: 0px;
}

.question-answer-item {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    margin-top: 10px;
}

.anketa-question {
    font-weight: bold;
    flex-basis: 40%;
}

.anketa-answer {
    flex-basis: 60%;
    text-align: right;
}

.image {
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
    width: 50px;
    height: 50px;
}

.anketa-colors {
    width: 50px;
    height: 50px;
}

.reference-col {
    position: relative;
}
.reference-col p {
    padding: 10px;
}
.anketa-tab-photo-delete {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 22px;
}
.anketa-tab-reference-delete {
    position: absolute;
    top: 7px;
    right: 20px;
    font-size: 22px;
    display: flex;
}
</style>

@endsection