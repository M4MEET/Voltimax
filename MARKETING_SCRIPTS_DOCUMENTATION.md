# Voltimax Marketing Scripts Documentation

## 🚀 Overview
The Voltimax theme includes a comprehensive marketing scripts management system that allows you to easily integrate various analytics, tracking, and marketing tools into your Shopware store. The system supports both global scripts (loaded on all pages) and checkout-specific scripts (loaded only during checkout and order confirmation).

## ✨ Features

### Priority-Based Loading
Scripts are loaded in a specific order to ensure optimal performance:
1. **🥇 Script 1** - Highest Priority (Google Tag Manager)
2. **🥈 Script 2** - High Priority (Facebook Pixel, LinkedIn)
3. **🥉 Script 3** - Medium Priority (Google Analytics)
4. **💬 Script 4** - Low Priority (Chat Widgets)
5. **🔧 Script 5** - Lowest Priority (Additional Tools)

### Performance Optimization
- **⚡ Async Loading**: Each script can be configured to load asynchronously for better page performance
- **✅ Toggle Control**: Enable/disable scripts without removing the code
- **🎯 HEAD Section Loading**: All scripts load in the HEAD section for proper tracking
- **💳 Checkout-Specific Scripts**: Separate scripts that only load on checkout pages for conversion tracking

## 📋 Configuration Guide

### Accessing Marketing Scripts
1. Navigate to **Admin → Themes → Voltimax Theme**
2. Click on the **🚀 Marketing & Analytics** tab
3. You'll see 5 script slots with clear labels and descriptions

### For Each Script Slot

#### Script Content Field
- **Purpose**: Paste your complete script including `<script>` tags
- **Format**: Full HTML script tags with JavaScript code
- **Example**:
```html
<script>
  (function(w,d,s,l,i){
    w[l]=w[l]||[];
    w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
    // ... rest of script
  })(window,document,'script','dataLayer','GTM-XXXXXX');
</script>
```

#### Enable Checkbox (✅)
- **Checked**: Script is active and will load on the storefront
- **Unchecked**: Script is saved but won't load (useful for testing)

#### Async Loading (⚡)
- **Checked**: Script loads asynchronously (recommended for performance)
- **Unchecked**: Script loads synchronously (use only if required by the script)

## 🛠️ Common Integrations

### Google Tag Manager (Script 1)
```html
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXX');</script>
```
- Replace `GTM-XXXXXX` with your container ID
- Enable async: ✅ Yes

### Facebook Pixel (Script 2)
```html
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', 'YOUR_PIXEL_ID');
fbq('track', 'PageView');
</script>
```
- Replace `YOUR_PIXEL_ID` with your pixel ID
- Enable async: ✅ Yes

### Google Analytics 4 (Script 3)
```html
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```
- Replace `G-XXXXXXXXXX` with your measurement ID
- Enable async: ✅ Yes

### Chat Widgets (Script 4)
Examples: Intercom, Drift, Zendesk Chat, Crisp
- Usually provided by your chat service provider
- Enable async: ✅ Yes (unless specified otherwise)

### Additional Tools (Script 5)
Examples: Hotjar, FullStory, Custom Analytics
- Heatmaps and session recording tools
- A/B testing scripts
- Custom tracking scripts
- Enable async: ✅ Yes (recommended)

## 💳 Checkout-Specific Scripts

### Overview
The Voltimax theme includes dedicated checkout scripts that only load on:
- Checkout pages (cart, address, payment, confirm)
- Order confirmation/thank you page

### Available Checkout Scripts

#### 🎯 Checkout Script 1 - Conversion Tracking
- **Purpose**: Track purchase completions and checkout events
- **Common Uses**: Google Ads Conversion, Facebook Conversion API
- **Loads On**: All checkout pages and order confirmation

