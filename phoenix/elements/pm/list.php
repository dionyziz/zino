<?php

	function ElementPmList() {
		global $page;
		global $water;
		global $libs;
		global $user;
		global $rabbit_settings;

        if ( !$user->Exists() ) {
            return;
        }

		$libs->Load( 'pm/pm' );
		$page->SetTitle( 'Προσωπικά μηνύματα' );
       	
        $finder = New PMFolderFinder();
        $folders = $finder->FindByUser( $user );
        $unreadCount = $user->Count->Unreadpms;

        $folder_dump = array();
        foreach ( $folders as $folder ) {
            $folder_dump[] = array( $folder->Userid, $folder->Name, $folder->Typeid );
        }

		?><script type="text/javascript">
	    var unreadpms = <?php
		echo $unreadCount;
		?></script>
		<br /><br /><br /><br />
		<div id="pms">
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
				echo $rabbit_settings[ 'imagesurl' ];
				?>email_open.png" alt="Νέο μήνυμα" title="Νέο μήνυμα" /> Νέο μήνυμα</a><br />
				<a href="" id="deletefolderlink" class="folder_links" onclick="return false;" style="display:none;"><img src="<?php
				echo $rabbit_settings[ 'imagesurl' ];
				?>folder_delete.png" alt="Διαγραφή φακέλου" title="Διαγραφή φακέλου" /> Διαγραφή φακέλου</a>
				<a href="" id="renamefolderlink" class="folder_links" onclick="return false;" style="display:none;"><img src="<?php
				echo $rabbit_settings[ 'imagesurl' ];
				?>folder_edit.png" alt="Μετονομασία φακέλου" title="Μετονομασία φακέλου" /> Μετονομασία φακέλου</a>
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
					echo $rabbit_settings[ 'imagesurl' ];
					?>accept.png" alt="Δημιουργία" title="Δημιουργία" /></a>
					<a href="" onclick="pms.CancelNewFolder();return false;"><img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
				</form>
			</div>
		</div>
		</div>
<?php
	}
?>
