@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('clients-statuses.create') }}"><button class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Добавить</button></a>
    </div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">

    <div class="table table-responsive">

        <table id="datatable"
               class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline">
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Действие</th>
            </tr>
            </thead>

            @foreach($statusesData as $data)
                <tr>
                    <th>{{$data->id}}</th>
                    <th>{{$data->name}}</th>
                    <th>
                        <a href="{{route('clients-statuses.edit', $data->id)}}">
                                <i class=" ml-3 fa far fa-edit text-primary" aria-hidden="true"></i>
                        </a>

                        <a data-toggle="modal" data-target="#deleteModal" data-name = "{{$data->name}}" data-id = "{{$data->id}}">

                            <i class="ml-3  fa far fa-trash-alt text-primary" aria-hidden="true"></i>
                        </a>

                    </th>
                </tr>
            @endforeach

        </table>
    </div>
    </div>
    </div>
    <!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <a data-url= "{{route('clients-statuses.destroy', '')}}" id = "button-delete" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">
            <button type="button" class="btn btn-secondary">Удалить</button>
        </a>
          <form id="delete-form" action="{{route('clients-statuses.destroy', '')}}" method="POST" style="display: none;">
              {{ csrf_field() }}
              @method('delete')
          </form>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Выход</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(window).on('load', function() {
            $('[data-target="#deleteModal"]').on('click', function(){
                var Modal = $('#deleteModal');
                Modal.find('.modal-body').html('Вы действительно хотите удалить статус ' + $(this).attr('data-name') + '?');
                var link = Modal.find('#button-delete').attr('data-url');
                Modal.find('#delete-form').attr('action', link +'/'+ $(this).attr('data-id'));
            });
        });
    </script>
@endsection
