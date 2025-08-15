<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanThemeConfigValuesCommand extends Command
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:clean-theme-config')
            ->setDescription('Remove phantom tracking fields from theme config_values');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Cleaning Theme Config Values');
        
        // Get the theme
        $theme = $this->connection->fetchAssociative(
            "SELECT id, name, config_values FROM theme WHERE technical_name = 'VoltimaxTheme'"
        );
        
        if (!$theme) {
            $io->error('VoltimaxTheme not found');
            return Command::FAILURE;
        }
        
        $io->section('Found theme: ' . $theme['name']);
        
        if (!$theme['config_values']) {
            $io->success('No config_values to clean');
            return Command::SUCCESS;
        }
        
        // Decode the config values
        $configValues = json_decode($theme['config_values'], true);
        
        if (!is_array($configValues)) {
            $io->warning('config_values is not a valid JSON array');
            return Command::FAILURE;
        }
        
        // Phantom fields to remove
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
        
        $io->section('Checking for phantom fields...');
        
        $removedFields = [];
        foreach ($phantomFields as $field) {
            if (isset($configValues[$field])) {
                unset($configValues[$field]);
                $removedFields[] = $field;
                $io->writeln("  ❌ Removed: $field");
            }
        }
        
        if (empty($removedFields)) {
            $io->success('No phantom fields found in config_values');
        } else {
            // Save the cleaned config
            $newConfigJson = json_encode($configValues);
            
            $this->connection->executeStatement(
                "UPDATE theme SET config_values = ? WHERE id = ?",
                [$newConfigJson, $theme['id']]
            );
            
            $io->success(sprintf('Removed %d phantom fields from config_values', count($removedFields)));
        }
        
        // Show current valid voltimax fields
        $io->section('Remaining voltimax fields in config_values:');
        $voltimaxFields = [];
        foreach (array_keys($configValues) as $key) {
            if (strpos($key, 'voltimax') !== false) {
                $voltimaxFields[] = $key;
            }
        }
        
        if (!empty($voltimaxFields)) {
            foreach ($voltimaxFields as $field) {
                $io->writeln("  ✅ $field");
            }
        } else {
            $io->info('No voltimax fields remaining');
        }
        
        $io->section('Next steps:');
        $io->listing([
            'Clear cache: php bin/console cache:clear',
            'Refresh theme: php bin/console theme:refresh',
            'Compile theme: php bin/console theme:compile',
            'Hard refresh browser (Ctrl+F5) in admin panel'
        ]);
        
        return Command::SUCCESS;
    }
}