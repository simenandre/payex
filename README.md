PAYEX PHP CLASS
===============

This class was made to make the way we communicate with
PayEx a bit more intutive as well as modern.

The classes are all static, and fairly easy to manage.


How to install?
---------------

1. Install the class somewhere.

2. Include
> include("payex.php");

3. Set your configuration in config.php.

4. Do your transaction.
> $transaction = PayEx::transaction(123, 1000.31);<br />
> if($transaction->isOK()) $transaction->Redirect();

ToDo
----

>This project is pretty fresh. We are still working
>heavly on it, and there are no production package 
>released yet.

- Clean up the class.
- Make a nice documentation
- Add support for refund.
- Add more awesome Payex features.

Licence
-------

The project is released under GNU-license. The class
are provided as is. And the class are in no way an offical
class by PayEx. The initial creators are however a sertified partner with PayEx.

What about Laravel support? 
---------------------------

This project had a Laravel-bundle here when Laravel 3 what the coolest.
Now that people has began using Laravel Four, we're removing the Laravel
version. To make this class work with Laravel, you only need to replace
line 163:
> header('Location: '.self::$status['redirectUrl']); exit;
with:
> return Redirect::to(self::$status['redirectUrl']);

We're planning on making a much better class to support Laravel
in the future.

Support
-------

Do you need help with the class, send us an e-mail
at cobraz@cobraz.no for assistance.    