<?php

    function ElementBennu( tInteger $age, tString $gender ) {
        global $user;
        global $libs;

        $prefage = $age->Get();
        $prefgender = strtolower( $gender->Get() );

        if ( $prefage < 1 || $prefage > 100 ) {
            $prefage = $user->Age();
        }

        if ( $prefgender != 'male' && $prefgender != 'female' && $prefgender != 'both' ) {
            $prefgender = ( $user->Gender() == 'male' ) ? 'female' : 'male';
        }

        $libs->Load( "bennu" );
        $bennu = New Bennu();
        
        $age = New BennuRuleAge();
        $age->Value = $prefage;
        $age->Score = 10;
        $age->Sigma = 2;

        if ( $prefgender != 'both' ) {
            $sex = New BennuRuleSex();
            $sex->Value = $prefgender;
            $sex->Score = 5;
        }

        $bennu->AddRule( $age );
        $bennu->AddRule( $sex );

        $bennu->Exclude( $user );
        $users = $bennu->Get( 20 );

        ?><h2>Friend Recommendations</h2>
        <h4>Powered by Bennu</h4>

        <form action="" method="get" style="background-color: #F8FBE2; padding: 5px;">
            <input type="hidden" name="p" value="bennu" />
            Preferred Age: 
            <select name="age"><?php
                for ( $i = 5; $i < 80; ++$i ) {
                    ?><option<?php
                    if ( $i == $prefage ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    echo $i;
                    ?></option><?php
                }
            ?></select><br />
            Preferred Gender:
            <select name="gender"><?php
                $genders = array( 'male', 'female', 'both' );

                for ( $i = 0; $i < count( $genders ); ++$i ) {
                    ?><option<?php
                        if ( $prefgender == $genders[ $i ] ) {
                            ?> selected="selected"<?php
                        } 
                        ?>><?php 
                        echo $genders[ $i ]; 
                    ?></option><?php
                }

            ?></select><br />
            <input type="submit" value="submit" /><br />
        </form>

        <ul style="list-style-type: none;"><?php
        foreach ( $users as $buser ) {
            ?><li><?php
                Element( "user/static", $buser );
            ?></li><?php
        }

        ?></ul><?php
    }

?>
