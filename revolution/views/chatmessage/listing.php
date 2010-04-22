<chatchannel id="<?= $channel[ 'id' ] ?>">
    <discussion>
        <? foreach ( $chatmessages as $chatmessage ): ?>
        <comment id="s<?= $chatmessage[ 'id' ] ?>">
            <author>
                <name><?= $chatmessage[ 'username' ] ?></name>
                <? if ( $chatmessage[ 'avatarid' ] ): ?>
                <avatar>
                    <media url="http://images2.zino.gr/media/<?= $chatmessage[ 'userid' ] ?>/<?= $chatmessage[ 'avatarid' ] ?>/<?= $chatmessage[ 'avatarid' ]?>_100.jpg" />
                </avatar>
                <? endif ?>
            </author>
            <text><?= $chatmessage[ 'text' ] ?></text>
        </comment>
        <? endforeach; ?>
    </discussion>
</chatchannel>
