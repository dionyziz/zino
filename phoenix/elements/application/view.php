<?php
    class ElementApplicationView extends Element {
        public function Render( Application $app ) {
            ?><li><div class="appbubble">
                <span class="banner"><img src="<?php echo htmlspecialchars( $app->Logo ); ?>" /></span>
                <span class="name"><h2><?php echo htmlspecialchars( $app->Name ); ?></h2></span><br />
                <span class="description"><h3>Περιγραφή: </h3><?php echo htmlspecialchars( $app->Description ); ?></span><br />
                <span class="links">
                    <h3>Διεύθυνση:</h3> <a href="<?php echo htmlspecialchars( $app->Url ); ?>"><?php echo htmlspecialchars( $app->Url ); ?></a>
                </span>
                <span class="key"><h3>Κλειδί: </h3><?php echo $app->GetToken(); ?></span>
            </div></li><?php
        }
    }
?>