@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection
@section('breadcrumb'){{ $title }}@endsection


@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <form action="{{ route('analytics.pr-report.index') }}" method="post">
                {{ csrf_field() }}
                <div class="row">
                    <div class="input-group col-lg-4 col-sm-4">
                        <div class="input-group-prepend" >
                            <span class="input-group-text" style="font-size: 1.1rem; font-weight:bold">дата с:</span>
                        </div>

                        <input data-date-format="dd-mm-yyyy" name="date_report_from"
                               placeholder="Дата с" id="date-report-from"
                               style="border-color: #0c0e1a; max-width: 110px; min-width: 110px; font-size: 1.1rem"
                               class="form-group form-control" value="{{ $date_report_from }}">
                    </div>

                    <div class="input-group col-lg-4 col-sm-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size: 1.1rem; font-weight:bold">дата по:</span>
                        </div>

                        <input data-date-format="dd-mm-yyyy" name="date_report_to"
                               placeholder="Дата с" id="date-report-from"
                               style="border-color: #0c0e1a; max-width: 110px; min-width: 110px; font-size: 1.1rem"
                               class="form-group form-control" value="{{ $date_report_to }}">
                    </div>

                    <div class="input-group col-sm-12 mt-10">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="font-size: 1.1rem; font-weight:bold">купон:</span>
                        </div>

                        <input name="coupon"
                               style="border-color: #0c0e1a; max-width: 200px; min-width: 200px; font-size: 1.1rem"
                               class="form-group form-control">
                    </div>

                    <div class="input-group col-sm-12 mt-10">
                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Сформировать</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>

    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/datetimepicker/jquery.datetimepicker.css')}}"/>
    <script src="{{asset('app-assets/js/scripts/datetimepicker/jquery.datetimepicker.full.js')}}"></script>
    <script>
        $(function () {

            $('#date-report-from, #date-report-to').datepicker({
                language: 'ru',
                monthNames : ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                dayNamesMin : ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                autoclose: true,
                format: 'dd-mm-yyyy',
            });
        });
    </script>

@endsection
