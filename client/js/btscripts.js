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
                    selector: '#bt-dropin',
                    card: {
                        cardholderName: true
                    },
                    paypal: {
                        flow: 'vault',
                        buttonStyle: {
                            color: 'blue',
                            shape: 'pill',
                            size: 'medium',
                            label: 'paypal'
                        }
                    }
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
                    selector: '#bted-dropin',
                    card: {
                        cardholderName: true
                    },
                    paypal: {
                        flow: 'vault',
                        buttonStyle: {
                            color: 'blue',
                            shape: 'pill',
                            size: 'medium'
                        }
                    }
                }, function (createErr, instance) {
                    if (createErr) {
                        console.log('Create Error', createErr);
                        return;
                    }

                    // update button as 'add' or 'remove'
                    EDPaymentForms.updateButton(instance,  form);

                    // update the button when new or none payment is requestable as well
                    instance.on('paymentMethodRequestable', function (event) {
                        EDPaymentForms.updateButton(instance,  form);
                    });
                    instance.on('noPaymentMethodRequestable', function (event) {
                        EDPaymentForms.updateButton(instance,  form);
                    });

                    form.find('.js-bt-button-edit-payment').on('click', function (e) {
                        e.preventDefault();

                        // save number of methods
                        var numberOfMethods = instance._model._paymentMethods.length;

                        instance.requestPaymentMethod(function (err, payload) {
                            if (err) {
                                console.log('Request Payment Method Error', err);
                                return;
                            }

                            // submit to remove payment method on server side if this is a 'removal'
                            if (numberOfMethods > 0) {
                                // Add the nonce to the form and submit
                                form.find('.js-bted-nonce').val(payload.nonce);
                                form.submit();
                            }
                        });
                    });
                });

            });
        },

        // change button label
        updateButton: function(instance, form) {
            if (instance._model._paymentMethods.length == 0) {
                form.find('.js-bt-button-edit-payment').html('Add Payment');
            } else {
                form.find('.js-bt-button-edit-payment').html('Remove Payment');
            }
        },

        showError: function(msg) {
            alert(msg);
        }
    };
    EDPaymentForms.init();

})
})(jQuery);