@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
<div class="float-right">
    <a href="{{ route('coupon.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Новый</button></a>
    <a href="{{ route('coupon.import') }}"><button class="btn btn-sm btn-primary"><i class="fa fa-file-excel-o"></i> Импорт купонов</button></a>
    <a href="{{ route('coupon.export') }}"><button class="btn btn-sm btn-warning"><i class="fa fa-file-excel-o"></i> Экспорт купонов</button></a>
</div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div id="kt_datatable_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

        <table id="datatable"
               class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline">
            <thead>
            <tr>
                <th>ID</th>
                <th>Наименование</th>
                <th>Тип</th>
                <th>Стоимость</th>
                <th>Действие</th>
            </tr>
            </thead>
        </table>
    </div>
    </div>
    </div>

@endsection

@section('scripts')
    <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
    <script src="/m/plugins/custom/datatables/datatables.bundle.js"></script>
    <script>
        $(function () {

            var table = $('#datatable').DataTable({
                order: [[0, "desc"]],
                dom: 'frtlp',
                processing: true,
                serverSide: true,
                stateSave: true,
                "lengthMenu": [50, 100 ],
                length: 50,
                "language": {
                    searchPlaceholder: "Поиск купона",
                    "url": "/app-assets/data/dt_ru.json",
                },

                ajax: {
                    url:'{!! route('coupon.index') !!}',
                    method: "POST",
                    data: function(d){
                        // d.range = $('#range').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id',class: ''},
                    {data: 'name', name: 'name',},
                    {data: 'params', name: 'params',},
                    {data: 'custom', name: 'custom',searchable: false, orderable: false},
                    {data: 'action', name: 'action',searchable: false, orderable: false},

                ],
            });

            $( document ).ajaxStart( function() {
                $('.body-block-loaders').removeClass('d-none');  // show Loading Div
            } ).ajaxStop ( function(){
                $('.body-block-loaders').addClass('d-none'); // hide loading div
            });


        });
    </script>

@endsection
