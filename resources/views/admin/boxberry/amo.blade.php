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
        <div class="input-group mb-6 col-sm-4">
            <form name="form" method="post" action="{{ route('boxberry.amo') }}" target="_blank">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Провести 1 сделку из списка
                </button>
            </form>
        </div>

            @if($api && is_array($api))
                <div class="row">
                @foreach($api as $key=>$value)
                    @if(!empty($value['delivery_point_id']))
                        <div class="col-sm-4 border-right">
                        <div class="d-flex flex-wrap align-items-center mb-10">
                            <div class="d-flex flex-column ml-4 flex-grow-1 mr-2">
                                <a href="https://thecapsula.amocrm.ru/leads/detail/{{ @$value['id'] }}"
                                   class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1"
                                   target="_blank"><i class="fas fa-external-link-alt text-primary mr-3"></i> {{ @$value['id'] }}</a>
                                <span class="text-muted font-weight-bold">Клиент: {{ @$value['name'] }} </span>
                                <span class="text-muted font-weight-bold">Телефон: {{ @$value['phone'] }} </span>
                                <span class="text-muted font-weight-bold">ПВЗ: {{ @$value['delivery_point_id'] }} </span>
                                <span class="text-muted font-weight-bold">Адрес: {{ @$value['delivery_address'] }} </span>
                                @if(!empty($value['uuid']))
                                    <a href="{{ route('leads.edit',$value['uuid']) }}" target="_blank"><span class="text-success font-weight-bold">Сделка есть в нашей системе</span></a>
                                @else
                                    <span class="text-success font-weight-bold">НЕТ в нашей системе</span>
                                @endif
                            </div>
                        </div>
                        </div>
                    @endif
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



