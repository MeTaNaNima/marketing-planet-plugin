(function() {
    tinymce.PluginManager.add('mp_notice_button', function(editor, url) {
        editor.addButton('mp_notice_button', {
            type: 'menubutton',
            text: 'Insert Notice',
            icon: false,
            menu: [
                {text: 'Info', onclick: function() { insertNotice('info'); }},
                {text: 'Success', onclick: function() { insertNotice('success'); }},
                {text: 'Warning', onclick: function() { insertNotice('warning'); }},
                {text: 'Danger', onclick: function() { insertNotice('danger'); }}
            ]
        });

        function insertNotice(type) {
            const selectedText = editor.selection.getContent({format: 'text'}) || 'Notice text';
            editor.insertContent('[notice type="' + type + '"]' + selectedText + '[/notice]');
        }
    });
})();
