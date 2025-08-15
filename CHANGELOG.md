# Changelog - Voltimax Theme

All notable changes to the Voltimax Theme will be documented in this file.

## [3.0.0] - 2025-01-12 - **MAJOR UNIFIED RELEASE**

### 🎉 Major Features Added
- **🏗️ Unified Plugin Architecture** - Consolidated all Battron plugin functionality into single theme
- **📱 Custom Header System** - Integrated customizable header with 4-section layout (left, middle, right, rightend)
- **💳 Payment & Shipping Icons** - Built-in footer sections with collapsible payment and shipping logo grids
- **🏭 Manufacturer Logo Display** - Automatic manufacturer logo integration in product listings
- **⚙️ Comprehensive Admin Interface** - Unified theme.json configuration for all integrated features
- **📱 Mobile-First Responsive Design** - Enhanced responsive behavior across all integrated components

### 🔧 Technical Enhancements
- **Component-Based SCSS Architecture** - Modular stylesheet organization with dedicated component files
- **Event Subscriber System** - ManufacturerMediaSubscriber for dynamic manufacturer media loading
- **Template Integration** - Seamless Twig template integration without conflicts
- **Theme Variable System** - Consistent styling using Shopware's theme variable system
- **Service Configuration** - Proper dependency injection for all integrated services

### 🚀 Shopware 6.6 Compatibility
- **Full Shopware 6.6.10.4 Support** - Complete compatibility with latest Shopware version
- **Enhanced Plugin Compatibility** - Improved integration with CheaperAd v2.0.0 and other plugins
- **Modern Build System** - Webpack-based asset compilation
- **Performance Optimizations** - Streamlined component loading and rendering

### 📦 Integrated Components
- **BattronCustomHeader** → Custom Header System (integrated)
- **BattronFooterIcons** → Payment & Shipping Icons (integrated) 
- **BattronListingBoxMedia** → Manufacturer Logo Display (integrated)

### 🎨 Design Improvements
- **TT Mussels Typography** - Enhanced font integration across all components
- **Responsive Grid Systems** - Improved layout consistency on all devices
- **Theme Color Consistency** - Unified color scheme across all integrated features
- **Component Hover Effects** - Enhanced user interaction feedback

### 👤 Authorship & Branding
- **Updated Author** - Meet Joshi (imeetjoshi@gmail.com)
- **Enhanced Branding** - Comprehensive theme labeling and descriptions
- **Documentation Overhaul** - Complete README.md rewrite with technical architecture

### 🔄 Latest Updates (2025-01-12)
- **[VOLTIMAX-001]** - Topbar Configuration Reorganization - Always enabled by default, better admin organization
- **[VOLTIMAX-002]** - Trustpilot Widget Configuration - Now configurable via admin instead of hardcoded
- **[VOLTIMAX-003]** - Bootstrap Utilities Visibility Fix - Proper responsive visibility without !important
- **[VOLTIMAX-004]** - Marketing Scripts Reorganization - Dedicated tab with loading priorities

### ⚠️ Breaking Changes
- **Topbar Always Enabled** - Removed toggle switch, topbar is now always active
- **Plugin Replacement** - Standalone Battron plugins should be removed to avoid conflicts
- **Configuration Migration** - New theme.json structure requires reconfiguration
- **Template Updates** - Enhanced template inheritance may require custom template updates
- **Bootstrap Compliance** - Aligned with Shopware 6.6 ADR for utilities without !important

### 🔄 Migration Notes
1. Remove standalone BattronCustomHeader, BattronFooterIcons, and BattronListingBoxMedia plugins
2. Reconfigure custom header, payment/shipping logos via new admin interface
3. Clear cache and recompile theme assets after installation
4. Test all customizations in staging environment before production deployment

---

## [2.2.3] - Previous Release
- Legacy version compatible with Shopware 6.5.x
- Separate plugin dependencies required
- Limited integrated functionality