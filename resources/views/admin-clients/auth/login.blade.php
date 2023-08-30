@extends('admin-clients.layouts.main-not-authorized')
@section('title'){{ $title }}@endsection

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="row flexbox-container">
                <div class="col-xl-8 col-11 d-flex justify-content-center">
                    <div class="card bg-authentication rounded-0 mb-0">
                        <div class="row m-0">
                            <div class="col-lg-6 d-lg-block d-none text-center align-self-center  py-0 pr-4">
                                <img src="{{asset('app-assets/images/capsula/logo-horisontal.svg')}}" width="300" height="102" alt="branding logo">
                            </div>
                            <div class="col-lg-6 col-12 p-0">
                                <div class="card rounded-0 mb-0 px-2">
                                    <div class="card-header pb-1">
                                        <div class="card-title">
                                            <h4 class="mb-0">Капсула: Личный кабинет</h4>
                                        </div>
                                    </div>
                                
                                    <div class="card-content">
                                        
                                        <div class="card-body pt-0" 
                                            id="clients-login-app" 
                                            data-old-phone="{{old('phone')}}">

                                            <div class="text-success">Вход через СМС</div>
                                            <other-validation-message 
                                                v-bind:error_msg="other_errors">        
                                            </other-validation-message>    
                                            <div class="tab-content pt-1">
                                                <div class="tab-pane active" aria-labelledby="sms-enter-tab" role="tabpanel">
                                                    <form method="post" id="sms-form" v-on:submit.prevent="OnSubmitLogin">
                                                        {{ csrf_field() }}
                                                    
                                                        <fieldset class="form-label-group form-group position-relative has-icon-left mt-2">
                                                            <input 
                                                                type="text" 
                                                                name="phone" 
                                                                v-on:input="phoneMask" 
                                                                class="form-control" 
                                                                id="phone" 
                                                                placeholder="телефон" 
                                                                autocomplete="off" 
                                                                required maxlength="24" 
                                                                v-model="phone">
                                                            <phone-validation-message 
                                                                v-bind:error_msg="phone_input_error"
                                                                v-if="phone_input_error!==null"
                                                            ></phone-validation-message>
                                                            <div class="form-control-position">
                                                                <i class="feather icon-phone"></i>
                                                            </div>
                                                            <label for="phone">Телефон</label>
                                                        </fieldset>

                                                        <fieldset class="form-label-group position-relative has-icon-left">
                                                            <input 
                                                                type="text" 
                                                                name="sms_code"  
                                                                class="form-control" 
                                                                id="user-sms-code" 
                                                                placeholder="Пароль из СМС" 
                                                                maxlength="20" 
                                                                required 
                                                                autocomplete="off" 
                                                                v-model="sms_code">
                                                            <sms-code-validation-message 
                                                                v-bind:error_msg="sms_code_input_error"
                                                                v-if="sms_code_input_error!==null"
                                                            ></sms-code-validation-message>
                                                            <div class="form-control-position">
                                                                <i class="feather icon-lock"></i>
                                                            </div>
                                                            <label for="user-sms-code">Пароль из СМС</label>
                                                        </fieldset>
                                                        <timer 
                                                            v-if="timer_start===true"
                                                            v-on:timer="DestroyTimer"
                                                        ></timer>
                                                        <div class="form-group justify-content-between align-items-center row pl-1 pr-1">
                                                            <div class="md-6 mb-1">
                                                                <btn-send-sms
                                                                    send_sms_url="{{route('admin-clients.auth.get-sms-code')}}"
                                                                    captcha_site_key="{{ config('config.CAPTCHA_SITE_KEY') }}"
                                                                    v-if="timer_start===null"
                                                                    v-bind:phone="phone"
                                                                    v-on:click_send_sms="clearMessageData"
                                                                    v-on:message="responseMessages"
                                                                ></btn-send-sms>
                                                            </div>    
                                                            <div class="md-6 mb-1">
                                                                <btn-submit-login
                                                                    submit_url="{{ route('admin-clients.auth.login-sms')}}"
                                                                    success_url="{{ route('admin-clients.main.index') }}"
                                                                    captcha_site_key="{{ config('config.CAPTCHA_SITE_KEY')  }}"
                                                                    v-bind:phone="phone"
                                                                    v-bind:sms_code="sms_code"
                                                                    v-on:click_submit_login="clearMessageData"
                                                                    v-on:message="responseMessages"
                                                                ></btn-submit-login>
                                                            </div>

                                                        </div>
                                                    </form>

                                                    <a href="https://api.whatsapp.com/send/?phone=78007000762&text&app_absent=0" target="_blank"
                                                       style="display: flex; border: 1px solid #7367f0; padding: 3px 5px; border-radius: 0.4285rem; width: 100%;">
                                                        <svg fill="#1f9d57" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="30px" height="30px">
                                                            <path d="M 15 3 C 8.373 3 3 8.373 3 15 C 3 17.251208 3.6323415 19.350068 4.7109375 21.150391 L 3.1074219 27 L 9.0820312 25.431641 C 10.829354 26.425062 12.84649 27 15 27 C 21.627 27 27 21.627 27 15 C 27 8.373 21.627 3 15 3 z M 10.892578 9.4023438 C 11.087578 9.4023438 11.287937 9.4011562 11.460938 9.4101562 C 11.674938 9.4151563 11.907859 9.4308281 12.130859 9.9238281 C 12.395859 10.509828 12.972875 11.979906 13.046875 12.128906 C 13.120875 12.277906 13.173313 12.453437 13.070312 12.648438 C 12.972312 12.848437 12.921344 12.969484 12.777344 13.146484 C 12.628344 13.318484 12.465078 13.532109 12.330078 13.662109 C 12.181078 13.811109 12.027219 13.974484 12.199219 14.271484 C 12.371219 14.568484 12.968563 15.542125 13.851562 16.328125 C 14.986562 17.342125 15.944188 17.653734 16.242188 17.802734 C 16.540187 17.951734 16.712766 17.928516 16.884766 17.728516 C 17.061766 17.533516 17.628125 16.864406 17.828125 16.566406 C 18.023125 16.268406 18.222188 16.319969 18.492188 16.417969 C 18.766188 16.515969 20.227391 17.235766 20.525391 17.384766 C 20.823391 17.533766 21.01875 17.607516 21.09375 17.728516 C 21.17075 17.853516 21.170828 18.448578 20.923828 19.142578 C 20.676828 19.835578 19.463922 20.505734 18.919922 20.552734 C 18.370922 20.603734 17.858562 20.7995 15.351562 19.8125 C 12.327563 18.6215 10.420484 15.524219 10.271484 15.324219 C 10.122484 15.129219 9.0605469 13.713906 9.0605469 12.253906 C 9.0605469 10.788906 9.8286563 10.071437 10.097656 9.7734375 C 10.371656 9.4754375 10.692578 9.4023438 10.892578 9.4023438 z"/></svg>
                                                        <span style="margin-left: 5px; width: 100%; display: flex; justify-content: center; padding-top: 4px" >Связаться с поддержкой</span>
                                                    </a>

                                                </div><!--tab-pane -->
                                            </div><!--tab-content -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection

