function main_wrapper() 
{
    $('#datatable_wrapper').addClass('datatable-table');
}

function pagination_style()
{
    
    if($('#PaginationWrapper').length == 0) {
        $('#datatable_info, #datatable_paginate').wrapAll('<div class="row justify-content-between" id="PaginationWrapper" style="padding-left: 20px; padding-right: 10px"></div>');
    }
    

    $('.paginate_button').wrap('<li class="paginate_button page-item"></li>');
            
    $('#datatable_paginate')
        .find('li')
        .wrapAll('<ul class="pagination"></ul>');

    $('#datatable_paginate').find('li').find('a').addClass('page-link');

    $('.paginate_button').filter('.previous').parent('.page-item').addClass('previous');
    $('#datatable_previous').html('<i class ="flaticon2-fast-back" style="font-size: 8px"></i>');
    $('.paginate_button').filter('.next').parent('.page-item').addClass('next');
    $('#datatable_next').html('<i class ="flaticon2-fast-next" style="font-size: 8px"></i>');

    if($('.paginate_button').hasClass('current')) {
        $('.paginate_button').filter('.current').parent('.page-item').addClass('active');
    }
}

function db_length_style()
{
    $('select[name="datatable_length"]')
    .addClass('custom-select custom-select-sm form-control form-control-sm')
    .css({'width':'auto', 'display': 'inline-block'});
    
    $('#datatable_length').css({'margin-left': '8px'});
}

function search_input_and_reset_style()
{
    $('#datatable_filter input')
    .addClass('form-control form-control-ml mb-1 mt-2')
    .css({'width':'300px'});;

    if(!$('div').is('.datatable-reset-search'))
    {
        
        $('#datatable_filter input')
            .after(`
                <div style="margin-left: 8px" class="mb-2 datatable-reset-search">
                    <a href="`+$('#datatable').data('route-reset-datatable-settings')+`">
                        сбросить настройки &#10006;
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
    
    $('.modal-delete').on('click', function(){
        let url = $('#datatable').data('route-destroy') +'/'+ $(this).attr('data-id');
        
        swal.fire({
            text: text + ' ' + $(this).attr(attr_data) + '?',
            showCancelButton: true,
            icon: "warning",
            cancelButtonColor: '#aeaad2',
            confirmButtonText: 'Удалить'
        }).then(function (result) {
            if (result.value === true) {
                let form = $(`
                    <form action="` + url + `" method="post">
                        <input type="hidden" name="_token" value="` + $('#datatable').data('csrf') + `">
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
