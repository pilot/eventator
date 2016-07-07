$(function() {
    var $body = $('body');

    /* speeches list table */
    var dataTable = $('#speeches').DataTable({
        processing: false,
        serverSide: true,
        lengthMenu: [50, 100, 200, 300],
        order: [[0, 'desc']],
        pagingType: 'full_numbers',
        dom: "<'row'<'span3'l><'span6'f>r>t<'row'<'span3'i><'span6'p>>",
        ajax: {
            url: Routing.generate('backend_speech_ajax_speeches_list'),
            data: function (d) {
                d.event = $('#select-event').val();
            }
        },
        language: {
            lengthMenu: "_MENU_ records per page",
            infoEmpty: '0 of 0 entries'
        },
        createdRow: function(row, data, index) {
            $(row).attr('id', 'item-' + data[0]);
        },
        columnDefs: [
            {searchable: false, orderable: true, targets: 0, width: '5%'},
            {searchable: true, orderable: true, targets: 1, width: '25%'},
            {searchable: false, orderable: true, targets: 2},
            {searchable: false, orderable: false, targets: 3, width: '10%',
                render: function(data, type, row) {
                    return '\
                        <div class="btn-group">\
                            <a class="btn btn-small" href="' + data['editUrl'] + '"><i class="icon-edit"></i> Edit</a>\
                            <a class="btn btn-small dropdown-toggle actions-' + row[0] + '" data-toggle="dropdown" href="#"><span class="caret"></span></a>\
                            <ul class="dropdown-menu">\
                                <li><a href="' + data['deleteUrl'] + '" class="delete-call" id="modal-confirm-' + row[0] + '"><i class="icon-trash"></i> Delete</a></li>\
                            </ul>\
                        </div>\
                    ';
                }
            }
        ]
    });
    /* end speeches list table */

    $body.on('change', '#select-event', function() {
        dataTable.ajax.reload();
    });
});
