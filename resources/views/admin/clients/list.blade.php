@extends('admin.main')

@section('title'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('clients.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
    </div>
@endsection


@section('content')

    <div class = "container" id = "clients-app" >

        <div class="table table-responsive">
            <table 

                id="datatable"  
                style="padding:0px" 
                class="no-footer table-hover table-striped table-bordered dtr-inline order-column"
                data-params-order-column = "{{$datatable_params['order_column']}}"
                data-params-order-dir = "{{$datatable_params['order_dir']}}"
                data-params-search-value ="{{$datatable_params['search_value']}}"
                data-params-limit-menu = "{{$datatable_params['limit_menu']}}"
                data-params-paging-start = "{{$datatable_params['paging_start']}}"
                data-route-fill-data = "{{ route('admin.clients.list.fill') }}"
                data-route-show = "{{route('clients.show', '')}}"
                data-route-edit = "{{route('clients.edit', '')}}"
                data-route-destroy = "{{route('clients.destroy', '')}}"
                data-route-reset-datatable-settings = "{{route('admin.clients.list.reset-datatable-settings')}}"
                data-delete-form-csrf =  "{{ csrf_token() }}">
                
                <thead>
                <tr>
                    <td class="font-weight-bolder"><div style="width: 150px">Действия</div></td>
                    <td class="font-weight-bolder">ID</td>
                    <td class="font-weight-bolder">Реферальный код</td>
                    <td class="font-weight-bolder">Имя</td>
                    <td class="font-weight-bolder">Фамилия</td>
                    <td class="font-weight-bolder">Телефон</td>
                    <td class="font-weight-bolder">e-mail</td>
                    <td class="font-weight-bolder">Комментарии</td>
                    <td class="font-weight-bolder">Статус</td>
                    <td class="font-weight-bolder">Дата создания</td>
                    <td class="font-weight-bolder">Дата редактирования</td>
                </tr>
                </thead>
            </table>
        </div>
    @endsection

@section('scripts')

<script src="/js/style-datatable.js"></script>
<script src="/js/clients-search-datatable.js"></script>

@endsection
