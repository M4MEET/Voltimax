# Voltimax Theme v3.1.0 - Unified Shopware 6.6 Theme

A modern, unified theme for Shopware 6.6.x that combines all Battron plugin functionality in a single, elegant solution. Designed for the German market with full responsive design and plugin compatibility.

## 🚀 Latest Updates (v3.1.0)

### Feature 2: Mobile Sidebar Navigation & Header
- **3-Button Header Layout** - Language switcher, home, and close buttons with equal spacing
- **Separated Navigation Design** - Clean distinction between navigation links and arrow buttons  
- **Optimized Mobile UX** - 44px consistent touch targets for better mobile interaction
- **80% CSS Reduction** - From 635 to 120 lines of SCSS for better performance
- **Bootstrap-First Approach** - Minimal custom CSS with Bootstrap utilities

[📖 Full Feature 2 Documentation](README-MOBILE-NAVIGATION.md) | [📝 Changelog](CHANGELOG-FEATURE-2.md)

## ✨ Integrated Features
- ✅ **Mobile Navigation System** - Completely redesigned offcanvas navigation (v3.1.0)
- ✅ **Custom Header System** - Configurable header panel with up to 4 icon/text sections (left, middle, right, rightend)
- ✅ **Payment & Shipping Icons** - Collapsible footer sections with customizable payment and shipping logos
- ✅ **Manufacturer Logos** - Automatic display of manufacturer logos in product listings
- ✅ **Shopware 6.6.10.4 Compatible** - Full compatibility with latest Shopware version
- ✅ **Modern Build System** - Webpack-based asset compilation
- ✅ **CheaperAd Plugin Integration** - Seamless integration with CheaperAd plugin v2.0
- ✅ **Custom Typography** - TT Mussels font with elegant styling
- ✅ **Fully Responsive** - Mobile-first design across all viewports
- ✅ **Theme Customization** - Complete admin interface for all settings
- ✅ **Tax Information Alerts** - German market MwSt. compliance
- ✅ **Footer USP Sections** - Customizable Unique Selling Points display
- ✅ **Component-Based SCSS** - Organized, maintainable stylesheet architecture

## Requirements
- Shopware 6.6.0 or higher
- PHP 8.1+
- Node.js 16+ (for building assets)

## Installation

1. **Copy plugin to your Shopware installation**:
   ```bash
   cp -r Voltimax-3.0.0 /path/to/shopware/custom/plugins/
   ```

2. **Install and activate** (in Docker container):
   ```bash
   docker exec -it shopware-6.6.10.4 bash
   cd /var/www/html
   bin/console plugin:refresh
   bin/console plugin:install VoltimaxTheme --activate
   ```

3. **Compile theme**:
   ```bash
   bin/console theme:compile
   bin/console cache:clear
   ```

## 🎛️ Configuration

### Theme Settings
Access comprehensive theme configuration in **Admin → Themes → Voltimax Theme**:

