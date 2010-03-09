<feed>
    <? foreach ( $news as $item ): ?>
    <entry type="<?= $item[ 'type' ] ?>">
        <author>
            <name><?= $item[ 'username' ]; ?></name>
            <subdomain<?= $item[ 'subdomain' ]; ?></subdomain>
            <gender><?= $item[ 'gender' ]; ?></gender>
        </author>
    </entry>
    <? endforeach; ?>
</feed>
