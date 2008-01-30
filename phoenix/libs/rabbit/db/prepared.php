<?php
    class DBQuery {
        protected $mRawSQL; // doesn't contain binded arguments
        protected $mBindings;
        protected $mDatabase;
        protected $mDriver;
        
        public function DBQuery( $raw, Database $database, DBDriver $driver ) {
            w_assert( is_string( $raw ), 'Cannot prepare SQL query with a non-string SQL statement' );
            w_assert( !empty( $raw ), 'Cannot prepare SQL query with an empty SQL statement' );
            
            $this->mRawSQL = $raw;
            $this->mDatabase = $database;
            $this->mBindings = array();
            $this->mTableBindings = array();
            $this->mDriver = $driver;
            $this->mTypeBindings = $this->TypeBindings();
        }
        private function Escape( $argument ) {
            switch ( gettype( $argument ) ) {
                case 'boolean':
                    return ( int )$argument;
                case 'integer':
                case 'double':
                    return $argument;
                case 'array':
                    return '(' . implode( ',', array_map( array( $this, 'Escape' ), $argument) ) . ')'; // RECURSE!
                default:
                    return "'" . addslashes( ( string )$argument ) . "'";
            }
        }
        public function Bind( $name, $argument ) {
            $this->mBindings[ ':' . ( string )$name ] = $this->Escape( $argument );
        }
        public function BindTable( /* $alias1, $alias2, ... */ ) {
            global $water;
            
            $args = func_get_args();
            w_assert( count( $args ), 'Binding a table requires at least one argument containing a table alias' );
            
            foreach ( $args as $alias ) {
                w_assert( is_string( $alias ), 'Database table aliases must be strings' );
                w_assert( strlen( $alias ), 'Database table aliases cannot be the empty string' );
                $table = $this->mDatabase->TableByAlias( $alias );
                if ( $table === false ) {
                    throw New DBException( 'Could not bind database table `' . $alias . '`' );
                }
                $this->mTableBindings[ ':' . $alias ] = '`' . $table->Name . '`';
            }
        }
        private function TypeBindings() {
            $driverTypes = $this->mDriver->DataTypes();
            $typeBindings = array();

            foreach ( $driverTypes as $constant => $type ) {
                $typeBindings[ ':_' . $constant ] = $type;
            }

            return $typeBindings;
        }
        public function Apply() {
            w_assert( !empty( $this->mRawSQL ), 'Cannot apply bindings to an empty SQL statement' );
            
            return strtr( $this->mRawSQL, array_merge( $this->mBindings, $this->mTypeBindings, $this->mTableBindings ) );
        }
        public function Execute() {
            $applied = $this->Apply();
            w_assert( !empty( $applied ), 'Cannot execute empty SQL query' );
            
            return $this->mDatabase->Query( $applied );
        }
    }
?>
