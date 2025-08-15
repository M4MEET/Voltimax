<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Doctrine\DBAL\Connection;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanupMarketingScriptsCommand extends Command
{
    private SystemConfigService $systemConfigService;
    private Connection $connection;
    
    public function __construct(
        SystemConfigService $systemConfigService,
        Connection $connection
    ) {
        parent::__construct();
        $this->systemConfigService = $systemConfigService;
        $this->connection = $connection;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:cleanup-scripts')
            ->setDescription('Clean up test marketing scripts and set proper defaults')
            ->addOption('reset', 'r', InputOption::VALUE_NONE, 'Reset all scripts to clean placeholders');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $reset = $input->getOption('reset');
        
        $io->title('Cleaning Up Marketing Scripts Configuration');
        
        // Step 1: Remove any test/debug entries
        $io->section('Step 1: Removing test entries from database');
        
        // Find all voltimax marketing script related entries
        $query = "SELECT id, configuration_key, sales_channel_id 
                  FROM system_config 
                  WHERE configuration_key LIKE '%voltimax%' 
                  OR configuration_key LIKE '%Marketing%'
                  OR configuration_key LIKE '%Tracking%'
                  ORDER BY configuration_key";
        
        $results = $this->connection->fetchAllAssociative($query);
        
        $io->writeln(sprintf('Found %d configuration entries', count($results)));
        
        $toDelete = [];
        $toKeep = [];
        
        foreach ($results as $row) {
            $key = $row['configuration_key'];
            
            // Keep only the proper VoltimaxTheme.config entries for current theme
            if (preg_match('/^VoltimaxTheme\.config\.voltimax(MarketingScript\d+(Active|Async)?|CustomHeader|Topbar)/', $key)) {
                $toKeep[] = $key;
            } else if (strpos($key, 'voltimaxTracking') !== false) {
                // Old tracking scripts - mark for deletion
                $toDelete[] = $row['id'];
                $io->writeln("  - Found old tracking config to remove: {$key}");
            } else if (!preg_match('/^VoltimaxTheme\.config\./', $key) && strpos($key, 'voltimax') !== false) {
                // Other voltimax entries not part of theme config
                $toDelete[] = $row['id'];
            }
        }
        
        if (!empty($toDelete)) {
            $io->writeln(sprintf('Deleting %d test/invalid entries...', count($toDelete)));
            
            // Use parameterized query to avoid SQL injection
            $placeholders = array_map(function($i) { return '?'; }, $toDelete);
            $deleteQuery = "DELETE FROM system_config WHERE id IN (" . implode(',', $placeholders) . ")";
            
            try {
                $this->connection->executeStatement($deleteQuery, $toDelete);
                $io->success('Test entries removed');
            } catch (\Exception $e) {
                $io->warning('Could not delete test entries: ' . $e->getMessage());
            }
        } else {
            $io->info('No test entries found to delete');
        }
        
        // Step 2: Set proper placeholder content
        if ($reset) {
            $io->section('Step 2: Setting clean placeholder scripts');
            
            $placeholders = [
                1 => [
                    'content' => "<!-- Google Tag Manager -->\n<!-- Replace GTM-XXXXXX with your container ID -->\n<script>\n(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\nnew Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\nj=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n})(window,document,'script','dataLayer','GTM-XXXXXX');\n</script>",
                    'active' => false,
                    'async' => false
                ],
                2 => [
                    'content' => "<!-- Facebook Pixel -->\n<!-- Replace YOUR_PIXEL_ID_HERE with your pixel ID -->\n<script>\n!function(f,b,e,v,n,t,s)\n{if(f.fbq)return;n=f.fbq=function(){n.callMethod?\nn.callMethod.apply(n,arguments):n.queue.push(arguments)};\nif(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';\nn.queue=[];t=b.createElement(e);t.async=!0;\nt.src=v;s=b.getElementsByTagName(e)[0];\ns.parentNode.insertBefore(t,s)}(window,document,'script',\n'https://connect.facebook.net/en_US/fbevents.js');\nfbq('init', 'YOUR_PIXEL_ID_HERE');\nfbq('track', 'PageView');\n</script>",
                    'active' => false,
                    'async' => true
                ],
                3 => [
                    'content' => "<!-- Google Analytics GA4 -->\n<!-- Replace G-XXXXXXXXXX with your measurement ID -->\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX\"></script>\n<script>\nwindow.dataLayer = window.dataLayer || [];\nfunction gtag(){dataLayer.push(arguments);}\ngtag('js', new Date());\ngtag('config', 'G-XXXXXXXXXX');\n</script>",
                    'active' => false,
                    'async' => true
                ],
                4 => [
                    'content' => "<!-- Custom Marketing Script -->\n<!-- Add your custom tracking or marketing script here -->\n<script>\n// Your custom code\n</script>",
                    'active' => false,
                    'async' => true
                ],
                5 => [
                    'content' => "<!-- Additional Analytics/Tracking -->\n<!-- Add any additional tracking scripts here -->\n<script>\n// Additional tracking code\n</script>",
                    'active' => false,
                    'async' => true
                ]
            ];
            
            foreach ($placeholders as $i => $config) {
                $this->systemConfigService->set("VoltimaxTheme.config.voltimaxMarketingScript{$i}", $config['content']);
                $this->systemConfigService->set("VoltimaxTheme.config.voltimaxMarketingScript{$i}Active", $config['active']);
                $this->systemConfigService->set("VoltimaxTheme.config.voltimaxMarketingScript{$i}Async", $config['async']);
            }
            
            $io->success('Clean placeholder scripts have been set (all inactive by default)');
        }
        
        // Step 3: Show current state
        $io->section('Step 3: Current Marketing Scripts Configuration');
        
        $table = [];
        for ($i = 1; $i <= 5; $i++) {
            $content = $this->systemConfigService->get("VoltimaxTheme.config.voltimaxMarketingScript{$i}");
            $active = $this->systemConfigService->get("VoltimaxTheme.config.voltimaxMarketingScript{$i}Active");
            $async = $this->systemConfigService->get("VoltimaxTheme.config.voltimaxMarketingScript{$i}Async");
            
            $contentPreview = 'Not configured';
            if ($content) {
                if (strpos($content, 'GTM-') !== false) {
                    $contentPreview = 'Google Tag Manager';
                } elseif (strpos($content, 'fbq') !== false) {
                    $contentPreview = 'Facebook Pixel';
                } elseif (strpos($content, 'gtag') !== false) {
                    $contentPreview = 'Google Analytics';
                } elseif (!empty($content)) {
                    $contentPreview = 'Custom Script';
                }
            }
            
            $table[] = [
                "Script {$i}",
                $contentPreview,
                $active ? '✅ Active' : '❌ Inactive',
                $async ? 'Async' : 'Sync'
            ];
        }
        
        $io->table(['Script', 'Content', 'Status', 'Load Type'], $table);
        
        $io->success('Cleanup complete!');
        $io->section('Next Steps:');
        $io->listing([
            'Clear cache: docker exec shopware-6.6.10.4 php bin/console cache:clear',
            'Go to Admin → Themes → Voltimax Theme → Marketing Scripts',
            'Replace placeholder IDs with your actual tracking IDs',
            'Enable the scripts you want to use',
            'Save and clear cache'
        ]);
        
        return Command::SUCCESS;
    }
}