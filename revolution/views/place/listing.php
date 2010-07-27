<places>
    <?php foreach ( $places as $place ): ?>
        <place>
            <id><?= $place[ 'id' ] ?></id>
            <name><?= $place[ 'name' ] ?></name>
        </place>
    <?php endforeach; ?>
</places>
