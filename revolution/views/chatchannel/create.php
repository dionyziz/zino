<chatchannel id="<?= $channel[ 'id' ] ?>">
    <? foreach ( $participants as $participant ): ?>
    <user id="<?= $participant[ 'id' ] ?>" />
    <? endforeach; ?>
</chatchannel>
