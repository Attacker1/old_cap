<script>
    $(document).ready(function () {
        var urlSortableList = [];

        // Очистка блока вложений
        $(document).on('click', '.erase-url-advice', function (e) {
            $("#"+$(this).data('id')).children().remove();
        });

        // Сохраняем вложения
        $(document).on('click', '.save-url-advice_', function (e) {

            var url_advice_array = [];
            $.each(urlSortableList, function (i, v) {
                url_advice_array.push({ 'id': parseInt(v.el.id.replace("url-product-advice-",'')), 'values' : v.toArray()});
            });

            $.ajax({
                url: '{{ route('notes.advice',$data->id) }}',
                type: "POST",
                data: {advices: url_advice_array},
                success: function(){
                    toastr.success('Сохранены сочетания из интернета');
                },
                error: function () {
                    toastr.error('Ошибка сохранения сочетаний!');
                }
            });

        });


    });
</script>