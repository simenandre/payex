PAYEX PHP CLASS
===============

This class was made to make the way we communicate with
PayEx a bit more intutive as well as more modern.

The classes are all static, and purly easy.

How to install?
---------------

1. Install the class somewhere.

2. Include <include("payex.php");>.

3. Set your configuration in config.php.

4. Do your transaction.
> $transaction = PayEx::transaction(123, 1000.31);
> if($transaction->isOK()) $transaction->Redirect();

ToDo
----

This project is pretty fresh. We are still working
heavly on it, and there are no production package 
done yet.

*We haven't had time to add the todolist yet*

Licence
-------

The project is released under GNU-license. The class
are provided as is. This class are in no way an offical
class of PayEx. 

Support
-------

Do you need help with the class, send us an e-mail
at cobraz@cobraz.no for assistance.