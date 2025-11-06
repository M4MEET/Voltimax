---
title: Topbar Configuration Reorganization
issue: VOLTIMAX-001
author: Meet Joshi
author_email: imeetjoshi@gmail.com
---

# Core

*  Changed theme.json structure to use sections and blocks for better organization of topbar configuration
*  Removed `voltimaxCustomHeaderActive` field as topbar is now always enabled by default
*  Added separate configuration blocks for each topbar section (Left, Middle, Right, RightEnd, Trustpilot)

# API

*  N/A

# Administration

*  Changed topbar configuration tab name from "Custom Header" to "Top-bar"
*  Added organized sections for topbar styling, content, and visibility settings
*  Changed boolean fields to switch type for better UX in admin panel
*  Added `scss: false` flag to non-styling configuration fields

# Storefront

*  Changed topbar to be always enabled without requiring toggle
*  Updated base.html.twig to remove conditional topbar activation check
*  Simplified topbar template structure with cleaner configuration blocks

# Upgrade Information

## Topbar Configuration Changes

The topbar configuration has been reorganized for better maintainability. The following changes affect existing configurations:

### Removed Configuration Fields
- `voltimaxCustomHeaderActive` - The topbar is now always enabled by default

### Changed Field Types
All visibility toggle fields have been changed from `bool` to `switch` type:
- `voltimaxTopbarLeftHideMobile`
- `voltimaxTopbarLeftHideTablet`
- `voltimaxTopbarMiddleHideMobile`
- `voltimaxTopbarMiddleHideTablet`
- `voltimaxTopbarRightHideMobile`
- `voltimaxTopbarRightHideTablet`
- `voltimaxTopbarRightEndHideMobile`
- `voltimaxTopbarRightEndHideTablet`

### Theme.json Structure
Configuration fields are now organized into sections:
- `topbarStyling` - Contains background, text color, height, and font size settings
- `topbarContent` - Contains text content and Trustpilot widget configuration
- `topbarVisibility` - Contains mobile/tablet visibility toggles

After updating, run:
```bash
bin/console theme:refresh
bin/console theme:compile
```