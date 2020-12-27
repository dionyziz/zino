<?php
    return;

    require_once '../libs/rabbit/helpers/email.php';

    $emails = 'dionyziz@gmail.com, dionyziz@di.uoa.gr';

    mail( 'oniz@kamibu.com', Email_FormatSubject( 'Πρόσκληση: Zino Meeting Θεσσαλονίκης' ),
        'Σε προσκαλούμε στο μεγάλο Zino Meeting στη Θεσσαλονίκη, το Σάββατο 25 Οκτωβρίου.

Τόπος και ώρα συγκέντρωσης 19.00 στην Καμάρα.

Ελπίζουμε να είσαι εκεί! Ευχαριστούμε,
Η Ομάδα του Zino

____
Αν θέλεις να ορίσεις τι e-mail λαμβάνεις από το Zino, πήγαινε στο:
http://www.zino.gr/settings#settings', "From: Zino <oniz@kamibu.com>\r\nBcc: " . $emails
    );
?>
