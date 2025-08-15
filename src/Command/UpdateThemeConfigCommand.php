<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateThemeConfigCommand extends Command
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:update-theme-config')
            ->setDescription('Update theme config values for marketing script test');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Updating Theme Config Values');
        
        // Get the theme
        $theme = $this->connection->fetchAssociative(
            "SELECT id, name, config_values FROM theme WHERE technical_name = 'VoltimaxTheme'"
        );
        
        if (!$theme) {
            $io->error('VoltimaxTheme not found');
            return Command::FAILURE;
        }
        
        // Decode existing config values
        $configValues = json_decode($theme['config_values'] ?: '{}', true);
        
        // Update Script 1 settings
        $configValues['voltimaxMarketingScript1'] = ['value' => '<script>hello-lol</script>'];
        $configValues['voltimaxMarketingScript1Active'] = ['value' => true];
        $configValues['voltimaxMarketingScript1Async'] = ['value' => false];
        
        $io->section('Setting in theme config_values:');
        $io->writeln('voltimaxMarketingScript1: <script>hello-lol</script>');
        $io->writeln('voltimaxMarketingScript1Active: true');
        $io->writeln('voltimaxMarketingScript1Async: false');
        
        // Save back to database
        $newConfigJson = json_encode($configValues);
        
        $this->connection->executeStatement(
            "UPDATE theme SET config_values = ? WHERE id = ?",
            [$newConfigJson, $theme['id']]
        );
        
        $io->success('Theme config values updated!');
        
        $io->section('Next steps:');
        $io->listing([
            'Clear cache: php bin/console cache:clear',
            'Check storefront: curl http://localhost | grep hello-lol'
        ]);
        
        return Command::SUCCESS;
    }
}