<?php
    class ElementMain extends Element {
        public function Render() {
            global $user;
            global $water;
            global $page;
            global $libs;
            global $rabbit_settings;
            global $xc_settings;
            
            ?>
            <!--
                    Σε ενδιαφέρει ο κώδικάς μας; Τότε ίσως θα ήθελες να δουλέψεις μαζί μας. Τσέκαρε στο http://www.kamibu.com/join
                    και μην διστάσεις να επικοινωνήσεις μαζί μας.
            -->
            <?php
            $page->AddMeta( 'author', 'Kamibu Development Team' );
            $page->AddKeyword( array( 'greek', 'friends', 'chat', 'community', 'greece', 'meet', 'people' ) );
            $page->AddMeta( 'description', 'Το Zino είναι η παρέα σου online - είσαι μέσα;' );
            
            $attachglobals = true;
            
            ob_start();
            ?>if ( typeof ExcaliburSettings == 'undefined' ) {
                ExcaliburSettings = {};
            }
            ExcaliburSettings.webaddress = '<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>';<?php
            if ( $rabbit_settings[ 'production' ] ) {
                ?>ExcaliburSettings.Production = true;<?php
            }
            else {
                ?>ExcaliburSettings.Production = false;<?php
            }
            $page->AttachInlineScript( ob_get_clean() );
            
            ob_start();
            $info = Element::MasterElement();
            $res = $info[ 0 ];
            $masterelement = $info[ 1 ];
            $master = ob_get_clean();
            
            if ( $res === false ) { //If the page requested is not in the pages available
                ?><div id="upstrip"><?php
                    Element( 'banner' );
                ?></div>
               
                <div id="midstrip">
                    <div id="strip1">
                        <div id="strip1left" class="s1_0013">
                        </div>
                        <div id="strip1right" class="s1_0014">
                        </div>
                    </div>
                    <div id="strip2" class="sx_0010">
                        <div id="content"><?php
                            Element( '404' );
                        ?></div> 
                    </div>
                    <div id="strip3">
                        <div id="strip3left" class="s1_0015">
                        </div>
                        <div id="strip3right" class="s1_0016">
                        </div>
                        <div id="strip3middle">
                        </div>
                    </div>
                </div>
                
                <div id="downstrip"><?php
                    Element( 'footer' );
                ?></div><?php
            }
            else {
                if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                    ?><div id="upstrip"><?php
                        Element( 'banner' );
                    ?></div>
                    <div id="midstrip">
                        <div id="strip1">
                            <div id="strip1left" class="s1_0013">
                            </div>
                            <div id="strip1right" class="s1_0014">
                            </div>
                        </div>

                        <div id="strip2" class="sx_0010"><?php
                }

                if ( !is_array( $res ) || !isset( $res[ 'selfmanaged' ] ) ) {
                    ?><div id="content"><?php
                }
                echo $master;
                if ( !is_array( $res ) || !isset( $res[ 'selfmanaged' ] ) ) {
                    ?></div><?php
                }
                 
                if ( !is_array( $res ) || !isset( $res[ 'tiny' ] ) ) {
                    ?></div> 
                    <div id="strip3">
                        <div id="strip3left" class="s1_0015">
                        </div>
                        <div id="strip3right" class="s1_0016">
                        </div>
                        <div id="strip3middle" class="sx_0003">
                        </div>
                    </div>
                    </div>    
                    <div id="downstrip" class="sx_0002" style="position:relative"><?php
                        Element( 'footer' );
                    ?></div>
					<iframe src="http://presence.zino.gr:8124" style="width: 0; height: 0;"></iframe>
                    <?php
                }
                
                if ( is_array( $res ) && isset( $res[ 'tiny' ] ) ) {
                    $attachglobals = false;
                }
            }
            
            if ( $attachglobals ) {
                // attaching ALL css files
                if ( $rabbit_settings[ 'production' ] ) {
                    $page->AttachStylesheet( $xc_settings[ 'staticcssurl' ] . 'global.css?' . $xc_settings[ 'cssversion' ] );
                }
                else {
                    $page->AttachStylesheet( $xc_settings[ 'staticcssurl' ] . 'global-beta.css?' . $xc_settings[ 'cssversion' ] );
                }
                if ( UserBrowser() == "MSIE" ) {
                    $page->AttachStylesheet( 'css/ie.css' );
                }
                //start javascript attaching
                $page->AttachScript( 'http://www.google-analytics.com/urchin.js', $language = 'javascript', false, '', 9 );
                $page->AttachInlineScript( "ExcaliburSettings.webaddress = '" . $rabbit_settings[ 'webaddress' ] . "';" );
                if ( $rabbit_settings[ 'production' ] ) {
                    $globaljs = $xc_settings[ 'staticjsurl' ] . 'global.js?' . $xc_settings[ 'jsversion' ];
                }
                else {
                    $globaljs = $xc_settings[ 'staticjsurl' ] . 'global-beta.js?' . $xc_settings[ 'jsversion' ];
                }
                $page->AttachScript( $globaljs, $language = 'javascript', false, '', 10 );
            }
            
            if ( !$page->TitleFinal() ) {
                if ( $page->Title() != '' ) { // If the title's page is not blank
                    $page->SetTitle( $page->Title() . ' | ' . $rabbit_settings[ 'applicationname' ] );
                }
                else {
                    $water->Notice( 'Title not defined for page' );
                    $page->SetTitle( $rabbit_settings[ 'applicationname' ] );
                }
            }

            Element( 'statistics/log', $masterelement );
            $libs->Load( 'memoryusage' );//<collecting memory usage information
            CheckMemoryUsage();

            // pass
            return $res;
        }
    }
?>
