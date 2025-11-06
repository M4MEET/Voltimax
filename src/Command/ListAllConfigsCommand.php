<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListAllConfigsCommand extends Command
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:list-configs')
            ->setDescription('List all voltimax and tracking related configurations');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('All Voltimax and Tracking Configurations');
        
        $query = "SELECT configuration_key, 
                         SUBSTRING(CAST(configuration_value AS CHAR), 1, 100) as value_preview 
                  FROM system_config 
                  WHERE configuration_key LIKE '%voltimax%' 
                     OR configuration_key LIKE '%Tracking%'
                  ORDER BY configuration_key";
        
        $results = $this->connection->fetchAllAssociative($query);
        
        if (empty($results)) {
            $io->info('No voltimax or tracking configurations found');
            return Command::SUCCESS;
        }
        
        $io->writeln(sprintf('Found %d configuration entries:', count($results)));
        $io->newLine();
        
        $table = [];
        foreach ($results as $row) {
            $key = $row['configuration_key'];
            $value = $row['value_preview'];
            
            // Decode the value if it's JSON
            if (is_string($value) && (strpos($value, '{') === 0 || strpos($value, '"') === 0)) {
                $decoded = @json_decode($value, true);
                if ($decoded !== null) {
                    if (is_array($decoded) && isset($decoded['_value'])) {
                        $val = is_string($decoded['_value']) ? $decoded['_value'] : json_encode($decoded['_value']);
                        $value = substr($val, 0, 50) . '...';
                    } elseif (is_string($decoded)) {
                        $value = substr($decoded, 0, 50) . '...';
                    } elseif (is_bool($decoded)) {
                        $value = $decoded ? 'true' : 'false';
                    }
                }
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif (!is_string($value)) {
                $value = json_encode($value);
            }
            
            // Mark old tracking configs
            $type = 'Current Theme';
            if (strpos($key, 'voltimaxTracking') !== false) {
                $type = '⚠️  OLD TRACKING';
            } elseif (!preg_match('/^VoltimaxTheme\.config\./', $key)) {
                $type = '❓ Unknown';
            }
            
            $table[] = [$type, $key, $value];
        }
        
        $io->table(['Type', 'Configuration Key', 'Value Preview'], $table);
        
        // Count old tracking entries
        $oldTracking = array_filter($results, function($row) {
            return strpos($row['configuration_key'], 'voltimaxTracking') !== false;
        });
        
        if (count($oldTracking) > 0) {
            $io->warning(sprintf('Found %d old tracking configurations that should be removed!', count($oldTracking)));
            $io->note('Run: docker exec shopware-6.6.10.4 php bin/console voltimax:cleanup-scripts --reset');
        }
        
        return Command::SUCCESS;
    }
}