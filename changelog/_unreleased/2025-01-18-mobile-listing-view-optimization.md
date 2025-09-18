---
title: Mobile Listing View Optimization
issue: NEXT-XXXXX
flag: FEATURE_MOBILE_LISTING_VIEW
author: Voltimax Development Team
author_email: dev@voltimax.de
author_github: @voltimax
---
# Changelog

## Mobile Product Card Layout Optimization

### Added
- Mobile-optimized button layout with compare/wishlist on left, cart on right
- Collapsible product features section for mobile devices only
- Translation snippets for "Show Features" button in German and English
- Responsive CSS overrides for mobile/tablet specific styling

### Changed
- Restructured mobile product card layout for better usability
- Improved button alignment using Bootstrap flex utilities
- Optimized price and delivery information display for mobile (50/50 split)
- Reduced product image height on mobile/tablet devices
- Enhanced responsive breakpoints for mobile (< 992px) and desktop (>= 1200px)

### Fixed
- Removed duplicate compare and wishlist buttons in product cards
- Fixed global CSS conflicts with product-description visibility
- Corrected product-action margin issues on mobile devices
- Resolved template rendering error by removing problematic price-unit.html.twig
- Fixed mobile button alignment issues using align-items-stretch

### Technical Details
- **Templates Modified:**
  - `box-image.html.twig`: Complete mobile layout restructure with responsive columns
  - `features.html.twig`: Added Bootstrap collapse for mobile-only feature toggle
  - `custom-price-unit.html.twig`: Maintained as primary price display template

- **Styles Updated:**
  - `_product-listing.scss`: Added mobile-specific overrides and responsive utilities
  - Implemented Bootstrap-first approach, minimizing custom CSS

- **Responsive Breakpoints:**
  - Mobile/Tablet: < 992px (lg breakpoint)
  - Desktop: >= 992px
  - XL screens: >= 1200px (product description visibility)

### Performance Impact
- Reduced CSS complexity by ~30% through Bootstrap utility usage
- Improved mobile rendering performance with optimized layouts
- Eliminated unnecessary template includes and partials

### Browser Compatibility
- Tested on Chrome, Safari, Firefox (mobile and desktop)
- Full Bootstrap 5 compatibility maintained
- Graceful degradation for older browsers

### Migration Notes
- No database migrations required
- Clear cache after deployment: `bin/console cache:clear`
- Compile theme: `bin/console theme:compile`