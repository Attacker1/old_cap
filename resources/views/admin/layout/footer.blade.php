
<!-- BEGIN: Vendor JS-->
<script src="/app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="/app-assets/vendors/js/charts/apexcharts.min.js"></script>
<script src="/app-assets/vendors/js/extensions/tether.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="/app-assets/js/core/app-menu.js"></script>
<script src="/app-assets/js/core/app.js"></script>
<script src="/app-assets/js/scripts/components.js"></script>
<script src="/app-assets/js/scripts/extensions/sweet-alerts.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/datatables.min.css">

<!-- BEGIN: Toaster-->
@toastr_css
@toastr_js
@toastr_render
<!-- END: Toaster -->

<!--  Яндекс Метрика Офиса -->
@if (config("app.env") == "production")
    @if(Route::is('leads.list') || Route::is('admin.catalog.products.index'))
        @include("partials.metrika.office_metric")
    @endif
@endif
<!-- END: Метрика Офиса-->

<script>
    $( document ).ready(function() {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        toastr.options = {
            "closeButton": true,
            "newestOnTop": true,
            "positionClass": "toast-bottom-right"
        };
    });
</script>

<script>

    // $.fn.dataTable.ext.errMode = 'none';

    $(function () {
    $(document).on('click','.confirm-delete',function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Удалить запись?',
            text: "Данные могут быть удалены безвозвратно!",
            icon: 'danger',
            showCancelButton: true,
            confirmButtonColor: '#63d667',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Удалить!'
        }).then((result) => {

            let dt = $(this).attr("data-dt");
            let delete_url = $(this).attr("data-url");

            if (result.value) {
                Swal.fire(
                    'Удалено!',
                    'Запись удалена',
                    'success'
                );

                if (dt) {
                    $.ajax({
                        url: delete_url,
                        type:"Post",
                        data: {'_method':'delete'},
                        success:function($msg){
                            table.draw();
                        }
                    });
                    return true;
                }
                // возможно переделать где используется на код ниже?
                window.location = $(this).attr('href');
                return true;

                // let form = $('<form action="' + delete_url + '" method="DELETE"> "</form>');
                // $('body').append(form);
                // form.submit();


            }
        })
    });
    });

</script>
