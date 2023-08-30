@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('scripts')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.0/tinymce.min.js" integrity="sha512-XaygRY58e7fVVWydN6jQsLpLMyf7qb4cKZjIi93WbKjT6+kG/x4H5Q73Tff69trL9K0YDPIswzWe6hkcyuOHlw==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: '#content',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
            });
        });
    </script>
@endsection


@section('content')
    <div class="card card-custom gutter-b example example-compact">
        <div class="header">
        </div>
    	<div class="card-body">
            
            @if(auth()->guard('admin')->user()->can('viewing-anketa-list-own'))
    		<div class="col-10 form-group">
                {{-- Для стилистов --}}
			    <form name="form1" method="post" action="{{ route('anketa.update', $anketaData->uuid) }}">
                    @method('PUT')
			        {{ csrf_field() }}
			        <h5>Комментарий стилиста</h5>
			        <textarea id="content" name="content" class="form-control" rows="12" required="required">
			            {{$anketaComments->content ?? ''}} 
			        </textarea>
			        
			        <input type="submit" value="Сохранить" class="mt-3 btn btn-info font-weight-bolder font-size-sm">
                    <a href="{{route('anketa.show', $anketa_uuid)}}" class="btn btn-success font-weight-bolder font-size-sm mt-3 ml-3 " target="blank">Просмотр анкеты</a>
                    <a href="{{route('admin.anketa.list.fill')}}" class="btn btn-secondary font-weight-bolder font-size-sm mt-3 ml-3 ">К списку</a>
			    </form>
            </div>
            @endif

            @if(auth()->guard('admin')->user()->can('manage-anketa'))
            <div class="col-6 form-group">
                {{-- Для менеджеров --}}
                <form name="form2" method="post" action="{{ route('anketa.update', $anketaData->uuid) }}">
                    @method('PUT')
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Назначить стилиста</label>
                        <select name="stylists" class="form-control" required="required">
                            <option selected= "selected" disabled> Стилист не выбран </option>
                            @foreach($stylistsData as $stylistData)
                                <option 
                                    value="{{ $stylistData->id }}" 
                                    @if($stylistData->id == $stylistSelected) selected = "selected" @endif>{{ $stylistData->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Комментарий менеджера</label>
                        <textarea id="content" name="manager_comment"  class="form-control">{{$anketaData->manager_comment}}</textarea>
                    </div>
                        
                    <input type="submit" value="Сохранить" class="mt-3 btn btn-info font-weight-bolder font-size-sm">
                    <a href="{{route('anketa.show', $anketa_uuid)}}" class="btn btn-success font-weight-bolder font-size-sm mt-3 ml-3 " target="blank">Просмотр анкеты</a>
                    <a href="{{route('admin.anketa.list.fill')}}" class="btn btn-secondary font-weight-bolder font-size-sm mt-3 ml-3 ">К списку</a>
                </form>
			</div>
            @endif
    	</div>
    </div>
@endsection

