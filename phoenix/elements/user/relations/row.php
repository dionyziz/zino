<?php
    class ElementUserRelationsRow extends Element {
        public function Render( $friendarray, $isfriend ) {
            global $user;
            global $libs;
            
            w_assert( is_bool( $isfriend ) );
            w_assert( is_array( $friendarray ) );
            w_assert( is_int( $friendarray[ 'user_id' ] ) );
            w_assert( $friendarray[ 'user_id' ] > 0 );
            w_assert( is_int( $friendarray[ 'user_avatarid' ] ) );
            
            $libs->Load( 'image/image' ); // user->Avatar

            ?><li id="user_<?php
                echo $friendarray[ 'user_id' ];
                ?>"><?php
                if ( $friendarray[ 'user_id' ] != $user->Id && $user->Id != 0 ) {
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
                }
                ?><div class="who"><?php
                ?><a href="<?php
                ob_start();
                Element( 'user/url', $friendarray[ 'user_id' ], $friendarray[ 'user_subdomain' ] );
                echo htmlspecialchars( ob_get_clean() );
                ?>"><?php
                Element( 'user/avatar' , $friendarray[ 'user_avatarid' ], $friendarray[ 'user_id' ], 100, 100, $friendarray[ 'user_name' ], 100 , 'avatar' , '' , true , 50 , 50 );
                Element( 'user/name' , $friendarray[ 'user_id' ], $friendarray[ 'user_name' ], $friendarray[ 'user_subdomain' ], false );
                ?></a><?php
                ?></div>
                <?php
                    if ( $friendarray[ 'user_gender' ] == 'f' ) {
                        $datalist[] = "Κορίτσι";
                    }
                    elseif ( $friendarray[ 'user_gender' ] == 'm') {
                        $datalist[] = "Αγόρι";
                    }
                    if ( $friendarray[ 'profile_dob' ] != '0000-00-00' ) {
                        $dob = Profile_Dob2Age( $friendarray[ 'profile_dob' ] );
                        if ( $dob !== false ) {
                            $datalist[] = $dob;
                        }
                    }
                    if ( !empty( $friendarray[ 'place_name' ] ) ) {
                        $datalist[] = $friendarray[ 'place_name' ];
                    }
                    if ( !empty( $datalist ) ) {
                        ?><span><?php
                        while ( $data = array_shift( $datalist ) ) {
                            echo htmlspecialchars( $data );
                            if ( !empty( $datalist ) ) {
                                ?><span> · </span><?php
                            }
                        }
                        ?></span><?php
                    }
                ?><span class="lastactive"><?php
                    if ( $friendarray[ 'user_gender' ] == 'f' ) {
                        echo "Ενεργή: ";
                    }
                    else {
                        echo "Ενεργός: ";
                    }
                    Element( 'date/diff', $friendarray[ 'lastactive_created' ] );
                ?></span>
                <div class="barfade">
                    <div class="leftbar"></div>
                    <div class="rightbar"></div>
                </div><?php
                if ( $isfriend && $user->Id != 0 ) {
                    ?><span class="already">φίλος</span><?php
                }
                ?>
            </li><?php
        }
    }
?>