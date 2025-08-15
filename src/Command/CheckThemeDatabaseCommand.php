<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckThemeDatabaseCommand extends Command
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:check-theme-db')
            ->setDescription('Check theme database for phantom tracking fields');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Checking Theme Database for Phantom Fields');
        
        // Check theme table
        $io->section('Checking theme table');
        try {
            $themes = $this->connection->fetchAllAssociative(
                "SELECT id, name, technical_name, author FROM theme WHERE technical_name = 'VoltimaxTheme'"
            );
            
            if (empty($themes)) {
                $io->info('No VoltimaxTheme found in theme table');
            } else {
                foreach ($themes as $theme) {
                    $io->writeln("Found theme: {$theme['name']} (ID: {$theme['id']})");
                    
                    // Get base_config
                    $baseConfig = $this->connection->fetchOne(
                        "SELECT base_config FROM theme WHERE id = ?",
                        [$theme['id']]
                    );
                    
                    if ($baseConfig) {
                        $configData = json_decode($baseConfig, true);
                        
                        if (isset($configData['fields'])) {
                            $io->writeln("Checking fields in base_config...");
                            
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
                            
                            $foundPhantom = [];
                            foreach ($phantomFields as $field) {
                                if (isset($configData['fields'][$field])) {
                                    $foundPhantom[] = $field;
                                }
                            }
                            
                            if (empty($foundPhantom)) {
                                $io->success('No phantom tracking fields found in base_config');
                            } else {
                                $io->warning('Found phantom fields in base_config:');
                                foreach ($foundPhantom as $field) {
                                    $io->writeln("  - $field");
                                }
                                
                                // Remove them
                                foreach ($foundPhantom as $field) {
                                    unset($configData['fields'][$field]);
                                }
                                
                                $newConfig = json_encode($configData, JSON_PRETTY_PRINT);
                                $this->connection->executeStatement(
                                    "UPDATE theme SET base_config = ? WHERE id = ?",
                                    [$newConfig, $theme['id']]
                                );
                                
                                $io->success('Phantom fields removed from theme base_config');
                            }
                            
                            // Show current voltimax fields
                            $io->section('Current Voltimax fields in base_config:');
                            $voltimaxFields = [];
                            foreach (array_keys($configData['fields']) as $key) {
                                if (strpos($key, 'voltimax') !== false) {
                                    $voltimaxFields[] = $key;
                                }
                            }
                            
                            if (!empty($voltimaxFields)) {
                                foreach ($voltimaxFields as $field) {
                                    $io->writeln("  - $field");
                                }
                            } else {
                                $io->info('No voltimax fields found');
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $io->error('Error accessing theme table: ' . $e->getMessage());
        }
        
        // Check theme_child table
        $io->section('Checking theme_child table');
        try {
            $children = $this->connection->fetchAllAssociative(
                "SELECT parent_id, child_id FROM theme_child WHERE parent_id IN (SELECT id FROM theme WHERE technical_name = 'VoltimaxTheme')"
            );
            
            if (!empty($children)) {
                $io->writeln(sprintf('Found %d child theme relationships', count($children)));
            } else {
                $io->info('No child themes found');
            }
        } catch (\Exception $e) {
            $io->note('theme_child table may not exist: ' . $e->getMessage());
        }
        
        // Check theme_sales_channel
        $io->section('Checking theme_sales_channel');
        try {
            $assignments = $this->connection->fetchAllAssociative(
                "SELECT tsc.sales_channel_id, sc.name 
                 FROM theme_sales_channel tsc
                 LEFT JOIN sales_channel sc ON sc.id = tsc.sales_channel_id
                 WHERE tsc.theme_id IN (SELECT id FROM theme WHERE technical_name = 'VoltimaxTheme')"
            );
            
            if (!empty($assignments)) {
                $io->writeln('Theme assigned to sales channels:');
                foreach ($assignments as $assignment) {
                    $io->writeln("  - {$assignment['name']}");
                }
            }
        } catch (\Exception $e) {
            $io->note('Error checking theme assignments: ' . $e->getMessage());
        }
        
        $io->success('Database check complete!');
        
        return Command::SUCCESS;
    }
}