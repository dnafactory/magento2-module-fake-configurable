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

        initialize: function (config) {
            this._super();
            this.productId = config.productId;
            this.brotherLabel = config.brotherLabel;
            this.getFakeConfigurable();
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
