<a class="btn btn-sm btn-success anketa_listing_downloads" id="anketa_listing_downloads"
   href="javascript:">
    <i class="fa fa-download"></i> Выгрузка анкет - все поля </a>

<div style="display: none;">
    <table id="quest_downloads_tbl">
        <tbody id="anketa_listing_downloads_tbody"></tbody>
    </table>

</div>

@section('scripts')
    @parent
    <script>
        $(function () {

            $(document).on('click', '.anketa_listing_downloads', function () {
                var perPageWhole = 1000;
                getData({
                    perPage: perPageWhole,
                    pageNr: 1
                })

                function getData(data) {
                    $.ajax({
                        type: "POST",
                        url: "{{route('admin.anketa.xsl-list.whole')}}",
                        data: data,
                        success: function (response) {
                            // response = JSON.parse(response);

                            console.log('resp',response);
                            console.log('typeof',typeof response);


                            if (response.current_page > response.last_page) {// stop

                                $('#anketa_listing_downloads ')
                                    .attr('href', '{{asset('storage/anketa_whole.csv')}}')
                                    .html('<i class="fa fa-download"></i> Скачать выгрузку')
                                    .removeClass('anketa_listing_downloads').addClass('anketa_listing_downloads_ready');

                            } else {

                                var nextPageWhole = Number(response.current_page) + 1
                                var itemsWhole = Number(response.current_page) * Number(response.perPage);
                                var textWhole = itemsWhole + ' из ' + response.total + ' анкет';

                                $('#anketa_listing_downloads ').html('<i class="fa fa-download"></i>' + textWhole);

                                getData({
                                    perPage: perPageWhole,
                                    pageNr: nextPageWhole
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
