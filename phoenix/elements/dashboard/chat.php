<?php
    class ElementDashboardChat extends Element {
        public function Render( $messages ) {
            global $user;

            ?><div id="chat">
                <a href="chat" class="maximize" title="Μεγιστοποίηση"></a>
                <a href="" class="minimize" title="Ελαχιστοποίηση"></a>

                <h2>Συζήτηση</h2>
                <ol>
                    <!-- <li class="history">Προβολή προηγούμενων μηνυμάτων</li> --><?php
                    foreach ( $messages as $message ) {
                        ?><li class="text" id="s_<?php
                        echo $message[ 'id' ];
                        ?>"><strong><?php
                        echo $message[ 'username' ];
                        ?></strong> <div class="text"><?php
                        echo $message[ 'html' ];
                        ?></div></li><?php
                    }
                ?></ol><?php
                if ( $user->Exists() ) {
                    ?><div class="input">
                        <textarea>Πρόσθεσε ένα σχόλιο στη συζήτηση</textarea>
                    </div><?php
                }
                ?>
            </div><?php
        }
    }
?>
