---
title: Marketing Scripts and Footer Reorganization
issue: VOLTIMAX-004
author: Meet Joshi
author_email: imeetjoshi@gmail.com
---

# Core

*  Changed theme.json structure to separate marketing scripts, payment methods, and shipping methods into distinct blocks

# API

*  N/A

# Administration

*  Added dedicated "Marketing & Analytics" tab for script management
*  Created separate configuration blocks for 5 marketing scripts plus 3 checkout-specific scripts
*  Added individual enable/disable toggles and async loading options for each script
*  Reorganized footer configuration with separate blocks for payment and shipping logos
*  Added helpful descriptions and emojis for better UX in admin panel

# Storefront

*  Changed marketing script implementation to use priority-based loading order
*  Added automatic async attribute injection for marketing scripts when configured
*  Consolidated footer icon management from multiple plugins into single theme

# Upgrade Information

## Marketing Scripts Configuration

Marketing and analytics scripts are now organized in a dedicated tab with better control over loading behavior.

### New Marketing Script Fields
Each of the 5 marketing scripts now has:
- `voltimaxMarketingScriptX` - Textarea for script content
- `voltimaxMarketingScriptXActive` - Toggle to enable/disable
- `voltimaxMarketingScriptXAsync` - Toggle for asynchronous loading

### Checkout-Specific Scripts
Three new checkout-only script slots:
- `voltimaxCheckoutScript1` - For conversion tracking
- `voltimaxCheckoutScript2` - For e-commerce tracking
- `voltimaxCheckoutScript3` - For order confirmation tracking

### Recommended Usage
1. Script 1: Google Tag Manager (GTM)
2. Script 2: Facebook Pixel, LinkedIn Insight
3. Script 3: Google Analytics 4, Matomo
4. Script 4: Chat widgets (Intercom, Drift)
5. Script 5: Heatmap tools (Hotjar, FullStory)

### Footer Reorganization
Payment and shipping logos are now in separate configuration blocks:
- `footerPayment` block - Contains all payment method logos
- `footerShipping` block - Contains all shipping provider logos

This consolidates functionality previously spread across multiple plugins.