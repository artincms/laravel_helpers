function init_isic_tree(selector_id, select2_selector_id, select2_selector_name, allowClear, multiple, global_variable, jstree_route, datatable_route, selectable_id) {
    global_variable = global_variable || selector_id;
    // jstree_route = jstree_route || isic_tree_route;
    datatable_route = datatable_route || isic_datatable_route;
    selectable_id = selectable_id || "";
    if (multiple) {
        allowClear = false;
    }
    var html_elements = '' +
        '<div class="form-group">' +
        //          '   <label class="control-label col-lg-2">عنوان آیسیک</label>' +
        '   <div class="col-lg-12">' +
        '       <div class="col-sm-12 pdr-0">' +
        '           <button class="close jsPanels" type="button" title="افزودن آیسیک"' +
        '               data-modal="modal"' +
        '               data-href="' + isic_jspanel_route + '?' +
        '               id=' + select2_selector_id + '_btn_add_isic&' +
        '               target_element_id=' + selector_id + '&' +
        '               modal_id=' + select2_selector_id + '&' +
        '               multiple=' + multiple + '&' +
        '               selectable_id=' + selectable_id + '&' +
        '               global_variable=' + global_variable + '&"  data-title="انتخاب آیسیک" style="font-size: 12px; color: red;">' +
        '               <i class="fa fa-plus"></i>' +
        '           </button>' +
        '           <select name="' + select2_selector_name + '" id="' + select2_selector_id + '"></select>' +
        '           <div class="space-4"></div>' +
        '       </div>' +
        '   </div>' +
        '</div>';
    $('#' + selector_id).html(html_elements);
    init_select2_ajax('#' + select2_selector_id, isic_select2_route, allowClear, multiple, false, false, selectable_id);
}
