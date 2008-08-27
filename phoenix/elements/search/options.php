<?php
    class ElementSearchOptions extends Element {
        public function Render(
            $minage, $maxage, Place $location, $gender, $sexual, $name,
            $offset = 0, $limit = 25
        ) {
            global $page;

            $page->AttachStylesheet( 'css/search.css' );
            if ( UserBrowser() == "MSIE" ) {
                $page->AttachStylesheet( 'css/search-ie.css' );
            }
?>
        <h2>Αναζήτηση ατόμων</h2>
        <div class="ybubble">
            <i class="tl"></i>
            <i class="tr"></i>
            <div class="body">
            <form action="" method="get">
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
                        ?><option value="any">αδιάφορο</option><?php
                        foreach ( $ages as $age ) {
                            ?><option value="<?php
                            echo $age;
                            ?>"><?php
                            echo $age;
                            ?></option><?php
                        }
                    ?></select>
                    
                    έως: 
                    <select name="maxage"><?php
                        $ages = range( 10, 80 );
                        ?><option value="any">αδιάφορο</option><?php
                        foreach ( $ages as $age ) {
                            ?><option value="<?php
                            echo $age;
                            ?>"><?php
                            echo $age;
                            ?></option><?php
                        }
                    ?></select>
                </div>
                
                <div class="search">
                    <h3>Περιοχή:</h3>
                    
                    <select name="location">
                        <option value="0" selected="selected">Από παντού</option>
                        <?php
                        $placefinder = New PlaceFinder();
                        $places = $placefinder->FindAll();
                        foreach ( $places as $place ) {
                            ?><option value="<?php
                            echo $place->Id;
                            ?>"><?php
                            echo htmlspecialchars( $place->Name );
                            ?></option><?php
                        }
                        ?>
                    </select>
                </div>

                <div class="search">
                    <h3>Σεξουαλικές προτιμήσεις:</h3>
                    
                    <select name="orientation">
                        <option value="0">Οτιδήποτε</option>
                        <option value="straight">Straight</option>
                        <option value="bi">Bisexual</option>
                        <option value="gay">Gay/Lesbian</option>
                    </select>
                </div>
                
                <div><input type="submit" value="Ψάξε!" class="submit" /></div>
            </form>
            </div>
            <i class="bl"></i>
            <i class="br"></i>
        </div><?php
        }
    }
?>
