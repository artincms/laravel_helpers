function init_data_persian_date_picker(div_id) {
    $('#'+div_id).MdPersianDateTimePicker({
        Placement: 'left',
        Trigger: 'click',
        EnableTimePicker: false,
        TargetSelector: '#ElementId',
        GroupId: '',
        ToDate: false,
        FromDate: false,
        DisableBeforeToday: false,
        Disabled: false,
        Format: 'yyyy/MM/dd',
        IsGregorian: false,
        EnglishNumber: false,
        InLine: false
    });
}
