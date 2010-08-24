<friends of="<?= $subdomain ?>" id="<?= $userid ?>" count="<?= count( $friends ); ?>">
    <? global $settings;
    foreach ( $friends as $friend ): ?>
    <friend id="<?= $friend[ 'id' ] ?>">
        <name><?= $friend[ 'name' ] ?></name>
            <subdomain><?= $friend[ 'subdomain' ] ?></subdomain>
        <?php if ( !empty( $friend[ 'age' ] ) ): ?>
        <age><?= $friend[ 'age' ] ?></age>
        <?php endif; ?>
        <?php if ( !empty( $friend[ 'avatarid' ] ) ): ?>
        <avatar id="<?= $friend[ 'avatarid' ] ?>">
            <media url="http://images2.zino.gr/media/<?= $friend[ 'id' ] ?>/<?= $friend[ 'avatarid' ] ?>/<?= $friend[ 'avatarid' ] ?>_150.jpg" />
        </avatar>
        <?php endif; ?>
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
