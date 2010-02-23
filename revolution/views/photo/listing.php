<feed>
    <? foreach ( $photos as $photo ): ?>
    <entry>
        <link href="photo/<?php
        echo $photo[ 'id' ];
        ?>" />
        <media url="http://images2.zino.gr/media/<?php
        echo $photo[ 'userid' ];
        ?>/<?php
        echo $photo[ 'id' ];
        ?>/<?php
        echo $photo[ 'id' ];
        ?>_150.jpg" />
        <discussion count="<?php
        echo $photo[ 'numcomments' ];
        ?>" />
    </entry>
    <? endforeach; ?>
</feed>
