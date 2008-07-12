<?php
	
	class ElementSpaceView extends Element {
        public function Render( tText $username , tText $subdomain ) {
            global $user;
            global $page;
            
            $username = $username->Get();
            $subdomain = $subdomain->Get();
            $finder = New UserFinder();
            if ( $username != '' ) {
                if ( strtolower( $username ) == strtolower( $user->Name ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindByName( $username );
                }
            }
            else if ( $subdomain != '' ) {
                if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindBySubdomain( $subdomain );
                }
            }
            if ( !isset( $theuser ) || $theuser === false ) {
                ?>Ο χρήστης δεν υπάρχει<?php
                return;
            }	
            if ( strtoupper( substr( $theuser->Name, 0, 1 ) ) == substr( $theuser->Name, 0, 1 ) ) {
                $page->SetTitle( $theuser->Name . " Χώρος" );
            }
            else {
                $page->SetTitle( $theuser->Name . " χώρος" );
            }
            Element( 'user/sections' , 'space' , $theuser );
            ?><div id="space">
                <h2>Χώρος</h2><?php
                if ( $user->Id == $theuser->Id || $user->HasPermission( PERMISSION_SPACE_EDIT_ALL ) ) {
                    ?><div class="owner">
                        <div class="edit">
                            <a href="?p=editspace">Επεξεργασία</a>
                        </div>
                    </div><?php
                }
                ?><div class="text"><?php
                echo $theuser->Space->Text;
                ?></div>
                <div class="eof"></div>
            </div><?php
        }
    }
?>
