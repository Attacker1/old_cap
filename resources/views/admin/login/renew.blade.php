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


                        <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Текущий пароль"/>
                            @if ($errors->has('current_password'))
                                <span class="help-block"><strong>{{ $errors->first('current_password') }}</strong></span>
                            @endif
                        </div>


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
                            <div class="confirmed rules-list">
                                <ul class="rules"><li class="length null"> Пароли совпадают</li></ul>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-12"><input type="submit" value="Сохранить пароль"
                                                                   class="btn btn-gt btn-block btn-small"
                                                                   tabindex="7"></div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>


    <!-- footer template -->
    @include('fleet.layouts.footer')

        <script>
            !function (a) {
                a.fn.passwordRulesValidator = function (b) {
                    function d(b, c, d, e) {
                        b.test(c) ? a("#" + e + " li." + d).removeClass("ko " + f.koClass).addClass("ok " + f.okClass) : a("#" + e + " li." + d).removeClass("ok " + f.okClass).addClass("ko " + f.koClass)
                    }

                    function e(b, c, e) {
                        a.each(f.rules, function (a, b) {
                            b.enable && d(new RegExp(b.regex, "g"), c, b.name, e)
                        })
                    }

                    var c = {
                        rules: {
                            length: {regex: ".{8,}", name: "length", message: "8 characters", enable: !0},
                            lowercase: {regex: "[a-z]{1,}", name: "lowercase", message: "1 lowercase", enable: !0},
                            uppercase: {regex: "[A-Z]{1,}", name: "uppercase", message: "1 uppercase", enable: !0},
                            number: {regex: "[0-9]{1,}", name: "number", message: "1 digit", enable: !0},
                            specialChar: {
                                regex: "[^a-zA-Z0-9]{1,}",
                                name: "special-char",
                                message: "1 специальный символ ",
                                enable: !0
                            }
                        },
                        msgRules: "Пароль должен содержать :",
                        container: void 0,
                        containerClass: null,
                        containerId: "checkRulesList",
                        okClass: null,
                        koClass: null,
                        onLoad: void 0
                    }, f = a.extend(!0, c, b);
                    return this.each(function () {
                        a.isFunction(f.onLoad) && f.onLoad(), oRulesBuilder = '<span class="rules">' + f.msgRules + "</span>", oRulesBuilder += '<ul class="rules">', a.each(f.rules, function (a, b) {
                            b.enable && (oRulesBuilder += '<li class="ko ' + f.koClass + " " + b.name + '">' + b.message + "</li>")
                        }), oRulesBuilder += "</ul>", "undefined" == typeof f.container ? (a(this).after('<div class="rules-list ' + f.containerClass + '" id="' + f.containerId + '"></div>'), a(oRulesBuilder).appendTo("#" + f.containerId)) : (f.container.addClass("rules-list"), a(oRulesBuilder).appendTo(f.container));
                        var b = "undefined" == typeof f.container ? f.containerId : f.container.attr("id");
                        e(f, a(this).val(), b), a(this).keyup(function () {
                            e(f, a(this).val(), b)
                        }), a(this).on("paste", function () {
                            e(f, a(this).val(), b)
                        }), a(this).change(function () {
                            e(f, a(this).val(), b)
                        })
                    })
                }
            }(jQuery);

            $(function() {
                $('#password').passwordRulesValidator({
                    'rules' : {
                        'length' : {
                            'regex': '.{10,20}',
                            'name': 'length',
                            'message': 'Мин. 10 символов',
                            'enable': true
                        },
                        'lowercase' :{
                            'regex': '[a-z]{1,}',
                            'name': 'lowercase',
                            'message': 'Мин. 1 прописную лат. букву',
                            'enable': true
                        },
                        'uppercase' : {
                            'regex': '[A-Z]{1,}',
                            'name': 'uppercase',
                            'message': 'Мин. 1 заглавную лат. букву',
                            'enable': true
                        },
                        'number' : {
                            'regex': '[0-9]{1,}',
                            'name': 'number',
                            'message': 'Мин. 1 цифру',
                            'enable': true
                        },
                        'specialChar' : {
                            'regex': '[!@#$%`&*]{1,}',
                            'name': 'special-char',
                            'message': 'Мин. 1 спец. символ !@#$%`&*',
                            'enable': true
                        },
                    },
                });
            });
            $(function() {

                $("#password_confirmation").keyup(function() {

                    var passwordVal = $("#password").val();
                    var checkVal = $("#password_confirmation").val();

                    if (passwordVal == checkVal) {
                        if (checkVal.length > 5 ) {
                            $(".confirmed > ul > li").removeClass('ko').addClass('ok');
                            $(".confirmed").show();
                        }
                    }
                    else {
                        $(".confirmed > ul > li").removeClass('ok').addClass('ko');
                        $(".confirmed").show();
                    }
                });

            });

        </script>

        <style>
            div.rules-list li.ko::before, div.rules-list li.ok::before {
                width: 32px;
                display: inline-block;
                text-align: right;
                margin-right: 10px
            }

            div.rules-list {
                margin-top: 10px
            }

            div.rules-list .rules {
                list-style-type: none;
                padding: 0
            }

            div.rules-list li {
                position: relative
            }

            div.rules-list li.ko::before {
                content: '\f00d';
                font: normal normal normal 14px/1 FontAwesome;
                font-size: 14px;
                color: red
            }

            div.rules-list li.ok::before {
                content: '\f00c';
                font: normal normal normal 14px/1 FontAwesome;
                font-size: 14px;
                color: green
            }
            .confirmed {
                display: none;
            }

        </style>
</div>