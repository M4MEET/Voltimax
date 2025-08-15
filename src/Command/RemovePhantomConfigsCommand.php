<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemovePhantomConfigsCommand extends Command
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:remove-phantom-configs')
            ->setDescription('Remove phantom tracking configurations that appear in admin but are not defined in theme.json');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Removing Phantom Tracking Configurations');
        
        // These are the phantom fields that shouldn't exist
        $phantomFields = [
            'voltimaxTrackingScriptsHead',
            'voltimaxTrackingHeadPriority',
            'voltimaxTrackingScriptsFooter',
            'voltimaxTrackingScriptsBodyStart',
            'voltimaxTrackingScriptsBodyEnd',
            'voltimaxTrackingScriptsCheckout',
            'voltimaxTrackingFooterPriority',
            'voltimaxTrackingBodyStartPriority',
            'voltimaxTrackingBodyEndPriority',
            'voltimaxTrackingCheckoutPriority'
        ];
        
        $io->section('Searching for phantom configurations...');
        
        $toDelete = [];
        foreach ($phantomFields as $field) {
            // Check various possible key formats
            $patterns = [
                "VoltimaxTheme.config.{$field}",
                "Voltimax.config.{$field}",
                $field
            ];
            
            foreach ($patterns as $pattern) {
                $query = "SELECT id, configuration_key FROM system_config WHERE configuration_key = ?";
                $result = $this->connection->fetchAssociative($query, [$pattern]);
                
                if ($result) {
                    $toDelete[] = $result;
                    $io->writeln("  Found: {$result['configuration_key']}");
                }
            }
        }
        
        if (empty($toDelete)) {
            $io->success('No phantom configurations found in database.');
            
            $io->section('Note:');
            $io->writeln('If these fields still appear in the admin panel:');
            $io->listing([
                'They may be cached in the browser - try hard refresh (Ctrl+F5)',
                'They may be in the theme configuration cache - run: php bin/console theme:refresh',
                'They may be in a different sales channel - check all sales channels'
            ]);
        } else {
            $io->section('Removing phantom configurations...');
            
            foreach ($toDelete as $config) {
                $this->connection->executeStatement(
                    "DELETE FROM system_config WHERE id = ?",
                    [$config['id']]
                );
                $io->writeln("  Removed: {$config['configuration_key']}");
            }
            
            $io->success(sprintf('Removed %d phantom configurations.', count($toDelete)));
        }
        
        // Also update the theme's base_config to ensure these fields aren't defined there
        $io->section('Checking theme base configuration...');
        
        $themeQuery = "SELECT id, base_config FROM theme WHERE name = 'VoltimaxTheme'";
        $theme = $this->connection->fetchAssociative($themeQuery);
        
        if ($theme && $theme['base_config']) {
            $baseConfig = json_decode($theme['base_config'], true);
            $updated = false;
            
            if (isset($baseConfig['fields'])) {
                foreach ($phantomFields as $field) {
                    if (isset($baseConfig['fields'][$field])) {
                        unset($baseConfig['fields'][$field]);
                        $io->writeln("  Removed {$field} from theme base_config");
                        $updated = true;
                    }
                }
            }
            
            if ($updated) {
                $this->connection->executeStatement(
                    "UPDATE theme SET base_config = ? WHERE id = ?",
                    [json_encode($baseConfig), $theme['id']]
                );
                $io->success('Updated theme base configuration.');
            } else {
                $io->info('Theme base configuration is clean.');
            }
        }
        
        $io->section('Next Steps:');
        $io->listing([
            'Clear all caches: php bin/console cache:clear',
            'Refresh theme: php bin/console theme:refresh',
            'Rebuild admin: ./bin/build-administration.sh',
            'Hard refresh browser: Ctrl+F5 in admin panel'
        ]);
        
        return Command::SUCCESS;
    }
}