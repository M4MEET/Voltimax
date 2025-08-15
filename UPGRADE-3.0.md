# Upgrade Guide - Voltimax Theme 3.0.0

This guide helps you upgrade from Voltimax Theme 2.x to 3.0.0.

## Prerequisites

- Shopware 6.6.10.4 or higher
- PHP 8.1 or higher
- Backup of your current theme configuration
- Test environment for validation

## Breaking Changes

### 1. Topbar Configuration

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
- Topbar always renders in template

### 2. Theme.json Structure

Complete reorganization of configuration fields into sections and blocks.

**Migration Required:**
- All topbar settings moved to `topbar` tab
- Marketing scripts moved to `marketing` tab
- Footer icons reorganized into `footerPayment` and `footerShipping` blocks

### 3. Visibility Toggle Fields

All visibility toggles changed from `bool` to `switch` type.

**Affected Fields:**
- `voltimaxTopbarLeftHideMobile` → type: `switch`
- `voltimaxTopbarLeftHideTablet` → type: `switch`
- (and all other visibility toggles)

### 4. Trustpilot Integration

Trustpilot widget is now configurable via admin panel.

**Before:** Hardcoded in template
**After:** Configure in Admin → Themes → Top-bar → Trustpilot section

## Migration Steps

### Step 1: Backup Current Configuration

```bash
# Export current theme configuration
bin/console theme:dump:configuration

# Backup database
mysqldump -u shopware -p shopware > backup_before_upgrade.sql
```

### Step 2: Install Update

```bash
# Copy new theme files
cp -r Voltimax-3.0.0 custom/plugins/

# Refresh plugins
bin/console plugin:refresh
```

### Step 3: Update Configuration

1. **Navigate to Admin Panel**
   - Go to Content → Themes → Voltimax Theme

2. **Reconfigure Topbar Settings**
   - Tab: Top-bar
   - Configure all 4 text sections
   - Set visibility toggles as needed
   - Add Trustpilot widget code

3. **Migrate Marketing Scripts**
   - Tab: Marketing & Analytics
   - Copy scripts from old configuration
   - Enable/disable as needed
   - Set async loading preferences

4. **Update Footer Icons**
   - Check payment method logos
   - Verify shipping provider icons

### Step 4: Compile Theme

```bash
# Refresh theme configuration
bin/console theme:refresh

# Clear cache
bin/console cache:clear

# Compile theme assets
bin/console theme:compile

# Build storefront
bin/console assets:install
```

### Step 5: Testing Checklist

#### Desktop View (≥992px)
- [ ] All topbar sections visible
- [ ] Trustpilot widget displays correctly
- [ ] Marketing scripts load in correct order
- [ ] Footer icons display properly

#### Tablet View (768px-991px)
- [ ] Configured sections hidden/visible as set
- [ ] Responsive layout works correctly
- [ ] No Bootstrap utility conflicts

#### Mobile View (<768px)
- [ ] Mobile visibility settings work
- [ ] Topbar items stack properly
- [ ] Performance acceptable

### Step 6: Production Deployment

```bash
# Clear production cache
bin/console cache:clear --env=prod

# Compile for production
bin/console theme:compile --env=prod

# Warm up cache
bin/console cache:warmup --env=prod
```

## Rollback Plan

If issues occur, rollback to previous version:

```bash
# Restore database
mysql -u shopware -p shopware < backup_before_upgrade.sql

# Restore theme files
cp -r backup/Voltimax-2.2.3 custom/plugins/

# Refresh and compile
bin/console plugin:refresh
bin/console theme:compile
bin/console cache:clear
```

## Common Issues

### Issue: Visibility toggles not working

**Solution:** Ensure Bootstrap utilities are loaded correctly. The theme now uses Shopware 6.6's approach without `!important`.

### Issue: Trustpilot widget not showing

**Solution:** 
1. Check widget code is properly pasted in admin
2. Verify TrustBox script loads in page head
3. Check browser console for errors

### Issue: Marketing scripts not loading

**Solution:**
1. Verify scripts are enabled in admin
2. Check async settings
3. Review browser network tab for loading errors

## Support

For issues or questions:
- Email: imeetjoshi@gmail.com
- Documentation: See `/changelog/_unreleased/` for detailed change logs

## Version Compatibility

| Voltimax Version | Shopware Version | PHP Version |
|-----------------|------------------|-------------|
| 3.0.0          | 6.6.10.4+       | 8.1+        |
| 2.2.3          | 6.5.x           | 8.0+        |
| 2.0.0          | 6.4.x           | 7.4+        |