<?php
    class ElementFrontpageShoutboxList extends Element {
        public function Render( $shoutboxseq ) {
            global $user;

            ?><h2>Συζήτηση <span>(<a href="shouts">προβολή όλων</a>)</span></h2>
            <div class="comments"><?php
                if ( $user->Exists() && $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) ) {
                    Element( 'shoutbox/reply' , $user->Id , $user->Avatar->Id , $user );
                    switch ( strtolower( $user->Name ) ) {
                        case 'dionyziz':
                        case 'pagio91':
                        case 'izual':
                        case 'petrosagg18':
                        case 'gatoni':
                        case 'ted':
                        case 'kostis90gr':
                        case 'bowling':
                        case 'parvati':
                        case 'finlandos':
                        case 'kardas_thrilikozzzz':
                        case 'blink':
                        case 'indy':
                        case 'd3nnn1z':
                        case 'chorvus':
                        case 'peach':
                        case 'kogi':
                        case 'dimo0koc':
                        case 'teddy':
                        case 'seraphim':
                        case 'elsa':
                        case 'funeral':
                        case 'cmad':
                        case 'teh-ninja':
                        case 'intzakosd':
                        case 'ronaldo7':
                            Element( 'shoutbox/comet' );
                    }
                }
                Element( 'frontpage/shoutbox/recent' , $shoutboxseq );
                Element( 'shoutbox/view', false , true );
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
