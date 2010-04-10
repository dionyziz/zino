<comment id="<?= $comment[ 'id' ] ?>"><author><name><?
echo $user[ 'username' ];
?></name><gender><?
echo $user[ 'gender' ];
?></gender><?
if ( $user[ 'avatarid' ] ) {
    ?><avatar><media url="http://images2.zino.gr/media/<?
    echo $user[ 'id' ];
    ?>/<?
    echo $user[ 'avatarid' ];
    ?>/<?
    echo $user[ 'avatarid' ];
    ?>_100.jpg" /></avatar><?
}
?></author><text><?= $comment[ 'text' ]; ?></text>
</comment>