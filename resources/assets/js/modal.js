window.dialog = function (settings, onConform) {
    options = {
        title: "删除",
        body: '确定操作？',
        cancel: "取消",
        confirm: "确定",
        type: "danger",
    };
    $.extend(options, settings);

    if ($('#xblog-modal').length === 0) {
        let html = '<div class="modal fade" id="xblog-modal" tabindex="-1" role="dialog" aria-labelledby="Xblog modal" aria-hidden="true">';
        html += '<div class="modal-dialog" role="document">';
        html += '<div class="modal-content">';
        html += '<div class="modal-header">';
        html += '<h5 class="modal-title" id="xblog-modal-title">确定</h5>';
        html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        html += '</div>';
        html += '<div id="xblog-modal-body" class="modal-body"></div>';
        html += '<div class="modal-footer">';
        html += '<button type="button" id="xblog-modal-cancel" class="btn btn-secondary" data-dismiss="modal">取消</button>';
        html += '<button type="button" id="xblog-modal-confirm" class="btn">确定</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $('body').append(html);
    }
    let modal = $('#xblog-modal');
    let modalTitle = modal.find('#xblog-modal-title');
    modalTitle.html(options.title);
    modalTitle.attr('class', 'modal-title text-' + options.type);
    modal.find('#xblog-modal-body').html(options.body);
    modal.find('#xblog-modal-cancel').html(options.cancel);
    modal.find('#xblog-modal-confirm').html(options.confirm);
    let confirm = modal.find('#xblog-modal-confirm');
    confirm.attr('class', 'btn btn-' + options.type);
    confirm.click(function () {
        if (onConform)
            onConform();
        modal.modal('hide');
    });
    modal.modal('show');
};
