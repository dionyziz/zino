<stream type="friends">
    <? global $settings;
    foreach ( $friends as $friend ): ?>
    <entry id="<?= $friend[ 'id' ] ?>">
        <name><?= $friend[ 'name' ] ?></name>
            <subdomain><?= $friend[ 'subdomain' ] ?></subdomain>
        <age><?= $friend[ 'age' ] ?></age>
        <media url="http://images2.zino.gr/media/<?= $friend[ 'avatarid' ] ?>/<?= $settings[ 'beta' ]? '_': '' ?><?= $friend[ 'avatarid' ] ?>/<?= $friend[ 'avatarid' ] ?>_150.jpg" />
        
    </entry>
    <? endforeach; ?>
</stream>