#### 📊 Checkout Script 2 - Enhanced E-commerce
- **Purpose**: Detailed purchase analytics
- **Common Uses**: Google Analytics 4 Enhanced E-commerce, Product Performance
- **Loads On**: Checkout flow and purchase completion

#### ✅ Checkout Script 3 - Order Confirmation
- **Purpose**: Thank you page specific tracking
- **Common Uses**: Customer surveys, post-purchase upsells, loyalty programs
- **Loads On**: Order confirmation page only

### Configuration
1. Navigate to **Admin → Themes → Voltimax Theme**
2. Go to **🚀 Marketing & Analytics** tab
3. Find **💳 Checkout-Specific Scripts** section
4. Add your scripts with `<script>` tags included
5. Enable with the checkbox

### Example: Google Ads Conversion (Checkout Script 1)
```html
<script>
gtag('event', 'conversion', {
    'send_to': 'AW-XXXXXXX/XXXXXXXXXXXXXXXXX',
    'value': 1.0,
    'currency': 'EUR',
    'transaction_id': ''
});
</script>
```

### Example: Enhanced E-commerce (Checkout Script 2)
```html
<script>
gtag('event', 'purchase', {
    'transaction_id': '12345',
    'value': 25.42,
    'currency': 'EUR',
    'items': []
});
</script>
```

## 🎨 Admin Panel Features

### Visual Organization
- **🚀 Marketing & Analytics Tab**: Easy to find in theme settings
- **📊 Analytics & Tracking Scripts Block**: Grouped script configurations
- **🏆 Trophy Icons**: Visual priority indicators (🥇🥈🥉)
- **📝 Detailed Help Text**: Instructions and examples for each field
- **⚡ Performance Tips**: Async loading recommendations

### User-Friendly Labels
- Clear script naming with suggested use cases
- Priority levels clearly indicated
- Helpful emoji icons for quick visual scanning
- Bilingual support (English/German)

## 🔒 Security Best Practices

1. **Verify Script Sources**: Only add scripts from trusted sources
2. **Test in Staging**: Always test new scripts in a staging environment first
3. **Monitor Performance**: Check page load times after adding scripts
4. **Regular Audits**: Review and remove unused scripts periodically
5. **Use Official Scripts**: Always copy scripts from official documentation

## 🐛 Troubleshooting

### Script Not Loading
1. Check if the script is enabled (✅ checkbox)
2. Clear Shopware cache: `php bin/console cache:clear`
3. Verify script syntax is correct
4. Check browser console for JavaScript errors

### Performance Issues
1. Enable async loading for all scripts where possible
2. Consider using Google Tag Manager to consolidate scripts
3. Load non-critical scripts in lower priority slots
4. Monitor with browser DevTools Network tab

### Script Conflicts
1. Check for duplicate script installations
2. Verify scripts don't conflict with each other
3. Use browser console to identify JavaScript errors
4. Test scripts one at a time to isolate issues

## 📝 Template Structure

### Global Scripts
Rendered in `/Resources/views/storefront/base.html.twig`:
- Scripts load in the `<head>` section on all pages
- Priority order is maintained (1-5)
- Async attribute is added when enabled
- Scripts only render when active

### Checkout Scripts
Rendered in checkout-specific templates:
- `/Resources/views/storefront/page/checkout/index.html.twig` - Checkout flow pages
- `/Resources/views/storefront/page/checkout/finish/finish-details.html.twig` - Order confirmation
- Only load on checkout and order confirmation pages
- Scripts render based on active status

## 🔄 Updates and Maintenance

- **Theme Updates**: Marketing scripts configuration is preserved during theme updates
- **Backup**: Always backup your script configurations before major updates
- **Testing**: Test all scripts after Shopware or theme updates

## 📞 Support

For issues or questions about the marketing scripts feature:
1. Check this documentation
2. Review browser console for errors
3. Contact theme support with specific error messages

---

**Version**: 3.0.0  
**Last Updated**: 2024  
**Compatible with**: Shopware 6.6.x