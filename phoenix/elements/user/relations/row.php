<?php
    class ElementUserRelationsRow extends Element {
        public function Render( $relation, $isfriend ) {
            ?><li id="user_<?php
                echo $relation->Friend->Id;
                ?>">
                <?php
                    Element( 'user/display', $relation->Friend->Id, $relation->Friend->Avatarid, $user, true );
                    echo htmlspecialchars( $relation->Friend->Name );
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