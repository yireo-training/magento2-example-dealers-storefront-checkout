# Magento 2 Step in Checkout
Magento 2 module showing how to add a step in the checkout

**This module is abandoned and no longer maintained. We have moved to our new [LokiCheckout](https://loki-checkout.com/) instead.**

### Installation
```
composer require yireo-training/magento2-example-dealers-storefront-checkout:dev-master
```

### Features
- Adds an additional step in the checkout
- Loads data from an AJAX URL `exampleDealersCheckout/ajax/selectOptions`
- Allows you to select a preferred dealer in the checkout step
- Selected field is saved to `exampleDealersCheckout/ajax/savePreferredDealer`
- Saved field is reloaded again in the CustomerData section `cart`