#### **Theme Colors Block**
- **Primary Color** (#F3B664) - Main brand color
- **Secondary Color** (#f0f0f0) - Supporting brand color
- **Border & Background Colors** - Complete color customization
- **Status Colors** - Success, info, warning, and error states

#### **Custom Header Block**
- **Enable Custom Header** - Toggle header display on/off
- **Header Background Color** - Customizable background
- **Left Text** - Configurable left section text
- **Middle Text** - Configurable middle section text  
- **Right Text** - Configurable right section text
- **Icon Upload** - Upload custom icons for each section
- **Link Configuration** - Set custom URLs and target options
- **Responsive Controls** - Mobile visibility settings

#### **Payment & Shipping Block**
- **Payment Logos 1-9** - Upload payment method logos
- **Shipping Logos 1-9** - Upload shipping provider logos
- **Link Configuration** - Set URLs for each logo
- **Responsive Grid** - Automatic responsive layout

#### **Typography Block**
- **Font Families** - TT Mussels for headers and body text
- **Text Colors** - Customizable text and headline colors
- **Responsive Sizing** - Automatic scaling across devices

#### **E-Commerce Block**
- **Price Colors** - Product pricing display
- **Buy Button Styling** - Purchase button appearance
- **Manufacturer Logo Display** - Automatic product listing integration

#### **Media Block**
- **Logo Management** - Desktop, tablet, mobile, favicon
- **USP Media** - Footer unique selling point icons
- **Asset Organization** - Centralized media management

## 🔌 Plugin Compatibility & Migration

### Unified Plugin Integration
This theme **replaces and consolidates** the following standalone Battron plugins:
- ❌ **BattronCustomHeader** (now integrated) - Custom header functionality built-in
- ❌ **BattronFooterIcons** (now integrated) - Payment/shipping icons built-in  
- ❌ **BattronListingBoxMedia** (now integrated) - Manufacturer logos built-in

> **Migration Note**: Remove the standalone Battron plugins after installing this theme to avoid conflicts.

### Compatible Plugins
✅ **CheaperAd Plugin v1.0.2+ and v2.0.0**:
- Seamless integration with existing styles
- Responsive display in product listings
- Theme-consistent button styling
- No additional configuration needed

✅ **Popular Shopware 6.6 Plugins**:
- Billiger.de Tracking - Full compatibility
- Trustpilot Integration - Pre-configured in footer
- Payment Providers - PayPal, Amazon Pay, etc.
- ERP Systems - PickwareErp, etc.
- SEO Tools - Frosh, etc.
- Analytics - Google Analytics, etc.

## Development

### Building Assets
```bash
# Install dependencies
npm install

# Development build with watch
npm run watch

# Production build
npm run build
```

### File Structure
```
Voltimax-3.0.0/
├── src/
│   ├── Resources/
│   │   ├── app/storefront/
│   │   │   └── src/scss/
│   │   │       ├── component/         # Integrated components
│   │   │       │   ├── _custom-header.scss
│   │   │       │   ├── _footer-icons.scss
│   │   │       │   ├── _manufacturer-logo.scss
│   │   │       │   ├── _cheaper-ad.scss
│   │   │       │   └── _mobile-offcanvas.scss  # v3.1.0 Mobile navigation
│   │   │       ├── fonts/
│   │   │       ├── base.scss          # Main stylesheet
│   │   │       └── overrides.scss     # Variable overrides
│   │   ├── config/
│   │   │   ├── config.xml             # Plugin configuration
│   │   │   └── services.xml           # Service definitions
│   │   ├── views/storefront/
│   │   │   ├── base.html.twig         # Custom header integration
│   │   │   ├── layout/
│   │   │   │   ├── header/            # Mobile header improvements (v3.1.0)
│   │   │   │   ├── footer/            # Footer icons integration
│   │   │   │   └── navigation/offcanvas/  # Mobile navigation (v3.1.0)
│   │   │   │       ├── navigation.html.twig
│   │   │   │       ├── categories.html.twig
│   │   │   │       ├── item-link.html.twig
│   │   │   │       ├── back-link.html.twig
│   │   │   │       ├── show-all-link.html.twig
│   │   │   │       └── show-active-link.html.twig
│   │   │   └── component/product/     # Manufacturer logo integration
│   │   └── theme.json                 # Unified theme configuration
│   ├── Subscriber/
│   │   ├── TaxInfoAlertSubscriber.php
│   │   └── ManufacturerMediaSubscriber.php
│   └── VoltimaxTheme.php              # Main theme class
├── changelog/                         # Shopware standard changelog
│   └── _unreleased/                  # Pending release notes
├── changelogs/                        # Major feature documentation
├── composer.json                      # PHP dependencies & metadata
├── package.json                       # Node dependencies
├── README.md                          # Main documentation
├── README-MOBILE-NAVIGATION.md       # Mobile nav guide (v3.1.0)
├── CHANGELOG.md                       # Version history
└── THEME-VARIABLES.md                # Theme variable reference
```

## 🏗️ Technical Architecture

### Component Integration Strategy
The unified theme follows a **component-based architecture** that consolidates:

1. **Template Layer** - Twig templates with integrated Battron functionality
2. **Style Layer** - Modular SCSS components with theme variable integration
3. **Logic Layer** - Event subscribers for dynamic functionality
4. **Configuration Layer** - Unified admin interface via theme.json

### Event Subscriber System
- **ManufacturerMediaSubscriber** - Automatically loads manufacturer media for product listings
- **TaxInfoAlertSubscriber** - Handles German tax compliance alerts

### Theme Variable System
All integrated components use the unified Shopware theme variable system for consistent styling and easy customization.

## Documentation

### General Documentation
- [CHANGELOG.md](CHANGELOG.md) - Version history
- [UPGRADE.md](UPGRADE.md) - Upgrade guide from v2.2.3
- [TECHNICAL_DOCUMENTATION.md](TECHNICAL_DOCUMENTATION.md) - Technical details
- [THEME-VARIABLES.md](THEME-VARIABLES.md) - Theme variables reference

### Feature 2 - Mobile Navigation (v3.1.0)
- [README-MOBILE-NAVIGATION.md](README-MOBILE-NAVIGATION.md) - Complete mobile navigation guide
- See `changelogs/2025-01-28-mobile-navigation-feature-2.md` for detailed feature documentation

## Support
For issues or questions:
1. Check the documentation files
2. Review Shopware logs in `var/log/`
3. Contact support with detailed error information

## License
MIT License - see LICENSE file for details
