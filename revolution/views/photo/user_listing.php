<user id="<?= $user[ 'id' ] ?>">
    <gender><?= $user[ 'gender' ] ?></gender>
    <name><?= $user[ 'name' ] ?></name>
    <?php
        include 'views/photo/listing.php';
    ?>
</user>
