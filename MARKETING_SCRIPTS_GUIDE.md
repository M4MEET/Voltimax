# Marketing Scripts Management Guide

## Overview
The Voltimax Theme 3.0.0 now includes a comprehensive marketing scripts management system that allows you to add custom scripts to the HTML HEAD section with priority ordering and performance optimization.

## Features
- ✅ **5 Priority-ordered Script Slots** - Organized from highest to lowest priority
- ✅ **Async Loading Support** - Non-blocking script execution for better performance
- ✅ **Enable/Disable Controls** - Easy activation and deactivation of scripts
- ✅ **Security Validation** - Basic sanitization and security warnings
- ✅ **Admin Integration** - Clean interface in theme configuration
- ✅ **Multi-language Support** - German and English translations

## How to Use

### 1. Access Marketing Scripts Configuration
1. Go to **Administration** → **Extensions** → **Themes**
2. Select the **Voltimax Theme**
3. Click on the **Marketing Scripts** tab

### 2. Add Your Scripts
Each script slot has three configuration options:
- **Script Content**: The actual JavaScript/HTML code
- **Enable Script**: Toggle to activate/deactivate the script
- **Load Async**: Whether to load the script asynchronously

### 3. Priority System
Scripts are loaded in priority order:
1. **Script 1** - Highest Priority (e.g., Google Tag Manager)
2. **Script 2** - High Priority (e.g., Facebook Pixel)
3. **Script 3** - Medium Priority (e.g., Analytics)
4. **Script 4** - Low Priority (e.g., Additional tracking)
5. **Script 5** - Lowest Priority (e.g., Secondary tools)

## Example Configurations

### Google Tag Manager
```html
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXX');</script>
<!-- End Google Tag Manager -->
```

### Facebook Pixel
```html
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', 'YOUR_PIXEL_ID');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
```

### Google Analytics 4
```html
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GA_MEASUREMENT_ID');
</script>
```

## Best Practices

### 1. Security
- ⚠️ Only add scripts from trusted sources
- 🔒 Avoid inline event handlers (onclick, onload, etc.)
- 🛡️ Review scripts for potential security issues

### 2. Performance
- 💡 Use async loading when possible
- ⚡ Place critical scripts in higher priority slots
- 📊 Monitor loading times and page performance

### 3. Testing
- 🧪 Always test in staging environment first
- 🔍 Check browser console for errors
- 📱 Test across different devices and browsers
- 🔄 Verify script functionality after updates

## Troubleshooting

### Scripts Not Loading
1. Ensure the script is **enabled** in configuration
2. Check that the **content is not empty**
3. Clear Shopware cache: `bin/console cache:clear`
4. Verify browser console for JavaScript errors

### Performance Issues
1. Enable **async loading** for non-critical scripts
2. Review script **priority ordering**
3. Consider reducing number of active scripts
4. Use browser dev tools to analyze loading times

### Admin Configuration Not Saving
1. Clear theme cache: `bin/console theme:compile`
2. Refresh admin page
3. Check file permissions on theme configuration
4. Verify theme is properly installed and active

## Technical Implementation

### File Structure
```
src/
├── Subscriber/MarketingScriptsSubscriber.php
├── Resources/
│   ├── theme.json (marketing configuration)
│   ├── config/services.xml (subscriber registration)
│   ├── views/storefront/base.html.twig (script injection)
│   └── snippet/
│       ├── de_DE/marketing.de-DE.json
│       └── en_GB/marketing.en-GB.json
```

### Event Subscription
The system uses `StorefrontRenderEvent` to inject scripts into the template rendering process, ensuring scripts are available on all pages.

### Script Sanitization
Basic security measures are implemented:
- XSS pattern detection
- Automatic script tag wrapping
- Security logging for monitoring

## Support

For technical support or feature requests related to marketing scripts:
1. Check this documentation first
2. Review Shopware logs for errors
3. Test with minimal script content
4. Contact theme developer with specific error details

---

**Version**: 3.0.0  
**Last Updated**: August 2025  
**Compatible with**: Shopware 6.6.10.4+