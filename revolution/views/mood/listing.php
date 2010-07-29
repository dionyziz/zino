<moodlist>
    <? foreach ( $moods as $mood ): ?>
        <mood id="<?= $mood[ 'id' ] ?>">
            <labelmale><?= $mood[ 'labelmale' ] ?></labelmale>
            <labelfemale><?= $mood[ 'labelfemale' ] ?></labelfemale>
            <media url="<?= $mood[ 'url' ] ?>" />
        </mood>
    <? endforeach; ?>
</moodlist>
