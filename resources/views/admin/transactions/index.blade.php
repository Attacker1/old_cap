@extends('admin.main')

@section('title'){{ $title }}@endsection
@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('bonuses.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
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
                <th>Клиент</th>
                <th>Бонусы</th>
                <th>Промокод</th>
                <th>Управление</th>
            </tr>
            </thead>
        </table>
    </div>
    </div>

@endsection

@section('scripts')
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
                    "url": "/app-assets/data/dt_ru.json"
                },

                ajax: {
                    url:'{!! route('bonus.transactions.index') !!}',
                    method: "POST",
                    data: function(d){

                    }
                },
                columns: [
                    {data: 'id', name: 'id', class:'small',searchable: false},
                    {data: 'client', name: 'client', class:'small'},
                    {data: 'points', name: 'points', class:'small'},
                    {data: 'promocode', name: 'promocode', class:'small',searchable: false},
                    {data: 'action', name: 'action',  class:'small', searchable: false, orderable: false},

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