@section('head_scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{config('config.CAPTCHA_SITE_KEY')}}"></script>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="{{asset('js/format-phone.js')}}"></script>
<script>

Vue.component('phoneValidationMessage', {
    template: `<small class="form-text text-danger font-weight-bold">@{{error_msg}}</small>`,
    props: {
        error_msg: {
            type: String,
            default: null
        }
    }
})

Vue.component('smsCodeValidationMessage', {
    template: `<small class="form-text text-danger font-weight-bold">@{{error_msg}}</small>`,
    props: {
        error_msg: {
            type: String,
            default: null
        }
    }
})

Vue.component('otherValidationMessage', {
    template: `<small class="form-text text-danger font-weight-bold mb-1">@{{error_msg}}</small>`,
    props: {
        error_msg: {
            type: String,
            default: null
        }
    }
})

Vue.component('timer', {
    template: `<small class="font-weight-bold mt-1">вам отправлено смс @{{ currentTime }}</small>`,
    
    data() { 
        return {
            currentTime: 120,
            timer: null,
          }
    },      
    mounted() {
        this.startTimer()
    },
    destroyed() {
        this.stopTimer()
    },
    methods: {
        startTimer() {
            this.timer = setInterval(() => {
                this.currentTime--
            }, 1000)
        },
        stopTimer() {
            this.$emit('timer');
            clearTimeout(this.timer)
        },
    },
    watch: {
        currentTime(time) {
            if (time === 0) {
                this.stopTimer()
            }
        }
    },
})

Vue.component('btnSendSms', {
    template: `
        <a href="" v-on:click.prevent="sendSMS" class="btn btn-outline-primary text-nowrap pl-1 pr-1 mr-1"><b>Получить пароль</b></a>
    `,
    props: {
        send_sms_url: {
            type: String,
            required: true
        },
        phone: String,
        captcha_site_key: {
            type: String,
            required: true
        },
    },
    
    methods: {
        sendSMS() {

            if(this.$root.env == 'production') {
                ym(82667803,'reachGoal','goal_2')
            } else console.log('2');

            this.$emit('click_send_sms');
            grecaptcha.ready(()=> {
                grecaptcha.execute(this.captcha_site_key, {action: 'submit'}).then( (token) => {
                    (async () =>{
                        
                        let data = {
                                phone: phone.value,
                                token: (typeof token === "undefined") ? '' : token,       
                            },
                        
                            response = await fetch(this.send_sms_url, { headers: headers, method: 'post', body: JSON.stringify(data) });
                        
                        if(response.ok) {
                            let data = await response.json();
                            this.$emit('message', data);
                        } else {
                            console.log('error', response.status);
                        }
                    })();
                    
                });
            });                    
        }
    },
})

Vue.component('btnSubmitLogin', {
    template: `
        <button type="button" v-on:click="submitLogin" class="btn btn-success float-right btn-inline">Войти</button>
    `,
    props: {
        submit_url: {
            type: String,
            required: true
        },
        success_url: {
            type: String,
            required: true
        },
        sms_code: String,
        phone: String,
        captcha_site_key: {
            type: String,
            required: true
        },
    },
    
    methods: {
        submitLogin() {

            if(this.$root.env == 'production') {
                ym(82667803,'reachGoal','goal_1');
            } else console.log('1');

            this.$emit('click_submit_login');
            grecaptcha.ready(()=> {
                grecaptcha.execute(this.captcha_site_key, {action: 'submit'}).then( (token) => {
                    (async () =>{
                        let data = {
                            phone: (this.phone === null) ? '' : this.phone,
                            sms_code: (this.sms_code === null) ? '' : this.sms_code,
                            token: (typeof token === "undefined") ? '' : token,
                        };
                        
                        let response = await fetch(this.submit_url, { headers: headers, method: 'post', body: JSON.stringify(data) });
                        if(response.ok) {
                            let data = await response.json();
                            this.$emit('message', data);
                            if(typeof data['errors'] === "undefined") {
                                document.location.href = data.redirect_to;
                            }
                        } else {
                            console.log('error', response.status);
                        }
                    })();    
                });
            });    
        }
    }
})

new Vue({
    el: '#clients-login-app',
    data: {
        name: 'Вход через СМС',
        phone: '+7 ',
        sms_code: null,
        phone_input_error: null,
        sms_code_input_error: null,
        other_errors: null,
        timer_start: null,
        env: '{{ config('app.env') ?? 'local' }}'
    },
    methods: {
        responseMessages: function(data) {
    
            if(typeof data['errors'] !== "undefined") {
                this.phone_input_error = (typeof data['errors']['phone'] !== "undefined") ? data['errors']['phone'][0] : '';
                this.sms_code_input_error = (typeof data['errors']['sms_code'] !== "undefined") ? data['errors']['sms_code'][0] : '';
                this.other_errors = (typeof data['errors']['token'] !== "undefined") ? data['errors']['token'][0] : '';
                this.other_errors = (typeof data['errors']['sms_send_res'] !== "undefined") ? data['errors']['sms_send_res'] : '';
            } else {
                this.timer_start = true;
            }
            console.log(data);
        },
        phoneMask: function() {
            let formated_phone = Validate_telefon_nummer_input(this.phone.substring(3));
            this.phone = (typeof formated_phone !== "undefined") ? '+7 ' + formated_phone : '+7 ';
        },
    
        submitLoginResponseMessage: function(data) {
            console.log(data);
        },

        DestroyTimer: function() {
            this.timer_start = null;
        },

        clearMessageData: function() {
            this.phone_input_error = null;
            this.sms_code_input_error = null;
            this.other_errors = null;
        }
    }
});

let headers = {
         "Content-Type": "application/json",
         "Accept": "application/json, text-plain, */*",
         "X-Requested-With": "XMLHttpRequest",
         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        };
</script>
@endsection
