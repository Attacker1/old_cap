$(function () {
    let table = $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "language": {
            "url": "/app-assets/data/dt_ru1.json",
            "searchPlaceholder": "Поиск всем полям",
            "lengthMenu": "кол-во на странице _MENU_"
        },
        "order": [[
            $('#datatable').data('params-order-column'),
            $('#datatable').data('params-order-dir') ]],
        "search": {
            "search": $('#datatable').data('params-search-value'),
        },

        "iDisplayLength": $('#datatable').data('params-limit-menu'),
        "displayStart": $('#datatable').data('params-paging-start'),
        "errMode": 'none',
        columns: [
            {data: 'actions', searchable: false, orderable: false, render: function(data, type, row) {

                let wrap = $('<div></div>'),
                    a_info = $('<a href="' + $('#datatable').data('route-show') + '/'+ row.uuid + '" style = "font-size:1.3rem"></a>'),
                    i_info = $('<i class="feather icon-eye" aria-hidden="true"></i>'),

                    link_update = $('#datatable').data('route-edit').replace("//edit", "/"+row.uuid+"/edit"),
                    a_update = $('<a href="'+ link_update + '" class="ml-1" style = "font-size:1.3rem"></a>'),
                    i_update = $('<i class="feather icon-edit" aria-hidden="true"></i>'),

                    a_delete = $('<a class="ml-1 text-danger modal-client-delete" style = "font-size:1.3rem" data-id='+ row.uuid + ' data-name='+row.name+'></a>'),
                    i_delete = $('<i class="fa fa-close" aria-hidden="true"></i>'),

                    elem = wrap.append(a_info.append(i_info)).append(a_update.append(i_update)).append(a_delete.append(i_delete));

                    return elem.html();
                }},
            {data: 'uuid', 'class':'small'},
            {data: 'referal_code'},
            {data: 'name'},
            {data: 'second_name'},
            {data: 'phone', 'class': 'text-nowrap', render: function (data, type, row) {
                return format_phone(data);    
            }},
            {data: 'email', 'class':'small'},
            {data: 'comments'},
            {data: 'client_status', render: function (data, type, row) {
                    if(data === null) return '';
                    return data.name
                }},
            {data: 'created_at', 'class':'small'},
            {data: 'updated_at', 'class':'small'}
        ],
        ajax: {
            url: $('#datatable').data('route-fill-data'),
            method: "POST",
        },

        "drawCallback": function( settings ) {

            db_length_style();
            pagination_style();
            search_input_and_reset_style();

            show_modal_delete('Вы действительно хотите удалить клиента ','data-name');
            console_alerts();
        }
    });
    
});