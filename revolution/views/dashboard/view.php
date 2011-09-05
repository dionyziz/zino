<?php
    ?>
    <activities>
    <?
    foreach( $lastcomments[ 'comments' ] as $comment ) {
        $type = $comment[ 'type' ];
        $itemid = $comment[ 'itemid' ];
        $itemtype = $comment[ 'type' ];
        
        ?>
        <activity>
            <type>comment</type>
            <user id="<?= $comment[ 'user' ][ 'id' ]; ?>">
                <name><?= $comment[ 'user' ][ 'name' ]; ?></name>
                <subdomain><?= $comment[ 'user' ][ 'subdomain' ]; ?></subdomain>
                <gender><?= $comment[ 'user' ][ 'gender' ]; ?></gender>
            </user>
        
            <comment id="<?= $comment[ 'id' ] ?>">
                <?
                switch ( $itemtype ) {
                    case 'photo':
                        $item = $lastcomments[ 'photos' ][ $itemid ];
                        break;
                    case 'journal':
                        $item = $lastcomments[ 'journals' ][ $itemid ];
                        break;
                    case 'poll':
                        $item = $lastcomments[ 'polls' ][ $itemid ];
                        break;
                    case 'profile':
                        $item = $lastcomments[ 'profiles' ][ $itemid ];
                        break;
                }
                ?><<?=$itemtype; ?> id="<?= $itemid ?>">
                    <author id="<?= $item[ 'userid' ] ?>">
                        <name><?= $item[ 'username' ]; ?></name>
                        <gender><?= $item[ 'gender' ]; ?></gender>
                    </author><?
                    switch ( $itemtype ) {
                        case 'journal':
                            ?><title><?= $item[ 'title' ]; ?></title><?
                            break;
                        case 'poll':
                            ?><question><?= $item[ 'question' ]; ?></question><?
                            break;
                    }
                ?></<?= $itemtype; ?>><?
                ?><text><?= $comment[ 'text' ]; ?></text>
            </comment>
        </activity>
        <?
    }
    ?></activities><?
?>