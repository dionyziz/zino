<?php
    class ElementiPhoneBanner extends Element {
        public function Render() {
            global $rabbit_settings;
            global $xc_settings;
            global $user;

            ?><div class="banner"><?php
                if ( $user->Exists() ) {
                    ?><a href="<?php
                    echo $xc_settings[ 'iphoneurl' ];
                    ?>?p=user&subdomain=<?php
                    echo $user->Subdomain;
                    ?>"><?php
                    Element( 'user/avatar', $user->Avatar->Id, $user->Id,
                             $user->Avatar->Width, $user->Avatar->Height,
                             $user->Name, 100, 'avatar', '', true, 32, 32 );
                    ?></a><?php
                }
                ?><img class="logo" src="<?php
                echo $rabbit_settings[ 'imagesurl' ];
                ?>iphone/zino.png" width="70" height="25" />
            </div><?php
        }
    }
?>
