<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class QuickSearchDatabaseCommand extends Command
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:quick-search-db')
            ->setDescription('Quick search for phantom tracking fields in key tables');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Quick Search for Phantom Tracking Fields');
        
        $searchTerm = 'voltimaxTracking';
        
        // Priority tables to search
        $priorityTables = [
            'system_config' => ['configuration_key', 'configuration_value'],
            'theme' => ['base_config', 'config_values'],
            'theme_translation' => ['custom_fields', 'config'],
            'user_config' => ['key', 'value'],
            'app_config' => ['key', 'value'],
            'plugin' => ['name', 'label', 'description'],
            'snippet' => ['value', 'translation_key'],
            'sales_channel' => ['custom_fields'],
            'sales_channel_translation' => ['custom_fields'],
        ];
        
        $foundResults = [];
        
        foreach ($priorityTables as $table => $columns) {
            // Check if table exists
            try {
                $tableExists = $this->connection->fetchOne(
                    "SELECT COUNT(*) FROM information_schema.TABLES 
                     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?",
                    [$table]
                );
                
                if (!$tableExists) {
                    $io->note("Table $table does not exist");
                    continue;
                }
                
                $io->section("Searching table: $table");
                
                foreach ($columns as $column) {
                    // Check if column exists
                    $columnExists = $this->connection->fetchOne(
                        "SELECT COUNT(*) FROM information_schema.COLUMNS 
                         WHERE TABLE_SCHEMA = DATABASE() 
                         AND TABLE_NAME = ? AND COLUMN_NAME = ?",
                        [$table, $column]
                    );
                    
                    if (!$columnExists) {
                        continue;
                    }
                    
                    // Search for the term
                    $query = "SELECT COUNT(*) FROM `$table` WHERE `$column` LIKE ?";
                    $count = $this->connection->fetchOne($query, ["%$searchTerm%"]);
                    
                    if ($count > 0) {
                        $io->warning("FOUND in $table.$column: $count occurrences");
                        
                        // Get sample with ID
                        $idColumn = $this->getIdColumn($table);
                        if ($idColumn) {
                            $sampleQuery = "SELECT `$idColumn`, SUBSTRING(`$column`, 1, 200) as preview 
                                          FROM `$table` 
                                          WHERE `$column` LIKE ? 
                                          LIMIT 3";
                            $samples = $this->connection->fetchAllAssociative($sampleQuery, ["%$searchTerm%"]);
                            
                            foreach ($samples as $sample) {
                                $io->writeln("  ID: {$sample[$idColumn]}");
                                $io->writeln("  Preview: " . substr($sample['preview'], 0, 100) . "...");
                            }
                        }
                        
                        $foundResults[] = "$table.$column";
                    }
                }
            } catch (\Exception $e) {
                $io->error("Error searching $table: " . $e->getMessage());
            }
        }
        
        // Also do a general search in all text columns of all tables (limited)
        $io->section('General search in other tables...');
        
        try {
            $allTables = $this->connection->fetchFirstColumn("SHOW TABLES");
            $otherTables = array_diff($allTables, array_keys($priorityTables));
            
            $limitedSearch = array_slice($otherTables, 0, 20); // Check first 20 other tables
            
            foreach ($limitedSearch as $table) {
                try {
                    $columns = $this->connection->fetchAllAssociative("SHOW COLUMNS FROM `$table`");
                    
                    foreach ($columns as $column) {
                        $columnType = strtolower($column['Type']);
                        if (strpos($columnType, 'char') !== false || 
                            strpos($columnType, 'text') !== false || 
                            strpos($columnType, 'json') !== false) {
                            
                            $columnName = $column['Field'];
                            $query = "SELECT COUNT(*) FROM `$table` WHERE `$columnName` LIKE ?";
                            $count = $this->connection->fetchOne($query, ["%$searchTerm%"]);
                            
                            if ($count > 0) {
                                $io->warning("FOUND in $table.$columnName: $count occurrences");
                                $foundResults[] = "$table.$columnName";
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Skip tables with errors
                    continue;
                }
            }
        } catch (\Exception $e) {
            $io->error("Error in general search: " . $e->getMessage());
        }
        
        $io->newLine();
        
        if (empty($foundResults)) {
            $io->success('No phantom tracking fields found in the database!');
        } else {
            $io->section('Summary:');
            $io->warning(sprintf('Found phantom tracking fields in %d locations:', count($foundResults)));
            foreach ($foundResults as $location) {
                $io->writeln("  - $location");
            }
            
            $io->section('To clean these entries:');
            $io->listing([
                'Run: php bin/console voltimax:remove-phantom-configs',
                'Clear all caches: php bin/console cache:clear',
                'Rebuild theme: php bin/console theme:compile'
            ]);
        }
        
        return Command::SUCCESS;
    }
    
    private function getIdColumn(string $table): ?string
    {
        $possibleIds = ['id', 'uuid', 'configuration_key', 'key', 'name'];
        foreach ($possibleIds as $id) {
            try {
                $exists = $this->connection->fetchOne(
                    "SELECT COUNT(*) FROM information_schema.COLUMNS 
                     WHERE TABLE_SCHEMA = DATABASE() 
                     AND TABLE_NAME = ? AND COLUMN_NAME = ?",
                    [$table, $id]
                );
                if ($exists) {
                    return $id;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        return null;
    }
}