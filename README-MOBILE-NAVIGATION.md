# Mobile Navigation & Header Documentation

## Overview
This document describes the mobile navigation sidebar and header implementation for the Voltimax 3.0 theme on Shopware 6.6.10.4.

---

## Architecture

### Component Structure
```
Mobile Navigation
├── Offcanvas Header (3-button layout)
│   ├── Language Switcher (Left)
│   ├── Home Button (Center)
│   └── Close Button (Right)
├── Navigation Body
│   ├── Categories Headline
│   ├── Navigation Items
│   │   ├── Link Component
│   │   └── Arrow Button (if has children)
│   ├── Back Link
│   ├── Show All Link
│   └── Active Category Display
└── Offcanvas Footer (Optional)
```

---

## File Structure

### Templates (`src/Resources/views/storefront/layout/`)
```
navigation/offcanvas/
├── navigation.html.twig      # Main offcanvas container & header
├── categories.html.twig      # Category listing wrapper
├── item-link.html.twig       # Individual category items
├── back-link.html.twig       # Back navigation
├── show-all-link.html.twig   # Show all categories
└── show-active-link.html.twig # Current active category

header/
├── header.html.twig          # Mobile header layout
└── logo.html.twig            # Logo component
```

### Styles (`src/Resources/app/storefront/src/scss/`)
```
component/
└── _mobile-offcanvas.scss    # All mobile navigation styles

overrides.scss                # Global overrides and imports
```

---

## Key Features

### 1. Three-Button Header Layout
The offcanvas header uses a Bootstrap grid system with three equal columns:

```twig
<div class="row align-items-center gx-2">
    <div class="col-4"><!-- Language --></div>
    <div class="col-4"><!-- Home --></div>
    <div class="col-4"><!-- Close --></div>
</div>
```

**Benefits:**
- Equal spacing
- Responsive alignment
- Touch-friendly targets
- Consistent heights

### 2. Separated Navigation Design
Navigation items split into two distinct elements:

```twig
<div class="d-flex gap-2">
    <a class="navigation-offcanvas-link"><!-- Main Link --></a>
    <button class="navigation-offcanvas-arrow-btn"><!-- Arrow --></button>
</div>
```

**Features:**
- Independent click areas
- Visual separation with gap
- Same height (44px)
- Clean hover states

### 3. Bootstrap-First Approach
Minimal custom CSS with Bootstrap utilities:

```scss
// Before (600+ lines custom CSS)
.voltimax-nav-item-wrapper {
  display: flex;
  align-items: center;
  background: #fff;
  border: 1px solid #ccc;
  // ... many more custom styles
}

// After (Bootstrap utilities)
class="d-flex gap-2 bg-white rounded"
```

---

## Styling System

### Theme Variables Used
```scss
$voltimax-bg-secondary-dark    // Header background
$voltimax-brand-primary        // Primary accent color
$voltimax-bg-secondary         // Body background
$voltimax-spacing-sm           // Spacing units
$voltimax-border-radius-lg     // Border radius
```

### Key CSS Classes

#### Navigation Links
```scss
.navigation-offcanvas-link {
  min-height: 44px;
  display: flex;
  align-items: center;
  
  &:hover {
    background-color: $voltimax-bg-secondary;
    color: $voltimax-brand-primary;
  }
}
```

#### Arrow Buttons
```scss
.navigation-offcanvas-arrow-btn {
  width: 44px;
  min-height: 44px;
  justify-content: center;
  
  &:hover {
    background-color: $voltimax-brand-primary;
    transform: rotate(-90deg);
  }
}
```

---

## Responsive Behavior

### Mobile Breakpoints
- Small devices (< 576px): Full mobile layout
- Medium devices (576px - 768px): Mobile with adjustments
- Large devices (> 768px): Desktop navigation

### Touch Optimization
- Minimum touch target: 44x44px
- Adequate spacing between elements
- Clear visual feedback on interaction
- Smooth transitions and animations

---

## Usage Examples

### Adding a Custom Navigation Item
```twig
{% block layout_navigation_offcanvas_custom_item %}
    <li class="navigation-offcanvas-list-item mb-2">
        <a class="nav-item nav-link bg-white rounded px-3">
            Custom Item
        </a>
    </li>
{% endblock %}
```

