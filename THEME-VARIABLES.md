# Voltimax Theme Variables Documentation

## Table of Contents
- [Voltimax Custom Variables](#voltimax-custom-variables)
- [Shopware Core Variables](#shopware-core-variables)
- [Bootstrap Variables](#bootstrap-variables)
- [Usage Examples](#usage-examples)

---

## Voltimax Custom Variables

### Brand Colors
```scss
$voltimax-brand-primary: #F3B664;        // Primary yellow/gold brand color
$voltimax-brand-secondary: #f0f0f0;      // Light gray secondary color
// Status colors use Shopware defaults:
// $sw-color-success: #3cc261;
// $sw-color-warning: #ffbd5d;
// $sw-color-danger: #e52427;
// $sw-color-info: #26b6cf;
```

### Background Colors
```scss
$voltimax-bg-primary: #ffffff;           // Primary background (white)
$voltimax-bg-secondary: #f0f0f0;         // Light gray background
$voltimax-bg-secondary-dark: #474747;    // Dark gray background for headers/footers
$voltimax-bg-tertiary: #ecf0f1;          // Very light gray background
$voltimax-bg-dark: #1a1a1a;              // Very dark background
```

### Text Colors
```scss
$voltimax-text-primary: #474747;         // Primary text color
$voltimax-text-secondary: #6c757d;       // Secondary text (gray)
$voltimax-text-color-light: #ffffff;     // Light text for dark backgrounds
$voltimax-text-on-dark: #ffffff;         // Text on dark backgrounds
$voltimax-text-on-primary: #474747;      // Text on primary color
$voltimax-text-muted: #868e96;           // Muted/disabled text
```

### Border & Shadow
```scss
$voltimax-border-color: #dee2e6;         // Default border color
$voltimax-border-radius: 0.375rem;       // Standard border radius (6px)
$voltimax-border-radius-lg: 0.75rem;     // Large border radius (12px)
$voltimax-border-radius-sm: 0.25rem;     // Small border radius (4px)
$voltimax-box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);  // Default shadow
$voltimax-box-shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);      // Large shadow
```

### Spacing
```scss
$voltimax-spacing-xs: 0.25rem;   // 4px
$voltimax-spacing-sm: 0.5rem;    // 8px
$voltimax-spacing-md: 1rem;      // 16px
$voltimax-spacing-lg: 1.5rem;    // 24px
$voltimax-spacing-xl: 2rem;      // 32px
$voltimax-spacing-xxl: 3rem;     // 48px
```

### Typography
```scss
$voltimax-font-family-base: 'TT Mussels', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
$voltimax-font-family-headings: 'TT Mussels', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
$voltimax-font-size-base: 1rem;          // 16px
$voltimax-font-size-sm: 0.875rem;        // 14px
$voltimax-font-size-lg: 1.125rem;        // 18px
$voltimax-font-weight-normal: 400;
$voltimax-font-weight-medium: 500;
$voltimax-font-weight-bold: 700;
$voltimax-line-height-base: 1.5;
```

### Topbar Specific Variables
```scss
$voltimax-topbar-background: #474747;    // Dark topbar background
$voltimax-topbar-text-color: #ffffff;    // White text on topbar
$voltimax-topbar-text-hover-color: #F3B664; // Primary color on hover
$voltimax-topbar-border-color: #e0e0e0;  // Light border
$voltimax-topbar-border-width: 1px;      // Border thickness
$voltimax-topbar-height: 35px;           // Fixed height
$voltimax-topbar-font-size: 13px;        // Small font size
$voltimax-topbar-padding: 0.5rem 0;      // Vertical padding
$voltimax-topbar-text-weight: 500;       // Medium font weight
```

### Glass Morphism Effects (from mega menu)
```scss
$glass-main: rgb(255 255 255 / 15%);
$glass-sidebar: rgba($voltimax-bg-secondary, 0.9);
$glass-content: rgba($voltimax-bg-secondary, 0.7);
```

---

## Shopware Core Variables

### Core Colors
```scss
$sw-color-brand-primary: #008490;        // Shopware default primary
$sw-color-brand-secondary: #5f7285;      // Shopware default secondary
$sw-color-success: #3cc261;              // Success green
$sw-color-info: #26b6cf;                 // Info blue
$sw-color-warning: #ffbd5d;              // Warning yellow
$sw-color-danger: #e52427;               // Danger red
```

### Background Colors
```scss
$sw-background-color: #fff;              // Default background
$sw-background-color-secondary: #f9f9f9; // Secondary background
$sw-background-color-dark: #17181a;      // Dark background
```

### Text Colors
```scss
$sw-text-color: #52667a;                 // Default text color
$sw-text-color-dark: #17181a;            // Dark text
$sw-text-color-light: #798490;           // Light text
$sw-text-muted-color: #9aa5b5;           // Muted text
```

### Border Variables
```scss
$sw-border-color: #d1d9e0;               // Default border
$sw-border-radius-default: 3px;          // Default radius
$sw-border-radius-sm: 2px;               // Small radius
$sw-border-radius-lg: 8px;               // Large radius
```

### Icon Colors
```scss
$icon-base-color: $sw-text-color;        // Base icon color
$icon-color-primary: $sw-color-brand-primary;
$icon-color-secondary: $sw-color-brand-secondary;
```

---

## Bootstrap Variables (Used in Shopware)

### Theme Colors Map
```scss
$primary: $sw-color-brand-primary;
$secondary: $sw-color-brand-secondary;
$success: $sw-color-success;
$info: $sw-color-info;
$warning: $sw-color-warning;
$danger: $sw-color-danger;
$light: #f8f9fa;
$dark: #343a40;
$white: #ffffff;
$black: #000000;
```

### Spacing Utilities
```scss
$spacer: 1rem;                    // Base spacing unit
$spacers: (
  0: 0,
  1: $spacer * 0.25,              // 4px
  2: $spacer * 0.5,               // 8px
  3: $spacer,                     // 16px
  4: $spacer * 1.5,               // 24px
  5: $spacer * 3,                 // 48px
);
```

### Breakpoints
```scss
$grid-breakpoints: (
  xs: 0,
  sm: 576px,
  md: 768px,
  lg: 992px,
  xl: 1200px,
  xxl: 1400px
);
```

---

## Usage Examples

### Using Voltimax Variables
```scss
// Button with theme colors
.voltimax-button {
  background-color: $voltimax-brand-primary;
  color: $voltimax-text-color-light;
  border-radius: $voltimax-border-radius;
  
  &:hover {
    background-color: darken($voltimax-brand-primary, 10%);
  }
}

// Dark section
.voltimax-dark-section {
  background: linear-gradient(135deg, 
    $voltimax-bg-secondary-dark, 
    darken($voltimax-bg-secondary-dark, 5%)
  );
  color: $voltimax-text-color-light;
}
```

### Using Shopware Variables
```scss
// Standard Shopware button
.sw-button {
  background-color: $sw-color-brand-primary;
  color: $white;
  border-radius: $sw-border-radius-default;
}

// Icon styling
.icon-custom {
  color: $icon-base-color;
  
  &:hover {
    color: $icon-color-primary;
  }
}
```

### Combining Variables
```scss
// Offcanvas header combining both systems
.offcanvas-header {
  // Voltimax dark theme
  background: $voltimax-bg-secondary-dark;
  border-bottom: 2px solid $voltimax-brand-primary;
  
  // Shopware button styles
  .btn {
    @extend .btn-secondary;
    color: $voltimax-text-color-light;
    
    // Bootstrap spacing
    padding: map-get($spacers, 2) map-get($spacers, 3);
  }
}
```

---

## Best Practices

1. **Use Voltimax variables** for custom components and theme-specific styling
2. **Use Shopware variables** when extending core components
3. **Use Bootstrap utilities** for spacing, display, and positioning
4. **Avoid hardcoding colors** - always use variables for maintainability
5. **Follow naming conventions**:
   - Voltimax: `$voltimax-{category}-{name}`
   - Shopware: `$sw-{category}-{name}`
   - Bootstrap: Standard Bootstrap naming

---

## Variable Priority

When choosing which variable to use:

1. **Voltimax variables** - First choice for theme-specific elements
2. **Shopware variables** - When working with Shopware components
3. **Bootstrap variables** - For layout and utilities
4. **CSS custom properties** - For runtime theming (if needed)

---

## Notes

- All color values are approximate and may be overridden in theme configuration
- Variables prefixed with `$voltimax-` are custom to this theme
- Variables prefixed with `$sw-` come from Shopware core
- Bootstrap variables are available globally through Shopware's build system
- Always compile theme after variable changes: `bin/console theme:compile`

---

## Related Files

- **Variables Definition**: `/src/Resources/app/storefront/src/scss/abstract/variables.scss`
- **Theme Config**: `/src/Resources/theme.json`
- **Component Styles**: `/src/Resources/app/storefront/src/scss/component/`
- **Bootstrap Override**: `/src/Resources/app/storefront/src/scss/overrides/_bootstrap.scss`