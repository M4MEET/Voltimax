# Voltimax Theme v3.0.0 - Development Guide

## Overview

This document provides comprehensive development guidelines for the Voltimax Theme v3.0.0, a production-ready Shopware 6.6.10.4 theme focused on German e-commerce markets.

## Project Structure

```
Voltimax-3.0.0/
├── src/
│   ├── Resources/
│   │   ├── app/storefront/           # Frontend assets
│   │   │   ├── src/
│   │   │   │   ├── scss/             # Sass stylesheets
│   │   │   │   │   ├── base.scss     # Base theme styles
│   │   │   │   │   ├── overrides.scss # Variable overrides
│   │   │   │   │   └── component/    # Component-specific styles
│   │   │   │   └── main.js           # JavaScript entry point
│   │   │   ├── dist/                 # Compiled assets
│   │   │   └── webpack.config.js     # Build configuration
│   │   ├── views/storefront/         # Twig templates
│   │   │   ├── component/            # Component templates
│   │   │   ├── layout/               # Layout templates
│   │   │   └── page/                 # Page templates
│   │   ├── snippet/                  # Translations
│   │   │   ├── de_DE/                # German translations
│   │   │   └── en_GB/                # English translations
│   │   ├── config/                   # Configuration
│   │   └── public/                   # Public assets
│   └── VoltimaxTheme.php            # Main plugin class
├── changelog/                        # Individual changelog entries
├── changelogs/                       # Major feature documentation
├── composer.json                     # Dependencies
├── package.json                      # Node.js dependencies
└── webpack.config.js                # Build configuration
```

## Development Environment

### Prerequisites

- **Docker**: Shopware 6.6.10.4 running in dockware container
- **Node.js**: v16+ for asset compilation
- **PHP**: 8.3 (configured in Docker)
- **Composer**: For PHP dependencies

### Docker Commands

```bash
# Start Shopware environment
docker-compose up -d

# Access main container
docker exec -it shopware-6.6.10.4 bash

# Check services
docker ps
```

### Asset Building

```bash
# Install dependencies
npm install

# Development build with watching
npm run watch

# Production build
npm run build

# Build specific assets
npm run build-js
npm run build-css
```

### Shopware Commands

```bash
# Theme operations
docker exec shopware-6.6.10.4 php bin/console theme:compile
docker exec shopware-6.6.10.4 php bin/console cache:clear

# Plugin management
docker exec shopware-6.6.10.4 php bin/console plugin:refresh
docker exec shopware-6.6.10.4 php bin/console plugin:install VoltimaxTheme --activate

# Build JavaScript/CSS assets using shopware-cli
docker exec shopware-6.6.10.4 bash -c "cd /var/www/html && shopware-cli extension build custom/plugins/Voltimax-3.0.0"
```

## Development Workflow

### Feature Branch Strategy

```bash
# Create feature branch
git checkout -b feature-{number}-{description}

# Examples:
git checkout -b feature-1-topbar-redesign
git checkout -b feature-2-mobile-navigation
git checkout -b feature-3-listing-view
```

### Bootstrap-First Development Principle

**Priority Order:**
1. **Bootstrap Utility Classes** - Use existing Bootstrap classes first
2. **Shopware Default Classes** - Leverage Shopware's built-in styling
3. **Theme Variables** - Use Voltimax theme variables for customization
4. **Custom CSS** - Only as last resort for specific requirements

### Template Development

#### Template Hierarchy
```
Shopware Core Template
    ↓ (extends)
Voltimax Override
    ↓ (extends)
Feature-Specific Override
```

#### Best Practices

1. **Always Extend Shopware Templates**
   ```twig
   {% sw_extends "@Storefront/storefront/component/product/card/box-standard.html.twig" %}
   ```

2. **Use Bootstrap Grid System**
   ```twig
   <div class="row g-0">
       <div class="col-4">Image</div>
       <div class="col-5">Info</div>
       <div class="col-3">Price</div>
   </div>
   ```

3. **Preserve Shopware Blocks**
   ```twig
   {% block component_product_box_name %}
       {# Custom implementation #}
       {{ parent() }}
   {% endblock %}
   ```

### SCSS Development

#### Variable Usage Priority

1. **Bootstrap Variables**
   ```scss
   color: var(--bs-primary);
   background: var(--bs-light);
   ```

2. **Voltimax Theme Variables**
   ```scss
   color: $voltimax-brand-primary;
   background: $voltimax-bg-secondary;
   ```

3. **Shopware Variables**
   ```scss
   color: $sw-color-brand-primary;
   background: $sw-background-color;
   ```

#### Component Structure

```scss
// Component-specific styles only
.product-box.box-image {
  // Minimal overrides only
  // Use Bootstrap classes in template instead
}
```

## Feature Development Guidelines

### Feature 1: Topbar Redesign ✅
- **Status**: Completed
- **Key Elements**: 5-element responsive layout
- **Integration**: Battron plugin consolidation

### Feature 2: Mobile Navigation ✅
- **Status**: Completed (v3.1.0)
- **Key Elements**: 3-button header, separated nav items
- **Documentation**: `README-MOBILE-NAVIGATION.md`

### Feature 3: Listing View 🚧
- **Status**: In Development
- **Branch**: `feature-3-listing-view`
- **Goal**: Bootstrap-first product listing layout
- **Requirements**: 
  - Horizontal product card layout
  - Clean information hierarchy
  - Responsive design
  - Plugin compatibility

