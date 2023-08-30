@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
<div class="float-right">
    <a href="{{ route('notes.list.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
</div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div id="kt_datatable_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
    <div class="row filters">

        <div class="input-group ml-1 col-sm-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="name_label">AMO ID</span>
            </div>
            <input type="text" name="order_id"  placeholder="фильтр ID" id="order_id" value=""
                   class="form-control " autocomplete="off" required="" aria-label="" aria-describedby="name_label">
        </div>

        <div class="input-group col-sm-2 offset-1" style="z-index: 9999">
            <input data-date-format="yyyy-mm-dd"  placeholder="фильтр дате" class="form-group form-control" id="datepicker" autocomplete="off">
            <div class="input-group-append">
                <button type="button" class="btn btn-sm btn-outline-primary waves-effect" id="reset_date" title="Сбросить фильтр по дате"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
        </div>

        <div class="col-sm-3 offset-1">
            <select name="stylist_id" id="stylist_id" class="form-control bootstrap-select">
                <option value="" selected >Стилист не выбран</option>
                @if(!empty($stylists))
                    @foreach($stylists as $k=>$v)
                        <option value="{{$k }}">{{ @$v }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="table table-responsive">
        <table id="datatable"
               class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline">
            <thead>
            <tr>
                <th>ID</th>
                <th class="text-body bold"><b>Дата создания</b></th>
                <th>Order ID (AMO)</th>
                <th>Пользователь</th>
                <th>Роль в проекте</th>
                <th>Статус</th>
                <th>Действие</th>
            </tr>
            </thead>
        </table>

    </div>
    </div>
    </div>
    </div>

@endsection

@section('scripts')
    <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
    <script src="/m/plugins/custom/datatables/datatables.bundle.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/locales/bootstrap-datepicker.ru.min.js" integrity="sha512-tPXUMumrKam4J6sFLWF/06wvl+Qyn27gMfmynldU730ZwqYkhT2dFUmttn2PuVoVRgzvzDicZ/KgOhWD+KAYQQ==" crossorigin="anonymous"></script>


    <script>
        $(function () {

            var table = $('#datatable').DataTable({
                order: [[0, "desc"]],
                dom: 'rtp',
                processing: true,
                serverSide: true,
                stateSave: true,
                "lengthMenu": [50, 100 ],
                length: 50,
                "language": {
                    searchPlaceholder: "Поиск по AMO ID",
                    "url": "/app-assets/data/dt_ru.json",
                },

                ajax: {
                    url:'{!! route('notes.list') !!}',
                    method: "POST",
                    data: function(d){
                        d.date = $('#datepicker').val();
                        d.order_id = $('#order_id').val();
                        d.stylist_id = $('#stylist_id').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id',class: '',searchable: false, orderable: true},
                    {data: 'created_at', name: 'created_at',searchable: false, class: 'text-danger small'},
                    {data: 'order_id', name: 'order_id',searchable: true, orderable: true},
                    {data: 'user', name: 'user',searchable: false, orderable: false},
                    {data: 'role', name: 'role',searchable: false, orderable: false},
                    {data: 'state', name: 'state', searchable: false, orderable: false},
                    {data: 'action', name: 'action',searchable: false, orderable: false},
                ],
            });

            $( document ).ajaxStart( function() {
                $('.body-block-loaders').removeClass('d-none');  // show Loading Div
            } ).ajaxStop ( function(){
                $('.body-block-loaders').addClass('d-none'); // hide loading div
            });

            $('#datepicker').datepicker({
                language: 'ru',
                weekStart: 1,
                daysOfWeekHighlighted: "6,0",
                autoclose: true,
                todayHighlight: true,
            });

            $(document).on('change', '#datepicker,#stylist_id', function (e) {
                table.draw();
            });

            $(document).on('click', '#reset_date', function (e) {
                $('#datepicker').val('');
                table.draw();
            });

            $("#order_id").on('keyup',function() {
                setTimeout(redraw, 500);
            });

            function redraw() {
                table.draw();
            }

            // $(document).on('click', '#filter_by_id', function (e) {
            //
            // });
        });
    </script>

@endsection
