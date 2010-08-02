<album id="<?= $album[ 'id' ] ?>">
    <author>
        <name><?= $user[ 'name' ] ?></name>
    </author>
    <?php
        include 'views/photo/listing.php';
    ?>
</album>
