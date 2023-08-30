@section('scripts')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans&display=swap" rel="stylesheet">
    <style>
        .note-tabs {
            font-family: 'Alegreya Sans', sans-serif;
            font-size: 110%;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?t=<?php echo(microtime(true).rand()); ?>">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.0/tinymce.min.js" integrity="sha512-XaygRY58e7fVVWydN6jQsLpLMyf7qb4cKZjIi93WbKjT6+kG/x4H5Q73Tff69trL9K0YDPIswzWe6hkcyuOHlw==" crossorigin="anonymous"></script>
    <script src="/assets/js/Sortable.js"></script>
    <link rel="stylesheet" href="{{asset('app-assets/css/plugins/slim-uploader/slim.min.css')}}">


    @include('admin.notes.scripts.product-advice-new')
    @include('admin.notes.scripts.custom-advice')
    <script>

        $(document).ready(function () {

            $('#kt_maxlength_letter_1,#kt_maxlength_letter_2').maxlength({
                threshold: 5,
                warningClass: "label label-danger label-rounded label-inline",
                limitReachedClass: "label label-primary label-rounded label-inline"
            });

            $(document).on('click','.nav-tabs a', function () {
                $('.nav-tabs a').removeClass('active');
               $(this).addClass('active');
            });

            $(document).on('click', '.customizer-toggle', function (e) {
                $('.customizer').toggleClass('open');
            });

            // Удалить товар из записки
            $(document).on('click', '.product_to_note__', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Удалить товар?',
                    icon: 'danger',
                    showCancelButton: true,
                    confirmButtonColor: '#63d667',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Удалить!'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire(
                            'Удалено!',
                            'Запись удалена',
                            'success'
                        );
                        window.location = $(this).attr('href');
                        return true;
                    }
                })
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: '#content,#content_advice',
                browser_spellcheck: true,
                height: 300,
                menubar: 'tools',
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
