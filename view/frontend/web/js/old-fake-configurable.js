define([
    'jquery',
    'underscore',
    'mage/translate',
    'domReady!'
], function ($, _) {
    'use strict';

    $.widget('dnafactory.fakeConfigurable', {
        options: {
            params: {},
            ajaxUrl: '',
            selector: '#fake-configurable .items'
        },

        _create: function () {
            this.getFakeConfigurable();
        },

        getFakeConfigurable: function () {
            let that = this;

            $.ajax({
                showLoader: false,
                url: that.options.ajaxUrl,
                data: $.param(that.options.params),
                type: 'POST'
            }).success(function (response) {
                if (response === null) {
                    return;
                }

                console.log(response);
                return;
                /*if ('deliveryDate' in response) {
                    $(that.options.deliveryDateSelector).text($.mage.__('Delivery estimated between %1 and %2')
                        .replace('%1', response.deliveryDate.bestDate)
                        .replace('%2', response.deliveryDate.worstDate));
                }*/
            });
        },
    });

    return $.dnafactory.fakeConfigurable;
});
