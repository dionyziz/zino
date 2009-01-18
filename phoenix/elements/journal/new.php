<?php
    
    class ElementJournalNew extends Element {
        public function Render( tInteger $id ) {
            global $user;
            global $page;
            global $water;
            global $libs;
            
            $libs->Load( 'wysiwyg' );
            
            $id = $id->Get();
            
            $page->AttachScript( 'js/wysiwyg.js' ); // TODO
            if ( $id > 0 ) {
                $journal = New Journal( $id );
                $page->SetTitle( $journal->Title );
            }
            else {
                $page->SetTitle( "Δημιουργία καταχώρησης" );
            }
            Element( 'user/sections', 'journal', $user );
            ?><div id="journalnew">
                <h2><?php
                if ( $id > 0 ) {
                    ?>Επεξεργασία <?php
                }
                else {
                    ?>Δημιουργία <?php
                }
                ?> καταχώρησης</h2><?php
                if ( ( isset( $journal ) && $journal->User->Id == $user->Id ) || $id == 0 ) {
                    ?><div class="edit">
                        <form method="post" action="do/journal/new" onsubmit="return JournalNew.Create( '<?php
                                echo $id;
                                ?>' );">
                            <input type="hidden" name="id" value="<?php
                            echo $id;
                            ?>" />
                            <div class="title">
                                <span>Τίτλος:</span><input type="text" value="<?php
                                if ( $id > 0 ) {
                                    echo htmlspecialchars( $journal->Title );
                                }
                                ?>" name="title" tabindex="1" />
                            </div>
                            <?php
                            if ( $id > 0 ) {
                                $text = WYSIWYG_PreProcess( $journal->Text );
                            }
                            else {
                                $text = '';
                            }
                            Element( 'wysiwyg/view', 'wysiwyg', $text );
                            ?>
                            <div class="submit">
                                <input type="submit" value="Δημοσίευση" id="publish" />
                            </div>
                        </form>
                    </div><?php
                    Element( 'wysiwyg/controls' );
                }
                else {
                    ?>Δεν έχεις δικαίωμα να επεξεργαστείς την καταχώρηση<?php
                }
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
