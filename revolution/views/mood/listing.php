<moodlist>
    <? foreach ( $moods as $mood ): ?>
        <mood id="<?= $mood[ 'id' ] ?>">
            <text gender="m"><?= $mood[ 'labelmale' ] ?></labelmale>
            <text gender="f"><?= $mood[ 'labelfemale' ] ?></labelfemale>
            <media url="http://static.zino.gr/phoenix/moods/<?= $mood[ 'url' ] ?>" />
        </mood>
    <? endforeach; ?>
</moodlist>
