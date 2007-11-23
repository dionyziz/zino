<?php
	function ElementUserspaceEdit( tBoolean $preview ) {
		global $page;
		global $libs;
		global $page;
		global $user;
		global $xc_settings;
		
		$preview = $preview->Get();
		
		if ( $user->IsAnonymous() ) {
			return Element( '404' );
		}
		
		$page->AttachStylesheet( 'css/newarticle.css' );
		$page->AttachScript( 'js/newarticle.js' );
		$page->AttachScript( 'js/photos.js' );
		
		$libs->Load( 'userspace' );
		$page->SetTitle( 'Επεξεργασία Χώρου' );
		
		$userspace = New Userspace( $user->Id() );
				
		if ( $preview ) {
		/*	$article->SetTitle( $_SESSION[ 's_stitle' ] );
			$article->SetText( $_SESSION[ 's_sstory' ] );
			$article->SetCategoryId( $_SESSION[ 's_scategoryid' ] );
			$article->SetShowEmoticons( $_SESSION[ 's_sshowemoticons' ] === true || $_SESSION[ 's_sshowemoticons' ] == 'yes' );
			$article->SetIconId( $_SESSION[ 's_siconid' ] );
			$id = $_SESSION[ 's_seid' ];
		*/
			// show how the blog looks like
			?><div style="text-align: center;"><?php
			$userspace = New Userspace( $user->Id() ) ;
			echo $userspace->Text();
			?></div><br /><?php
		
		}
		?><div class="newarticle">
			<form method="post" id="newarticle" action="do/user/space/update" onsubmit="return NewArticle.Validate()"><?php
				
				?><br /><br /><br /><br /><?php
					
				/* Photo Uploading */

				if ( $xc_settings[ 'allowuploads' ] ) {

					Element( 'article/new/filmstrip' , false );
					?><br /><a href="" onclick="Photos.Newphoto( this );return false;" class="addphoto" id="newphotolink">Νέα φωτογραφία&#187;</a>
					<br /><br /><div style="padding-left:30px;width:350px;height:100px;display:none;" id="newphoto">
					<iframe src="index.php?p=uploadframe&amp;albumid=0" frameborder="no" style="width:350px;height:100px;overflow:hidden;"></iframe>
					</div><?php
				}

				/* End Photo Uploading */

				?><textarea name="text"><?php 
                echo htmlspecialchars( $userspace->TextRaw() ); 
                ?></textarea><br />
				<input type="hidden" name="userid" value="<?php
				echo $user->Id();
				?>" />
				<br /><br />
				<input type="submit" value="Δημοσίευση" />
			</form>
		</div>
		<?php
	}
?>
