# Marketing Scripts Test Configuration

## Test Scripts for Verification

Use these simple test scripts to verify the marketing scripts functionality is working correctly:

### Test Script 1 (Console Log Test)
```html
<script>
console.log('Voltimax Marketing Script 1 - Loaded successfully!');
console.log('Priority: 1 (Highest)');
console.log('Timestamp:', new Date().toISOString());
</script>
```

### Test Script 2 (DOM Ready Test)  
```html
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Voltimax Marketing Script 2 - DOM Ready!');
    if (typeof window.voltimaxMarketing === 'undefined') {
        window.voltimaxMarketing = {};
    }
    window.voltimaxMarketing.script2Loaded = true;
});
</script>
```

### Test Script 3 (Async Test)
```html
<script>
(function() {
    console.log('Voltimax Marketing Script 3 - Async Test');
    setTimeout(function() {
        console.log('Voltimax Script 3 - Delayed execution after 1 second');
    }, 1000);
})();
</script>
```

### Test Script 4 (Global Variable Test)
```html
<script>
window.VOLTIMAX_SCRIPT_4_LOADED = true;
console.log('Voltimax Marketing Script 4 - Global variable set');
console.log('window.VOLTIMAX_SCRIPT_4_LOADED:', window.VOLTIMAX_SCRIPT_4_LOADED);
</script>
```

### Test Script 5 (Performance Test)
```html
<script>
console.time('VoltimaxScript5Performance');
for (let i = 0; i < 1000; i++) {
    // Simple performance test
}
console.timeEnd('VoltimaxScript5Performance');
console.log('Voltimax Marketing Script 5 - Performance test completed');
</script>
```

## Verification Steps

1. **Add Test Scripts**: Copy any of the above test scripts to the marketing script configuration fields
2. **Enable Scripts**: Make sure to toggle "Enable Script" for each test script
3. **Configure Async**: Set "Load Async" based on your testing needs
4. **Clear Cache**: Run `bin/console cache:clear` and `bin/console theme:compile`
5. **Check Frontend**: Load any page on your storefront
6. **Verify in Console**: 
   - Open browser Developer Tools (F12)
   - Go to Console tab
   - Look for the test script output messages
   - Check that scripts load in correct priority order (1, 2, 3, 4, 5)

## Expected Console Output

When all test scripts are enabled, you should see output like:
```
Voltimax Marketing Script 1 - Loaded successfully!
Priority: 1 (Highest)  
Timestamp: 2025-08-09T10:30:45.123Z
Voltimax Marketing Script 2 - DOM Ready!
Voltimax Marketing Script 3 - Async Test  
Voltimax Marketing Script 4 - Global variable set
window.VOLTIMAX_SCRIPT_4_LOADED: true
VoltimaxScript5Performance: 0.123ms
Voltimax Marketing Script 5 - Performance test completed
Voltimax Script 3 - Delayed execution after 1 second
```

## Troubleshooting

- **No console output**: Check that scripts are enabled and cache is cleared
- **Wrong load order**: Scripts should appear in priority order (1-5)  
- **Missing scripts**: Verify theme is active and configuration is saved
- **Async not working**: Check that async option is enabled for relevant scripts

## Production Use

🚨 **Important**: Remove test scripts before going live. Replace with your actual marketing scripts like Google Tag Manager, Facebook Pixel, etc.
