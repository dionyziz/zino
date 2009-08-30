<?php
    class ElementUserRelationsRow extends Element {
        public function Render( $relation, $isfriend ) {
            ?><li id="user_<?php
                echo $relation->Friend->Id;
                ?>"><a href="<?php
                    Element( 'url', $relation->Friend );
                ?>">
                <?php
                Element( 'user/avatar', $relation->Friend->Avatarid, $relation->Friend->Id, 50, 50, $relation->Friend->Name, 100, '', '', true, 50, 50 );
                echo htmlspecialchars( $relation->Friend->Name );
                ?>
                </a>
                    <?php
                    if ( !$isfriend ) {
                        ?><a class="add" href="">+
                         <span>Γίνε φίλος<?php
                    }
                    else {
                        ?><a class="remove" href="">-
                        <span>Διαγραφή φίλου<?php
                    }
                    ?>
                        <i class="tr corner"></i>
                        <i class="tl corner"></i>
                        <i class="br corner"></i>
                        <i class="bl corner"></i>
                    </span>
                </a>
                <?php
                    if ( $relation->Friend->Gender == 'f' ) {
                        $datalist[] = "Κορίτσι";
                    }
                    else {
                        $datalist[] = "Αγόρι";
                    }
                    if ( $relation->Friend->Profile->Age ) {
                        $datalist[] = $relation->Friend->Profile->Age;
                    }
                    if ( $relation->Friend->Profile->Placeid > 0 ) {
                        $datalist[] = htmlspecialchars(  $relation->Friend->Profile->Location->Name );
                    }
                    if ( !empty( $datalist ) ) {
                        ?><span><?php
                        while ( $data = array_shift( $datalist ) ) {
                            echo $data;
                            if ( !empty( $datalist ) ) {
                                ?><span> · </span><?php
                            }
                        }
                        ?></span><?php
                    }
                ?><div class="barfade">
                    <div class="leftbar"></div>
                    <div class="rightbar"></div>
                </div><?php
                if ( $isfriend ) {
                    ?><span class="already">φίλος</span><?php
                }
                ?>
            </li><?php
        }
    }
?>