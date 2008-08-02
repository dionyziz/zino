<?php

    function UnitFrontpageWelcomeoptions( tInteger $place , tText $education , tInteger $school ) {
        global $user;
        
        if ( $user->Exists() ) {
            $place = $place->Get();
            $education = $education->Get();
            $school = $school->Get();
            
            if ( $place ) {
                if ( $place == -1 ) {
                    $placeid = 0;
                }
                else {
                    $newplace = New Place( $place );
                    if ( $newplace->Exists() ) {
                        $placeid = $newplace->Id;
                    }
                }                
                $user->Profile->Placeid = $placeid;
            }
            if ( $education ) {
                $user->Profile->Education = $education;
            }
            if ( $school ) {
                if ( $school == -1 ) {
                    $schoolid = 0;
                }
                else {
                    $newschool = New School( $school );
                    if ( $newschool->Exists() ) {
                        $schoolid = $newschool->Id;
                    }
                }
                $user->Profile->Schoolid = $schoolid;
            }
            $user->Save();
            $user->Profile->Save();
            ?>$( 'div.frontpage div.ybubble div.body div.saving' ).animate( { opacity : "0" } , 200 , function() {
                $( 'div.frontpage div.ybubble div.body div.saving' ).addClass( 'invisible' ).css( "opacity" , "1" );
                $( 'div.frontpage div.ybubble div.body div.saved' )
                    .css( "opacity" , "0" )
                    .removeClass( "invisible" )
                    .animate( { opacity : "1" } , 2000 , function() {
                        $( 'div.frontpage div.ybubble div.body div.saved' ).addClass( 'invisible' );
                } );
            } );<?php
            static $edumap = array(
                'elementary' => 1,
                'gymnasium' => 2,
                'TEE' => 3,
                'lyceum' => 4,
                'TEI' => 5,
                'university' => 6
            );
            $typeid = $edumap[ $user->Profile->Education ];
            $showschool = isset( $typeid ) && $user->Profile->Placeid > 0;
            if ( $showschool ) {
                if ( $place || $education ) {
                    ?>$( '#selectuni' ).html( <?php
                        ob_start();
                        ?><span>Πανεπιστήμιο</span><?php
                        Element( 'user/settings/personal/school', $user->Profile->Placeid, $typeid );
                        echo w_json_encode( ob_get_clean() );
                    ?> );
                    $( '#selectuni select' ).change( function() {
                        var school = $( '#selectuni select' )[ 0 ].value;
                        $( 'div.frontpage div.ybubble div.body div.saving' ).removeClass( 'invisible' );
                        Coala.Warm( 'frontpage/welcomeoptions' , { school : school } );
                    });
                    if ( $( '#selectuni' ).hasClass( 'invisible' ) ) {
                        $( '#selectuni' ).css( "opacity" , "0" ).removeClass( "invisible" ).animate( { opacity : "1" } , 200 );
                    }<?php
                }
            }
            else {
                if ( $place || $education ) {
                    ?>if ( !$( '#selectuni' ).hasClass( 'invisible' ) ) {
                        $( '#selectuni' ).animate( { opacity : "0" } , 200 , function() {
                            $( this ).addClass( "invisible" );
                        } );
                    }<?php
                }
            }
            
            
            
        }
    }
?>
