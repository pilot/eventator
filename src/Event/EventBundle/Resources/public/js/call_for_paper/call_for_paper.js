$(function() {
    var $body = $('body');

    /* calls list table */
    var dataTable = $('#call-for-paper').DataTable({
        processing: false,
        serverSide: true,
        order: [[ 5, 'desc' ]],
        pagingType: 'full_numbers',
        dom: "<'row'<'span3'l><'span6'f>r>t<'row'<'span3'i><'span6'p>>",
        ajax: {
            url: Routing.generate('backend_call_for_paper_ajax_calls_list'),
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
            {searchable: false, orderable: true, targets: 0},
            {searchable: true, orderable: true, targets: 1},
            {searchable: false, orderable: true, targets: 2},
            {searchable: false, orderable: true, targets: 3},
            {searchable: false, orderable: true, targets: 4,
                render: function(data, type, row) {
                    var label = '';
                    if (!data['id']) {
                        label = 'label-info';
                    } else if (data.id == 1) {
                        label = 'label-success';
                    }

                    return '<span class="label ' + label + '">' + data['name'] + '</span>';
                }
            },
            {searchable: false, orderable: true, targets: 5},
            {searchable: false, orderable: false, targets: 6, width: '15%',
                render: function(data, type, row) {
                    var statusLi = '';
                    if (!row[4]['id']) {
                        statusLi = '\
                            <li><a class="change-status approve" href="#" data-id="' + row[0] + '" data-status="1"><i class="icon-ok"></i> Approve</a></li>\
                            <li><a class="change-status decline" href="#" data-id="' + row[0] + '" data-status="2"><i class="icon-remove"></i> Decline</a></li>\
                        ';
                    } else if (row[4]['id'] == 1) {
                        statusLi = '<li><a class="change-status decline" href="#" data-id="' + row[0] + '" data-status="2"><i class="icon-remove"></i> Decline</a></li>';
                    } else if (row[4]['id'] == 2) {
                        statusLi = '<li><a class="change-status approve" href="#" data-id="' + row[0] + '" data-status="1"><i class="icon-ok"></i> Approve</a></li>';
                    }
                    return '\
                        <div class="btn-group">\
                            <a class="btn btn-small" href="' + data['showUrl'] + '"><i class="icon-file"></i> Show</a>\
                            <a class="btn btn-small dropdown-toggle actions-' + row[0] + '" data-toggle="dropdown" href="#"> <span class="caret"></span></a>\
                            <ul class="dropdown-menu">\
                                ' + statusLi + '\
                                <li><a href="' + data['deleteUrl'] + '" class="delete-call" id="modal-confirm-' + row[0] + '"><i class="icon-trash"></i> Delete</a></li>\
                            </ul>\
                        </div>\
                    ';
                }
            }
        ]
    });
    /* end calls list table */

    $body.on('change', '#select-event', function() {
        dataTable.ajax.reload();
    });

    /* change status */
    $body.on('click', '.change-status', function(e) {
        e.preventDefault();
        var $that = $(this),
            id = $that.attr('data-id'),
            status = $that.attr('data-status');

        $.ajax({
            url: Routing.generate('backend_call_for_paper_change_status', {id: id, status: status}),
            method: "POST",
            success: function (response) {
                dataTable.draw(false);
            }
        });
    });
    /* end change status */
});
