<? if ( empty( $error ) ): ?>
<photo id="<?= $photo[ 'id' ] ?>">
    <author id="<?= $user[ 'id' ] ?>">
        <name><?= $user[ 'name' ] ?></name>
        <? if ( isset( $user[ 'gender' ] ) ): ?>
        <gender><?= $user[ 'gender' ] ?></gender>
        <? endif; ?>
    </author>
    <width><?= $photo[ 'width' ] ?></width>
    <height><?= $photo[ 'height' ] ?></height>
    <size><?= $photo[ 'filesize' ] ?></size>
    <mime><?= $photo[ 'mime' ] ?></mime>
    <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_150.jpg" />
    <containedWithin>
        <album id="<?= $albumid ?>">
            <photos count="<?= $album[ 'numphotos' ] + 1 ?>" />
        </album>
    </containedWithin>
</photo>
<? else: ?>
<error type="<?= $error ?>"></error>
<?
/*
wrongextension -> Αυτός ο τύπος εικόνας δεν υποστηρίζεται
largefile -> H φωτογραφία σου δεν πρέπει να ξεπερνάει τα 7MB
fileupload -> Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας
*/
?>
<? endif; ?>
