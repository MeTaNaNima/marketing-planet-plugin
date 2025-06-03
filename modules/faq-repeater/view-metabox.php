<?php

?>
<table class="form-table" id="faq-repeater-table">
    <tbody>
        <?php foreach ($faqs as $index => $faq): ?>
        <tr>
            <td>
                <strong>Question:</strong><br>
                <input type="text" name="faq_repeater[<?= $index ?>][question]" value="<?= esc_attr($faq['question'] ?? '') ?>" style="width:100%;" />
                <br><br>
                <strong>Answer:</strong><br>
                <?php
                wp_editor(
                    $faq['answer'] ?? '',
                    "faq_repeater_{$index}_answer",
                    [
                        'textarea_name' => "faq_repeater[{$index}][answer]",
                        'textarea_rows' => 4,
                        'media_buttons' => false,
                        'teeny' => false,
                        'quicktags' => true,
                        'editor_class' => 'faq-answer-editor',
                    ]
                );
                ?>
                <br>
                <button type="button" class="button remove-faq">Remove</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<p><button type="button" class="button button-primary" id="add-faq">Add FAQ</button></p>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const table = document.querySelector('#faq-repeater-table tbody');
        let faqIndex = table.children.length;

        // Add new FAQ row
        document.getElementById('add-faq').addEventListener('click', () => {
            const newId = `faq_repeater_${faqIndex}_answer`;

            const row = document.createElement('tr');
            row.innerHTML = `
            <td>
                <strong>Question:</strong><br>
                <input type="text" name="faq_repeater[${faqIndex}][question]" style="width:100%;" /><br><br>
                <strong>Answer:</strong><br>
                <textarea id="${newId}" name="faq_repeater[${faqIndex}][answer]" rows="4" style="width:100%;"></textarea><br>
                <button type="button" class="button remove-faq">Remove</button>
            </td>`;

            table.appendChild(row);

            // Re-init TinyMCE
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: `#${newId}`,
                    menubar: false,
                    toolbar: 'bold italic underline | bullist numlist | link',
                    quicktags: true,
                    height: 150
                });
            }

            faqIndex++;
        });

        // Remove FAQ row
        table.addEventListener('click', e => {
            if (e.target.classList.contains('remove-faq')) {
                const row = e.target.closest('tr');
                const textarea = row.querySelector('textarea');
                if (textarea && tinymce.get(textarea.id)) {
                    tinymce.get(textarea.id).remove();
                }
                row.remove();
            }
        });
    });
</script>

