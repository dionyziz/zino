<?php
    function ActionAdManagerCheckout(
        tInteger $numviews,
        tText $firstname,
        tText $lastname,
        tText $email,
        tText $payment
    ) {
        $numviews = $numviews->Get();
        $firstname = $firstname->Get();
        $lastname = $lastname->Get();
        $email = $email->Get();
        $payment = $payment->Get();
    }
?>
