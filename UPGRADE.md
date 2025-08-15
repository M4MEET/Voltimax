# Upgrade Guide: Voltimax Theme 2.2.3 → 3.0.0

## Overview
This guide covers upgrading the Voltimax theme from version 2.2.3 to 3.0.0 for Shopware 6.6.10.4 compatibility.

## Prerequisites
- Shopware 6.6.10.4 or higher
- PHP 8.1 or higher
- Node.js 16+ (for build tools)

## Breaking Changes
1. **Template Inheritance**: Product detail template now calls `{{ parent() }}` - custom overrides may need adjustment
2. **Deprecated Functions Removed**:
   - `feature()` twig function replaced
   - `component_product_box_rich_snippets` block removed
3. **SCSS Variables**: Now uses proper SCSS variables instead of CSS custom properties in some places

## Upgrade Steps

### 1. Backup Current Installation
```bash
cp -r custom/plugins/Voltimax-2.2.3 custom/plugins/Voltimax-2.2.3.backup
```

### 2. Install New Version
```bash
# Copy new version to plugins directory
cp -r custom/plugins/Voltimax-3.0.0 custom/plugins/

# Inside Docker container
docker exec -it shopware-6.6.10.4 bash
cd /var/www/html
```

### 3. Update Plugin
```bash
# Refresh plugins
bin/console plugin:refresh

# Update the plugin
bin/console plugin:update VoltimaxTheme

# Clear cache
bin/console cache:clear
```

### 4. Compile Theme
```bash
# Refresh theme configuration
bin/console theme:refresh

# Compile theme
bin/console theme:compile
```

## Template Migration

### If You Have Custom Template Overrides

1. **Product Detail Page**
   - Ensure you call `{{ parent() }}` in `page_product_detail_content` block
   - This allows plugin blocks (like CheaperAd) to render

2. **Product Cards**
   - Remove any references to `component_product_box_rich_snippets`
   - Update deprecated feature checks

3. **Tax Link Button**
   - Now uses proper link element instead of button
   - Styled with Bootstrap classes: `text-primary text-decoration-underline font-weight-bold`

## Configuration Changes

### Theme.json Updates
- Removed explicit `@CheaperAd` reference (handled by `@Plugins`)
- All color configurations remain the same

### New Build System
- Webpack configuration added for modern asset building
- Use `npm run build` for production builds

## Plugin Compatibility

### CheaperAd Plugin
The theme is now fully compatible with CheaperAd plugin:
- Blocks render correctly below product content
- Custom styling matches theme design
- No additional configuration needed

### Other Plugins
All plugins that properly extend templates will work seamlessly.

## Rollback Procedure
If issues occur:
```bash
# Disable new version
bin/console plugin:deactivate VoltimaxTheme

# Restore backup
rm -rf custom/plugins/Voltimax-3.0.0
mv custom/plugins/Voltimax-2.2.3.backup custom/plugins/Voltimax-2.2.3

# Refresh and reactivate
bin/console plugin:refresh
bin/console plugin:activate VoltimaxTheme
bin/console theme:compile
```

## Support
For issues or questions, check:
- CHANGELOG.md for detailed changes
- README.md for general information
- Contact support with error logs if needed