define([
    'ko',
    'uiComponent',
    'mage/url',
    'mage/storage',
], function (ko, Component, urlBuilder,storage) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'DNAFactory_FakeConfigurable/fakeconfigurable',
        },

        brotherLabel: "",
        productId: null,
        products: ko.observableArray([]),
        shouldShowBrothers: ko.observable(false),
        defaultBrotherValue: ko.observable(""),
        brotherValue: ko.observable(false),

        initialize: function (config) {
            this._super();
            this.productId = config.productId;
            this.brotherLabel = config.brotherLabel;
            this.getFakeConfigurable();

            this.defaultBrotherValue = ko.computed(function (){
                if (!this.products().length) {
                    return "";
                }
                return this.products()[0]["attribute"];
            }, this);
            this.restoreDefaultAttributeValue();
        },

        setAttributeValue: function (product, event, context) {
            var value = product["attribute"];
            if (!context) {
                context = this;
            }
            context.brotherValue(value);
        },

        restoreDefaultAttributeValue: function (product, event, context) {
            if (!context) {
                context = this;
            }
            context.brotherValue(context.defaultBrotherValue());
        },

        getFakeConfigurable: function () {
            var that = this;

            var serviceUrl = urlBuilder.build('fakeconfigurable/product/getBrothers?productId=' + that.productId);
            return storage.post(
                serviceUrl,
                ''
            ).done(
                function (response) {
                    if (response.status >= 0) {
                        var numProducts = response.data.length;

                        if (numProducts > 0) {
                            that.shouldShowBrothers(true);
                        }

                        for (var i = 0; i < numProducts; i++) {
                            that.products.push(response.data[i]);
                        }
                    }
                }
            ).fail(
                function (response) {

                }
            );
        }
    });
});
