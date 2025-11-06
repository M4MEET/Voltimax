# Technical Documentation - Voltimax Theme 3.0.0

## Architecture Overview

### Plugin Structure
```
Voltimax-3.0.0/
├── src/
│   ├── VoltimaxTheme.php              # Main plugin class
│   ├── Resources/
│   │   ├── app/storefront/
│   │   │   ├── build/
│   │   │   │   └── webpack.config.js  # Webpack configuration
│   │   │   ├── src/
│   │   │   │   ├── main.js           # JavaScript entry point
│   │   │   │   └── scss/
│   │   │   │       ├── base.scss      # Main styles
│   │   │   │       └── component/
│   │   │   │           └── _cheaper-ad.scss  # Plugin compatibility styles
│   │   ├── config/
│   │   │   ├── config.xml             # Plugin configuration
│   │   │   └── services.xml           # Service definitions
│   │   ├── views/storefront/         # Twig templates
│   │   └── theme.json                 # Theme configuration
├── composer.json                      # Composer dependencies
├── package.json                       # NPM dependencies
└── CHANGELOG.md                       # Version history
```

## Key Technical Changes

### 1. Template Inheritance Pattern
```twig
{# Before (v2.2.3) - Blocked plugin content #}
{% block page_product_detail_content %}
    <div class="product-detail-content">
        <!-- Custom content without parent() -->
    </div>
{% endblock %}

{# After (v3.0.0) - Allows plugin content #}
{% block page_product_detail_content %}
    <div class="product-detail-content">
        <!-- Custom content -->
    </div>
    {{ parent() }} {# Critical: Allows plugins to inject content #}
{% endblock %}
```

### 2. SCSS Variable Usage
```scss
// Before - CSS custom properties in SCSS functions (breaks compilation)
background-color: darken(var(--sw-color-brand-primary), 10%);

// After - Proper SCSS variables
background-color: darken($sw-color-brand-primary, 10%);
```

### 3. Deprecated Twig Functions
```twig
{# Before - Using deprecated feature flag #}
{% if feature('ACCESSIBILITY_TWEAKS') %}
    <button>...</button>
{% else %}
    <a>...</a>
{% endif %}

{# After - Direct implementation #}
<a class="product-detail-tax-link text-primary text-decoration-underline font-weight-bold">
    {{ taxText }}
</a>
```

## Shopware 6.6 Compatibility

### Hybrid Template Approach
The theme uses a hybrid approach to support both:
1. **Traditional product detail pages** (for backward compatibility)
2. **CMS-based product pages** (Shopware 6.6 default)

```twig
{% if page.cmsPage %}
    {# CMS layout mode #}
    {{ parent() }}
{% else %}
    {# Traditional layout mode #}
    <!-- Custom layout code -->
{% endif %}
```

### Template Structure
- `/page/product-detail/index.html.twig` - Handles both modes
- `/element/cms-element-product-box.html.twig` - Styles CMS buy box
- `/block/cms-block-product-heading.html.twig` - Adds tax banner to CMS layouts

## Plugin Integration

### CheaperAd Compatibility
The theme ensures CheaperAd blocks render by:

1. **Template Hierarchy**:
   ```
   @Storefront
   └── @Plugins (includes CheaperAd automatically)
       └── @VoltimaxTheme
   ```

2. **Block Structure**:
   ```twig
   page_product_detail_content (Voltimax)
   └── parent() call
       └── cheaper_alternatives block (CheaperAd)
   ```

3. **Styling Integration**:
   ```scss
   .cheaper-alternatives {
       .card {
           border-color: $sw-border-color;
           // Uses theme variables for consistency
       }
   }
   ```

## Build Process

### Development
```bash
# Install dependencies
npm install

# Watch for changes
npm run watch
```

### Production
```bash
# Build assets
npm run build

# In Docker container
bin/console theme:refresh
bin/console theme:compile
```

## Service Configuration

### TaxInfoAlertSubscriber
- Listens to `ProductPageLoadedEvent`
- Adds tax configuration to product pages
- Uses modern dependency injection

### Configuration Fields
- `taxInfoAlert`: Enable/disable tax alerts
- `taxEntity`: Select specific tax entity
- `taxInfoText`: Customizable tax information text
- `taxInfoCmsPage`: Link to CMS page for details

## Performance Considerations

1. **Asset Loading**:
   - Fonts loaded via @font-face with swap
   - Optimized webpack configuration
   - Proper asset paths using $my-asset-path

2. **Template Caching**:
   - All templates use proper cache tags
   - Parent calls maintain cache hierarchy

## Debugging Tips

### Common Issues

1. **CheaperAd not showing**:
   - Check if parent() is called in product detail template
   - Verify CheaperAd plugin is active
   - Check browser console for JavaScript errors

2. **SCSS Compilation Errors**:
   - Ensure all variables use $ prefix
   - Check for CSS custom properties in SCSS functions
   - Verify all imports have correct paths

3. **Theme Not Updating**:
   ```bash
   bin/console theme:refresh
   bin/console cache:clear
   bin/console theme:compile
   ```

## API Extensions

### Page Extensions
```php
// Tax info configuration
$page->addExtension('configProductTaxId', $taxInfoConfigStruct);

// CheaperAd alternatives (handled by CheaperAd plugin)
$page->addExtension('cheaperAlternatives', $alternatives);
```

## Security Considerations

1. All user inputs are sanitized using `sw_sanitize`
2. Template paths use sw_extends for security
3. No direct database queries in templates
4. Proper escaping in all output contexts