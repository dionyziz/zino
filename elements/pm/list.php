<?php 
	function ElementPMList( tString $id, tString $to ) {
		global $page;
		global $user;
		global $libs;
		global $xc_settings;
		
        $id = $id->Get();
        $to = $to->Get();
        
		$libs->Load( 'pm' );
		$page->SetTitle( 'Προσωπικά Μηνύματα' );
		$page->AttachStylesheet( 'css/pms.css' );
		$page->AttachScript( 'js/pms.js' );
		
		if ( $user->IsAnonymous() || $user->IsBanned() ) {
			?><br />Πρέπει να εισέλθεις ή να <a href="?p=register">εγγραφείς</a> στο Chit-Chat για να δεις τα μηνύματά σου.<br /><?php
			return;
		}
		
		$inboxpms = $user->ReceivedPMs();
		$outboxpms = $user->SentPMs();
		
		$allpms = array_merge( $inboxpms, $outboxpms );
		PM_FormatMulti( $allpms );
		
		$inboxpms = array_slice( $allpms, 0, count( $inboxpms ) );
		$outboxpms = array_slice( $allpms, count( $inboxpms ) );
		
		$user->MessagesRead();
		
		static $display = array( 'inbox' => 'none', 'outbox' => 'none', 'new' => 'none' );
		
		if ( $id != '' && isset( $display[ $id ] ) ) {
			$display[ $id ] = 'block';
		}
		else {
			$display[ 'inbox' ] = 'block';
		}
		
		?><div class="pms">
			<div class="top">
				<div id="pmstitle" class="title">Εισερχόμενα</div>
				<ul>
					<li onclick="Pms.Display( 'inbox' );" id="inbox_link" class="active">Εισερχόμενα</li>
					<li onclick="Pms.Display( 'outbox' );" id="outbox_link" class="inactive">Απεσταλμένα</li>
					<li onclick="Pms.Display( 'new' );" id="new_link" class="inactive">Νέο Μήνυμα</li>
					<li>
						<a href="?p=faqc&amp;id=26" style="display: inline;">
							<img src="<?php
								echo $xc_settings[ 'staticimagesurl' ];
							?>icons/help.png" alt="Πληροφορίες για τα προσωπικά μηνύματα" style="width: 16px; height: 16px; opacity: 0.5;" onmouseover="this.style.opacity=1;g( 'commenthelp' ).style.visibility='visible';" onmouseout="this.style.opacity=0.5;g( 'commenthelp' ).style.visibility='hidden';" />
						</a>
					</li>
				</ul>
			</div>
			<div style="display: none;" id="pm_page_id"><?php
				echo $id;
			?></div>
			<div id="inbox_div" style="display: <?php
			echo $display[ 'inbox' ];
			?>">
				<div class="main">
					<div class="details"><?php
						switch( count( $inboxpms ) ) {
							case 0:
								?>Δεν έχεις κανένα μήνυμα<?php
								break;
							case 1:
								?>Έχεις 1 μήνυμα<?php
								break;
							default:
								?>Έχεις <?php 
                                echo count( $inboxpms ); 
                                ?> μηνύματα<?php
								break;
						}
					?> στα Εισερχόμενά σου.</div>
					<br /><br />
					<div class="comments" style="margin-top: 50px;"><?php
						foreach ( $inboxpms as $pm ) {
							Element( 'pm/view', $pm );
						}
					?></div>
				</div>
			</div>
			<div id="outbox_div" style="display: <?php
				echo $display[ 'outbox' ];
			?>;">
				<div class="main">
					<div class="details"><?php
						switch( count( $outboxpms ) ) {
							case 0:
								?>Δεν έχεις στείλει κανένα μήνυμα.<?php
								break;
							case 1:
								?>Έχεις στείλει 1 μήνυμα.<?php
								break;
							default:
								?>Έχεις στείλει <?php 
                                echo count( $inboxpms ); 
                                ?> μηνύματα.<?php
								break;
						}
					?></div>
					<br /><br />
					<div class="comments" style="margin-top: 50px;"><?php
						foreach ( $outboxpms as $pm ) {
							Element( 'pm/view', $pm );
						}
					?></div>
				</div>
			</div>
			<div id="new_div" style="display: <?php
				echo $display[ 'new' ];
                ?>;">
				<form action="do/pm/new" method="post" class="newpm" name="newpm">
					Προς: <input type="text" name="to" class="mytext" size="35" id="receiver" <?php
                    if ( $to != '' ) {
                        ?>value="<?php
                        echo $to;
                        ?>"<?php
                    }
					?> /><br /><br />
					<textarea cols="80" rows="15" name="text" id="pmtext"></textarea><br /><br />
					<input type="submit" value="Αποστολή" /><input type="reset" value="Επαναφορά" />
				</form>
			</div>
		</div><?php
	}
?>
