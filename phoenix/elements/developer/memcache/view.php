<?php
    class ElementDeveloperMemcacheView extends Element {
        public function Render( tText $key ) {
            global $mc;
            global $user;

            if ( !$user->HasPermission( PERMISSION_MEMCACHE_VIEW ) ) {
                ?>Access denied<?php
                return;
            }

            $key = $key->Get();

            ?>
            <br /><br /><form method="get">
                <input type="hidden" name="p" value="mc" />
                Memcache key: <input type="text" name="key" value="<?php
                echo htmlspecialchars( $key );
                ?>" />
                <input type="submit" value="Check" />
            </form><?php

            $value = $mc->get( $key );
            ?><br /><br /><br /><?php
            ob_start();
            echo var_dump( $value );
            echo htmlspecialchars( ob_get_clean() );
            ?><br /><br />---------------<br /><br /><?php
            ob_start();
            print_r( $value );
            echo htmlspecialchars( ob_get_clean() );

            ?><form action="do/memcache/delete" method="post">
                <input type="hidden" name="key" value="<?php
                echo htmlspecialchars( $key );
                ?>" />
                <input type="submit" value="Delete this key" />
            </form>
            
            <?php
        }
    }
?>
