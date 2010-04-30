<entry id="<?= $poll[ 'id' ] ?>">
    <title><?= htmlspecialchars( $poll[ 'question' ] ) ?></title>
    <author>
        <name><?= $poll[ 'username' ] ?></name>
        <subdomain><?= $poll[ 'subdomain' ] ?></subdomain>
        <gender><?= $poll[ 'gender' ] ?></gender>
        <avatar>
            <media url="http://images2.zino.gr/media/<?= $poll[ 'userid' ] ?>/<?= $poll[ 'avatarid' ] ?>/<?= $poll[ 'avatarid' ] ?>_100.jpg" />
        </avatar>
    </author>
    <options totalvotes="<?= $poll[ 'numvotes' ] ?>">
        <? foreach ( $options as $option ): ?>
        <option id="<?= $option[ 'id' ] ?>" votes="<?= $option[ 'numvotes' ] ?>"
            <? if ( isset( $myvote ) && $myvote == $option[ 'id' ] ): ?>
            voted="voted"
            <? endif ?>>
            <title><?= htmlspecialchars( $option[ 'text' ] ) ?></title>
        </option>
        <? endforeach; ?>
    </options>
    <published><?= $poll[ 'created' ] ?></published>
    <? if ( isset( $comments ) ) {
        include 'views/comment/listing.php';
    } ?>
    <? if ( !empty( $favourites ) ): ?>
    <favourites count="<?= count( $favourites ) ?>">
        <? foreach ( $favourites as $favourite ): ?>
        <user><name><?= $favourite[ 'username' ] ?></name></user>
        <? endforeach; ?>
    </favourites>
    <? endif; ?>
</entry>
