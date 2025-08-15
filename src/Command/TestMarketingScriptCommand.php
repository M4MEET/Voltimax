<?php declare(strict_types=1);

namespace VoltimaxTheme\Command;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestMarketingScriptCommand extends Command
{
    private SystemConfigService $systemConfigService;
    
    public function __construct(SystemConfigService $systemConfigService)
    {
        parent::__construct();
        $this->systemConfigService = $systemConfigService;
    }
    
    protected function configure(): void
    {
        $this->setName('voltimax:test-marketing-script')
            ->setDescription('Set up test marketing script with hello-lol');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->title('Setting up test marketing script');
        
        // Set the test script
        $testScript = '<script>hello-lol</script>';
        
        // Set Script 1 with the test content
        $this->systemConfigService->set('VoltimaxTheme.config.voltimaxMarketingScript1', $testScript);
        $this->systemConfigService->set('VoltimaxTheme.config.voltimaxMarketingScript1Active', true);
        $this->systemConfigService->set('VoltimaxTheme.config.voltimaxMarketingScript1Async', false);
        
        $io->success('Test script set and activated:');
        $io->writeln($testScript);
        $io->writeln('');
        $io->writeln('Script 1 is now ACTIVE with hello-lol content');
        
        $io->section('Next steps:');
        $io->listing([
            'Clear cache: php bin/console cache:clear',
            'Visit storefront: curl http://localhost | grep hello-lol',
            'Check browser console for: hello-lol'
        ]);
        
        return Command::SUCCESS;
    }
}