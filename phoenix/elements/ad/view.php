<?php
    class ElementAdView extends Element {
        public function Render( $type ) {
            switch ( $type ) {
                case AD_JOURNAL:
                    ?><object data="ads.php?type=<?php
                    echo $type;
                    ?>" type="text/html">
                    </object><?php
                    break;
                case AD_USERPROFILE:
                    ?>
                    <br />
                    <div style="text-align:center">
                        <a href="http://www.gameplanet.gr/" style="margin:auto"><img src="http://static.zino.gr/images/ads/gameplanet-leaderboard.jpg" alt="Gameplanet" /></a>
                    </div><br />
                    <?php
                    break;
                case AD_PHOTO:
                    ?><div class="banner b728x90">
                        <a href="http://www.mad.tv/madradio/"><img src="http://static.zino.gr/phoenix/banners/madradio.gif" /></a>
                    </div><?php
                    break;
                default:
                    ?><div>(ad type not defined)</div><?php
            }
        }
    }
?>
