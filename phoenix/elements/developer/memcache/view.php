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
            <br /><br /><form action="?p=testmc" method="get">
                Memcache key: <input type="text" name="key" value="<?php
                echo htmlspecialchars( $key );
                ?>" />
                <input type="submit" value="Check" />
            </form><?php

            $value = $mc->get( $key );
            ?><br /><br /><br /><?php
            echo var_dump( $value );
            ?><br /><br />---------------<br /><br /><?php
            print_r( $value );


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
