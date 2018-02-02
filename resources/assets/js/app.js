/**
 * @author lufficc
 */

require('./boot');

(function ($) {
    let Xblog = {
        init: function () {
            this.bootUp();
            $('[data-toggle="tooltip"]').tooltip();
        },
        bootUp: function () {
            console.log('bootUp');
            loadComments(false, false);
            initComment();
            initMarkdownTarget();
            initTables();
            autoSize();
            initProjects();
            initDeleteTarget();
            highLightCode();
        },
    };

    function initDeleteTarget() {
        $('.swal-dialog-target').append(function () {
            return "\n" +
                "<form action='" + $(this).attr('data-url') + "' method='post' style='display:none'>\n" +
                "   <input type='hidden' name='_method' value='" + ($(this).data('method') ? $(this).data('method') : 'delete') + "'>\n" +
                "   <input type='hidden' name='_token' value='" + XblogConfig.csrfToken + "'>\n" +
                "</form>\n"
        }).click(function () {
            let deleteForm = $(this).find("form");
            let method = ($(this).data('method') ? $(this).data('method') : 'delete');
            let url = $(this).attr('data-url');
            let data = $(this).data('request-data') ? $(this).data('request-data') : '';
            let title = $(this).data('dialog-title') ? $(this).data('dialog-title') : '删除';
            let message = $(this).data('dialog-msg');
            let type = $(this).data('dialog-type') ? $(this).data('dialog-type') : 'danger';
            let cancel_text = $(this).data('dialog-cancel-text') ? $(this).data('dialog-cancel-text') : '取消';
            let confirm_text = $(this).data('dialog-confirm-text') ? $(this).data('dialog-confirm-text') : '确定';
            let enable_html = $(this).data('dialog-enable-html') == '1';
            let enable_ajax = $(this).data('enable-ajax') == '1';
            console.log(data);
            dialog({
                    title: title,
                    body: message,
                    type: type,
                    cancel: cancel_text,
                    confirm: confirm_text
                },
                function () {
                    deleteForm.submit();
                });
        });
    }

    function loadComments(shouldMoveEnd, force) {
        let container = $('#comments-container');
        if (force || container.children().length <= 0) {
            console.log("loading comments");
            $.ajax({
                method: 'get',
                url: container.data('api-url'),
            }).done(function (data) {
                container.html(data);
                initDeleteTarget();
                highLightCodeOfChild(container);
                if (shouldMoveEnd) {
                    moveEnd($('#comment-submit'));
                }
            });
        }
    }

    function initComment() {
        let form = $('#comment-form');
        let submitBtn = form.find('#comment-submit');
        let commentContent = form.find('#comment-content');

        let username = form.find('input[name=username]');
        let email = form.find('input[name=email]');
        let site = form.find('input[name=site]');
        let captcha = form.find('input[name=captcha]');

        if (window.localStorage) {
            username.val(localStorage.getItem('comment_username') === undefined ? '' : localStorage.getItem('comment_username'));
            email.val(localStorage.getItem('comment_email') === undefined ? '' : localStorage.getItem('comment_email'));
            site.val(localStorage.getItem('comment_site') === undefined ? '' : localStorage.getItem('comment_site'));
        }

        form.on('submit', function () {
            if (username.length > 0) {
                if ($.trim(username.val()) === '') {
                    username.focus();
                    return false;
                }
                else if ($.trim(email.val()) === '') {
                    email.focus();
                    return false;
                }
            }

            if ($.trim(commentContent.val()) === '') {
                commentContent.focus();
                return false;
            }

            let usernameValue = username.val();
            let emailValue = email.val();
            let siteValue = site.val();
            let captchaValue = captcha ? captcha.val() : '';

            submitBtn.val('提交中...').addClass('disabled').prop('disabled', true);
            $.ajax({
                method: 'post',
                url: $(this).attr('action'),
                headers: {
                    'X-CSRF-TOKEN': XblogConfig.csrfToken
                },
                data: {
                    commentable_id: form.find('input[name=commentable_id]').val(),
                    commentable_type: form.find('input[name=commentable_type]').val(),
                    content: commentContent.val(),
                    username: usernameValue,
                    email: emailValue,
                    site: siteValue,
                    captcha: captchaValue,
                },
            }).done(function (data) {
                if (data.status === 200) {
                    if (window.localStorage) {
                        localStorage.setItem('comment_username', usernameValue);
                        localStorage.setItem('comment_email', emailValue);
                        localStorage.setItem('comment_site', siteValue);
                    }
                    username.val('');
                    email.val('');
                    site.val('');
                    commentContent.val('');
                    form.find('#comment_submit_msg').attr('class', 'text-success').text('Comment succeed! Will be shown after review.');
                    loadComments(true, true);
                } else {
                    form.find('#comment_submit_msg').attr('class', 'text-danger').text(data.msg);
                }
            }).always(function () {
                submitBtn.val("回复").removeClass('disabled').prop('disabled', false);
                form.find('#comment_submit_msg').fadeIn();
                form.find('#captcha').attr('src', '/captcha/flat?' + Math.random());
                setTimeout(function () {
                    form.find('#comment_submit_msg').fadeOut();
                }, 1500);
            });
            return false;
        });
    }

    function initMarkdownTarget() {
        $('.markdown-target').each(function (i, element) {
            element.innerHTML =
                marked($(element).data("markdown"), {
                    renderer: new marked.Renderer(),
                    gfm: true,
                    tables: true,
                    breaks: false,
                    pedantic: false,
                    smartLists: true,
                    smartypants: false,
                });
        });
    }

    function highLightCode() {
        $('pre code').each(function (i, block) {
            hljs.highlightBlock(block);
        });
    }

    function highLightCodeOfChild(parent) {
        $('pre code', parent).each(function (i, block) {
            console.log(block);
            hljs.highlightBlock(block);
        });
    }

    function initTables() {
        $('.post-detail-content table').addClass('table table-striped table-responsive');
    }

    function autoSize() {
        autosize($('.autosize-target'));
    }

    function initProjects() {
        let projects = $('.projects');
        if (projects.length > 0) {
            $.get('https://api.github.com/users/' + XblogConfig.github_username + '/repos?type=owner',
                function (repositories) {
                    if (!repositories) {
                        projects.html('<div><h3>加载失败</h3><p>请刷新或稍后再试...</p></div>');
                        return;
                    }
                    projects.html('');
                    repositories = repositories.sort(function (repo1, repo2) {
                        return repo2.stargazers_count - repo1.stargazers_count;
                    });
                    repositories = repositories.filter(function (repo) {
                        return repo.description != null;
                    });
                    repositories.forEach(function (repo) {
                        let repoTemplate = $('#repo-template').html();
                        let item = repoTemplate.replace(/\[(.*?)\]/g, function () {
                            return eval(arguments[1]);
                        });
                        projects.append(item)
                    });
                    projects.attr('data-masonry', '{ "itemSelector": ".col", "columnWidth":".col" }');
                    projects.masonry();
                });
        }
    }

    window.Xblog = Xblog;
})(jQuery);
$(document).ready(function () {
    Xblog.init();
});

window.replySomeone = function (username) {
    if (!username)
        return;
    let commentContent = $("#comment-content");
    let oldContent = commentContent.val();
    prefix = "@" + username + " ";
    let newContent = '';
    if (oldContent.length > 0) {
        newContent = oldContent + "\n" + prefix;
    } else {
        newContent = prefix
    }
    commentContent.focus();
    commentContent.val(newContent);
    moveEnd(commentContent);
}

window.moveEnd = function (obj) {
    obj.focus();
    let len = obj.value === undefined ? 0 : obj.value.length;

    if (document.selection) {
        let sel = obj.createTextRange();
        sel.moveStart('character', len);
        sel.collapse();
        sel.select();
    } else if (typeof obj.selectionStart == 'number' && typeof obj.selectionEnd == 'number') {
        obj.selectionStart = obj.selectionEnd = len;
    }
};
