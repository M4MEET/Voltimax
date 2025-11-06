---
title: Bootstrap Utilities Visibility Fix
issue: VOLTIMAX-003
author: Meet Joshi
author_email: imeetjoshi@gmail.com
---

# Core

*  N/A

# API

*  N/A

# Administration

*  N/A

# Storefront

*  Changed visibility toggle implementation to use proper Bootstrap utility classes
*  Removed manual Bootstrap utility CSS overrides with !important flags
*  Changed template logic to handle all combinations of mobile/tablet visibility settings correctly
*  Aligned with Shopware 6.6 ADR for Bootstrap utilities without !important

# Upgrade Information

## Bootstrap Visibility Classes Fix

The visibility toggle system for topbar sections has been fixed to properly work with Bootstrap utilities according to Shopware 6.6 best practices.

### Changes to Visibility Logic
The template now correctly applies Bootstrap display utilities based on configuration:
- Hide on mobile only: `d-none d-md-block`
- Hide on tablet only: `d-block d-md-none d-lg-block`
- Hide on both mobile and tablet: `d-none d-lg-block`

### Removed Custom CSS
The following custom CSS implementations have been removed:
- Manual Bootstrap utility definitions with !important flags
- Custom media query implementations
- Forced specificity overrides

### Shopware 6.6 Compliance
The theme now follows Shopware 6.6 ADR (Architecture Decision Record) which states:
- Bootstrap utilities don't use !important by default
- Themes can override utilities without using !important
- Use Bootstrap components and utilities first, customize only when necessary

### Testing Visibility
After updating, test visibility on different viewports:
1. Mobile (<768px): Check configured mobile-hidden elements
2. Tablet (768px-991px): Check configured tablet-hidden elements  
3. Desktop (≥992px): All elements should be visible unless specifically configured