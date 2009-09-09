<?php
    class ElementAdminpanelHappeningsView extends Element {
        public function Render() {
            global $libs;
            $libs->Load( 'happening' );
            ?>Τρέχουσες Εκδηλώσεις:<?php
            $hapfinder = New HappeningFinder();
            $happenings = $hapfinder->FindAll();
            ?><table div="happeninglist">
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
                        $timestamp = New DateTime( $happening->Date );
                        echo htmlspecialchars( $timestamp->format( 'j-n-Y H:i' ) );
                    ?></td>
                </tr><?php
            }
            ?></table>
            <div div="participants" class="column">
                <h3>Αθήνα 25/6: Σύνταγμα</h3>
                <ul>
                </ul>
            </div>
            <?php
        }
    }
?>