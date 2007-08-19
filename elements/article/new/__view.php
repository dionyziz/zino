<?php
	function ElementArticleNewView( tInteger $id, tBoolean $preview ) {
		global $page;
		global $libs;
		global $page;
		global $user;
		
		if ( !$user->CanModifyStories() ) {
			return;
		}
		
        $id = $id->Get();
        $preview = $preview->Get();
		$page->AttachStylesheet( 'css/newarticle.css' );
		$page->AttachScript( 'js/newarticle.js' );
		$page->AttachScript( 'js/photos.js' );
		$libs->Load( 'article' );
		
		if ( $id > 0 ) {
			$article = New Article( $id );
			if ( !$article->Exists() ) {
				return;
			}
			if ( !$article->CanModify( $user ) ) {
				return;
			}
			$page->SetTitle( 'Επεξεργασία άρθρου' );
		}
		else {
			$page->SetTitle( 'Νέο άρθρο' );
			$article = New Article( array() );
			$article->SetPageviews( 0 );
			$article->SetSubmitDate( NowDate() );
		}
		
		if ( $preview ) {
			$article->SetTitle(         $_SESSION[ 's_sname' ]                   );
			$article->SetText(          $_SESSION[ 's_sarticlehtml' ]            );
			$article->SetCategoryId(    $_SESSION[ 's_scategoryid' ]             );
			$article->SetShowEmoticons( $_SESSION[ 's_sshowemoticons' ] === true );
			$article->SetIconId(        $_SESSION[ 's_sicon' ]                   );
			$id =                       $_SESSION[ 's_seid' ];
			
			$page->AttachStyleSheet( 'css/article.css' );

			if( $id > 0 ) {
				$creator = $article->Editors();
			}
			else {
				$creator = array( $user );
			}
			
			?><div class="article"><?php
				/* Element( 'article/main' , 0 , $article->Title() , $article->IconId() , mformatstory( $article->TextRaw() , $article->ShowEmoticons() , $article->SubmitDate() 
										, $article->Pageviews() , $creator , false , $article->Category()->Id() , $article->Category()->Icon()
										, $article->Category()->Name() , $article->NumComments() , -1 ); */
                Element( 'article/main' , $article, $creator, false , -1 );
			?></div><hr size="1" /><br /><?php
		}
		?><div class="newarticle" id="newarticle">
			<form method="post" action="do/article/new" onsubmit="return NewArticle.Validate()"><?php
				if ( $id > 0 ) {
					?><input type="hidden" value="<?php
					echo $id;
					?>" name="eid" /><?php
				}
				?>
				<div>
					<span class="label">Τίτλος:</span>
					<span class="input">
						<input type="text" name="name" id="name" value="<?php
						echo $article->Title();
						?>" />
					</span>
				</div>
				<div>
					<span class="label">Κατηγορία:</span>
					<span class="input">
						<select name="categoryid"><?php
							$allcategories = Subcategories();
							Element( 'article/new/categories' , $allcategories , 0 , 0 , $article->Category()->Id() );
						?></select>
					</span>
				</div>
				<br /><?php
				Element( 'article/new/filmstrip' , false );
				?><br /><?php
				if ( $user->CanModifyStories() ) { 
					?><a href="" onclick="Photos.Newphoto( this );return false;" class="addphoto" id="newphotolink">Νέα φωτογραφία&#187;</a>
					<br /><br /><div style="padding-left:30px;width:350px;height:100px;display:none;" id="newphoto">
					<iframe src="index.php?p=uploadframe&amp;albumid=0" frameborder="no" style="width:350px;height:100px;overflow:hidden;"></iframe>
	                </div><?php
				}
                Element( 'wysiwyg' , 'articlehtml' , $article->Text() ); 
                ?><br />
				<input type="checkbox" id="showemoticons" name="showemoticons" value="1" <?php
				if ( $article->ShowEmoticons() ) {
					?>checked="checked"<?php
				}
				?>/>
				<label for="showemoticons">
					Εμφάνιση Χαμόγελων
				</label><br />
				<?php
				if ( $article->Exists() ) {
					?><input type="checkbox" id="minor" name="minor" value="yes" checked="checked" />
					<label for="minor">
						Μικρή Αλλαγή
					</label><?php
				}
				?><br /><br /><br />
				<div>
					<span class="label">Σχόλιο:</span>
					<span class="input">
						<input type="text" name="comment" style="width: 200px" value=""/>
					</span>
				</div>
				<div>
					<span class="label">Εικονίδιο:</span>
					<span class="input">
						<input type="text" name="icon" style="width: 50px" value="<?php
						echo $article->IconId();
						?>"/>
					</span>
				</div><br /><br />
				<input type="submit" name="preview" value="Προεπισκόπιση" />
				<input type="submit" value="Δημοσίευση" />
			</form>
		</div>
		<?php
	}
?>
