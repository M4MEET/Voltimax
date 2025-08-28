# Upgrade Guide - Voltimax Theme

## From v3.0.0 to v3.1.0

### Overview
Version 3.1.0 introduces Feature 2: Mobile Navigation Overhaul with significant improvements to mobile UX.

### Upgrade Steps
1. **Backup current theme**
   ```bash
   cp -r custom/plugins/Voltimax-3.0.0 custom/plugins/Voltimax-3.0.0.backup
   ```

2. **Update theme files**
   - Pull latest changes from git
   - Or copy new version to plugins directory

3. **Clear cache and compile**
   ```bash
   docker exec -it shopware-6.6.10.4 bash
   bin/console cache:clear
   bin/console theme:compile
   ```

### Breaking Changes
- **Custom Navigation CSS Classes Removed**:
  - `voltimax-nav-item-wrapper`
  - `voltimax-nav-link`
  - `voltimax-nav-text`
  - `voltimax-nav-arrow-btn`
- **Bootstrap 5 Utilities Required**: Ensure Bootstrap utilities are available
- **Mobile Navigation Structure**: Previous customizations will be overridden

### Migration Guide
1. Review any custom CSS targeting removed classes
2. Update selectors to use Bootstrap utilities
3. Test thoroughly on mobile devices
4. Check touch target sizes (now standardized to 44px)

---

## From v2.x to v3.0.0

### Major Changes
- **Unified Plugin Architecture**: Consolidated Battron plugins into single theme
- **Topbar Always Enabled**: No longer toggleable
- **Complete Theme.json Reorganization**: New structure with tabs and blocks
- **Bootstrap Compliance**: ADR-compliant utilities

### Prerequisites
- Shopware 6.6.10.4 or higher
- PHP 8.1 or higher
- Node.js 16+ for build tools

### Breaking Changes

#### 1. Topbar Configuration
The topbar is now **always enabled** and cannot be disabled.

**Before (2.x):**
```json
"voltimaxCustomHeaderActive": {
    "type": "bool",
    "value": false
}
```

**After (3.0):**
- Field removed completely
- Topbar always renders

#### 2. Consolidated Plugins
Remove these standalone plugins (functionality now integrated):
- BattronCustomHeader
- BattronFooterIcons
- BattronListingBoxMedia

#### 3. Template Changes
- Product detail template requires `{{ parent() }}` call
- `component_product_box_rich_snippets` block removed
- Tax link changed from button to link element

#### 4. Configuration Migration
- All topbar settings moved to `topbar` tab
- Marketing scripts moved to `marketing` tab
- Footer icons reorganized into blocks
- Visibility toggles changed from `bool` to `switch`

### Upgrade Steps

1. **Backup Current Installation**
   ```bash
   cp -r custom/plugins/Voltimax-2.x custom/plugins/Voltimax-2.x.backup
   ```

2. **Remove Old Battron Plugins**
   ```bash
   bin/console plugin:uninstall BattronCustomHeader
   bin/console plugin:uninstall BattronFooterIcons
   bin/console plugin:uninstall BattronListingBoxMedia
   ```

3. **Install Voltimax 3.0.0**
   ```bash
   cp -r Voltimax-3.0.0 custom/plugins/
   bin/console plugin:refresh
   bin/console plugin:install VoltimaxTheme --activate
   ```

4. **Migrate Configuration**
   - Export old theme configuration
   - Reconfigure settings in new admin interface
   - Upload logos and icons to new fields
   - Configure marketing scripts in new tab

5. **Compile Theme**
   ```bash
   bin/console theme:compile
   bin/console cache:clear
   ```

### Template Migration

#### Custom Overrides
If you have custom template overrides:

1. **Product Detail**: Ensure `{{ parent() }}` is called
2. **Product Cards**: Remove `component_product_box_rich_snippets` references
3. **Tax Links**: Update to use link element with Bootstrap classes
4. **Header/Footer**: Review against new unified structure

### Rollback Procedure
```bash
# Deactivate new version
bin/console plugin:deactivate VoltimaxTheme

# Restore backup
rm -rf custom/plugins/Voltimax-3.0.0
mv custom/plugins/Voltimax-2.x.backup custom/plugins/Voltimax-2.x

# Reactivate old version
bin/console plugin:refresh
bin/console plugin:activate VoltimaxTheme
bin/console theme:compile
```

## Version History

### v3.1.0 (2025-01-28)
- Feature 2: Mobile Navigation Overhaul
- 80% CSS reduction
- Improved mobile UX

### v3.0.0 (2025-01-12)
- Major unified release
- Consolidated Battron plugins
- Shopware 6.6.10.4 compatibility

### v2.2.3
- Legacy version
- Separate plugin dependencies

## Support
For issues or questions:
1. Check CHANGELOG.md for detailed changes
2. Review README.md for configuration
3. See changelogs/ folder for feature documentation
4. Contact support with error logs if needed