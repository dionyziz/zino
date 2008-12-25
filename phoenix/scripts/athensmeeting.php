<?php
    require_once '../libs/rabbit/helpers/email.php';
    
    
    $emails = 'dionyziz@kamibu.com, petros_aggelatos@yahoo.com';
    
    mail( 'oniz@kamibu.com', Email_FormatSubject( 'Πρόσκληση: Χριστουγεννιάτικο Zino Meeting Ιωάννινα' ), 
        'Σε προσκαλούμε στο μεγάλο Zino Meeting στα Ιωάννινα, το Σάββατο 27 Δεκεμβρίου.

Τόπος και ώρα συγκέντρωσης 19.00 στην Νομαρχία.

Περισσότερες πληροφορίες μπορείς να βρεις στο:
http://oniz.zino.gr/journals/Xristougenniatiko_Zino_Meeting_sta_Iwannina

Ελπίζουμε να είσαι εκεί! Ευχαριστούμε,
Η Ομάδα του Zino

____
Αν θέλεις να ορίσεις τι e-mail λαμβάνεις από το Zino, πήγαινε στο:
http://www.zino.gr/settings#settings', "From: Zino <oniz@kamibu.com>\r\nBcc: " . $emails
    );
?>
