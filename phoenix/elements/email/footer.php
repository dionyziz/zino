<?php
    /// Content-type: text/plain ///
    class ElementEmailFooter extends Element {
        public function Render( $existinguser = true ) {
            global $rabbit_settings;

            ?>


Ευχαριστούμε,
Η Ομάδα του Zino

<?php
            if ( $existinguser ) {
                ?>______
Αν θέλεις να ορίσεις τι e-mail λαμβάνεις από το Zino, πήγαινε στο:
<?php
                echo $rabbit_settings[ 'webaddress' ];
                ?>/settings#settings<?php
            }
        }
    }
?>
