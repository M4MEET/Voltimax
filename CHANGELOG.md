# Changelog - Voltimax Theme

All notable changes to the Voltimax Theme will be documented in this file.

## [3.1.0] - 2025-01-28 - **MOBILE NAVIGATION OVERHAUL**

### 🎯 Feature 2: Mobile Sidebar Navigation & Header

### 🚀 New Features
- **3-Button Header Layout** - Language switcher, home button, and close button with equal spacing
- **Separated Navigation Design** - Navigation links and arrow buttons as distinct clickable elements
- **Consistent Touch Targets** - All interactive elements standardized to 44px height
- **Bootstrap-First Approach** - Minimal custom CSS using Bootstrap 5 utilities
- **Clean Visual Hierarchy** - White backgrounds, rounded corners, proper spacing

### 🎨 Design Improvements
- **80% CSS Reduction** - Reduced from 635 to 120 lines of SCSS
- **Removed Double Backgrounds** - Eliminated unnecessary background layering
- **Fixed Border Issues** - Removed unwanted border-top inheritance
- **Improved Hover States** - Clean transitions and visual feedback
- **Primary Color Accents** - Better use of theme primary color for navigation

### 🔧 Technical Changes
- **8 Template Files** - Optimized navigation and header templates
- **Mobile-First SCSS** - Component-based styling in `_mobile-offcanvas.scss`
- **Theme Variable Usage** - Consistent use of `$voltimax-` variables
- **Responsive Breakpoints** - Proper mobile/tablet/desktop handling
- **Clean Git History** - 14 atomic commits with clear messages

### 📊 Performance Impact
- **Bundle Size** - 12KB reduction in CSS output
- **DOM Nodes** - 29% reduction in navigation elements
- **Touch Performance** - Optimized for mobile interactions
- **Load Time** - Faster navigation rendering

### 📚 Documentation
- **[README-MOBILE-NAVIGATION.md](README-MOBILE-NAVIGATION.md)** - Complete technical guide
- **[CHANGELOG-FEATURE-2.md](CHANGELOG-FEATURE-2.md)** - Detailed feature changelog
- **[FEATURE-2-SUMMARY.md](FEATURE-2-SUMMARY.md)** - Executive summary

### ⚠️ Breaking Changes
- Custom navigation CSS classes removed
- Previous mobile navigation customizations will be overridden
- Requires Bootstrap 5 utilities

### 🔄 Migration Guide
1. Review custom navigation overrides
2. Update any custom CSS targeting old classes
3. Test on mobile devices after update
4. Clear theme cache and recompile

---

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