<photo>
    <id><?= $photo[ 'id' ] ?></id>
    <typeid><?= $typeid ?></typeid>
    <owner id="<?= $user[ 'id' ] ?>">
        <name><?= $user[ 'name' ] ?></name>
    </owner>
    <width><?= $photo[ 'width' ] ?></width>
    <height><?= $photo[ 'height' ] ?></height>
    <size><?= $photo[ 'filesize' ] ?></size>
    <mime><?= $photo[ 'mime' ] ?></mime>
    <media url="http://images2.zino.gr/media/<?= $photo[ 'userid' ] ?>/<?= $settings[ 'beta' ]? '_': '' ?><?= $photo[ 'id' ] ?>/<?= $photo[ 'id' ] ?>_150.jpg" />
    <album id="<?= $albumid ?>">
        <name></name>
        <photocount><?= $album[ 'numphotos' ] + 1 ?></photocount>
    </album>
</photo>
