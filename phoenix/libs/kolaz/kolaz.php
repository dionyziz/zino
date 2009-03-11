<?php
    class KolazCreator {
        public $mPositions = Array();
        public $ambiguous = 0;
        private $mPath; //'/var/www/zino.gr/beta/phoenix/libs/kolaz/a.out';  
        
        public function Add( $id, $xpos, $ypos ) {
            $this->mPositions[ $id ] = array( "xpos" => $xpos, "ypos" => $ypos );
            echo "added " . $id . " " . $this->mPositions[ $id ][ 'xpos' ] . " " . $this->mPositions[ $id ][ 'ypos' ];
            return;
        }          

        public function RetrievePositions( $images ) {
            global $rabbit_settings;
            $this->mPath = $rabbit_settings[ 'rootdir' ] . '/libs/kolaz/';
            /* Pipe */
            $descriptorspec = array(
               0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
               1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
               2 => array("pipe", "w") // stderr is a pipe that the child will write to
            );
            
            $process = proc_open( $this->mPath . './a.out' , $descriptorspec, $pipes);
            
            if (!is_resource($process)) {
                die ("Can't execute " . $this->mPath ."!");
            }

            $n = count( $images );
            fwrite($pipes[0], "$n\n");
            foreach ( $images as $img ) {
                fwrite($pipes[0], ($img["id"] . " " . $img["width"] . " " . $img["height"] . '\n' ));    
            }
            fclose($pipes[0]);        // 0 => stdin
            
            $allpositions = stream_get_contents($pipes[1]);
            fclose($pipes[1]);        // 1 => stdout
    
            $errors = stream_get_contents($pipes[2]);
            fclose($pipes[2]);        // 2 => stderr
            
            // It is important that you close any pipes before calling proc_close in order to avoid a deadlock
            $return_value = proc_close($process);
            
            /* Exceptions */
            //die ( "$return_value: $allcontacts \n$errors" );
            if ( $return_value == 1 ) { // input failure
                return "problem#1" . $errors;
            }
            
            if ( $return_value != 0 ) { // unknown error
                //die ( "Error: $allcontacts \n$errors" );
                return "problem # 2" . $errors;
            }

            /* Parsing */
            
            $pieces = explode("\n", $allpositions);
            echo "output : ";
            foreach ( $pieces as $piece ) {
                $columns = explode("\t", $piece);
                $id = $columns[0];
                $xpos = $columns[1];
                $ypos = $columns[2];

                $this->Add( $id, $xpos, $ypos );

            }

            return true;
        }
    }
?>
