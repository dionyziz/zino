<?php
    class ElementAdView extends Element {
        public function Render( $type ) {
            switch ( $type ) {
                case AD_JOURNAL:
                    ?><script type="text/javascript"><!--
                    google_ad_client = "pub-6131563030489305";
                    /* 728x90, created 8/14/08 */
                    google_ad_slot = "5223999939";
                    google_ad_width = 728;
                    google_ad_height = 90;
                    //-->
                    </script>
                    <script type="text/javascript"
                    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                    </script><?php
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
            }
        }
    }
?>
