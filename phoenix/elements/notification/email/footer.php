<?php
    /// Content-type: text/plain ///
	function ElementNotificationEmailFooter() {
		global $rabbit_settings;

        ?>

        Ευχαριστούμε,
        Η Ομάδα του Zino

        ______
        Αν θέλεις να ορίσεις τι e-mail λαμβάνεις από το Zino, πήγαινε στο:
        <?php
        echo $rabbit_settings[ 'webaddress' ];
        ?>/settings#settings
        <?php
	}
?>
