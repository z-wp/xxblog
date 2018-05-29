import SimpleMDE from 'simplemde';
import 'simplemde/dist/simplemde.min.css';

require('./codemirror-4.inline-attachment');
(function ($) {
    function initEditor() {
        $(document).ready(function () {
            let target = $('#simplemde-textarea');
            if (target.length === 0) {
                return;
            }
            let simplemde = new SimpleMDE({
                autoDownloadFontAwesome: true,
                autosave: {
                    enabled: true,
                    uniqueId: target.data('save-id'),
                    delay: 1000,
                },
                element: document.getElementById('simplemde-textarea'),
                insertTexts: {
                    image: ["![](", ")"],
                    link: ["[", "]()"],
                },
                renderingConfig: {
                    codeSyntaxHighlighting: true,
                },
                spellChecker: false,
                toolbar: ["bold", "italic", "heading", "|", "quote", 'code', 'ordered-list', 'unordered-list', 'link',
                    'image',
                    {
                        name: "gallery",
                        action: function (simplemde) {
                            let cm = simplemde.codemirror;
                            let pos = cm.getCursor();
                            cm.setSelection(pos, pos);
                            let gallery = '<div markdown="1" class="figure third"  caption="">\n' +
                                '\n' +
                                '</div>\n';
                            cm.replaceSelection(gallery);
                            cm.focus();
                        },
                        className: "fa fa-newspaper-o",
                        title: "Gallery",
                    },
                    {
                        name: "note",
                        action: function (simplemde) {
                            let cm = simplemde.codemirror;
                            let pos = cm.getCursor();
                            cm.setSelection(pos, pos);
                            let note = '<div markdown="1" class="alert alert-info">\n' +
                                '\n' +
                                '</div>\n';
                            cm.replaceSelection(note);
                            cm.focus();
                        },
                        className: "fa fa-info",
                        title: "Note",
                    },
                    'table',
                    '|', 'preview', 'side-by-side', 'fullscreen'],
            });
            inlineAttachment.editors.codemirror4.attach(simplemde.codemirror, {
                uploadUrl: $("#upload-img-url").data('upload-img-url'),
                uploadFieldName: 'image',
                extraParams: {
                    '_token': XblogConfig.csrfToken,
                    'type': 'xrt'
                },
            });
        });

    }

    initEditor();
})(jQuery);
