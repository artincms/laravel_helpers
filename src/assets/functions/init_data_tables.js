var CommonDom_DataTables = '<"datatable_custom_proccessing" r><"datatable-header"fl><"datatable-scroll"t ><"datatable-footer"ip>';
var LangJson_DataTables = {
    "decimal": "",
    "emptyTable": "هیچ داده ای در جدول وجود ندارد",
    "info": "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
    "infoEmpty": "نمایش 0 تا 0 از 0 رکورد",
    "infoFiltered": "(فیلتر شده از _MAX_ رکورد)",
    "infoPostFix": "",
    "thousands": ",",
    "lengthMenu": "نمایش _MENU_ رکورد",
    "loadingRecords": "در حال بارگزاری...",
    "processing": "در حال پردازش...",
    "search": "جستجو: ",
    "zeroRecords": "رکوردی با این مشخصات پیدا نشد",
    "paginate": {
        "first": "ابتدا",
        "last": "انتها",
        "next": "بعدی",
        "previous": "قبلی"
    },
    "aria": {
        "sortAscending": ": فعال سازی نمایش به صورت صعودی",
        "sortDescending": ": فعال سازی نمایش به صورت نزولی"
    }
};

$.extend($.fn.dataTable.defaults, {
    autoWidth: false,
    dom: CommonDom_DataTables,
    language: LangJson_DataTables,
    processing: true,
    serverSide: true,
    drawCallback: function () {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
    },
    preDrawCallback: function () {
        $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
    }
});

function dataTablesGrid(selector, var_grid_name, url, columns, more_data, initComplete, scrollX, scrollY, scrollCollapse, orderBy, orderByDesc, row_select, fixedColumns, start_item) {
    scrollX = scrollX || false;
    scrollY = scrollY || false;
    scrollCollapse = scrollCollapse || false;
    orderBy = orderBy || 0;
    orderByDesc = orderByDesc || "desc";
    more_data = more_data || {};
    row_select = row_select || false;
    start_item = start_item || 0;
    fixedColumns = fixedColumns || false;
    var columnDefs = [];
    window[var_grid_name + '_rows_selected'] = [];
    var select_all_class = var_grid_name + '_select_all';
    var select_one_class = var_grid_name + '_select_one';
    if (row_select) {
        checkbox_column = {
            title: '<input class="' + select_all_class + '" value="1" type="checkbox"/>',
            searchable: false,
            orderable: false,
            width: '1%',
            className: 'dt-body-center',
            render: function (data, type, full, meta) {
                return '<input class="' + select_one_class + '" type="checkbox">';
            }
        };
        columns.unshift(checkbox_column);
    }

    var dataTableOptionObj =
        {
            initComplete: function () {
                if (initComplete == true) {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="filter-select" data-placeholder="Filter"><option value=""></option></select>')
                            .appendTo($(column.footer()).not(':last-child').empty())
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            },
            displayStart: start_item,
            fixedColumns: fixedColumns,
            ajax: {
                url: url,
                type: 'POST',
                data: more_data
            },
            columns: columns,
            scrollX: scrollX,
            scrollY: scrollY,
            scrollCollapse: scrollCollapse,
            order: [[orderBy, orderByDesc]],
            rowCallback: function (row, data, dataIndex) {
                if (row_select) {
                    var rowId = data;
                    if (func_search_in_obj('id', data['id'], window[var_grid_name + '_rows_selected'])) {
                        $(row).find('.' + select_one_class).prop('checked', true);
                        $(row).addClass('selected');
                    }
                }
            },
            destroy: true,
        };

    if (!scrollY) {
        delete dataTableOptionObj.scrollY;
        delete dataTableOptionObj.scrollCollapse;
    }

    window[var_grid_name] = $(selector).DataTable(dataTableOptionObj);

    if (row_select) {
        $(document).on('click', '.' + select_one_class, function (e) {

            var $row = $(this).closest('tr');
            var clicked_row_number = $row.data('dt-row');
            if (fixedColumns) {
                var fixed_scrolled_rows = $(selector + '_wrapper .dataTables_scrollBody tr');
                var $fixed_scrolled_rows = $(fixed_scrolled_rows[clicked_row_number + 1]);
                var fixed_right_rows = $(selector + '_wrapper .DTFC_RightBodyWrapper tr');
                var $fixed_right_rows = $(fixed_right_rows[clicked_row_number + 1]);
            }

            // Get row data
            var data = window[var_grid_name].row($row).data();
            // Determine whether row data is in the list of selected row datas
            var index = $.inArray(data, window[var_grid_name + '_rows_selected']);
            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
                window[var_grid_name + '_rows_selected'].push(data);
                // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
            } else if (!this.checked && index !== -1) {
                window[var_grid_name + '_rows_selected'].splice(index, 1);
            }
            if (this.checked) {
                $row.addClass('selected');
                if (fixedColumns) {
                    $fixed_scrolled_rows.addClass('selected');
                    $fixed_right_rows.addClass('selected');
                }
            } else {
                $row.removeClass('selected');
                if (fixedColumns) {
                    $fixed_scrolled_rows.removeClass('selected');
                    $fixed_right_rows.removeClass('selected');
                }
            }
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(window[var_grid_name], select_one_class, select_all_class,fixedColumns);
            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle click on table cells with checkboxes
        // $(selector).on('click', 'tbody td, thead th:first-child', function (e) {
        //     $(this).parent().find('.'+select_one_class).trigger('click');
        // });

        // Handle click on "Select all" control
        $(document).on('click', '.' + select_all_class, function (e) {
            if (fixedColumns) {
                if (this.checked) {
                    $('.DTFC_LeftBodyWrapper .' + select_one_class + ':not(:checked)').trigger('click');
                } else {
                    $('.DTFC_LeftBodyWrapper .' + select_one_class + ':checked').trigger('click');
                }
            }
            else
            {
                if (this.checked) {
                    $('.' + select_one_class + ':not(:checked)').trigger('click');
                } else {
                    $('.' + select_one_class + ':checked').trigger('click');
                }
            }


            // Prevent click event from propagating to parent
            e.stopPropagation();
        });

        // Handle table draw event
        window[var_grid_name].on('draw', function () {
            // Update state of "Select all" control
            setTimeout(function () {
                updateDataTableSelectAllCtrl(window[var_grid_name], select_one_class, select_all_class,fixedColumns);
            },500)
        });
    }
}

function updateDataTableSelectAllCtrl(table, select_one_class, select_all_class,fixedColumns) {

    if (fixedColumns)
    {
        window.$chkbox_all = $('.DTFC_LeftBodyWrapper  .' + select_one_class);
        window.$chkbox_checked = $('.DTFC_LeftBodyWrapper  .' + select_one_class + ':checked');
    }
    else
    {
        window.$chkbox_all = $('.' + select_one_class);
        window.$chkbox_checked = $('.' + select_one_class + ':checked');
    }
    window.chkbox_select_all = $('.' + select_all_class);
    // If none of the checkboxes are checked
    if ($chkbox_checked.length === 0) {
        chkbox_select_all.prop('checked', false);
        chkbox_select_all.prop("indeterminate", false);
        // If all of the checkboxes are checked
    } else if ($chkbox_checked.length === $chkbox_all.length) {
        chkbox_select_all.prop('checked', true);
        chkbox_select_all.prop("indeterminate", false);

        // If some of the checkboxes are checked
    } else {
        chkbox_select_all.prop('checked', false);
        chkbox_select_all.prop("indeterminate", true);
    }
}
