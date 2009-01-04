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
            $tag = New Tag( $text );
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
                            $people = $userfinder->FindByTag( $tag );
                            Element( 'user/list', $people );
                        ?><div class="eof"></div>
                    </div>
                    <div class="popularhobbies">
                        <h3>Άλλα hobbies</h3>
                        <ul>
                            <li><a href="" style="font-size: 105%">πλέξιμο</a></li>
                            <li><a href="" style="font-size: 190%">κορίτσια</a></li>
                            <li><a href="" style="font-size: 200%">Bill Kaulitz</a></li>
                            <li><a href="" style="font-size: 180%">sex</a></li>
                            <li><a href="" style="font-size: 175%">pc</a></li>
                            <li><a href="" style="font-size: 100%">κολύμπι</a></li>
                            <li><a href="" style="font-size: 120%">skate</a></li>
                            <li><a href="" style="font-size: 180%">music</a></li>
                            <li><a href="" style="font-size: 200%">Tokio Hotel</a></li>
                            <li><a href="" style="font-size: 175%">αυτοκίνητα</a></li>
                            <li><a href="" style="font-size: 135%">γατάκια</a></li>
                            <li><a href="" style="font-size: 190%">αγόρια</a></li>
                            <li><a href="" style="font-size: 165%">parkour</a></li>
                            <li><a href="" style="font-size: 160%">tennis</a></li>
                            <li><a href="" style="font-size: 130%">φαγητό</a></li>
                            <li><a href="" style="font-size: 110%">underage</a></li>
                            <li><a href="" style="font-size: 100%">ουρολαγνία</a></li>
                            <li><a href="" style="font-size: 195%">κορίτσια</a></li>
                            <li><a href="" style="font-size: 140%">καφρίλες</a></li>
                            <li><a href="" style="font-size: 120%">kogi</a></li>
                            <li><a href="" style="font-size: 105%">μπαλέτο</a></li>
                            <li><a href="" style="font-size: 195%">ψώνια</a></li>
                            <li><a href="" style="font-size: 170%">flirt</a></li>
                            <li><a href="" style="font-size: 165%">βόλτες</a></li>
                            <li><a href="" style="font-size: 195%">καφές</a></li>
                            <li><a href="" style="font-size: 105%">μαθηματικά</a></li>
                            <li><a href="" style="font-size: 125%">αλητείες</a></li>
                            <li><a href="" style="font-size: 150%">ποδήλατο</a></li>
                            <li><a href="" style="font-size: 135%">Linkin Park</a></li>
                            <li><a href="" style="font-size: 100%">κουκλοθέατρο</a></li>
                            <li><a href="" style="font-size: 105%">ζωγραφική</a></li>
                            <li><a href="" style="font-size: 100%">φιλοσοφία</a></li>
                            <li><a href="" style="font-size: 150%">εκδρομές</a></li>
                            <li><a href="" style="font-size: 100%">ποίηση</a></li>
                            <li><a href="" style="font-size: 180%">ταινίες</a></li>
                            <li><a href="" style="font-size: 155%">cinema</a></li>
                        </ul>
                    <div>
                </div>
            </div><?php
        }
    }
?>
