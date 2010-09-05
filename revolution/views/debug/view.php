<debug>
    <time><?= Water::TotalTime() ?></time><?php
    $profiles = Water::GetProfiles();
    foreach ( $profiles as $profile ):
        ?><profile>
            <description><?= $profile[ 'description' ] ?></description>
            <time><?= $profile[ 'time' ] ?></time>
        </profile><?
    endforeach;
    $traces = Water::GetTraces();
    foreach ( $traces as $trace ):
        ?><trace>
            <description><?= $trace[ 'description' ] ?></description>
            <data><?= $trace[ 'data' ] ?></data>
        </trace><?php
    endforeach;
    $queries = Water::GetQueries();
    ?><db><?
    foreach ( $queries as $query ):
        ?><query>
            <sql><?= htmlspecialchars( $query[ 'sql' ] ) ?></sql>
            <time><?= $query[ 'time' ] ?></time>
        </query><?
    endforeach;
    ?></db>
</debug>
