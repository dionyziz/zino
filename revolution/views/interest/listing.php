<? if ( sizeof( $interests ) ): ?>
    <taglists>
<? endif; ?>
<? foreach ( $interests as $type => $tags ): ?>
    <taglist type="<?
        switch ( $type ) {
            case TAG_HOBBIE:
                ?>hobbies<?
                break;
            case TAG_MOVIE:
                ?>movies<?php
                break;
            case TAG_BOOK:
                ?>books<?php
                break;
            case TAG_SONG:
                ?>songs<?php
                break;
            case TAG_ARTIST:
                ?>artists<?php
                break;
            case TAG_GAME:
                ?>games<?php
                break;
            case TAG_SHOW:
                ?>shows<?php
                break;
            default:
                ?>unknown<?php
                break;
        }
    ?>">
        <? foreach ( $tags as $tag ): ?>
            <tag id="<?= $tag[ 'id' ] ?>"><?= htmlspecialchars( $tag[ 'text' ] ); ?></tag>
        <? endforeach; ?>
    </taglist>
<? endforeach; ?>
<? if( sizeof( $interests ) ): ?>
    </taglists>
<? endif; ?>
