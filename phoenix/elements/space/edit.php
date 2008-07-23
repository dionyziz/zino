<?php
    
    class ElementSpaceEdit extends Element {
        public function Render() {
            global $user;
            global $page;
            global $libs;

            $page->AttachScript( 'js/wysiwyg.js' ); // TODO
            $libs->Load( 'wysiwyg' );
            $page->SetTitle( 'Επεξεργασία χώρου' );
            Element( 'user/sections' , 'space' , $user );
            ?><div id="editspace">
                <h2>Επεξεργασία χώρου</h2>
                <div class="edit">
                    <form method="post" action="do/space/edit">
                        <?php
                            Element( 'wysiwyg/view', 'wysiwyg', WYSIWYG_PreProcess( $user->Space->Text ) );
                        ?>
                        <div class="submit">
                            <input type="submit" value="Δημοσίευση" />
                        </div>
                    </form><?php
                    Element( 'wysiwyg/controls' );
                    ?>
                </div>
                <div class="eof"></div>
            </div><?php
        
        }
    }
?>
