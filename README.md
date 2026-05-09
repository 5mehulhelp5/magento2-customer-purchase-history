Introduction
------------
Sanjeev_CustomerPurchaseHistory is a light weight Magento 2 module that computes customers purchase history metrics and exposes them in customers listing in admin. The module collects store wise aggregates like total purchases amount, average purchase  amounts, and last purchase date of each customer. A cron job or console command triggers the computation.

Features
--------
- Computes per-customer aggregates: total purchase amount, average purchase amount, and last purchase date.
- Filter customers based on purchase history metrics.
- Enable / disable store wise calcuation of purchase history metrics via store configuration.
- Store wise select orders' statues to include in the metrics via store configuration.
- Console command: on-demand metrics calculation run manually CLI command

Composer installation
---------------------
```bash
composer require sanjeev-kr/magento2-customer-purchase-history
php bin/magento module:enable Sanjeev_CustomerPurchaseHistory
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```

Manual installation
-------------------
1. Copy the module to `app/code/Sanjeev/CustomerPurchaseHistory`.
3. Enable and install the module:

```bash
php bin/magento module:enable Sanjeev_CustomerPurchaseHistory
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```
## Uninstall (optional)

Disable and remove the module from `app/code`, then:

```bash
bin/magento module:disable Sanjeev_CustomerPurchaseHistory
bin/magento setup:upgrade
bin/magento cache:flush
```

(Adjust for your deployment; you may need `setup:di:compile` and `setup:static-content:deploy -f` again in production.)


Running the generator
---------------------
- Cron: The job `sanjeev_customer_purchase_history_generate` is scheduled in `etc/crontab.xml` to run nightly at 02:00 AM for enabled stores.
- CLI: Run the console command to generate the report on demand for enabled stores:

```bash
php bin/magento sanjeev:customer-purchase-history:report
```
---

## License

This project is licensed under the MIT License. See `LICENSE.txt`.

---

## Author

Sanjeev Kumar

If my work helps you and you want to appreciate, please [Sponor](https://github.com/sponsors/sanjeev-kr). I would appreciate you.