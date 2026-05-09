<?php
/**
 * @author Sanjeev Kumar
 * @license MIT
 */
declare(strict_types=1);

namespace Sanjeev\CustomerPurchaseHistory\Plugin;

use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\DB\Select;


class JoinCustomerPurchaseHistory 
{

    public function afterSearch(Reporting $subject, $collection, $searchCriteria)
    {
        if ($collection->getMainTable() === $collection->getResource()->getTable('customer_grid_flat')) {

            $collection->getSelect()->joinLeft(
                ['purchase_history' => $collection->getResource()->getTable('sanjeev_customer_purchase_history')],
                'main_table.entity_id = purchase_history.customer_id',
                ['total_purchase_amount', 'avg_purchase_amount', 'currency_code', 'last_purchase_date']
            );

            $whereParts = $collection->getSelect()->getPart(Select::WHERE);
            $newWhereParts = [];
            foreach ($whereParts as $part) {
                if (strpos($part, 'total_purchase_amount') === false &&
                    strpos($part, 'avg_purchase_amount') === false &&
                    strpos($part, 'currency_code') === false &&
                    strpos($part, 'last_purchase_date') === false) {
                    $newWhereParts[] = $part;
                    continue;
                }

                $newPart = str_replace('main_table', 'purchase_history', $part);
                $newWhereParts[] = $newPart;
            }

            $collection->getSelect()->reset(Select::WHERE);
            $collection->getSelect()->setPart(Select::WHERE, $newWhereParts);
        }
        return $collection ;
    }

}
