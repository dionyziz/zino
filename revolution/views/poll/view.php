<poll id="<?= $poll[ 'id' ] ?>">
    <title><?= htmlspecialchars( $poll[ 'question' ] ) ?></title>
    <? if ( isset( $user ) ): ?>
    <author>
        <name><?= $user[ 'name' ] ?></name>
        <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
        <gender><?= $user[ 'gender' ] ?></gender>
        <avatar>
            <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
        </avatar>
    </author>
    <? endif; ?>
    <options totalvotes="<?= $poll[ 'numvotes' ] ?>">
        <? foreach ( $options as $option ): ?>
        <option id="<?= $option[ 'id' ] ?>" votes="<?= $option[ 'numvotes' ] ?>"
            <? if ( isset( $myvote ) && $myvote == $option[ 'id' ] ): ?>
            voted="yes"
            <? endif; ?>>
            <title><?= htmlspecialchars( $option[ 'text' ] ) ?></title>
        </option>
        <? endforeach; ?>
    </options>
    <published><?= $poll[ 'created' ] ?></published>
    <?
    if ( isset( $comments ) ):
        include 'views/comment/listing.php';
    endif;
    if ( !empty( $favourites ) ): ?>
    <favourites count="<?= count( $favourites ) ?>">
        <? foreach ( $favourites as $favourite ): ?>
        <user><name><?= $favourite[ 'username' ] ?></name></user>
        <? endforeach; ?>
    </favourites>
    <? endif; ?>
</poll>
