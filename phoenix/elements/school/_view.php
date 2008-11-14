<?php
	
	class ElementSchoolView extends Element {
		public function Render( tInteger $id, tInteger $pageno, tInteger $commentid ) {
            global $user; 
            global $libs;

            $libs->Load( 'Comment' );
			
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
            if ( !$institution->Exists() ) {
                die( 'Το σχολείο που προσπαθείς να δεις δεν εντάσσεται σε κάποιο ίδρυμα.' );
                return Element( '404' );
            }

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
				Element( 'school/members/members' , $students );
                if ( $school->Album->Exists() ) {
                    ?><div class="photos">
                        <h4>Φωτογραφίες</h4>
                        <div class="plist">
                            <ul>
                                <li>
                                    <a href="?p=photo&amp;id=125909"><span class="imageview"><img src="http://images.zino.gr/media/4531/125909/125909_100.jpg" style="width:100px;height:100px;" title="eirinougen" alt="eirinougen" /><span class="info"><span class="commentsnumber">&nbsp;</span>16</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125907"><span class="imageview"><img src="http://images.zino.gr/media/4725/125907/125907_100.jpg" style="width:100px;height:100px;" title="Peach" alt="Peach" /><span class="info"><span class="commentsnumber">&nbsp;</span>46</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125903"><span class="imageview"><img src="http://images.zino.gr/media/2887/125903/125903_100.jpg" style="width:100px;height:100px;" title="Black_Sign_Of_Death" alt="Black_Sign_Of_Death" /><span class="info"><span class="commentsnumber">&nbsp;</span>1</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125902"><span class="imageview"><img src="http://images.zino.gr/media/4557/125902/125902_100.jpg" style="width:100px;height:100px;" title="halloween" alt="halloween" /><span class="info"><span class="commentsnumber">&nbsp;</span>11</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125901"><span class="imageview"><img src="http://images.zino.gr/media/3049/125901/125901_100.jpg" style="width:100px;height:100px;" title="cRazY_SoFy" alt="cRazY_SoFy" /></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125900"><span class="imageview"><img src="http://images.zino.gr/media/5253/125900/125900_100.jpg" style="width:100px;height:100px;" title="veteran" alt="veteran" /></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125899"><span class="imageview"><img src="http://images.zino.gr/media/2128/125899/125899_100.jpg" style="width:100px;height:100px;" title="natalie" alt="natalie" /><span class="info"><span class="commentsnumber">&nbsp;</span>3</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125897"><span class="imageview"><img src="http://images.zino.gr/media/1884/125897/125897_100.jpg" style="width:100px;height:100px;" title="xaliasas" alt="xaliasas" /></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125896"><span class="imageview"><img src="http://images.zino.gr/media/4195/125896/125896_100.jpg" style="width:100px;height:100px;" title="ErotOxtiPimeN0" alt="ErotOxtiPimeN0" /><span class="info"><span class="commentsnumber">&nbsp;</span>10</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125895"><span class="imageview"><img src="http://images.zino.gr/media/4765/125895/125895_100.jpg" style="width:100px;height:100px;" title="realrealthanos" alt="realrealthanos" /><span class="info"><span class="commentsnumber">&nbsp;</span>37</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125894"><span class="imageview"><img src="http://images.zino.gr/media/1/125894/125894_100.jpg" style="width:100px;height:100px;" title="dionyziz" alt="dionyziz" /><span class="info"><span class="commentsnumber">&nbsp;</span>10</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125893"><span class="imageview"><img src="http://images.zino.gr/media/5052/125893/125893_100.jpg" style="width:100px;height:100px;" title="manarakia_xD" alt="manarakia_xD" /><span class="info"><span class="commentsnumber">&nbsp;</span>8</span></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125891"><span class="imageview"><img src="http://images.zino.gr/media/2172/125891/125891_100.jpg" style="width:100px;height:100px;" title="Teddy" alt="Teddy" /></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125890"><span class="imageview"><img src="http://images.zino.gr/media/4750/125890/125890_100.jpg" style="width:100px;height:100px;" title="spyr0c" alt="spyr0c" /></span></a>
                                </li>
                                <li>
                                    <a href="?p=photo&amp;id=125889"><span class="imageview"><img src="http://images.zino.gr/media/5260/125889/125889_100.jpg" style="width:100px;height:100px;" title="funeral" alt="funeral" /><span class="info"><span class="commentsnumber">&nbsp;</span>28</span></span></a>
                                </li>                
                            </ul>
                        </div>
                    </div><?php
                }
                ?>
				<div class="eof"></div>
				<div class="comments">
					<h4>Σχόλια σχετικά με <?php
					echo htmlspecialchars( $school->Name );
					?></h4><?php
					if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
						Element( 'comment/reply' , $school->Id , TYPE_SCHOOL , $user->Id , $user->Avatar->Id );
					}
                    $page->AttachInlineScript( 'var nowdate = "' . NowDate() . '";' );
                    Element( 'comment/list' , $comments , TYPE_SCHOOL , $school->Id );
                    ?><div class="pagifycomments"><?php
                    $link = '?p=school&id=' . $school->Id . '?pageno=';
                    Element( 'pagify' , $pageno , $link, $total_pages );
                    ?></div>
				</div>
				<div class="eof"></div>
			</div><?php
		}
	}
?>
