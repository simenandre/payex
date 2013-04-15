PAYEX PHP CLASS
===============

This class was made to make the way we communicate with
PayEx a bit more intutive as well as modern.

The classes are all static, and fairly easy to manage.
We use Laravel as a framework for most of our web applications, so this is made to be easy to install. Its on the roadmap to make a bundle for Laravel aswell.

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

*We haven't had time to add the todolist yet*

Licence
-------

The project is released under GNU-license. The class
are provided as is. And the class are in no way an offical
class by PayEx. The initial creators are however a sertified partner with PayEx.

Support
-------

Do you need help with the class, send us an e-mail
at cobraz@cobraz.no for assistance.    