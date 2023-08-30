<script src="{{asset('app-assets/js/scripts/slim-uploader/slim.jquery.js')}}"></script>
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

        var customSortableList = [];
        $(document).on('click', '.save-custom-advice_', function (e) {
            saveCustomAdvice();
            toastr.success('Сохранено');
        });

        // Сохраняем сочетания
        function saveCustomAdvice() {

            var custom_advice_array = [];
            $.each(customSortableList, function (i, v) {
                custom_advice_array.push(v.toArray());
            });

            $.ajax({
                url: '{{ route('notes.custom.advice',$data->id) }}',
                type: "POST",
                data: {advices: custom_advice_array},
                success: function () {
                    // toastr.success('Сохранено');
                },
                error: function () {
                    toastr.error('Ошибка сохранения сочетаний!');
                }
            });
        }

        $(document).on('click', '.delete-custom-advice-left', function (e) {
            $("#" + $(this).data('id')).children().remove();
            saveCustomAdvice();
        });

        updatedCustomAdvice();

        function updatedCustomAdvice() {
            for (let i = 1; i <= 3; i++) {
                var srt = new Sortable(document.getElementById('custom-advice-left-' + i), {
                    group: {
                        name: 'custom',
                        sort: true,
                    },
                    dataIdAttr: 'id',
                    onAdd: function (evt) {

                        if ($(evt.item).parent('div').children('div').length > 4) {
                            toastr.error('Превышено кол-во сочетаний!');
                            $(evt.item).parent('div').children('div').last().remove();
                        }
                    },
                    filter: ".js-remove",
                    onChange: function (evt) {
                        saveCustomAdvice();
                    },
                    animation: 150
                });

                customSortableList.push(srt);
            }
        };

        customAdviceSource = document.getElementById('custom-advice-source');
        new Sortable(customAdviceSource, {
            group: {
                name: 'custom',
                pull: 'clone',
                put: false,
            },
            animation: 150,
            sort: false,
        });

        new Sortable(document.getElementById('product-custom-advice-right'), {
            group: {
                name: 'custom',
                pull: 'clone',
                put: false,
            },
            animation: 150,
            sort: false,
        });


        $(document).on('dblclick', '.advice-source .resizeable', function (e) {
            var imgKey = $(this).attr('id');

            if ($(this).find('.slim-result').length === 0) {
                var permanentSlim = $(this).slim({
                    ratio: '16:21',
                    service: '{{route('notes.replace.image')}}',
                    push: true,
                    meta: {
                        key: imgKey,
                        noteId: {{$data->id}}
                    },
                    didUpload(error, data, response) {
                        location.reload();
                        // permanentSlim.slim('destroy')
                        if (error) {
                            toastr.error(error)
                            return false
                        }
                    }
                });
            } else {
                $(this).slim('destroy')
            }


        });




    });
</script>
<style>
    .slim {
        width: 64px;
        height: 84px;
        overflow: hidden;
    }

    .slim img {
        width: 64px;
    }

</style>

