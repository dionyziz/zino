<?php
	function ElementCommentLatest() {
		global $water;
		global $page;
		global $libs;
		global $xc_settings;
        
		$libs->Load( 'search' );
		$libs->Load( 'comment' );
		$libs->Load( 'article' );

		$search = New Search_Comments_Latest();
		$latestcomments = $search->Get();
		
		?><div class="box latestcomments">
			<div class="header">
				<div style="float:right"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraright.jpg" alt="" /></div>
				<div style="float:left"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraleft.jpg" alt="" /></div>
				<h3>Νεότερα σχόλια</h3>
			</div>
			<div class="body"><?php
				$i = 1;
				
				while ( $comment = array_shift( $latestcomments ) ) {
					?><div><?php
                        if ( $comment->User()->Id() ) {
                            Element( "user/icon" , $comment->User() , true , true );
                        }
                        else {
                            ?>Ανώνυμος <?php
                        }
						?> στ<?php
						
						switch ( $comment->TypeId() ) {
							case 2:
								?>ην εικόνα <a href="?p=photo&amp;id=<?php
								echo $comment->Page()->Id();
								?>#comment_<?php
								echo $comment->Id();
								?>"><?php
								break;
							case 1:
								if ( $comment->Page()->Gender() == 'female' ) {
									?>η<?php
                                    switch ( strtolower( substr( $comment->Page()->Username() , 0 , 1 ) ) ) {
                                        case 'a':
                                        case 'e':
                                        case 'o':
                                        case 'u':
                                        case 'i':
                                        case 't':
                                        case 'p':
                                        case 'k':
                                            ?>ν<?php
                                            break;
                                        default:
                                    }
								}
								else {
									?>ον<?php
								}
								?> <a href="user/<?php
								echo $comment->Page()->Title();
								?>#comment_<?php
								echo $comment->Id();
								?>"><?php
								break;
							case 0:
								?>o <a href="?p=story&amp;id=<?php
								echo $comment->Page()->Id();
								?>#comment_<?php
								echo $comment->Id();
								?>"><?php
								break;
                            /* 
                            case 3:
                                // please fix YSoD
								?>ην δημοσκόπηση <a href="?p=poll&amp;id=<?php
								echo $comment->Page()->Id();
								?>#comment_<?php
								echo $comment->Id();
								?>"><?php
								break;
                            */
                            default:
                                ?><a>(error)<?php
                                $water->Warning( 'Invalid comment typeid' );
						}
						
						echo htmlspecialchars( $comment->Page()->Title() );
						?></a><?php
						
						?><span>, πριν <?php
						echo dateDistance( $comment->SQLDate() );
						?></span><div style="clear:left;"></div>
					</div><?php
					
					++$i;
					if ( $i > 5 ) {
						break;
					}
				}
				
				if ( count( $latestcomments ) ) {
					?><div id="morecomments" class="boxexpand"><?php
					
					$i = 1;
					
					while ( $comment = array_shift( $latestcomments ) ) {
					?><div><?php
                        if ( $comment->User()->Id() == 0 ) {
                            ?>Ανώνυμος <?php
                        }
                        else {
                            Element( "user/icon" , $comment->User() , true , true );
                        }
						?> στ<?php
						
						switch ( $comment->TypeId() ) {
							case 2:
								?>ην εικόνα <a href="?p=photo&amp;id=<?php
								echo $comment->Page()->Id();
								?>#comment_<?php
								echo $comment->Id();
								?>"><?php
								break;
							case 1:
								if ( $comment->Page()->Gender() == 'female' ) {
									?>η<?php
								}
								else {
									?>ον<?php
								}
								?> <a href="user/<?php
								echo $comment->Page()->Title();
								?>#comment_<?php
								echo $comment->Id();
								?>"><?php
								break;
							case 0:
								?>o <a href="?p=story&amp;id=<?php
								echo $comment->Page()->Id();
								?>#comment_<?php
								echo $comment->Id();
								?>"><?php
								break;
                            // case 3: ....
                            default:
                                ?><a>(error)<?php
                                $water->Warning( 'Invalid comment typeid' );
						}
						
						echo htmlspecialchars( $comment->Page()->Title() );
						?></a><?php
						
						?><span>, πριν <?php
						echo dateDistance( $comment->SQLDate() );
						?></span><div style="clear:left;"></div>
					</div><?php
					
					++$i;
					if ( $i > 5 ) {
						break;
					}
				}
				
				?></div><a id="commentslink" href="javascript:ShowMore('comments');" class="arrow" title="Περισσότερα σχόλια"></a><?php
				}
				
			?></div>
		</div><?php
	}
?>
