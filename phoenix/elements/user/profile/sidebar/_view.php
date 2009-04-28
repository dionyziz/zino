<?php
    class ElementUserProfileSidebarView extends Element {
        protected $mPersistent = array( 'theuserid' , 'updated', 'schoolexists' );
        public function Render( $theuser , $theuserid , $updated, $schoolexists ) { 
        	global $page;
            ?><div class="sidebar">
                <div class="basicinfo"><?php
                    Element( 'user/profile/sidebar/basicinfo' , $theuser , $theuserid , $updated, $schoolexists ); 
                    ?><dl class="online"><dt><strong>Online</strong></dt><dd></dd></dl><?php
                ?></div>
				<div><?php
				$page->AttachScript("js/pm.js");
				?><a href="" onclick="return pms.NewMessage( '<?php echo $theuser->Name ?>' , '' )">Αποστολή προσωπικού μηνύματος</a>
				</div><?php
                Element( 'user/profile/sidebar/details' , $theuser , $theuserid , $updated );
            ?>
            <div class="ads"></div>
            </div><?php
        }
    }
?>
