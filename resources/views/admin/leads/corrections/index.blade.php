@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
            <a href="{{ route('lead.corrections.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить коррекцию</button></a>
    </div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div id="kt_datatable_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                <div class="table table-responsive">
                    <table id="datatable"
                           class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата</th>
                            <th>ID сделки</th>
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
            <link rel="stylesheet" type="text/css" href="/m/plugins/custom/datatables/datatables.bundle.css">
            <script src="/m/plugins/custom/datatables/datatables.bundle.js"></script>
            <script>
                $(function () {

                     var table = $('#datatable').DataTable({
                        order: [[1, "desc"]],
                        dom: 'rtp',
                        processing: true,
                        serverSide: true,
                        stateSave: false,
                        "lengthMenu": [50, 100],
                        length: 50,
                        "language": {
                            "url": "/app-assets/data/dt_ru.json"
                        },

                        ajax: {
                            url: '{!! route('lead.corrections.index') !!}',
                            method: "GET",
                            data: function (d) {
                                // d.client = $('#client').val();
                            }
                        },
                        columns: [
                            {data: 'id', name: 'id', class: 'date w75', class: 'small'},
                            {data: 'created_at', name: 'created_at', class: 'text-danger  small'},
                            {data: 'amo_id', name: 'amo_id', class: 'small'},
                            {data: 'user_name', name: 'user_name', class: 'small'},
                            {data: 'action', name: 'action', searchable: false, orderable: false, class: 'small'},

                        ],
                    });

                });
            </script>

@endsection
