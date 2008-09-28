<?php
    class ElementDeveloperDionyzizSQLite extends Element {
        public function Render() {
            var_dump( function_exists( 'sqlite_open' ) );
        }
        
    }
?>
