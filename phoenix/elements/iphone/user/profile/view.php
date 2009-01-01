<?php
    class ElementiPhoneUserProfileView extends Element {
        public function Render( tText $subdomain ) {
            global $user;
            global $xc_settings;
            global $user;
            global $libs;

            $libs->Load( 'user/statusbox' );

            $subdomain = $subdomain->Get();
            $finder = New UserFinder();
            $theuser = $finder->FindBySubdomain( $subdomain );

            if ( !$theuser->Exists() ) {
                return;
            }

            $finder = New StatusBoxFinder();
            $tweet = $finder->FindLastByUserId( $theuser->Id );
 
            ?><div class="profile"><?php
            Element( 'user/avatar', $theuser->Avatar->Id, $theuser->Id,
                     $theuser->Avatar->Width, $theuser->Avatar->Height,
                     $theuser->Name, 100, 'avatar', '', true, 50, 50 );
            ?><h2><?php
            echo $theuser->Name;
            ?></h2>
            <span class="subtitle"><?php
            echo htmlspecialchars( $theuser->Profile->Slogan );
            ?></span><?php
            if ( $tweet !== false ) {
                ?><div class="tweet"><?php
                if ( $theuser->Gender == 'f' ) {
                    ?>Η <?php
                }
                else {
                    ?>Ο <?php
                }
                echo htmlspecialchars( $theuser->Name );
                ?> <?php
                echo htmlspecialchars( $tweet->Message );
                ?></div><?php
            }
            ?><div class="eof"></div><?php
            $schoolexists = $theuser->Profile->School->Numstudents > 2;
            Element( 'user/profile/sidebar/info', $theuser, $schoolexists );
            ?><div class="details"><img src="<?php
            echo $xc_settings[ 'imagesurl' ];
            ?>body-male-slim-short.jpg" alt="" /><?php
            Element( 'user/profile/sidebar/look', $theuser->Profile->Height, $theuser->Profile->Weight, $theuser->Gender );
            ?></div><?php
            Element( 'user/profile/sidebar/social/view', $theuser );
            ?></div><?php
        }
    }
?>
