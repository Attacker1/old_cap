<script>

    $(document).ready(function () {

        // Сохраняем сочетания
        function saveAdvice(){

            var product_advice_array = [];
            $.each(sortableList, function (i, v) {
                product_advice_array.push(v.toArray());
            });

            $.ajax({
                url: '{{ route('notes.product.advice',$data->id) }}',
                type: "POST",
                data: {advices: product_advice_array},
                success: function(){
                    // toastr.success('Сохранены сочетания товаров');
                },
                error: function () {
                    toastr.error('Ошибка сохранения сочетаний!');
                }
            });
        }

        var sortableList = [];
        $(document).on('click', '.save-product-advice_', function (e) {
            saveAdvice();
            toastr.success('Сохранено');
        });

        $(document).on('click', '.delete-product-advice-left', function (e) {
            $("#"+$(this).data('id')).children().remove();
            saveAdvice();
        });

        // TODO: очистить от Г*
        leftAdvice1 = document.getElementById('product-advice-left-1');
        leftAdvice2 = document.getElementById('product-advice-left-2');
        leftAdvice3 = document.getElementById('product-advice-left-3');

        adviceSource = document.getElementById('product-advice-right');


        upd();
        function upd() {
            for (let i = 1; i <= 3; i++) {

                var ssrt = new Sortable(eval("leftAdvice" + i), {
                    group: {
                        name: 'shared',
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

                sortableList.push(ssrt);
            }
        };

        new Sortable(adviceSource, {
            group: {
                name: 'shared',
                pull: 'clone',
                put: false,

            },
            animation: 150,
            sort: false,
        });




    });
</script>