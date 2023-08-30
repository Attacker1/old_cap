@section('title'){{ $title }}@endsection

@include('fleet.layouts.header_login')

<div id="wrapper">

    <section id="content" >

        <div class="container login-page">

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <form role="form" class="register-form" action="{{route("fleet.password.forgot")}}" method="post">
                        <h3>{{ $title }}</h3>
                        {{ csrf_field() }}
                        <div class="form-group ">
                            <input type="text" name="email" id="email" class="form-control input-lg" value=""
                                   placeholder="Ваш e-mail, указанный при регистрации" tabindex="4" autocomplete="off">
                        </div>

                        <div class="row ">
                            <div class="col-xs-12 col-md-6 form-group"><input type="submit" value="Восстановить пароль"
                                                                   class="btn btn-gt btn-block btn-small"
                                                                   tabindex="7"></div>
                            <div class="col-xs-12 col-md-6 form-group"><a href="{{ route('fleet.login') }}"  class="btn btn-default btn-block btn-small">Авторизация</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>


    <!-- footer template -->
    @include('fleet.layouts.footer')
</div>