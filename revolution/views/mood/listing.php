<moodlist>
    <? foreach ( $moods as $mood ): ?>
        <mood id="<?= $mood[ 'id' ] ?>">
            <text gender="m"><?= $mood[ 'labelmale' ] ?></text>
            <text gender="f"><?= $mood[ 'labelfemale' ] ?></text>
            <media url="http://static.zino.gr/phoenix/moods/<?= $mood[ 'url' ] ?>" />
        </mood>
    <? endforeach; ?>
</moodlist>
