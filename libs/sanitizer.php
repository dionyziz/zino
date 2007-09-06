<?php

    class XHTMLSanitizer {
        private $mXHTML;
        private $mSource;
        private $mAllowedTags;

        public function SetSource( $source ) {
            $this->mSource =  $source;
        }
        public function AllowTag( $sanetag ) {
            $this->mAllowedTags[] = $sanetag;
        }
        public function Sanitize() {
            $tags           = "";
            $attributes     = "";
            $allowedTags    = $this->mAllowedTags;
            while ( $tag = array_shift( $allowedTags ) ) {
                $tags .= $tag->Name();
                $allowedAttributes = $tag->AllowedAttributes();
                while ( $attribute = array_shift( $allowedAttributes ) ) {
                    $attributes .= $tag->Name() . " " . $attribute->Name();
                }
            }

            $data = $tags . "/n/n" . $attributes . "/n/n" . $this->mSource;

            $descriptorspec = array(
                0 => array( "pipe", "r" ),
                1 => array( "pipe", "w" ),
                2 => array( "tmp/error-output.txt", "a" )
            );

            $cwd = './bin/sanitizer/sanitizer';

            $proccess = proc_open( $cwd, $descriptorspec, $pipes );
            if ( !is_resource( $proccess ) ) {
                die( "Error opening sanitizer process" );
            }
            
            fwrite( $pipes[ 0 ], $data );
            fclose( $pipes[ 0 ] );

            $this->mXHTML = stream_get_contents( $pipes[ 1 ] );
            fclose( $pipes[ 1 ] );
        }
        public function GetXHTML() {
            return $this->mXHTML;
        }
        public function XHTMLSanitizer() {
        }
    }

    class XHTMLSaneTag {
        private $mName;
        private $mAllowedAttributes;

        public function Name() {
            return mName;
        }
        public function AllowAttribute( $attribute ) {
            $this->mAllowedAttributes[] = $attribute;
        }
        public function XHTMLSaneTag( $name ) {
            $this->mName = $name;
        }
    }

    class XHTMLSaneAttribute {
        public function Name() {
            return mName;
        }
        public function XHTMLSaneAttribute( $name ) {
            $this->mName = $name;
        }
    }

?>
