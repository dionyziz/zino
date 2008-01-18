<?php
    abstract class Scaffolder {
        protected $mItemName;
    }
    
    class LibraryScaffolder extends Scaffolder {
        public function Scaffold() {
            ?>
            class Foo extends Satori {
                public function <?php
                    echo $this->mItemName;
                    ?>( $construct ) {
                    $this->SetDb();
                    $this->SetTable();
                    $this->SetFields( array() );
                    $this->Satori( $construct );
                }
            }
            <?php
        }
    }
    
    class ElementScaffolder extends Scaffodler {
        public function Scaffold() {
            ?>
            function Element<?php
                echo $this->mItemName;
                ?>() {
                return;
            }
            <?php
        }
    }
?>
