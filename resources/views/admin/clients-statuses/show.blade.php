@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection


@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
    <div class="row">
        <div class="col-12 row">

            <div class="col-sm-12 col-lg-6 mb-3">
                <div class="row">
                    <div class="col-sm-3 col-lg-4 mb-1"><i class="feather icon-user mr-1"></i><b>ФИО</b></div>
                    <div class="col-sm-9 col-lg-8 mb-1"><h4>{{ $clientData->name }} {{ $clientData->second_name }}</h4></div>
                </div>

                <div class="row">
                    <div class="col-sm-3 col-lg-4 mb-1"><i class="fa fa-phone mr-1" aria-hidden="true"></i><b>Телефон</b></div>
                    <div class="col-sm-9 col-lg-8 mb-1"><h4 class="client-phone">{{ $clientData->phone }}</h4></div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-3 col-lg-4 mb-1"><i class="fa fa-envelope-o mr-1" aria-hidden="true"></i><b>E-mail</b></div>
                    <div class="col-sm-9 col-lg-8 mb-1">{{ $clientData->email }}</div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-6">

                <div class="row small">
                    <div class="col-sm-3 col-lg-4 mb-1">Дата создания</div>
                    <div class="col-sm-9 col-lg-8 mb-1">{{ $clientData->created_at }}</div>
                </div>

                <div class="row small">
                    <div class="col-sm-3 col-lg-4 mb-1">Дата изменения</div>
                    <div class="col-sm-9 col-lg-8 mb-1">{{ $clientData->updated_at }}</div>
                </div>

                <div class="row small mb-2">
                    <div class="col-sm-3 col-lg-4 mb-1">ID</div>
                    <div class="col-sm-9 col-lg-8 mb-1">{{ $clientData->uuid }}</div>
                </div>
            </div>
            
            <a href="{{route('clients.edit', $clientData->uuid)}}" class="btn btn-primary waves-effect mr-2">Изменить</a>
            <a  
                data-route-destroy = "{{route('clients.destroy', $clientData->uuid)}}"
                data-delete-form-csrf =  "{{ csrf_token() }}" 
                data-name = "{{ $clientData->name }} {{ $clientData->second_name }}"
                class="btn btn-outline-danger ml-1 waves-effect text-danger mr-2 modal-client-delete">Удалить</a>

            <a href="{{route('clients.list')}}" class="btn btn-outline-secondary text-bold text-dark waves-effect">К списку</a>

        </div>
    </div>
    </div>
    </div>

@endsection

@section('scripts') 
<script>
            
    $(window).on('load', function() {
        let str = $('.client-phone').html();
        if(str.indexOf('_') == -1) {
            let new_str = [
                '+', 
                str[0], 
                ' (', str.substr(1,3), ') ', 
                str.substr(4,3), 
                '-',  
                str.substr(4,2), 
                '-', 
                str.substr(4,2)];

            $('.client-phone').html(new_str);
        } 
    });

    $('.modal-client-delete').on('click', function(){
                let url = $(this).data('route-destroy');
                swal.fire({
                    text: 'Вы действительно хотите удалить клиента ' + $(this).data('name') + '?',
                    showCancelButton: true,
                    confirmButtonColor: '#28c76f',
                    cancelButtonColor: '#aeaad2',
                    confirmButtonText: 'Удалить'
                }).then(function (result) {
                    if (result.value===true) {
                        let form = $('<form action="' + url + '" method="post"><input type="hidden" name="_token" value="' + $('.modal-client-delete').data('delete-form-csrf') + '"> "<input type="hidden" name="_method" value="delete"></form>');
                        $('body').append(form);
                        form.submit();
                    }
                });

            });
            
</script>
@endsection
