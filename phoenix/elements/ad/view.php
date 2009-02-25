<?php
    class ElementAdView extends Element {
        protected $mPersistent = array( 'type', 'xmlstrict' );

        public function Render( $type, $xmlstrict ) {
            ?><br />
            <div class="banner b728x90"><?php
            switch ( $type ) {
                case AD_JOURNAL:
                    if ( !$xmlstrict ) {
                        ?><script type="text/javascript"><!--
                        google_ad_client = "pub-6131563030489305";
                        /* 728x90, journal view */
                        google_ad_slot = "0626736805";
                        google_ad_width = 728;
                        google_ad_height = 90;
                        //-->
                        </script>
                        <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script><?php
                        break;
                    }
                    // else, fallthrough
                case AD_POLL:
                    if ( !$xmlstrict ) {
                        ?><script type="text/javascript"><!--
                        google_ad_client = "pub-6131563030489305";
                        /* 728x90, poll view */
                        google_ad_slot = "8159773384";
                        google_ad_width = 728;
                        google_ad_height = 90;
                        //-->
                        </script>
                        <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script><?php
                        break;
                    }
                    // else fallthrough
                    ?>
                    <object data="ads.php?type=<?php
                    echo $type;
                    ?>" type="text/html" style="width:733px;height:95px">
                    </object><?php
                    break;
                case AD_USERPROFILE:
                    /* ?><a href="http://www.gameplanet.gr/" style="margin:auto"><img src="http://static.zino.gr/images/ads/gameplanet-leaderboard.jpg" alt="Gameplanet" /></a><?php */
                    break;
                case AD_PHOTO:
                    ?><a href="http://www.mad.tv/madradio/"><img src="http://static.zino.gr/phoenix/banners/madradio.gif" alt="MadRadio" /></a><?php
                    break;
                default:
                    ?><div>(ad type not defined)</div><?php
            }
            ?></div><?php
        }
    }
?>
