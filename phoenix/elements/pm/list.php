<?php

	function ElementPmList() {
		global $page;
		global $water;
		global $libs;
		global $user;
		global $xc_settings;
		
        if ( !$user->Exists() ) {
            return;
        }

        $water->Enable();
        
		$libs->Load( 'pm/pm' );
		$page->SetTitle( 'Προσωπικά μηνύματα' );
        
		$page->AttachStyleSheet( 'css/pm.css' );
		$page->AttachStyleSheet( 'css/modal.css' );
        
		$page->AttachScript( 'js/pm.js' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/modal.js' );
		$page->AttachScript( 'js/animations.js' );
        // $page->AttachScript( 'js/jquery.js' );
		$page->AttachScript( 'js/ui.base.js' );
		$page->AttachScript( 'js/ui.draggable.js' );
		$page->AttachScript( 'js/ui.droppable.js' );
		$page->AttachScript( 'js/jquery.dimensions.js' );
		
        $finder = New PMFolderFinder();
        $folders = $finder->FindByUser( $user );
        $unreadCount = $user->Count->Unreadpms;

		?><script type="text/javascript">
	    var unreadpms = <?php
		echo $unreadCount;
		?></script>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
		<br /><br /><br /><br />
		<div class="body">
			<div class="upper">
				<span class="title">Μηνύματα</span>
				<div class="subheading">Εισερχόμενα</div>
			</div>
			<div class="leftbar">
				<div class="folders" id="folders"><?php
                    $inbox = false;
					foreach ( $folders as $folder ) {
                        if ( $folder->Typeid == PMFOLDER_INBOX ) {
                            $inbox = $folder;
                        }
                        Element( 'pm/folder/link', $folder );
					}

					?><div class="newfolder top" id="newfolderlink" alt="Δημιούργησε έναν νέο φάκελο" title="Δημιούργησε έναν νέο φάκελο" onclick="pms.NewFolder();return false;"><a href="" class="folderlinksnew">Νέος Φάκελος</a></div>
				</div><br />
				<a href="" class="folder_links" onclick="pms.NewMessage( '' , '' );return false;"><img src="<?php
				echo $xc_settings[ 'staticimagesurl' ];
				?>email_open.png" alt="Νέο μήνυμα" title="Νέο μήνυμα" /> Νέο μήνυμα</a><br />
				<a href="" id="deletefolderlink" class="folder_links" onclick="return false;" style="display:none;"><img src="<?php
				echo $xc_settings[ 'staticimagesurl' ];
				?>icons/folder_delete.png" alt="Διαγραφή φακέλου" title="Διαγραφή φακέλου" /> Διαγραφή φακέλου</a>
				<a href="" id="renamefolderlink" class="folder_links" onclick="return false;" style="display:none;"><img src="<?php
				echo $xc_settings[ 'staticimagesurl' ];
				?>icons/folder_edit.png" alt="Μετονομασία φακέλου" title="Μετονομασία φακέλου" /> Μετονομασία φακέλου</a>
			</div>
			<div class="rightbar" style="float:left;">
				<div class="messages" id="messages"><?php
					Element( 'pm/folder/view', $inbox );
				?></div>
			</div>
			<div style="clear:left;"></div>
			<div class="newfoldermodal" id="newfoldermodal" style="display:none;">
				Δώσε ένα όνομα για τον φάκελό σου<br /><br />
				<form id="newfolderform" onsubmit="pms.CreateNewFolder( this );return false;" action="" method="">
					<input type="textbox" style="width:130px;" /> 
					<a href="" onclick="pms.CreateNewFolder( this.parentNode );return false;"><img src="<?php
					echo $xc_settings[ 'staticimagesurl' ];
					?>icons/accept.png" alt="Δημιουργία" title="Δημιουργία" /></a>
					<a href="" onclick="pms.CancelNewFolder();return false;"><img src="<?php
					echo $xc_settings[ 'staticimagesurl' ];
					?>icons/cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
				</form>
			</div>
		</div>
<?php
	}
?>
