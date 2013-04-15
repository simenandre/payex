<?php

include("payex.php");

$transaction = PayEx::transaction(123, 1000.31);
if($transaction->isOK()) $transaction->Redirect();
