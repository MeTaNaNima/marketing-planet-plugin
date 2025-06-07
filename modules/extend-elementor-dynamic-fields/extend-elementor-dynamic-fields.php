<?php

$GLOBALS['marketing_planet_module_titles']['extend-elementor-dynamic-fields'] = 'Extend Elementor Dynamic Fields';

class ExtendElementorDynamicFields {
    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        if ($this->is_elementor_active()) {
            add_filter('elementor_pro/display_conditions/dynamic_tags/custom_fields_meta_limit', [$this, 'increase_meta_limit']);
            add_action('elementor/editor/footer', [$this, 'inject_search_script']);
        } else {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
        }
    }

    /**
     * Check if Elementor and Elementor Pro are active
     */
    private function is_elementor_active(): bool {
        return did_action('elementor/loaded') && class_exists('\ElementorPro\Plugin');
    }

    /**
     * Increase the custom fields limit
     */
    public function increase_meta_limit($limit) {
        return 1000;
    }

    /**
     * Inject JavaScript for searchable dropdown in Elementor editor
     */
    public function inject_search_script() {
        if (!\Elementor\Plugin::instance()->editor->is_edit_mode()) return;
        ?>
        <script type="text/javascript">
            function addSearchInput() {
                const menuList = document.querySelector('.e-conditions-select-menu .MuiMenu-list');
                if (menuList && !menuList.querySelector('.search-input-item')) {
                    const searchInputItem = document.createElement('li');
                    searchInputItem.classList.add('search-input-item');
                    searchInputItem.innerHTML = '<input type="text" placeholder="Search..." oninput="filterMenu(this)" />';
                    searchInputItem.querySelector('input').addEventListener('keydown', e => e.stopPropagation());
                    menuList.insertBefore(searchInputItem, menuList.firstChild);
                }
            }

            function filterMenu(input) {
                const filter = input.value.toLowerCase();
                const items = input.closest('ul').querySelectorAll('li:not(.search-input-item)');
                items.forEach(item => {
                    item.style.display = item.textContent.toLowerCase().includes(filter) ? '' : 'none';
                });
            }

            const observer = new MutationObserver(mutations => {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (node.classList && node.classList.contains('MuiPopover-root')) {
                            addSearchInput();
                        }
                    });
                });
            });

            observer.observe(document.body, { childList: true, subtree: true });

            const style = document.createElement('style');
            style.textContent = `
                .search-input-item {
                    position: sticky;
                    top: 0;
                    background: white;
                    z-index: 10;
                    padding: 5px;
                }
                .search-input-item input {
                    width: 100%;
                    box-sizing: border-box;
                    padding: 5px;
                }
            `;
            document.head.appendChild(style);
        </script>
        <?php
    }

    /**
     * Show an admin notice if Elementor or Pro is not active
     */
    public function admin_notice_missing_elementor() {
        if (current_user_can('activate_plugins')) {
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p><strong>Extend Elementor Dynamic Fields</strong> requires both <strong>Elementor</strong> and <strong>Elementor Pro</strong> to be active.</p>';
            echo '</div>';
        }
    }
}

new ExtendElementorDynamicFields();
