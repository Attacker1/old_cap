@include('admin.layout.header')

{{--  Лист1 ВЕЩИ из подборки --}}
@include('admin.notes.print.sheet_1')
{{-- END ВЕЩИ из подборки   --}}
<div class="pagebreak"> </div>

{{--  Лист2 ВЕЩИ из подборки + URL --}}
@include('admin.notes.print.sheet_2')
{{-- END ВЕЩИ из подборки + URL   --}}


<div class="pagebreak"> </div>

{{--  Лист3 СТОИМОСТЬ --}}
@include('admin.notes.print.sheet_3')

{{-- END Лист СТОИМОСТЬ  --}}

@include('admin.layout.footer')

<script>
    $(document).ready(function () {
        window.print();
        var printEvent = window.matchMedia('print');
        printEvent.addListener(function(printEnd) {
            if (!printEnd.matches) {
                window.close()
            };
        });

    });
</script>

