<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SearchEntireDatabaseCommand extends Command
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:search-entire-db')
            ->setDescription('Search entire database for phantom tracking fields');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Searching Entire Database for Phantom Tracking Fields');
        
        $searchTerms = [
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
        
        $io->section('Search terms:');
        foreach ($searchTerms as $term) {
            $io->writeln("  - $term");
        }
        
        // Get all tables
        $tables = $this->connection->fetchFirstColumn("SHOW TABLES");
        $io->writeln(sprintf("\nSearching through %d tables...\n", count($tables)));
        
        $foundResults = [];
        
        foreach ($tables as $table) {
            try {
                // Get columns for this table
                $columns = $this->connection->fetchAllAssociative("SHOW COLUMNS FROM `$table`");
                
                $textColumns = [];
                foreach ($columns as $column) {
                    $columnType = strtolower($column['Type']);
                    // Only search in text-like columns
                    if (strpos($columnType, 'char') !== false || 
                        strpos($columnType, 'text') !== false || 
                        strpos($columnType, 'json') !== false ||
                        strpos($columnType, 'blob') !== false) {
                        $textColumns[] = $column['Field'];
                    }
                }
                
                if (empty($textColumns)) {
                    continue;
                }
                
                // Search each text column for our terms
                foreach ($textColumns as $columnName) {
                    foreach ($searchTerms as $searchTerm) {
                        try {
                            $query = "SELECT COUNT(*) as cnt FROM `$table` WHERE `$columnName` LIKE ?";
                            $count = $this->connection->fetchOne($query, ["%$searchTerm%"]);
                            
                            if ($count > 0) {
                                $foundResults[] = [
                                    'table' => $table,
                                    'column' => $columnName,
                                    'term' => $searchTerm,
                                    'count' => $count
                                ];
                                
                                // Get sample data
                                $sampleQuery = "SELECT `$columnName` FROM `$table` WHERE `$columnName` LIKE ? LIMIT 1";
                                $sample = $this->connection->fetchOne($sampleQuery, ["%$searchTerm%"]);
                                
                                $io->warning("FOUND in $table.$columnName:");
                                $io->writeln("  Search term: $searchTerm");
                                $io->writeln("  Occurrences: $count");
                                
                                if ($sample) {
                                    $preview = substr($sample, 0, 200);
                                    if (strlen($sample) > 200) {
                                        $preview .= '...';
                                    }
                                    $io->writeln("  Sample: $preview");
                                }
                                
                                // Try to get ID column for deletion
                                $idColumns = ['id', 'uuid', 'configuration_key', 'key'];
                                $idColumn = null;
                                foreach ($idColumns as $possibleId) {
                                    if ($this->columnExists($table, $possibleId)) {
                                        $idColumn = $possibleId;
                                        break;
                                    }
                                }
                                
                                if ($idColumn) {
                                    $idQuery = "SELECT `$idColumn` FROM `$table` WHERE `$columnName` LIKE ? LIMIT 5";
                                    $ids = $this->connection->fetchFirstColumn($idQuery, ["%$searchTerm%"]);
                                    if (!empty($ids)) {
                                        $io->writeln("  IDs: " . implode(', ', array_slice($ids, 0, 5)));
                                    }
                                }
                                
                                $io->newLine();
                            }
                        } catch (\Exception $e) {
                            // Skip errors for individual column searches
                            continue;
                        }
                    }
                }
            } catch (\Exception $e) {
                // Skip tables that can't be accessed
                continue;
            }
        }
        
        $io->newLine();
        
        if (empty($foundResults)) {
            $io->success('No phantom tracking fields found in any database table!');
        } else {
            $io->section('Summary of findings:');
            
            $byTable = [];
            foreach ($foundResults as $result) {
                $key = $result['table'] . '.' . $result['column'];
                if (!isset($byTable[$key])) {
                    $byTable[$key] = [];
                }
                $byTable[$key][] = $result['term'];
            }
            
            foreach ($byTable as $location => $terms) {
                $io->writeln("$location:");
                foreach ($terms as $term) {
                    $io->writeln("  - $term");
                }
            }
            
            $io->warning(sprintf('Found phantom fields in %d locations', count($byTable)));
            
            // Offer to clean them
            $io->section('Cleanup Options:');
            $io->writeln('To remove these entries, you can:');
            $io->listing([
                'Run: php bin/console voltimax:remove-phantom-configs',
                'Manually delete the entries from the affected tables',
                'Clear all caches and rebuild the theme'
            ]);
        }
        
        return Command::SUCCESS;
    }
    
    private function columnExists(string $table, string $column): bool
    {
        try {
            $result = $this->connection->fetchOne(
                "SELECT COUNT(*) FROM information_schema.COLUMNS 
                 WHERE TABLE_SCHEMA = DATABASE() 
                 AND TABLE_NAME = ? 
                 AND COLUMN_NAME = ?",
                [$table, $column]
            );
            return $result > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}