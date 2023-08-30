@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('breadcrumb'){{ $title }}@endsection

@section('actions')
    <div class="float-right">
        <a href="{{ route('leads.list') }}"><i class="fas fa-chevron-circle-left text-danger"></i></a>
    </div>
@endsection

@section('content')

    <div class="card card-custom gutter-b">
        <div class="card-body">
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form name="form" method="post" action="{{ route('leads.store') }}" id="form">
        {{ csrf_field() }}
        <input type="hidden" name="state_id" value="0">

    <div class="row">
        <div class="input-group mb-3 col-sm-6">
            <div class="input-group-prepend">
                <span class="input-group-text" id="name_label">Телефон клиента</span>
            </div>
            <input type="text" name="phone" id="phone" value=""  class="form-control " placeholder="79ххх" autocomplete="off" required aria-label="" aria-describedby="name_label">
        </div>


        <div class="input-group mb-2 col-sm-3">
        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Создать сделку</button>
        </div>



    </div>
    </form>
        </div>
    </div>

@endsection
@section('scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .ui-autocomplete-loading {
            background: white url("/img/ui-anim_basic_16x16.gif") right center no-repeat;
        }
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {

            $( function() {
                function log( message ) {
                    $( "<div>" ).text( message ).prependTo( "#log" );
                    $( "#log" ).scrollTop( 0 );
                }

                $( "#form" ).submit(function( event ) {
                    $('.body-block-loaders').removeClass('d-none');
                });

                $( "#phone" ).autocomplete({
                    source: function( request, response ) {
                        $.ajax( {
                            type: 'POST',
                            url: '{{ route('leads.search.client') }}',

                            data: {
                                term: request.term
                            },
                            success: function( data ) {
                                response( data );
                            }
                        } );
                    },
                    minLength: 2,
                } );
            } );
        });
    </script>

@endsection



