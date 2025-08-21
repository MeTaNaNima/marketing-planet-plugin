<?php

?>
<div class="question-tag-selector-wrapper">
  <label for="faq_repeater_tag">Select Title Tag for All FAQs:</label>
  <select name="faq_repeater_tag" id="faq_repeater_tag">
    <option value="h1" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'h1'); ?>>H1</option>
    <option value="h2" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'h2'); ?>>H2</option>
    <option value="h3" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'h3'); ?>>H3</option>
    <option value="h4" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'h4'); ?>>H4</option>
    <option value="h5" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'h5'); ?>>H5</option>
    <option value="h6" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'h6'); ?>>H6</option>
    <option value="p" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'p'); ?>>p</option>
    <option value="div" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'div'); ?>>div</option>
    <option value="span" <?php selected(get_post_meta($post->ID, '_faq_repeater_tag', true), 'span'); ?>>span</option>
  </select>
</div>
<table class="form-table" id="faq-repeater-table">
  <tbody>
    <?php foreach ($faqs as $index => $faq): ?>
      <tr>
        <td>
          <div class="question"><strong>Question:</strong>
            <input type="text" name="faq_repeater[<?= $index ?>][question]" value="<?= esc_attr($faq['question'] ?? '') ?>" style="width:100%;" />
          </div>

          <div class="answer">
            <strong>Answer:</strong>
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
          </div>
          <button type="button" class="button remove-faq">Remove</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div class="add-button-wrapper">
  <button type="button" class="button button-primary add-faq" id="add-faq">Add FAQ</button>
</div>

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
                <div class="question">
                <strong>Question:</strong>
                <input type="text" name="faq_repeater[${faqIndex}][question]" style="width:100%;" />
                </div>
                <div class="answer">
                <strong>Answer:</strong>
                <textarea id="${newId}" name="faq_repeater[${faqIndex}][answer]" rows="4" style="width:100%;"></textarea>
                </div>
                <button type="button" class="button remove-faq">Remove</button>
            </td>`;

      table.appendChild(row);

      // Re-init TinyMCE
      if (typeof tinymce !== 'undefined') {
        tinymce.init({
          selector: `#${newId}`,
          menubar: false,
          toolbar: 'formatselect | bold italic underline | bullist numlist | link | forecolor',
          plugins: 'link image lists charmap hr fullscreen media paste textcolor', // Include additional plugins for extra features
          quicktags: true,
          height: 150,
          branding: false,

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