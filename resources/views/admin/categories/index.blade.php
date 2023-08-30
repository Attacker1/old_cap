@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
<div class="float-right">
    <a href="{{ route('admin.catalog.categories.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
    <a href="{{ route('admin.catalog.categories.import') }}"><button class="btn btn-sm btn-warning"><i class="fa fa-file-excel-o"></i> Импорт</button></a>
</div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div id="kt_datatable_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

        <table id="datatable"
               class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline">
            <thead>
            <tr>
                <th>ID</th>
                <th>Наименование</th>
                <th>Родитель</th>
                <th>Характеристики</th>
                <th>Видимость</th>
                <th>Пользователь</th>
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
                order: [[0, "asc"]],
                dom: 'rt',
                processing: true,
                serverSide: true,
                stateSave: false,
                "lengthMenu": [50, 100 ],
                length: 50,
                "language": {
                    "url": "/app-assets/data/dt_ru.json"
                },

                ajax: {
                    url:'{!! route('admin.catalog.categories.index') !!}',
                    method: "POST",
                    data: function(d){
                        // d.range = $('#range').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id',class: 'date w75 invisible d-none '},
                    {data: 'name', name: 'name',},
                    {data: 'parent', name: 'parent',searchable: false, orderable: true},
                    {data: 'attributes', name: 'attributes',searchable: false, orderable: false},
                    {data: 'visible', name: 'visible',searchable: false, orderable: false},
                    {data: 'user', name: 'user', searchable: false, orderable: false},
                    {data: 'action', name: 'action2', searchable: false, orderable: false},

                ],
            });


            $(document).on('change','#city_id, #owner, #range',function () {
                table.draw();
            });

            // $.fn.dataTable.ext.errMode = 'none';


            $( document ).ajaxStart( function() {
                $('.body-block-loaders').removeClass('d-none');  // show Loading Div
            } ).ajaxStop ( function(){
                $('.body-block-loaders').addClass('d-none'); // hide loading div
            });


        });
    </script>

@endsection
