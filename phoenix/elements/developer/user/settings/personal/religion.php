<?php

    class ElementDeveloperUserSettingsPersonalReligion extends Element {
        public function Render( $selected , $gender ) {
            ?><select><?php
                $religions = array( '-' , 'christian' , 'muslim' , 'atheist' , 'agnostic' , 'nothing', 'pastafarian',
                                    'pagan', 'budhist', 'greekpolytheism', 'hindu' );
                foreach ( $religions as $religion ) {
                    ?><option value="<?php
                    echo $religion;
                    ?>"<?php
                    if ( $selected == $religion ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'developer/user/trivial/religion' , $religion , $gender );
                    ?></option><?php
                }
                ?>
            </select><?php
        }
    }
?>
