<?php
    class ElementUserProfileSidebarView extends Element {
        protected $mPersistent = array( 'theuserid' , 'updated', 'schoolexists' );
         
        public function Render( $theuser , $theuserid , $updated, $schoolexists ) { 
            ?><div class="sidebar">
                <div class="basicinfo"><?php
                    Element( 'user/profile/sidebar/basicinfo' , $theuser , $theuserid , $updated, $schoolexists ); 
                    ?><dl class="online"><dt><strong>Online</strong></dt><dd></dd></dl><?php
                ?></div>
				<div>
				<a href="" class="folder_links newpm" onclick="return pms.NewMessage( '' , '' )"><span>&nbsp;</span>Νέο μήνυμα</a>
				</div><?php
                Element( 'user/profile/sidebar/details' , $theuser , $theuserid , $updated );
            ?>
            <div class="ads"></div>
            </div><?php
        }
    }
?>
