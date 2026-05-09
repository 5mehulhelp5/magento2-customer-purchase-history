<?php
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Cron;

/*
 * Cron job class responsible for triggering the generation of customer purchase history reports.
 */
class Generate
{
    /** @var \Sanjeev\CustomerPurchaseHistory\Service\ReportGenerator */
    private  $reportGenerator;

    /**
     * @param \Sanjeev\CustomerPurchaseHistory\Service\ReportGenerator $reportGenerator
     */
    public function __construct(
    \Sanjeev\CustomerPurchaseHistory\Service\ReportGenerator $reportGenerator
    )
    {
        $this->reportGenerator = $reportGenerator;
    }

    public function execute(): void
    {
        $this->reportGenerator->generate();
    }
}
