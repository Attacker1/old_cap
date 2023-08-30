@extends('admin.main')

@section('title'){{ $title }}@endsection

@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endsection

@section('content')

    <div class="table table-responsive">

        <table id="datatable"
               class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline">
            <thead>
            <tr>
                <th>ID</th>
                <th>Lead ID</th>
                <th>Клиент ID</th>
                <th>Дата</th>
                <th>Действие</th>
            </tr>
            </thead>

            @foreach($feedbackData as $data)
                <tr>
                    <th>{{$data->id}}</th>
                    <th>{{$data->lead_id}}</th>
                    <th>{{$data->client_uuid}}</th>
                    <th>{{$data->created_at}}</th>
                    <th>
                        <a href="{{route('feedback.show', $data->id)}}">
                            <button class="btn-sm btn-outline-primary mr-1 mb-1">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </a>
                        @if(auth()->guard('admin')->user()->can('destroy-feedback-quizes'))
                            <button type="button" class="btn-sm btn-outline-primary text-danger" data-toggle="modal" data-target="#deleteModal" data-id = "{{$data->id}}">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        @endif
                    </th>
                </tr>
            @endforeach

        </table>
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
        <a data-url= "{{route('feedback.delete', '')}}" id = "button-delete" onclick="event.preventDefault(); document.getElementById('delete-form').submit();">
            <button type="button" class="btn btn-secondary">Удалить</button>
        </a>
          <form id="delete-form" action="" method="POST" style="display: none;">
              {{ csrf_field() }}
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
                Modal.find('.modal-body').html('Вы действительно хотите удалить анкету id=' + $(this).attr('data-id') + '?');
                var link = Modal.find('#button-delete').attr('data-url');
                Modal.find('#delete-form').attr('action', link +'/'+ $(this).attr('data-id'));
            });
        });
    </script>
@endsection
