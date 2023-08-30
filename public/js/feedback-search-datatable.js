$(function () {
    let table = $('#datatable').DataTable({
        order: [[1, "desc"]],
        dom: 'frtlp',
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
                {data: 'created_at', 'class':'small', render: function (data) {
                        var date = Date.parse(data);
                        var s = new Date(date).toLocaleDateString("ru-RU")
                        return  s
                    }},
                {data: 'name'},
                {data: 'amo_id'},
                {data: 'status'},
                {data: 'actions', searchable: false, orderable: false, render: function(data, type, row) {
                     let destroy_button = '';
                     if($('#datatable').data('route-destroy-user-can') == 'can') {

                         destroy_button =  ` 

                    <a class="btn btn-icon btn-light btn-hover-primary btn-sm mx-2 modal-delete" data-id=${row.id}>
                        <span class="svg-icon svg-icon-md svg-icon-primary">

                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>

                        </span>
                    </a>`;
                     }
                     return `
                    <a href="${$('#datatable').data('route-show') + '/'+ row.id}" 
                    class="btn btn-icon btn-light btn-hover-primary btn-sm mx-2">
                        <span class="svg-icon svg-icon-md svg-icon-primary">

                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                    <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path>
                                </g>
                            </svg>
                        
                        </span>
                    </a>

                    <a href="${$('#datatable').data('route-edit').replace("//edit", "/"+row.id+"/edit")}" 
                    class="btn btn-icon btn-light btn-hover-primary btn-sm mx-2">
                        <span class="svg-icon svg-icon-md svg-icon-primary">
            
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                        <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                        <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                </g>
                            </svg>
                
                        </span>
                    </a>

                   ${destroy_button}`;
                 }},
        ],
        ajax: {
            url: $('#datatable').data('route-fill-data'),
            method: "POST",
            "beforeSend" : function(xhr) {
              xhr.setRequestHeader('X-CSRF-TOKEN',$('#datatable').data('csrf'));
            }
        },

        "drawCallback": function( settings ) {
            main_wrapper();
            db_length_style();
            pagination_style();
            search_input_and_reset_style();
            show_modal_delete('Вы действительно хотите удалить feedback ','data-id');
            console_alerts();
        }
    });
    
});