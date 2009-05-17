<?php
    class ElementSearchOptions extends Element {
        public function Render(
            $minage, $maxage, Place $location, $gender, $sexual, $name,
            $offset = 0, $limit = 25
        ) {
            global $page;
?>
        <h2>Αναζήτησε άτομα</h2>
        <div class="ybubble">
            <i class="tl"></i>
            <i class="tr"></i>
            <div class="body">
            <form action="" method="get" onsubmit="return Search.check()">
                <input type="hidden" name="p" value="search" />
                <div class="search">
                    <h3>Φύλο:</h3><?php
                    $genders = array(
                        'm' => 'Αγόρια',
                        'f' => 'Κοπέλες',
                        'b' => 'Και τα δύο'
                    );
                    $i = 0;
                    if ( $gender != 'm' && $gender != 'f' ) {
                        $gender = 'b';
                    }
                    foreach ( $genders as $key => $caption ) {
                        ?><input type="radio" name="gender" value="<?php
                        echo $key;
                        ?>" id="gender_<?php
                        echo $key;
                        ?>"<?php
                        if ( $gender == $key ) {
                            ?> checked="checked"<?php
                        }
                        ?> /><label for="gender_<?php
                        echo $key;
                        ?>"><?php
                        echo $caption;
                        ?></label><?php
                        ++$i;
                    }
                ?></div>
                
                <div class="search">
                    <h3>Ηλικία:</h3>
                    από: 
                    <select name="minage"><?php
                        $ages = range( 10, 80 );
                        ?><option value="any"<?php
                        if ( $minage == 0 ) {
                            ?> selected="selected"<?php
                        }
                        ?>>αδιάφορο</option><?php
                        foreach ( $ages as $age ) {
                            ?><option value="<?php
                            echo $age;
                            ?>"<?php
                            if ( $minage == $age ) {
                                ?> selected="selected"<?php
                            }
                            ?>><?php
                            echo $age;
                            ?></option><?php
                        }
                    ?></select>
                    
                    έως: 
                    <select name="maxage"><?php
                        $ages = range( 10, 80 );
                        ?><option value="any"<?php
                        if ( $maxage == 0 ) {
                            ?> selected="selected"<?php
                        }
                        ?>>αδιάφορο</option><?php
                        foreach ( $ages as $age ) {
                            ?><option value="<?php
                            echo $age;
                            ?>"<?php
                            if ( $maxage == $age ) {
                                ?> selected="selected"<?php
                            }
                            ?>><?php
                            echo $age;
                            ?></option><?php
                        }
                    ?></select>
                </div>
                
                <div class="search" style="clear:both;">
                    <h3>Περιοχή:</h3>
                    
                    <select name="placeid">
                        <option value="0" selected="selected">Από παντού</option>
                        <?php
                        $placefinder = New PlaceFinder();
                        $places = $placefinder->FindAll();
                        $locationid = $location->Id;
                        foreach ( $places as $place ) {
                            ?><option value="<?php
                            echo $place->Id;
                            ?>"<?php
                            if ( $place->Id == $locationid ) {
                                ?> selected="selected"<?php
                            }
                            ?>><?php
                            echo htmlspecialchars( $place->Name );
                            ?></option><?php
                        }
                        ?>
                    </select>
                </div>
                <div class="search">
                    <h3>Σεξουαλικές προτιμήσεις:</h3>
                    <select name="orientation"><?php
                        $orientations = array(
                            '' => 'Οτιδήποτε',
                            'straight' => 'Straight',
                            'bi' => 'Bisexual',
                            'gay' => 'Gay/Lesbian'
                        );
                        foreach ( $orientations as $key => $caption ) {
                            ?><option value="<?php
                            echo $key;
                            ?>"<?php
                            if ( $key == $sexual ) {
                                ?> selected="selected"<?php
                            }
                            ?>><?php
                            echo htmlspecialchars( $caption );
                            ?></option><?php
                        }
                    ?></select>
                </div>
                <div><div style="width:120px;margin:auto;padding:0px;"><input style="top:0px;" type="submit" value="Ψάξε!" class="submit" /></div></div>
            </form>
            </div>
            <i class="bl"></i>
            <i class="br"></i>
        </div>
		<div class="eof"></div><?php
        }
    }
?>
