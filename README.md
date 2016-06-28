LpWeb's PaymentBundle
=====================

`LpWebPaymentBundle` provides easy integration with payments gateway for Symfony2 and Twig

[![Latest Stable Version](https://poser.pugx.org/lpwebit/payment-bundle/v/stable)](https://packagist.org/packages/lpwebit/payment-bundle)
[![Total Downloads](https://poser.pugx.org/lpwebit/payment-bundle/downloads)](https://packagist.org/packages/lpwebit/payment-bundle)

Installation
============

### Step 1: Download the LpWebPaymentBundle

***Using the vendors script***

Add the following lines to your `deps` file:

```
    [LpWebPaymentBundle]
        git=https://github.com/lpwebit/PaymentBundle.git
        target=/bundles/LpWeb/PaymentBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

***Using submodules***

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add git://github.com/lpwebit/PaymentBundle.git vendor/bundles/LpWeb/PaymentBundle
$ git submodule update --init
```

***Using Composer***

Add the following to the "require" section of your `composer.json` file:

```
    "lpwebit/payment-bundle": "dev-master"
```

You can also choose a version number, (tag, commit ...)

And update your dependencies

```
    php composer.phar update
```

### Step 2: Configure the Autoloader

If you use composer, you can skip this step.

Add it to your `autoload.pp` :

```php
<?php
...
'LpWeb' => __DIR__.'/../vendor/bundles',
```

### Step 3: Enable the bundle

Registers the bundle in your `app/AppKernel.php`:

```php
<?php
...
public function registerBundles()
{
    $bundles = array(
        ...
        new LpWeb\PaymentBundle\LpWebPaymentBundle(),
        ...
    );
...
```

### Step 4: Configure the bundle and set up the directories

Adds the following configuration to your `app/config/config.yml`:

    - { resource: @LpWebPaymentBundle/Resources/config/config.yml }

    lp_web_payment: ~

If you want to use Paypal as your payment gateway you must configure CLIENT_ID and CLIENT_SECRET,
to generate these please refer to Paypal documentation:

    lp_web_payment:
        paypal:
            client_id: YOUR_KEY
            client_secret: YOUR_SECRET
        
Complete configuration:

    lp_web_payment:
        paypal: # for information on mode and loggingLevel please refer to https://github.com/paypal/PayPal-PHP-SDK/wiki/Logging
            client_id: YOUR_KEY
            client_secret: YOUR_SECRET
            mode: sandbox|live
            logLevel: DEBUG|INFO|WARN|ERROR

### Step 5: add routing

Add this to your `app/config/routing.yml`:

    lp_web_payment:
        resource: "@LpWebPaymentBundle/Controller/"
        type:     annotation
        prefix:   /
        
Export all assetic
    
    php app/console assets:install

Usage
=====

Basics
------

To make a request for payment with WPS you simply have to set a few information,
and then redirect the user to the generated approval url:

```php
<?php
...
/** @var PayPalWPS $wpsService */
$wpsService = $this->get('lpweb_payment.paypal.wps');
$wpsService->setAmout(10.0);
$wpsService->setDescription('Transaction Description');
$wpsService->setInvoiceNumber(1);
// set the redirect url to which the user has to be redirected after successfull completion
$wpsService->setRedirectUrl($this->generateUrl('route_name'));

// invoice number, amount and description is compulsory
$wpsService->validateData();

// Specify the checkout experience to present to the user.
$data['cmd'] = '_cart';

// Signify we're passing in a shopping cart from our system.
$data['upload'] = '1';

// The store's PayPal e-mail address
$data['business'] = $this->businessMail;

// The application generating the API request
$data['bn'] = 'LpWebPayment_Bundle_WPS';

// Set the correct character set
$data['charset'] = 'utf-8';

// Do not display a comments prompt at PayPal
$data['no_note'] = '1';

// Do not display a shipping address prompt at PayPal
$data['no_shipping'] = '1';

// Return to the review page when payment is canceled
$data['cancel_return'] = $wpsService->getCancelUrl(self::paymentMethod);

// Return to the payment redirect page for processing successful payments
$data['return_url'] = $wpsService->getSuccessUrl(self::paymentMethod);

// The path PayPal should send the IPN to
$data['notify_url'] = $wpsService->getNotifyUrl(self::paymentMethod);

$data['rm'] = '2';

// The type of payment action PayPal should take with this order
$data['paymentaction'] = $wpsService::ACTION;

// Set the currency and language codes
$data['currency_code'] = 'AUD';
$data['lc'] = 'EN';

// Use the timestamp to generate a unique invoice number
$data['invoice'] = $wpsService->getInvoiceNumber();

// Define a single item in the cart representing the whole order
$data['amount_1'] = $wpsService->getAmount()->getTotal();
$data['item_name_1'] = $wpsService->getDescription();

$paypalRequest = new PayPalRequest();
$paypalRequest->setUniqueId($wpsService->getUniqueId());
$paypalRequest->setCmd($data['cmd']);
$paypalRequest->setUpload($data['upload']);
$paypalRequest->setBusiness($data['business']);
$paypalRequest->setBn($data['bn']);
$paypalRequest->setCharset($data['charset']);
$paypalRequest->setNoNote($data['no_note']);
$paypalRequest->setNoShipping($data['no_shipping']);
$paypalRequest->setCancelReturn($data['cancel_return']);
$paypalRequest->setReturnUrl($data['return_url']);
$paypalRequest->setNotifyUrl($data['notify_url']);
$paypalRequest->setRm($data['rm']);
$paypalRequest->setPaymentaction($data['paymentaction']);
$paypalRequest->setCurrencyCode($data['currency_code']);
$paypalRequest->setLc($data['lc']);
$paypalRequest->setInvoice($data['invoice']);
$paypalRequest->setAmount1($data['amount_1']);
$paypalRequest->setItemName1($data['item_name_1']);

$em = $this->container->get('doctrine')->getManager();
$em->persist($paypalRequest);
$em->flush();

return $this->render('LpWebPaymentBundle:Payment:process.html.twig', ['data' => $data]);
```


Requirements
============

`LpWebPaymentBundle` no further requirements are needed

Support
============

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/lpwebitpaymentbundle) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/lpwebit/PaymentBundle/issues),
or better yet, fork the library and submit a pull request.

License
=======

This bundle is under MIT license
