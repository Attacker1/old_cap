<script>

    $(document).ready(function () {

        // Управление панелями (скрыть/закрыть)
        function toggleIcon(e) {
            $(e.target)
                .prev('.panel-heading')
                .find(".ficon")
                .toggleClass('icon-chevron-down icon-chevron-right');
        }

        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        var adviceSortableList = [];
        $(document).on('click', '.save-advice_', function (e) {
            saveAdvice();
            toastr.success('Сохранено');
        });

        // Сохраняем сочетания
        function saveAdvice(){

            var advice_array = [];
            $.each(adviceSortableList, function (i, v) {
               advice_array.push(v.toArray());
            });

            $.ajax({
                url: '{{ route('notes.product.advice',$data->id) }}',
                type: "POST",
                data: {advices: advice_array},
                success: function(){
                    // toastr.success('Сохранено');
                },
                error: function () {
                    toastr.error('Ошибка сохранения сочетаний!');
                }
            });
        }

        $(document).on('click', '.delete-product-advice-left', function (e) {
            $("#"+$(this).data('id')).children().remove();
            saveAdvice();
        });

        updatedAdvice();
        function updatedAdvice() {
            for (let i = 1; i <= 3; i++) {
                var srt = new Sortable(document.getElementById('product-advice-left-' + i), {
                    group: {
                        name: 'advice',
                        sort: true,
                    },
                    dataIdAttr:'id',
                    onAdd: function (evt) {

                        if ($(evt.item).parent('div').children('div').length > 4){
                            toastr.error('Превышено кол-во сочетаний!');
                            $(evt.item).parent('div').children('div').last().remove();
                        }
                    },
                    filter: ".js-remove",
                    onChange: function (evt) {
                        saveAdvice();
                    },
                    animation: 150
                });

                adviceSortableList.push(srt);
            }
        };

        // Правая панель ВЕЩИ из интернет
        customAdviceSource = document.getElementById('url-source');
        new Sortable(customAdviceSource, {
            group: {
                name: 'advice',
                pull: 'clone',
                put: false,
            },
            animation: 150,
            sort: false,
        });

        // Правая панель ВЕЩИ из ПОДБОРКИ
        new Sortable(document.getElementById('product-advice-right'), {
            group: {
                name: 'advice',
                pull: 'clone',
                put: false,
            },
            animation: 150,
            sort: false,
        });




    });
</script>