### Modifying Header Button
```twig
{% block utilities_offcanvas_header_home_button %}
    <a href="{{ path('frontend.home.page') }}" 
       class="btn btn-light btn-sm rounded">
        {% sw_icon 'home' %}
    </a>
{% endblock %}
```

### Customizing Hover Effects
```scss
.navigation-offcanvas-link {
  &:hover {
    background-color: lighten($voltimax-brand-primary, 45%);
    border-left: 3px solid $voltimax-brand-primary;
  }
}
```

---

## Configuration

### Theme Configuration
Located in `theme.json`:
```json
{
  "config": {
    "fields": {
      "voltimax-bg-secondary-dark": {
        "value": "#2a2a2a"
      },
      "voltimax-brand-primary": {
        "value": "#007bff"
      }
    }
  }
}
```

### Admin Settings
Navigate to: **Themes > Voltimax > Mobile Navigation**
- Enable/disable mobile menu
- Configure button visibility
- Adjust colors and spacing

---

## Troubleshooting

### Common Issues

#### 1. Buttons Not Aligned
**Solution:** Ensure Bootstrap grid classes are intact:
```twig
<div class="row align-items-center gx-2">
```

#### 2. Missing Hover Effects
**Solution:** Check SCSS compilation:
```bash
bin/console theme:compile
bin/console cache:clear
```

#### 3. Double Backgrounds
**Solution:** Remove inline styles and use Bootstrap classes:
```twig
<!-- Wrong -->
<div style="background: white" class="bg-white">

<!-- Correct -->
<div class="bg-white">
```

---

## Performance Considerations

### Optimization Tips
1. **Minimize DOM depth** - Use flat structure where possible
2. **Reduce CSS specificity** - Prefer utility classes
3. **Lazy load images** - Use loading="lazy" attribute
4. **Debounce interactions** - Prevent rapid clicks
5. **Use CSS transforms** - For animations (GPU accelerated)

### Bundle Size
- Before optimization: ~15KB CSS
- After optimization: ~3KB CSS
- Reduction: 80%

---

## Browser Compatibility

### Supported Browsers
- ✅ Safari iOS 14+
- ✅ Chrome Mobile 90+
- ✅ Firefox Mobile 88+
- ✅ Samsung Internet 14+
- ✅ Edge Mobile 90+

### Required Features
- CSS Flexbox
- CSS Grid
- CSS Custom Properties
- Touch Events API

---

## Future Enhancements

### Planned Features
1. Swipe gestures for navigation
2. Animated transitions between levels
3. Search integration in header
4. Recent categories section
5. Customizable button order

### Known Limitations
- No RTL support yet
- Limited accessibility features
- No keyboard navigation optimization
- Missing breadcrumb trail

---

## Development Workflow

### Local Development
```bash
# Start Docker environment
docker-compose up -d

# Watch for changes
npm run watch

# Compile theme
docker exec shopware-6.6.10.4 bin/console theme:compile

# Clear cache
docker exec shopware-6.6.10.4 bin/console cache:clear
```

### Testing
```bash
# Mobile viewport testing
npm run test:mobile

# Cross-browser testing
npm run test:browsers

# Performance audit
npm run audit:lighthouse
```

---

## Code Standards

### Twig Templates
- Use `{% sw_extends %}` for inheritance
- Implement proper block structure
- Add meaningful block names
- Include accessibility attributes

### SCSS Guidelines
- Use theme variables
- Minimize nesting (max 3 levels)
- Prefer Bootstrap utilities
- Document custom styles

### Git Workflow
- Feature branch from develop
- Atomic commits per file
- Descriptive commit messages
- PR with screenshots

---

## Resources

### Documentation
- [Shopware 6 Docs](https://docs.shopware.com)
- [Bootstrap 5 Utilities](https://getbootstrap.com/docs/5.0/utilities)
- [Twig Documentation](https://twig.symfony.com/doc/3.x/)

### Tools
- Chrome DevTools Device Mode
- BrowserStack for testing
- Lighthouse for performance
- Wave for accessibility

---

## Support

For issues or questions:
1. Check this documentation
2. Review CHANGELOG-FEATURE-2.md
3. Contact development team
4. Create issue in project tracker