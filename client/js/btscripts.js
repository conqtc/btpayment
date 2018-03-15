(function($) {
$(function () {
    var PaymentForms = {
        init: function () {
            this.block = $('.js-bt-payment-form');

            this.block.each(function (index) {
                var form = $(this);
                var clientToken = form.data('client-token');

                braintree.dropin.create({
                    authorization: clientToken,
                    selector: '#bt-dropin'
                }, function (createErr, instance) {
                    if (createErr) {
                        console.log('Create Error', createErr);
                        return;
                    }

                    form.find('.js-bt-button-make-payment').on('click', function (e) {
                        e.preventDefault();

                        instance.requestPaymentMethod(function (err, payload) {
                            if (err) {
                                console.log('Request Payment Method Error', err);
                                return;
                            }

                            // Add the nonce to the form and submit
                            form.find('.js-bt-nonce').val(payload.nonce);
                            form.submit();
                        });
                    });
                });

            });
        },

        showError: function(msg) {
            alert(msg);
        }
    };
    PaymentForms.init();

    var EDPaymentForms = {
        init: function () {
            this.block = $('.js-bted-payment-form');

            this.block.each(function (index) {
                var form = $(this);
                var clientToken = form.data('client-token');

                braintree.dropin.create({
                    authorization: clientToken,
                    selector: '#bted-dropin'
                }, function (createErr, instance) {
                    if (createErr) {
                        console.log('Create Error', createErr);
                        return;
                    }

                    form.find('.js-bted-methods-length').val(instance._model._paymentMethods.length);
                    if (instance._model._paymentMethods.length == 0) {
                        form.find('.js-bt-button-edit-payment').html('Add Payment');
                    } else {
                        form.find('.js-bt-button-edit-payment').html('Remove Payment');
                    }

                    form.find('.js-bt-button-edit-payment').on('click', function (e) {
                        e.preventDefault();

                        instance.requestPaymentMethod(function (err, payload) {
                            if (err) {
                                console.log('Request Payment Method Error', err);
                                return;
                            }

                            // Add the nonce to the form and submit
                            form.find('.js-bted-nonce').val(payload.nonce);
                            form.submit();
                        });
                    });
                });

            });
        },

        showError: function(msg) {
            alert(msg);
        }
    };
    EDPaymentForms.init();

})
})(jQuery);