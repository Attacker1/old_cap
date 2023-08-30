@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('category.translator.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
    </div>
@endsection

@section('content')

    <div class="card card-custom gutter-b col-sm-8 offset-2">
        <div class="card-body">
            <div id="kt_datatable_2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

        <table id="datatable"
               class="table table-separate table-head-custom table-checkable dataTable no-footer dtr-inline">
            <thead>
            <tr>
                <th>ID</th>
                <th>Наименование в МС</th>
                <th>ЛК</th>
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
                order: [[1, "asc"]],
                dom: 'frtlp',
                processing: true,
                serverSide: true,
                stateSave: false,
                "lengthMenu": [100],
                length: 100,
                "language": {
                    "url": "/app-assets/data/dt_ru.json"
                },

                ajax: {
                    url:'{!! route('category.translator.index') !!}',
                    method: "GET",
                    data: function(d){
                        // d.range = $('#range').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id',class: 'date w75 invisible d-none '},
                    {data: 'ms_name', name: 'ms_name',},
                    {data: 'cap_name', name: 'cap_name',searchable: false, orderable: false},
                    {data: 'action', name: 'action',searchable: false, orderable: false},

                ],
            });

            $( document ).ajaxStart( function() {
                $('.body-block-loaders').removeClass('d-none');  // show Loading Div
            } ).ajaxStop ( function(){
                $('.body-block-loaders').addClass('d-none'); // hide loading div
            });

           $(document).on('click','.delete-item',function () {
               var result = confirm("Удалить запись?");
               if (result==true) {
                   $.ajax({
                       url: $(this).attr("data-url"),
                       type:"Post",
                       data: {'_method':'delete'},
                       success:function($msg){
                           table.draw();
                       }
                   });
               }
           });

        });
    </script>

@endsection
