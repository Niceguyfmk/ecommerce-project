<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <?php if (session()->getFlashdata('message')) : ?>
                            <div 
                                style="color: green;
                                    border: 2px green solid;
                                    text-align: center;
                                    padding: 5px;margin-bottom: 10px;">
                                Payment Successful!
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default credit-card-box">
                    <div class="panel-body">
                    <form id='payment-form' method='post' class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="<?= STRIPE_KEY ?>" action="<?php echo base_url('payment'); ?>">             
                            
                            <div class="form-row row">
                                <div class="col-xs-12 form-group required">
                                    <label for="control-label">Name on card</label>
                                    <input class="form-control" type="text" size="4">
                                </div>
                            </div>

                            <div class="form-row row">
                                <div class="col-xs-12 form-group required">
                                    <label for="control-label">Card Number</label>
                                    <input class="form-control card-number" type="text" size="4">
                                </div>
                            </div>

                            <div class="form-row row">
                                <div class="col-xs-12 col-md-4 form-group required">
                                    <label for="control-label">CVC</label>
                                    <input autocomplete="off" class="form-control card-cvc" type="text" placeholder="cvc">
                                </div>  
                                <div class="col-xs-12 col-md-4 form-group required">
                                    <label for="control-label">Expiration Month</label>
                                    <input autocomplete="off" class="form-control card-expiry-month" type="text" placeholder="MM" size="4">
                                </div>
                                <div class="col-xs-12 col-md-4 form-group required">
                                    <label for="control-label">Expiration Year</label>
                                    <input autocomplete="off" class="form-control card-expiry-year" type="text" placeholder="YYYY" size="4">
                                </div>
                            </div>

                            <div class="form-row row">
                                <div class="col-xs-12 error form-group d-none">
                                    <div class="alert-danger alert">
                                        Please correct the errors and try again.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 mt-3  ">
                                    <button class="btn btn-primary btn-lg btn-block" type="submit" id="payButton">Pay Now</button>
                                </div>
                            </div>
            
                        <form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  
    <script src="https://js.stripe.com/v3/" ></script>

    <script>
    $(function(){
        var $form = $(".require-validation");

        // Trigger payment on the client side
        $('#payButton').on('click', function () {
            e.preventDefault();
            fetch('payment', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    window.location.href = data.url; 
                } else {
                    console.error('Error creating checkout session:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        $('form.require-validation').bind('submit', function(e){
            var $form = $(".require-validation"),
                inputSelector = ['input[type=text]'].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;

            $errorMessage.addClass('d-none');
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i,el){
                var $input = $el;
                if($input.val() === ''){
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('d-none');
                    e.preventDefault();
                }
            });

            if(!$form.data('cc-on-file')){
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val(),
                }, stripeResponseHandler); 
            }
        });

        function stripeResponseHandler(status, response){
            if(response.error){
                $('.error')
                    .removeClass('d-none')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    });
   </script>
</body>
</html>