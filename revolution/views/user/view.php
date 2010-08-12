<user id="<?= $user[ 'id' ] ?>">
    <name><?= $user[ 'name' ] ?></name>
    <subdomain><?= $user[ 'subdomain' ] ?></subdomain>
    <? if ( isset( $user[ 'gender' ] ) ): ?>
    <gender><?= $user[ 'gender' ] ?></gender>
    <? endif; ?>
    <? if ( isset( $user[ 'profile' ][ 'age' ] ) ): ?>
    <age><?= $user[ 'profile' ][ 'age' ] ?></age>
    <? endif; ?>
    <? if ( !empty( $user[ 'avatarid' ] ) ): ?>
    <avatar id="<?= $user[ 'avatarid' ] ?>">
        <media url="http://images2.zino.gr/media/<?= $user[ 'id' ] ?>/<?= $user[ 'avatarid' ] ?>/<?= $user[ 'avatarid' ] ?>_100.jpg" />
    </avatar>
    <? endif; ?>
    <? if ( isset( $user[ 'location' ] ) ): ?>
    <location id="<?= $user[ 'location_id' ]?>"><?= $user[ 'location' ] ?></location>
    <? endif;
    if ( isset( $counts ) ): ?>
        <friends count="<?= $counts[ 'friends' ]; ?>" />
        <photos count="<?= $counts[ 'images' ]; ?>" />
        <polls count="<?= $counts[ 'polls' ]; ?>" />
        <journals count="<?= $counts[ 'journals' ]; ?>" />
        <answers count="<?= $counts[ 'answers' ]; ?>" />
        <favourites count="<?= $counts[ 'favourites' ]; ?>" />
        <chat count="<?= $counts[ 'shouts' ]; ?>" />
    <? endif; ?>
    <? if ( isset( $friendofuser ) && $friendofuser ): ?>
        <knownBy><?= $_SESSION[ 'user' ][ 'name' ]; ?></knownBy>
    <? endif; ?>
    <? if ( isset( $song ) ): ?>
        <song id="<?= $song[ 'songid' ] ?>" />
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
           if ( isset( $user[ 'profile' ][ $stat ] ) && $user[ 'profile' ][ $stat ] != '-' ): ?>
           <<?= $stat ?>><?= htmlspecialchars( $user[ 'profile' ][ $stat ] ) ?></<?= $stat ?>>
        <? endif;
           endforeach; ?>
    </details>
    <contact>
        <? $ims = array( 'skype', 'msn', 'gtalk', 'yim' );
           foreach ( $ims as $im ):
           if ( isset( $user[ 'profile' ][ $im ] ) ): ?>
        <im type="<?= $im ?>"><?= $user[ 'profile' ][ $im ] ?></im>
        <? endif;
           endforeach;
           if ( isset( $user[ 'profile' ][ 'email' ] )
                && isset( $_SESSION[ 'user' ] )
                && $_SESSION[ 'user' ][ 'id' ] == $user[ 'id' ] ): ?>
           <email><?= $user[ 'profile' ][ 'email' ] ?></email>
        <? endif; ?>
    </contact>
    <?
    if ( isset( $user[ 'mood' ] ) ):
    ?>
    <mood id="<?= $user[ 'mood' ][ 'id' ]; ?>">
        <label><? if ( $user[ 'gender' ] == 'f' ):
               echo $user[ 'mood' ][ 'labelfemale' ];
           else:
               echo $user[ 'mood' ][ 'labelmale' ];
           endif;
        ?></label>
        <media url="http://static.zino.gr/phoenix/moods/<?= $user[ 'mood' ][ 'url' ] ?>" />
    </mood>
    <?
    endif;
    if ( isset( $activities ) ):
        include 'views/activity/listing.php';
    endif;
    if ( isset( $interests ) ):
        include 'views/interest/listing.php';
    endif;
    if ( isset( $comments ) ):
        include 'views/comment/listing.php';
    endif;
    ?>
</user>
