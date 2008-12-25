<?php
    require_once '../libs/rabbit/helpers/email.php';
    
    
    $emails = 'dionyziz@kamibu.com, petros_aggelatos@yahoo.com, petrosagg18@hotmail.com';
    
    mail( 'oniz@kamibu.com', Email_FormatSubject( 'Πρόσκληση: Χριστουγεννιάτικο Zino Meeting Ιωάννινα' ), 
        'Σε προσκαλούμε στο μεγάλο Zino Meeting στα Ιωάννινα, το Σάββατο 27 Δεκεμβρίου.

Τόπος και ώρα συγκέντρωσης 19.00 στην Νομαρχία.

Ελπίζουμε να είσαι εκεί! Ευχαριστούμε,
Η Ομάδα του Zino', "From: Zino <oniz@kamibu.com>\r\nBcc: " . $emails
    );
?>
