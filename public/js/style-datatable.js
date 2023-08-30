function pagination_style()
{
    $('.paginate_button').addClass('page-link').wrap('<li class="page-item" style="padding-top:0!important"></li>');
            
    $('li.page-item')
        .wrapAll('<section id = "ecommerce-pagination"></section>')
        .wrapAll('<nav aria-label = "Product pagination"></nav>')
        .wrapAll('<ul class="pagination"></ul>');

    $('.paginate_button').filter('.previous').parent('.page-item').addClass('previous');
    $('.paginate_button').filter('.next').parent('.page-item').addClass('next');
        
    if($('.paginate_button').hasClass('current')) {
        $('.paginate_button').filter('.current').parent('.page-item').addClass('active');
    }
}

function db_length_style()
{
    $('select[name="datatable_length"]').addClass('custom-select form-control').css({'width':'4rem', 'margin': '0 5px 0 5px', 'background-position-y':'10px', 'border': '1px solid #c4c1c1'});
    $('#datatable_length').css({'margin-left': '8px'});
}

function search_input_and_reset_style()
{
    $('#datatable_filter input').addClass('form-control datatable-search-clients-input mb-1');

    if(!$('div').is('.datatable-reset-search'))
    {
        
        $('#datatable_filter input')
            .after(`
                <div style="margin-left: 8px" class="mb-2 datatable-reset-search">
                    <a href="`+$('#datatable').data('route-reset-datatable-settings')+`">
                        сбросить настройки <i class="fa fa-close" aria-hidden="true"></i>
                    </a>
                </div>`);
    }
}

function format_phone(data)
{
    if(data.indexOf('_') != -1) return data;
    if(data == '') return '';
    return ['+', data[0], ' (', data.substr(1,3), ') ', data.substr(4,3), '-',
        data.substr(4,2), '-', data.substr(4,2)].join('');
}

function show_modal_delete(text, attr_data)
{
    $('.modal-client-delete').on('click', function(){
        let url = $('#datatable').data('route-destroy') +'/'+ $(this).attr('data-id');
        
        swal.fire({
            text: text + ' ' + $(this).attr(attr_data) + '?',
            showCancelButton: true,
            confirmButtonColor: '#28c76f',
            cancelButtonColor: '#aeaad2',
            confirmButtonText: 'Удалить'
        }).then(function (result) {
            if (result.value === true) {
                let form = $(`
                    <form action="` + url + `" method="post">
                        <input type="hidden" name="_token" value="` + $('#datatable').data('delete-form-csrf') + `">
                        <input type="hidden" name="_method" value="delete">
                    </form>`);
                $('body').append(form);
                form.submit();
            }
        });

    });
}

function console_alerts()
{
    window.alert = (function() {
        var nativeAlert = window.alert;
        return function(message) {
            window.alert = nativeAlert;
            message.indexOf("DataTables warning") === 0 ?
                console.warn(message) :
                nativeAlert(message);
        }
    })();
}
