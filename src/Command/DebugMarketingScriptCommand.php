<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugMarketingScriptCommand extends Command
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
        $this->setName('voltimax:debug-marketing')
            ->setDescription('Debug marketing script values from config and database');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Debug Marketing Scripts');
        
        // Check what's in the database directly
        $io->section('Database Values (system_config table):');
        
        $dbValues = $this->connection->fetchAllAssociative(
            "SELECT configuration_key, configuration_value 
             FROM system_config 
             WHERE configuration_key LIKE '%voltimaxMarketingScript1%'
             ORDER BY configuration_key"
        );
        
        foreach ($dbValues as $row) {
            $key = str_replace('VoltimaxTheme.config.', '', $row['configuration_key']);
            $value = json_decode($row['configuration_value'], true);
            
            if (is_array($value) && isset($value['_value'])) {
                $displayValue = $value['_value'];
            } else {
                $displayValue = $row['configuration_value'];
            }
            
            $io->writeln("$key:");
            if (is_bool($displayValue)) {
                $io->writeln("  Value: " . ($displayValue ? 'true' : 'false'));
            } else {
                $io->writeln("  Raw: " . substr(json_encode($displayValue), 0, 200));
                $io->writeln("  Decoded: " . substr((string)$displayValue, 0, 200));
            }
            $io->newLine();
        }
        
        // Check what SystemConfigService returns
        $io->section('SystemConfigService Values:');
        
        $script1 = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxMarketingScript1');
        $active1 = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxMarketingScript1Active');
        $async1 = $this->systemConfigService->get('VoltimaxTheme.config.voltimaxMarketingScript1Async');
        
        $io->writeln('Script 1 Content:');
        $io->writeln('  Type: ' . gettype($script1));
        $io->writeln('  Value: ' . substr((string)$script1, 0, 200));
        $io->writeln('  Active: ' . ($active1 ? 'true' : 'false'));
        $io->writeln('  Async: ' . ($async1 ? 'true' : 'false'));
        
        // Check if it would be rendered
        $io->section('Would it be rendered?');
        
        if ($active1 && !empty(trim((string)$script1))) {
            $io->success('YES - Script is active and has content');
            $io->writeln('Content that would be rendered:');
            $io->writeln($script1);
        } else {
            $io->warning('NO - Script is either inactive or empty');
            $io->writeln('Active: ' . ($active1 ? 'yes' : 'no'));
            $io->writeln('Has content: ' . (!empty(trim((string)$script1)) ? 'yes' : 'no'));
        }
        
        return Command::SUCCESS;
    }
}