## Coding Standards

### Template Guidelines

1. **Use Semantic HTML**
   ```twig
   <article class="product-box">
       <header class="product-header">
       <main class="product-content">
       <footer class="product-actions">
   ```

2. **Bootstrap Classes First**
   ```twig
   <!-- Good -->
   <div class="d-flex align-items-center mb-3">
   
   <!-- Avoid -->
   <div class="custom-flex-container">
   ```

3. **Accessibility**
   ```twig
   {% if feature('ACCESSIBILITY_TWEAKS') %}
       class="stretched-link"
   {% endif %}
   ```

### SCSS Guidelines

1. **Minimal Custom Styles**
   ```scss
   // Only when Bootstrap can't achieve the design
   .specific-component {
     // Very specific styling only
   }
   ```

2. **Use Theme Variables**
   ```scss
   // Good
   color: $voltimax-brand-primary;
   
   // Avoid
   color: #F3B664;
   ```

3. **Component Scoping**
   ```scss
   // Scope to specific layouts
   .product-box.box-image {
     // Layout-specific styles
   }
   ```

## Plugin Integration

### FaesslichFeaturesOnProducts
```twig
{% set isFeatureActive = config('FaesslichFeaturesOnProducts.config.active') %}
{% if isFeatureActive %}
    {% sw_include '@FaesslichFeaturesOnProducts/storefront/component/features-on-product/features.html.twig' %}
{% endif %}
```

### Wishlist Integration
```twig
{% if config('core.cart.wishlistEnabled') %}
    {% sw_include '@Storefront/storefront/component/product/card/wishlist.html.twig' with {
        appearance: 'circle',
        productId: id
    } %}
{% endif %}
```

## Testing Workflow

### Theme Compilation
```bash
# Always test compilation after changes
docker exec shopware-6.6.10.4 php bin/console theme:compile
docker exec shopware-6.6.10.4 php bin/console cache:clear
```

### Browser Testing
- **Desktop**: 1920x1080, 1366x768
- **Tablet**: iPad (768px), iPad Pro (1024px)
- **Mobile**: iPhone (375px), Android (360px)

### Plugin Compatibility
- Test with all active plugins (60+ in production)
- Verify wishlist functionality
- Check product badges and features display
- Validate lazy loading performance

## Common Issues & Solutions

### Hot Reload JavaScript Errors
**Problem**: `getSizeElement` errors during development
**Solution**: Avoid CSS properties that interfere with lazy loading:
```scss
// Avoid
display: flex;

// Use instead
display: block;
line-height: {height}px;
```

### Plugin Template Conflicts
**Problem**: Multiple plugins overriding same templates
**Solution**: Use plugin-specific includes and preserve blocks:
```twig
{% block component_product_box_rating %}
    {{ parent() }}
    {# Additional content #}
{% endblock %}
```

### Theme Variable Undefined
**Problem**: SCSS compilation errors for missing variables
**Solution**: Check `overrides.scss` for available variables:
```scss
// Available variables
$voltimax-brand-primary: #F3B664;
$voltimax-spacing-md: 1rem;
$voltimax-font-size-sm: 0.875rem;
```

## Performance Optimization

### Asset Building
- Use webpack for optimized builds
- Enable tree shaking for unused code
- Compress images and assets

### CSS Optimization
- Minimize custom CSS
- Use Bootstrap utilities for consistency
- Avoid redundant style definitions

### Template Efficiency
- Use template inheritance properly
- Cache includes where possible
- Minimize template complexity

## Git Workflow

### Branch Naming
```
feature-{number}-{description}
hotfix-{description}
release-{version}
```

### Commit Messages
```
feat: add horizontal product listing layout
fix: resolve wishlist positioning overflow
docs: update development guidelines
style: improve SCSS organization
```

### Release Process
1. Feature development on feature branch
2. Testing and documentation
3. Merge to main branch
4. Tag release (e.g., v3.1.0)
5. Update CHANGELOG.md

## Documentation Standards

### Code Comments
```twig
{# Bootstrap Grid Layout - Horizontal Product Cards #}
<div class="row g-0">
    {# Image Column #}
    <div class="col-4">
```

### SCSS Comments
```scss
/**
 * Product Listing - Bootstrap First Approach
 * Minimal custom styling, relies on Bootstrap utilities
 */
```

### Changelog Format
```markdown
## [3.1.0] - 2025-01-28

### Added
- Feature 2: Mobile Navigation Overhaul

### Changed
- Improved responsive design patterns

### Fixed
- Resolved lazy loading compatibility issues
```

## Troubleshooting

### Common Commands
```bash
# Reset development environment
docker exec shopware-6.6.10.4 php bin/console cache:clear
docker exec shopware-6.6.10.4 php bin/console theme:compile

# Debug asset compilation
npm run build -- --mode development

# Check plugin status
docker exec shopware-6.6.10.4 php bin/console plugin:list
```

### Debug Mode
```bash
# Enable Shopware debug mode
docker exec shopware-6.6.10.4 php bin/console debug:container
```

## Contributing

1. **Follow Bootstrap-first principle**
2. **Test with all plugins enabled**
3. **Update documentation**
4. **Write clear commit messages**
5. **Test responsive design**
6. **Validate accessibility features**

---

For questions or issues, refer to:
- `README.md` - Project overview
- `PROJECT_PROGRESS.md` - Feature completion status
- `CHANGELOG.md` - Version history
- Individual feature changelogs in `changelogs/` directory