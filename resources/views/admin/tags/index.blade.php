@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('manage.tags.add') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
    </div>
@endsection

@section('styles')
    <style>
        .tag_color {
            position: relative;
        }
    </style>
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
                        <th>Название</th>
                        <th>Цвет</th>
                        <th>Тип</th>
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
                stateSave: false,
                "lengthMenu": [50, 100 ],
                length: 50,
                "language": {
                    "url": "/app-assets/data/dt_ru.json"
                },

                ajax: {
                    url:'{!! route('manage.tags.index') !!}',
                    method: "POST",
                    data: function(d){
                        // d.range = $('#range').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id',class: 'date w75'},
                    {data: 'name', name: 'name',},
                    {data: 'role', name: 'role', class: 'tag_color', searchable: false, orderable: false},
                    {data: 'type', name: 'type'},
                    {data: 'action', name: 'action2', searchable: false, orderable: false},

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
