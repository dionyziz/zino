<?php
    class ElementTagView extends Element {
        public function Render( tText $text, tInteger $type ) {
            global $page;
            global $libs;

            $libs->Load( 'tag' );
            $type = $type->Get();
            $text = trim( $text->Get() );
            if ( !Tag_ValidType( $type ) || empty( $text ) ) {
                return Element( '404' );
            }
            $tag = New Tag();
            $tag->Text = $text;
            $tag->Typeid = $type;
            $page->SetTitle( $text );
            ?><div id="interestlist">
                <h2>άτομα με <?php
                Element( 'tag/name', $type );
                ?>: <?php
                echo htmlspecialchars( $text );
                ?></h2>
                <div class="addhobby"><a href="" style="font-size: 105%">Προσθήκη <?php
                Element( 'tag/name', $type, true );
                ?> μου</a></div>
                <div class="list">
                    <div class="people"><?php
                            $userfinder = New UserFinder();
                            $people = $userfinder->FindByTag( $tag );
                            Element( 'user/list', $people );
                        ?><div class="eof"></div>
                    </div>
                    <div class="popularhobbies">
                        <h3>Άλλα hobbies</h3>
                        <ul><?php
                            $tagfinder = New TagFinder();
                            $populartags = $tagfinder->FindPopular();
                            foreach ( $populartags as $text => $popularity ) {
                                ?><li><a href="" style="font-size: <?php
                                echo round( $popularity * 100 + 100 );
                                ?>%"><?php
                                echo htmlspecialchars( $text );
                                ?></a></li><?php
                            }
                        ?></ul>
                    </div>
                </div>
            </div><?php
        }
    }
?>
