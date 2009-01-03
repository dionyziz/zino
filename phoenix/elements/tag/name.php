<?php
    class ElementTagName extends Element {
        public function Render( $type, $plural = false ) {
            switch ( $type ) {
                case TAG_HOBBIE:
                    if ( $plural ) {
                        ?>στα ενδιαφέροντα<?php
                    }
                    else {
                        ?>ενδιαφέρον<?php
                    }
                    break;
                case TAG_MOVIE:
                    if ( $plural ) {
                        ?>στις αγαπημένες ταινίες<?php
                    }
                    else {
                        ?>αγαπημένη ταινία<?php
                    }
                    break;
                case TAG_BOOK:
                    if ( $plural ) {
                        ?>στα αγαπημένα βιβλία<?php
                    }
                    else {
                        ?>αγαπημένο βιβλίο<?php
                    }
                    break;
                case TAG_SONG:
                    if ( $plural ) {
                        ?>στα αγαπημένα τραγούδια<?php
                    }
                    else {
                        ?>αγαπημένο τραγούδι<?php
                    }
                    break;
                case TAG_ARTIST:
                    if ( $plural ) {
                        ?>στους αγαπημένους καλλιτέχνες<?php
                    }
                    else {
                        ?>αγαπημένο καλλιτέχνη<?php
                    }
                    break;
                case TAG_GAME:
                    if ( $plural ) {
                        ?>στα αγαπημένα παιχνίδια<?php
                    }
                    else {
                        ?>αγαπήμένο παιχνίδι<?php
                    }
                    break;
                case TAG_SHOW:
                    if ( $plural ) {
                        ?>στις αγαπημένες σειρές<?php
                    }
                    else {
                        ?>αγαπημένη σειρά<?php
                    }
                    break;
            }
        }
    }
?>
