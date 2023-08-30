@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
<div class="float-right">
    <a href="{{ route('quantity.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
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
                <th>Артикул</th>
                <th>Наименование Товара</th>
                <th>Категория</th>
                <th>Бренд</th>
                <th>Кол-во на складе</th>
                <th>Пользователь</th>
                <th>Управление</th>
            </tr>
            </thead>
        </table>
    </div>
    </div>
    </div>

@endsection

@section('scripts')

    <style>
        .filloption {
            float: left;
            border: 1px solid lightgrey;
            cursor: pointer;
            margin: 0 5px;
            max-height: 20px;
            max-width: 20px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
    <script src="/m/plugins/custom/datatables/datatables.bundle.js"></script>
    <script>
        $(function () {

            var table = $('#datatable').DataTable({
                order: [[0, "asc"]],
                dom: 'frtpl',
                processing: true,
                serverSide: true,
                stateSave: false,
                "lengthMenu": [50, 100 ],
                length: 50,
                "language": {
                    "url": "/app-assets/data/dt_ru.json"
                },

                ajax: {
                    url:'{!! route('quantity.index') !!}',
                    method: "POST",
                },
                columns: [
                    {data: 'id', name: 'id', },
                    {data: 'sku', name: 'sku', },
                    {data: 'product', name: 'product',},
                    {data: 'category', name: 'category',searchable: false,orderable:false},
                    {data: 'brand', name: 'brand',searchable: false,orderable:false},
                    {data: 'amount', name: 'amount',searchable: false, orderable:false},
                    {data: 'user', name: 'user',searchable: false,orderable:false},
                    {data: 'action', name: 'action2',searchable: false, orderable:false, class:'w-15'},

                ],
            });

            $( document ).ajaxStart( function() {
                $('.body-block-loaders').removeClass('d-none');
            } ).ajaxStop ( function(){
                $('.body-block-loaders').addClass('d-none');
            });


        });
    </script>

@endsection
