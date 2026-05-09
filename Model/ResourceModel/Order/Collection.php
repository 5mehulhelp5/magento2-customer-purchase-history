<?php
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Model\ResourceModel\Order;

use Magento\Framework\DB\Select;
use DateTimeZone;

class Collection extends \Magento\Reports\Model\ResourceModel\Order\Collection
{
    /**
     *  Set customer id filter
     *
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->getSelect()->where('main_table.customer_id = ?', $customerId);
        return $this;
    }

    public function selectLastPurchaseDate()
    {
        $this->getSelect()->columns(['last_purchase_date' => new \Zend_Db_Expr('MAX(main_table.created_at)')]);
        return $this;
    }

    public function applyDateRangeFilter($fromDate)
    {
        $toDate = new \DateTime();
        $toDate->setTimezone(new \DateTimeZone('UTC'));
        $this->addFieldToFilter('created_at', ['gteq' => $fromDate]);
        $this->addFieldToFilter('created_at', ['lteq' => $toDate->format('Y-m-d H:i:s')]);
        return $this;
    }

}
