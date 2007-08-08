<?php
	function ElementPmList() {
		global $page;
		global $water;
		global $libs;
		global $user;
		
		$libs->Load( 'pm' );
		$page->SetTitle( 'Προσωπικά μηνύματα' );
		$page->AttachStyleSheet( 'css/pmnew.css' );
		$page->AttachStyleSheet( 'css/modal.css' );
		$page->AttachScript( 'js/pmsnew.js' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/modal.js' );
		$page->AttachScript( 'js/animations.js' );
		$userfolders = PM_UserFolders();
		$unreadmsgs = PM_UserCountUnreadPms( $user );
		?><script type="text/javascript">
	    var unreadpms = <?php
		echo $unreadmsgs;
		?>;</script>
		<br /><br /><br /><br />
		<div class="body">
			<div class="upper">
				<span class="title">Μηνύματα</span>
				<div class="subheading">Εισερχόμενα</div>
			</div>
			<div class="leftbar">
				<div class="folders">
					<div class="activefolder" alt="Εισερχόμενα" title="Εισερχόμενα" onload="pms.activefolder = this;return false;" id="firstfolder"><a href="" class="folderlinksactive" onclick="pms.ShowFolderPm( this.parentNode , -1 );return false;">Εισερχόμενα<?php
					if ( $unreadmsgs != 0 ) {
						?> (<?php
						echo $unreadmsgs;
						?>)<?php
					}
					?></a></div>
					<div class="folder top" alt="Απεσταλμένα" title="Απεσταλμένα"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , -2 );return false;">Απεσταλμένα</a></div><?php
					foreach ( $userfolders as $folder ) {
						?><div class="folder top" id="folder_<?php
						echo $folder->Id;
						?>" alt="<?php
						echo $folder->Name;
						?>" title="<?php
						echo $folder->Name;
						?>"><a href="" class="folderlinks" onclick="pms.ShowFolderPm( this.parentNode , '<?php
						echo $folder->Id;
						?>' );return false;"><?php
						echo $folder->Name;
						?></a></div><?php
					} ?>
					<div class="newfolder top" id="newfolderlink" alt="Δημιούργησε έναν νέο φάκελο" title="Δημιούργησε έναν νέο φάκελο" onclick="pms.NewFolder();return false;"><a href="" class="folderlinksnew">Νέος Φάκελος</a></div>
				</div><br />
				<a href="" class="folder_links" onclick="pms.NewMessage( '' , '' );return false;"><img src="http://static.chit-chat.gr/images/email_open.png" alt="Νέο μήνυμα" title="Νέο μήνυμα" /> Νέο μήνυμα</a><br />
				<a href="" id="deletefolderlink" class="folder_links" onclick="return false;" style="display:none;"><img src="http://static.chit-chat.gr/images/folder_delete.png" alt="Διαγραφή φακέλου" title="Διαγραφή φακέλου" /> Διαγραφή φακέλου</a>
			</div>
			<div class="rightbar" style="float:left;">
				<div class="messages" id="messages"><?php
					Element( 'pm/showfolder' , -1 );
				?></div>
			</div>
			<div style="clear:left;"></div>
			<div class="newfoldermodal" id="newfoldermodal" style="display:none;">
				Δώσε ένα όνομα για τον φάκελό σου<br /><br />
				<form id="newfolderform" onsubmit="pms.CreateNewFolder( this );return false;" action="" method="">
					<input type="textbox" style="width:130px;" /> 
					<a href="" onclick="pms.CreateNewFolder( this.parentNode );return false;"><img src="http://static.chit-chat.gr/images/icons/accept.png" alt="Δημιουργία" title="Δημιουργία" /></a>
					<a href="" onclick="pms.CancelNewFolder();return false;"><img src="http://static.chit-chat.gr/images/icons/cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
				</form>
			</div>
		</div>
<?php
	}
?>