---
title: Trustpilot Widget Configuration
issue: VOLTIMAX-002
author: Meet Joshi
author_email: imeetjoshi@gmail.com
---

# Core

*  Added configurable Trustpilot widget field in theme configuration
*  Removed hardcoded Trustpilot HTML from template

# API

*  N/A

# Administration

*  Added `voltimaxTrustpilotWidget` textarea field in topbar configuration
*  Added visibility toggles for Trustpilot section on mobile and tablet devices
*  Added helpful instructions for obtaining Trustpilot widget code

# Storefront

*  Changed Trustpilot implementation from hardcoded HTML to configurable widget
*  Added Trustpilot TrustBox bootstrap script to base template head
*  Implemented responsive column sizing (30% for Trustpilot, 70% for other sections)
*  Changed from container-fluid to container for consistent layout

# Upgrade Information

## Trustpilot Widget Configuration

The Trustpilot widget is now configurable through the admin panel instead of being hardcoded in the template.

### New Configuration Field
- `voltimaxTrustpilotWidget` - Textarea field for pasting complete Trustpilot widget HTML code

### Migration Steps
1. Get your Trustpilot widget code from your Trustpilot Business account
2. Navigate to Theme Configuration > Top-bar > Trustpilot section
3. Paste the complete widget HTML code in the textarea
4. Save and compile the theme

### Default Widget
The theme includes a default horizontal Trustpilot widget template that can be customized:
- Business Unit ID: 641844b25c3148d8e2f02d34
- Template: Horizontal widget
- Theme: Dark

To disable Trustpilot, simply clear the widget field in the admin configuration.