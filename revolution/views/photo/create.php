<? if ( empty( $error ) ): ?>
<photo id="<?= $photo[ 'id' ] ?>">
    <owner id="<?= $user[ 'id' ] ?>">
        <name><?= $user[ 'name' ] ?></name>
    </owner>
    <width><?= $photo[ 'width' ] ?></width>
    <height><?= $photo[ 'height' ] ?></height>
    <size><?= $photo[ 'filesize' ] ?></size>
    <mime><?= $photo[ 'mime' ] ?></mime>
    <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $settings[ 'beta' ]? '_': '' ?><?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_150.jpg" />
    <album id="<?= $albumid ?>">
        <name></name>
        <photocount><?= $album[ 'numphotos' ] + 1 ?></photocount>
    </album>
</photo>
<? else: ?>
<error type="<?= $error ?>"></error>
<?
/*
wrongextension -> Αυτός ο τύπος εικόνας δεν υποστηρίζεται
largefile -> H φωτογραφία σου δεν πρέπει να ξεπερνάει τα 4MB
fileupload -> Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας
*/
?>
<? endif; ?>
