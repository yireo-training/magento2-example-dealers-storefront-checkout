define(
    [
        'ko',
        'uiComponent',
        'jquery',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/customer-data',
        'Magento_Ui/js/model/messageList'
    ],
    function (ko,
              Component,
              $,
              _,
              stepNavigator,
              customerData,
              messageList
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Yireo_ExampleDealersStorefrontCheckout/checkout-example-dealers-step',
                form: {'preferredDealer': ''}
            },
            initialize: function () {
                this._super();
                var cartData = customerData.get('cart');
                this.form.preferredDealer = ko.observable('' + cartData().preferred_dealer_id);
                this.dealers = ko.observableArray();
                stepNavigator.registerStep(
                    'checkout-example-dealers-step',
                    'dealers-step',
                    'Preferred Dealer',
                    this.isVisible,
                    _.bind(this.navigate, this),
                    15
                );

                $.get("/exampleDealersCheckout/ajax/selectOptions", (function (data) {
                    for (var i = 0; i < data.length; i++) {
                        this.dealers.push(data[i]);
                    }
                }).bind(this));

                return this;
            },
            isVisible: ko.observable(false),
            navigate: function () {
                this.isVisible(true);
            },
            navigateToNextStep: function () {
                const preferredDealerId = this.form.preferredDealer();
                if (!preferredDealerId) {
                    messageList.addErrorMessage({message: 'Please select a preferred dealer'});
                    return false;
                }

                $.post("/exampleDealersCheckout/ajax/savePreferredDealer", {'id': preferredDealerId})
                    .done(function () {
                        messageList.addSuccessMessage({message: 'Saved preferred dealer'});
                        customerData.invalidate(['cart']);
                        stepNavigator.next();
                    })
                    .fail(function (data) {
                        messageList.addErrorMessage({message: data.responseText});
                    });
            }
        });
    }
)
;
