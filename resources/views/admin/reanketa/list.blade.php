@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('content')

    <div class = "card">
        <div class="card-body">
            <div class="table table-responsive datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded">
                <table
                    id="datatable"
                    class="no-footer order-column"
                    data-params-order-column = "{{$datatable_params['order_column']}}"
                    data-params-order-dir = "{{$datatable_params['order_dir']}}"
                    data-params-search-value ="{{$datatable_params['search_value']}}"
                    data-params-limit-menu = "{{$datatable_params['limit_menu']}}"
                    data-params-paging-start = "{{$datatable_params['paging_start']}}"
                    data-route-fill-data = "{{ route('admin.reanketa.list.fill') }}"
                    data-route-show = "{{route('reanketa.show', '')}}"
                    data-route-edit = "{{route('reanketa.edit', '')}}"
                    data-route-destroy-user-can = @if(auth()->guard('admin')->user()->can('manage-anketa')) can @endif
                    data-route-destroy = "{{route('reanketa.destroy', '')}}"
                    data-route-reset-datatable-settings = "{{route('admin.anketa.list.reset-datatable-settings')}}"
                    data-csrf =  "{{ csrf_token() }}">

                    <thead>
                    <tr>
                        <td class="font-weight-bolder"><div style="width: 150px">Действия</div></td>
                        <td class="font-weight-bolder" style= "cursor: pointer;">UUID</td>
                        <td class="font-weight-bolder" style= "cursor: pointer">Кол-во</td>
                        <td class="font-weight-bolder" style= "cursor: pointer">created_at</td>
                        <td class="font-weight-bolder" style= "cursor: pointer">updated_at</td>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="/js/style-m-datatable.js"></script>
    <script src="/js/anketa-search-datatable.js"></script>
@endsection

@section('styles')
    <link href="{{ asset('m/css/pages/anketa/anketa-datatable.css') }}" rel="stylesheet" type="text/css"/>
@endsection
