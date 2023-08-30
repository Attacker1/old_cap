<!-- header template -->
@include('fleet.layouts.header_login')

<div id="wrapper">

    <section id="content" >

        <div class="container login-page">

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <form role="form" class="register-form" action="" method="post">
                        <h3>{{ $title }}</h3>
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">




                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Пароль"/>
                            @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Повторите пароль"/>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-6"><input type="submit" value="Сохранить пароль"
                                                                   class="btn btn-gt btn-block btn-small"
                                                                   tabindex="7"></div>
                            <div class="col-xs-12 col-md-6"><a href="{{ route('fleet.login') }}"  class="btn btn-default btn-block btn-small">Авторизация</a>
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