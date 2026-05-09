<?php
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Console\Command;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Sanjeev\CustomerPurchaseHistory\Service\ReportGenerator;

class CustomerPurchaseReport extends Command
{
    /**
     * ReportGenerator
     *
     * @var $handler
     */
    protected $handler;

    /** State
     *
     * @var $state
     */
    protected $state;

    /**
     * Constructor
     *
     * @param ReportGenerator $handler
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        ReportGenerator $handler,
        \Magento\Framework\App\State $state
    ) {
        parent::__construct();
        $this->handler = $handler;
        $this->state = $state;
    }

    protected function configure()
    {
        $this->setName('sanjeev:customer-purchase-history:report');
        $this->setDescription('This command will generate customers\'s purchase report for registered customers.');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $startTime = microtime(true);
        $output->writeln("");
        $output->writeln("<fg=green>progress started....</>");
        $output->writeln("");

        $response = $this->handler->generate();
        $output->writeln("<fg=green>process end.</>");
        $output->writeln("");

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        $output->writeln("");
        $output->writeln("<fg=green>Execution time: ".$executionTime." sec.</>");
        $output->writeln("");
        return Cli::RETURN_SUCCESS;
    }
}
