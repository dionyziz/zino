<user id="<?= $user[ 'id' ] ?>">
    <name><?= $user[ 'name' ] ?></name>
    <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
    <gender><?= $user[ 'gender' ] ?></gender>
    <avatar id="<?= $user[ 'avatarid' ] ?>">
        <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
    </avatar>
    <? if ( isset( $user[ 'location' ] ) ): ?>
    <location><?= $user[ 'location' ] ?></location>
    <? endif;
    if ( isset( $counts ) ): ?>
        <friends count="<?= $counts[ 'friends' ]; ?>" />
        <stream type="photo" count="<?= $counts[ 'images' ]; ?>" />
        <stream type="poll" count="<?= $counts[ 'polls' ]; ?>" />
        <stream type="journal" count="<?= $counts[ 'journals' ]; ?>" />
        <answers count="<?= $counts[ 'answers' ]; ?>" />
        <favourites count="<?= $counts[ 'favourites' ]; ?>" />
        <discussion type="chat" count="<?= $counts[ 'shouts' ]; ?>" />
    <? endif; ?>
    <details>
        <? $stats = array(
            'height', 'weight', 'smoker', 'drinker',
            'relationship', 'sexualorientation',
            'politics', 'religion',
            'slogan', 'aboutme',
            'eyecolor', 'haircolor'
           );
           foreach ( $stats as $stat ):
           if ( isset( $user[ 'profile' ][ $stat ] ) ): ?>
           <<?= $stat ?>><?= $user[ 'profile' ][ $stat ] ?></<?= $stat ?>>
        <? endif;
           endforeach; ?>
    </details>
    <contact>
        <? $ims = array( 'skype', 'msn', 'gtalk', 'yim' );
           foreach ( $ims as $im ):
           if ( isset( $user[ 'profile' ][ $im ] ) ): ?>
        <im type="<?= $im ?>"><?= $user[ 'profile' ][ $im ] ?></im>
        <? endif;
           endforeach; ?>
    </contact>
    <?
    if ( isset( $comments ) ):
        include 'views/comment/listing.php';
    endif;
    ?>
</user>
