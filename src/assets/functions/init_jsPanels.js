$(document).off("click", ".jsPanels");
$(document).on("click", ".jsPanels", function () {
    var $this = $(this);
    var variableName = $this.data('variable_name') || 'jsPanelVariable' + Math.random().toString(30).substr(2, 12);
    var ajaxData = $this.data('ajax_data') || {};
    ajaxData.panelVariableName = variableName;
    var link = $this.data('href');
    var title = $this.data('title');
    var modal = $this.data('modal');
    var just_one_panel = $this.data('just_one_panel') || false;
    modal = modal ? modal : false;

    //console.log(link);
    var h = $(window).height();
    var w = $(window).width();

    if (typeof window[variableName] == 'object' && just_one_panel) {
        window[variableName].close();
    }
    window[variableName] = $.jsPanel({
        contentAjax: {
            url: link,
            method: 'POST',
            dataType: 'json',
            data: ajaxData, // note that data type is set with setRequestHeader()
            done: function (data, textStatus, jqXHR, panel) {
                panel.headerTitle(data.header);
                panel.content.html(data.content);
                panel.toolbarAdd('footer', [{item: data.footer}]);
            }
        },
        headerTitle: title,
        theme: 'bootstrap-info',
        paneltype: modal,
        /*headerControls: {
         minimize: 'disable',
         smallify: 'disable'
         }*/
        contentOverflow: {horizontal: 'hidden', vertical: 'auto'},
        panelSize: {width: w * 0.65, height: h * 0.8}
    });
    window[variableName].content.html('<div class="loader"></div>');
    return false
});

$(document).off('click', '.jsPanelsV4');
$(document).on('click', '.jsPanelsV4', function (e) {
    var $this = $(this);
    var modal = $this.data('modal') || false;
    var variableName = $this.data('variable_name') || 'jsPanelVariable' + Math.random().toString(30).substr(2, 12);
    var container = $this.data('container') || $('body');
    var theme = $this.data('theme') || 'info';
    var position = $this.data('position') || 'center';
    var z_index = $this.data('z_index') || 10000000;
    var href = $this.data('href');
    var preFooterText = $this.data('pre_footer_text') || 'درحال بارگذاری ...';
    var headerTitle = $this.data('pre_header_title') || 'عنوان';
    var ajaxData = $this.data('ajax_data') || {};
    ajaxData.panelVariableName = variableName;

    jsPanel.zi=false;
    jsPanel.ziBase=z_index;
    if (modal) {
        window[variableName] = jsPanel.modal.create({
            container: container,
            position: position,
            theme: theme,
            border: '1px solid',
            headerTitle: headerTitle,
            panelSize: {
                width: function () {
                    return (window.innerWidth * 85) / 100;
                },
                height: function () {
                    return (window.innerHeight * 80) / 100;
                }
            },
            footerToolbar: '<div class="jspanel_footer" style="direction:rtl; width:100%;"><i class="fal fa-clock"></i><span class="clock">' + preFooterText + '</span><div>',
            contentAjax: {
                method: 'POST',
                responseType: 'json',
                url: href,
                data: JSON.stringify(ajaxData), // note that data type is set with setRequestHeader()
                done: function (panel) {
                    //console.log(this.response, this.response.header, this.response.content);
                    panel.setHeaderTitle(this.response.header);
                    panel.content.innerHTML = this.response.content;
                    eval(this.response.inline_js);
                    panel.footer.querySelector('.jspanel_footer').innerHTML = this.response.footer;
                },
                evalscripttags: true,
                beforeSend: function () {
                    this.setRequestHeader('Content-Type', 'application/json');
                    this.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                }
            }
        });
    }
    else {
        window[variableName] = jsPanel.create({
            container: container,
            position: position,
            theme: theme,
            border: '1px solid',
            headerTitle: headerTitle,
            panelSize: {
                width: function () {
                    return (window.innerWidth * 85) / 100;
                },
                height: function () {
                    return (window.innerHeight * 80) / 100;
                }
            },
            footerToolbar: '<div class="jspanel_footer" style="direction:rtl; width:100%;"><i class="fal fa-clock"></i><span class="clock">' + preFooterText + '</span><div>',
            contentAjax: {
                method: 'POST',
                responseType: 'json',
                url: href,
                data: JSON.stringify(ajaxData), // note that data type is set with setRequestHeader()
                done: function (panel) {
                    //console.log(this.response, this.response.header, this.response.content);
                    panel.setHeaderTitle(this.response.header);
                    panel.content.innerHTML = this.response.content;
                    eval(this.response.inline_js);
                    panel.footer.querySelector('.jspanel_footer').innerHTML = this.response.footer;
                },
                //evalscripttags: true,
                beforeSend: function () {
                    this.setRequestHeader('Content-Type', 'application/json');
                    this.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                }
            }
        });
    }
});
