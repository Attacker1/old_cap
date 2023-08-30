<a class="btn btn-sm btn-success xls_downloads" id="xls_downloads" href="javascript:">
    <i class="fa fa-download"></i> Выгрузка анкет - краткая </a>
<!--
anketa_listing_downloads
-->

@section('scripts')
    @parent
    <script>
        $(function () {

            $(document).on('click', '.xls_downloads', function () {
                var perPage = 500;
                getData({
                    perPage: perPage,
                    pageNr: 1
                })

                function getData(data) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('admin.anketa.xsl-list.short')}}",
                        data: data,
                        success: function (response) {
                            if (response.current_page > response.last_page) {// stop

                                $('#xls_downloads ')
                                    .attr('href', '{{asset('storage/anketa_short.csv')}}')
                                    .html('<i class="fa fa-download"></i> Скачать выгрузку')
                                    .removeClass('xls_downloads').addClass('xls_downloads_ready');

                            } else {

                                var nextPage = Number(response.current_page) + 1
                                var items = Number(response.current_page) * Number(response.perPage);
                                var text = items + ' из ' + response.total + ' анкет';

                                $('#xls_downloads ').html('<i class="fa fa-download"></i>' + text);

                                getData({
                                    perPage: perPage,
                                    pageNr: nextPage
                                })
                            }
                        },
                        error: function (e) {
                            console.log('err', e);
                        }
                    })
                }
            });
        })
    </script>



@endsection
