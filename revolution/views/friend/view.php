<? if ( ( $strength & FRIENDS_A_HAS_B ) == FRIENDS_A_HAS_B ): ?>
    <user id="<?= $a[ 'id' ] ?>">
        <name><?= $a[ 'name' ] ?></name>
        <knows>
            <user id="<?= $b[ 'id' ] ?>">
                <name><?= $b[ 'name' ] ?></name>
            </user>
        </knows>
    </user>
<? endif; ?>
<? if ( ( $strength & FRIENDS_B_HAS_A ) == FRIENDS_B_HAS_A ): ?>
    <user id="<?= $b[ 'id' ] ?>">
        <name><?= $b[ 'name' ] ?></name>
        <knows>
            <user id="<?= $a[ 'id' ] ?>">
                <name><?= $a[ 'name' ] ?></name>
            </user>
        </knows>
    </user>
<? endif; ?>
