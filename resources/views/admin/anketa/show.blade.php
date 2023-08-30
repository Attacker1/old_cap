@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection

@section('content')
    <div class="card card-custom col-12 mb-6">
        <div class="card-body pt-4">
            @include('admin.anketa.show.top')
            @if($anketa)
                <div style="display: flex;">
                    @include('admin.anketa.show.block-capsula')
                    @include('admin.anketa.show.block-params')
                </div>

                <div style="display: flex; margin-top: 40px">
                    @include('admin.anketa.show.block-strogie-net')
                    @include('admin.anketa.show.block-style')
                </div>

                @include('admin.anketa.show.obrasy-colors')
            @else
                <b>нет данных по анкете</b>
            @endif
        </div><!--card-body-->
    </div><!--card-->

    @include('admin.anketa.comments')
@endsection

@section('styles')
    <style>
        .anketa-head {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
        }

        .question-answer-wrapper {
            background-color: #f6f6f9;
            border-radius: 3px;
            padding: 10px 10px 20px 10px;
            margin-top: 10px;
            min-width: 350px;
        }

        .question-answer-wrapper .top-0 {
            margin-top: 0px;
        }

        .question-answer-item {
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            margin-top: 10px;
        }

        .anketa-question {
            font-weight: bold;
            flex-basis: 40%;
        }

        .anketa-answer {
            flex-basis: 60%;
            text-align: right;
        }

        .image {
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            width: 50px;
            height: 50px;
        }

        .anketa-colors {
            width: 50px;
            height: 50px;
        }

        .reference-col {
            position: relative;
        }
        .reference-col p {
            padding: 10px;
        }
        .anketa-tab-photo-delete {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 22px;
        }
        .anketa-tab-reference-delete {
            position: absolute;
            top: 7px;
            right: 20px;
            font-size: 22px;
            display: flex;
        }


    </style>
    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/slim-uploader/slim.min.css')}}">
    <script src="{{asset('app-assets/js/scripts/slim-uploader/slim.kickstart.min.js')}}"></script>
@endsection

@section('scripts')
    <script>
        $('.modal-show-foto').on('click', function () {
            swal.fire({
                imageUrl: $(this).data('url'),
                showCloseButton: true
            });
        });
        $('[href="#kt_tab_pane_1"]').on('click', function () {
            $('.card-header-tabs-line').find('.card-title').html('Образы');
        });
        $('[href="#kt_tab_pane_2"]').on('click', function () {
            $('.card-header-tabs-line').find('.card-title').html('Цвета');
        });
        $('[href="#kt_tab_pane_3"]').on('click', function () {
            $('.card-header-tabs-line').find('.card-title').html('Фото');
        });
        $('[href="#kt_tab_pane_4"]').on('click', function () {
            $('.card-header-tabs-line').find('.card-title').html('Референсы');
        });

// Табы РЕФЕРЕНСЫ

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#reference-photo').change(function () {

            let reader = new FileReader();

            reader.onload = (e) => {
                $('#image_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#tabs-reference').submit(function (e) {

            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: "{{route('admin.anketa.tabs.reference.store')}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    // this.reset();
                    $(document).find('#reference-items').html(data)
                    $(document).find('#image_preview_container').attr('src','/app-assets/images/anketa/noscreen.png')
                },
                error: function (e) {
                    toastr.error('Ошибка сохранения референса')
                }
            });
        });

        $(document).on('click','.anketa-tab-reference-delete',function(){
            var anketaTabReferenceId = $(this).attr('data-id')

            swal.fire({
                text: 'Вы действительно хотите удалить референс?',
                showCancelButton: true,
                confirmButtonColor: '#c7131c',
                cancelButtonColor: '#b4b4b4',
                cancelButtonText: 'Отмена',
                confirmButtonText: 'Удалить',

            }).then(function (result) {
                if(result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{route('admin.anketa.tabs.reference.destroy','')}}/"+anketaTabReferenceId,
                        success: function( data ) {
                            $(document).find('#reference-items').html(data)
                        },
                        error: function( e ) {
                            toastr.error('Ошибка удаления референса')
                        }
                    })
                }
            })




        });

// Табы ФОТО
        new Slim(document.getElementById('anketa_tab_photo'), {
            service: '{{route('admin.anketa.tabs.support-photo.store')}}',
            label: 'Кликните здесь или бросьте изображение в эту область',
            buttonConfirmLabel: 'Применить',
            buttonCancelLabel: 'Отменить',
            internalCanvasSize: '200,200',//{ width:200, height:200 },
            meta: {
                uuid: '{{$uuid}}'
            },
            didUpload(error, data, response) {
                if (error) {
                    toastr.error(error)
                    return false
                }
                $('#admin-tab-support-photo').html($(response))
                this.remove();
            }
        });

        $(document).on('click','.anketa-tab-photo-delete',function(){
            var dataAttachment = $(this).attr('data-attachmentUuid')

            swal.fire({
                text: 'Вы действительно хотите удалить фото?',
                showCancelButton: true,
                confirmButtonColor: '#c7131c',
                cancelButtonColor: '#b4b4b4',
                cancelButtonText: 'Отмена',
                confirmButtonText: 'Удалить'
            }).then(function (result) {
                if(result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{route('admin.anketa.tabs.support-photo.destroy', '')}}/"+dataAttachment,
                        success: function( data ) {
                            $('#admin-tab-support-photo').html($(data))
                        },
                        error: function( xhr, ajaxOptions, thrownError ) {
                            toastr.error('Ошибка удаления фото')
                        }
                    })
                }
            })

        });

    </script>
@endsection
