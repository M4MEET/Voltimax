import template from './sw-theme-manager.html.twig';
import './sw-theme-manager.scss';

const { Component } = Shopware;

// Override the theme manager detail component to enhance marketing tab display
Component.override('sw-theme-manager-detail', {
    template,

    computed: {
        // Check if we're on the marketing tab
        isMarketingTab() {
            return this.currentTab === 'marketing';
        }
    },

    mounted() {
        this.enhanceMarketingTab();
    },

    updated() {
        if (this.isMarketingTab) {
            this.$nextTick(() => {
                this.enhanceMarketingTab();
            });
        }
    },

    methods: {
        enhanceMarketingTab() {
            // Add custom classes to marketing tab fields for better styling
            const marketingFields = document.querySelectorAll('[class*="voltimaxMarketingScript"], [class*="voltimaxCheckoutScript"]');
            
            marketingFields.forEach(field => {
                if (field.classList.contains('sw-field--textarea')) {
                    field.classList.add('voltimax-script-textarea');
                }
                if (field.classList.contains('sw-field--checkbox') || field.classList.contains('sw-field--switch')) {
                    field.classList.add('voltimax-script-checkbox');
                }
            });

            // Group checkboxes side by side
            this.groupCheckboxes();
        },

        groupCheckboxes() {
            const scripts = [
                'voltimaxMarketingScript1',
                'voltimaxMarketingScript2',
                'voltimaxMarketingScript3',
                'voltimaxMarketingScript4',
                'voltimaxMarketingScript5',
                'voltimaxCheckoutScript1',
                'voltimaxCheckoutScript2',
                'voltimaxCheckoutScript3'
            ];

            scripts.forEach(scriptBase => {
                const activeField = document.querySelector(`.sw-field-id-${scriptBase}Active`);
                const asyncField = document.querySelector(`.sw-field-id-${scriptBase}Async`);
                
                if (activeField && asyncField && !activeField.parentElement.classList.contains('voltimax-checkbox-group')) {
                    // Create wrapper for checkboxes
                    const wrapper = document.createElement('div');
                    wrapper.className = 'voltimax-checkbox-group';
                    
                    // Find the parent container
                    const parent = activeField.parentElement;
                    if (parent) {
                        parent.insertBefore(wrapper, activeField);
                        wrapper.appendChild(activeField);
                        wrapper.appendChild(asyncField);
                    }
                }
            });
        }
    }
});