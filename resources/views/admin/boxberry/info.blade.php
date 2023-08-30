@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('boxberry.index') }}"><i class="fas fa-chevron-circle-left text-danger"></i></a>
    </div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

            @if($api && is_array($api))
                <div class="timeline timeline-6 mt-12">
                @foreach($api as $key=>$value)
                    <div class="timeline-item align-items-start">
                        <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">{{ @$value['Date'] }}</div>
                        <div class="timeline-badge">
                            <i class="fa fa-genderless @if( @$value['Name'] == 'Принято к доставке')text-primary  @elseif( $value['Name'] == 'Поступило в пункт выдачи') text-success @else text-warning @endif icon-xl"></i>
                        </div>
                        <div class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">{{ @$value['Name'] }}</div>
                    </div>
                @endforeach
                </div>
                @else
                <div class="alert alert-custom alert-outline-danger fade show mb-5" role="alert">
                    <div class="alert-icon">
                        <i class="flaticon-warning"></i>
                    </div>
                    <div class="alert-text">Нет данных по заказу</div>
                </div>
            @endif

    </div>

        <style>
            .timeline.timeline-6 .timeline-item .timeline-label {
                width: 100px !important;
            }
            .timeline.timeline-6:before {
                left: 100.5px !important;
            }
        </style>

@endsection



