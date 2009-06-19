<?php
    class ElementAdminpanelHappeningsView extends Element {
        public function Render() {
            global $libs;
            $libs->Load( 'happening' );
            ?>Τρέχουσες Εκδηλώσεις:<?php
            $hapfinder = New HappeningFinder();
            $happenigns = $hapfinder->FindAll();
            ?><table class="happeninglist">
                <tr>
                    <th>ID</th>
                    <th>Όνομα</th>
                    <th>Τοποθεσία</th>
                    <th>Hμ/Ώρα</th>
                </tr>
            <?php
            foreach ( $happenings as $happening ) {
                ?><tr>
                    <td><?php echo $happening->Id; ?></td>
                    <td><?php echo htmlspecialchars( $happening->Title ); ?></td>
                    <td><?php echo htmlspecialchars( $happening->Place->Name ); ?></td>
                    <td><?php
                        $timestamp = new DateTime( $happening->Date );
                        echo htmlspecialchars( $timestamp->format( 'j/n/Y H:i:s' ) );
                    ?></td>
                </tr><?php
            }
            ?></table><?php
        }
    }
?>