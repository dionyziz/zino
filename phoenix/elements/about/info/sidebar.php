<?php
    class ElementAboutInfoSidebar extends Element {
        public function Render( $selectedsection, Array $sections ) {
            ?><div id="aboutmenu">
                <h3>To Zino</h3>
                <ol><?php
                    foreach ( $sections as $section => $caption ) {
                        ?><li class="<?php
                        echo htmlspecialchars( $section );
                        if ( $selectedsection == $section ) {
                            ?> selected<?php
                        }
                        ?>"><a href="about/<?php
                        echo htmlspecialchars( $section );
                        ?>"><?php
                        echo htmlspecialchars( $caption );
                        ?></a></li><?php
                    }
                    ?>
                </ol>
            </div><?php
        }
    }
?>
