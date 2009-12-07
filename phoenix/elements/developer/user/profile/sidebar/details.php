<?php
    class ElementDeveloperUserProfileSidebarDetails extends Element {
        protected $mPersistent = array( 'theuserid', 'lastupdated' );
        
        public function Render( $theuser, $theuserid, $lastupdated ) {
            global $libs;

            $libs->Load( 'store' );
            $libs->Load( 'badge' );

            $profile = $theuser->Profile;
            ?><div class="look">
				<span class="s1_0054">&nbsp;</span><?php
                Element( 'developer/user/profile/sidebar/look', $profile->Height, $profile->Weight,  $theuser->Gender );
            ?></div>
            <div class="social"><?php
                Element( 'developer/user/profile/sidebar/social/view' , $theuser );
            ?></div>
			<div class="mplayer"><?php
				Element( 'developer/user/profile/sidebar/player', $theuser );
			?></div>
			<div class="aboutme"><?php
                Element( 'developer/user/profile/sidebar/aboutme' , $profile->Aboutme );
            ?></div>
            <div class="interests"><?php
                Element( 'developer/user/profile/sidebar/interests' , $theuser );
            ?></div>
            <div class="contacts"><?php
                /*Removed by: Chorvus
                  Reason: to counter web-crawlers searching for IMs
                  Element( 'developer/user/profile/sidebar/contacts' , $profile->Skype , $profile->Msn , $profile->Gtalk , $profile->Yim ); */
            ?></div><?php
            $finder = New StorepurchaseFinder();
            $purchases = $finder->FindByUserid( $theuserid );

            if ( !empty( $purchases ) ) {
                $itemids = array();
                foreach ( $purchases as $purch ) {
                        $itemids[] = $purch->Itemid;
                }

                $badgefinder = New BadgeFinder();
                $badges = $badgefinder->FindByItemIds( $itemids );

                foreach ( $badges as $badge ) {
                    ?><div class="supporter" style="padding: 5px 0">
                    <img src="<?php echo $badge->Icon;?>" alt="badge" />
                    <?php echo $badge->Name;?>
                    </div><?php    
                } 
            }
            ?>
            <div id="reportabuse"><?php
                Element( 'developer/user/profile/sidebar/abuse', $theuser->Id );
            ?></div><?php
        }
    }
?>
