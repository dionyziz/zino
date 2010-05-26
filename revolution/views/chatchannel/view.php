<chatchannel id="<?= $channel[ 'id' ] ?>">
    <? foreach ( $participants as $participant ): ?>
    <user id="<?= $participant[ 'id' ] ?>">
        <name><?= $participant[ 'name' ] ?></name>
    </user>
    <? endforeach; ?>
</chatchannel>
