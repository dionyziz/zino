<friends of="<?= $subdomain ?>" count="<?= count( $friends ); ?>">
    <? global $settings;
    foreach ( $friends as $friend ): ?>
    <friend id="<?= $friend[ 'id' ] ?>">
        <name><?= $friend[ 'name' ] ?></name>
            <subdomain><?= $friend[ 'subdomain' ] ?></subdomain>
        <?php if ( $friend[ 'age' ] > 0 ): ?>
        <age><?= $friend[ 'age' ] ?></age>
        <?php endif; ?>
        <avatar id="<?= $friend[ 'avatarid' ] ?>">
        <?php if ( $friend[ 'avatarid' ] > 0 ): ?>
            <media url="http://images2.zino.gr/media/<?= $friend[ 'id' ] ?>/<?= $friend[ 'avatarid' ] ?>/<?= $friend[ 'avatarid' ] ?>_150.jpg" />
        <?php endif; ?>
        </avatar>
        <?php if ( $friend[ 'placeid' ] > 0 ): ?>
        <location id="<?= $friend[ 'placeid' ]; ?>">
        <?= $friend[ 'placename' ] ?>
        </location>
        <?php endif; ?>
        <?php if ( $friend[ 'gender' ] != '-' ): ?>
        <gender><?= $friend[ 'gender' ] ?></gender>
        <?php endif; ?>
        <? if ( isset( $friend[ 'friendofuser' ] ) ): ?>
            <knownBy><?= $_SESSION[ 'user' ][ 'name' ]; ?></knownBy>
        <? endif; ?>
    </friend>
    <? endforeach; ?>
</friends>
