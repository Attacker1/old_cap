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
                <div class="row">
                @foreach($api as $key=>$value)
                        <div class="col-sm-4 border-right">
                        <div class="d-flex flex-wrap align-items-center mb-10">
                            <div class="d-flex flex-column ml-4 flex-grow-1 mr-2">
                                <a href="https://thecapsula.amocrm.ru/leads/detail/{{ @$value['ID'] }}"
                                   class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1"
                                   target="_blank"><i class="fas fa-external-link-alt text-primary mr-3"></i> {{ @$value['ID'] }}</a>
                                <span class="text-muted font-weight-bold">Сумма доставки: {{ @$value['Delivery_sum'] }} </span>
                            </div>
                            <!--end::Title-->
                            <!--begin::btn-->
                            <span class="label label-lg @if($value['Status'] == 'На отделении-получателе')label-light-success @else label-light-primary @endif label-inline mt-lg-0 mb-lg-0 my-2 font-weight-bold py-4">{{ @$value['Status'] }}</span>
                            <!--end::Btn-->
                        </div>
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



