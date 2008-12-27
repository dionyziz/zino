<?php
	
	class ElementSchoolView extends Element {
		public function Render( tInteger $id, tInteger $pageno, tInteger $commentid ) {
            global $user; 
            global $libs;
            global $page;

            $libs->Load( 'comment' );
            $libs->Load( 'notify' );
			
			$id = $id->Get();
            $pageno = $pageno->Get();
            $commentid = $commentid->Get();
			
            $school = New School( $id );
			$userfinder = New UserFinder();
			$students = $userfinder->FindBySchool( $school , 0 , 12 );
            if ( !$school->Exists() ) {
                die( 'Το σχολείο που προσπαθείς να δεις δεν υπάρχει.' );
                return Element( '404' );
            }

            $institution = $school->Institution;
            $page->SetTitle( $school->Name );

            if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                $finder = New CommentFinder();
                if ( $commentid == 0 ) {
                    $comments = $finder->FindByPage( $school, $pageno, true );
                    $total_pages = $comments[ 0 ];
                    $comments = $comments[ 1 ];
                }
                else {
                    $speccomment = New Comment( $commentid );
                    $comments = $finder->FindNear( $school, $speccomment );
                    if ( $comments === false ) {
                        ob_start();
                        Element( 'url', $school );
                        return Redirect( ob_get_clean() );
                    }
                    $total_pages = $comments[ 0 ];
                    $pageno = $comments[ 1 ];
                    $comments = $comments[ 2 ];
                    $finder = New NotificationFinder();
                    $finder->DeleteByCommentAndUser( $speccomment, $user );
                }
            }

			?><div id="schview"><?php
				Element( 'school/info' , $school , false );
				Element( 'school/members/members' , $students, $school->Id );
                if ( $school->Album->Exists() ) {
                    ?><div class="photos">
                        <?php
                        $finder = New ImageFinder();
                        $images = $finder->FindByAlbum( $school->Album );
                        if ( count( $images ) || $user->Profile->Schoolid == $school->Id ) {
                            ?><h4>Φωτογραφίες</h4><?php
                            Element( 'school/image/list', $images , $id );
                        }
                    ?></div>
                    <div id="schooluploadmodal" style="display:none">
                        <div class="schooluploadcontainer"><?php
                        if ( $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
                            if ( $user->Profile->Schoolid == $school->Album->Owner->Id ) {
                                ?><div class="uploaddiv"><?php
                                    if ( UserBrowser() == 'MSIE' ) {
                                        ?><iframe src="?p=upload&amp;albumid=<?php
                                        echo $school->Album->Id;
                                        ?>&amp;typeid=3" class="uploadframe" id="uploadframe" scrolling="no" frameborder="0">
                                        </iframe><?php
                                    }
                                    else {
                                        ?><object data="?p=upload&amp;albumid=<?php
                                        echo $school->Album->Id;
                                        ?>&amp;typeid=3" class="uploadframe" id="uploadframe" type="text/html">
                                        </object><?php
                                    }
                                ?></div><?php
                            }
                        }
                        ?><a class="close button" href="">Ακύρωση</a>
                        </div>
                    </div><?php
                }
                ?><div class="eof"></div>
				<div class="comments">
					<h4>Σχόλια σχετικά με <?php
					echo htmlspecialchars( $school->Name );
					?></h4><?php
					if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
						Element( 'comment/reply' , $school->Id , TYPE_SCHOOL , $user->Id , $user->Avatar->Id );
					}
                    $page->AttachInlineScript( 'var nowdate = "' . NowDate() . '";' );
                    Element( 'comment/list' , $comments, TYPE_SCHOOL, $school->Id );
                    if ( $commentid !== 0 ) {
                        if ( $user->Exists() ) {
                            $page->AttachInlineScript( 'Comments.ToggleReply( ' . $commentid . ', ' . ( $indentation[ $commentid ] - 1 ) . ' );' );
                        }
                    }
                    ?><div class="pagifycomments"><?php
                    $link = '?p=school&id=' . $school->Id . '&pageno=';
                    if ( $pageno <= 0 ) {
                        $pageno = 1;
                    }
                    Element( 'pagify' , $pageno , $link, $total_pages );
                    ?></div>
				</div>
				<div class="eof"></div>
			</div><?php
		}
	}
?>
