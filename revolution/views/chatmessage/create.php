<chatchannel id="<?= $channel[ 'id' ] ?>">
    <discussion>
        <comment id="s<?= $chatmessage[ 'id' ] ?>">
            <author id="<?= $chatmessage[ 'user' ][ 'id' ] ?>">
                <name><?= $chatmessage[ 'user' ][ 'name' ] ?></name>
            </author>
            <text><?= $chatmessage[ 'text' ] ?></text>
            <date><?= $chatmessage[ 'date' ] ?></date>
        </comment>
    </discussion>
</chatchannel>
