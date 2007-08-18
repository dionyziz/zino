<?php
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
    global $db;
    
    function AccountCreations() {
        global $db;

        $sql = "SELECT
                    DATE( `user_created` )AS date, COUNT( * ) AS new_users
                FROM
                    `merlin_users`
                WHERE   
                    ( NOW() - INTERVAL 1 MONTH ) < `user_created`
                GROUP BY date
                ORDER BY date DESC;";

        $res = $db->Query( $sql );
        $sum = 0;
        $ret = array();
        while ( $row = $res->FetchArray() ) {
            $ret[ $row[ 'date' ] ] = $row[ 'new_users' ];
            $sum += $row[ 'new_users' ];
        }

        $ret[ 'Average' ] = substr( $sum / 30, 0, 4 );

        return $ret;
    }

    function PageviewsLastMonth() {
        global $db;

        $sql = "SELECT COUNT( * ) AS pageviews FROM `merlin_logs` WHERE `log_date` > NOW() - INTERVAL 1 MONTH;";

        $fetched = $db->Query( $sql )->FetchArray();
        return $fetched[ 'pageviews' ];
    }

    function PageviewsLastMonths( $num ) {
        global $db;

        $sql = "SELECT 
                    MONTHNAME( `log_date` ) AS month,
                    COUNT( * ) AS pageviews
                FROM 
                    `merlin_logs`
                WHERE 
                    `log_date` > NOW( ) - INTERVAL $num MONTH 
                GROUP BY 
                    month 
                ORDER BY 
                    `log_date` DESC
                ;";
        
        $res = $db->Query( $sql );
        $ret = array();
        while ( $row = $res->FetchArray() ) {
            $ret[ $row[ 'month' ] ] = $row[ 'pageviews' ];
        }

        return $ret;
    }

    function UserGenderPercentage() {
        global $db;
        
        $sql = "SELECT 
                    `user_gender`, COUNT( * ) AS count 
                FROM 
                    `merlin_users` 
                WHERE 
                    `user_gender` != '-' 
                GROUP BY `user_gender`;";

        $res = $db->Query( $sql );
        $stat = array();
        $sum = 0;
        while ( $row = $res->FetchArray() ) {
            $stat[ $row[ 'user_gender' ] ] = (int)$row[ 'count' ];
            $sum += (int)$row[ 'count' ];
        }

        return array(
            'male' => substr( $stat[ 'male' ] * 100 / $sum, 0, 4 ),
            'female' => substr( $stat[ 'female' ] * 100 / $sum, 0, 4 ),
            'count' => $sum
        );
    }

    function AverageUserAge() {
        global $db;

        $sql = "SELECT 
                    AVG( `user_age` ) AS average, 
                    COUNT( * ) AS count
               FROM (
                    SELECT 
                        YEAR( NOW( ) ) - YEAR( `user_dob` ) AS user_age
                    FROM 
                        `merlin_users`
                    WHERE 
                        `user_dob` != '0000-00-00 00:00:00'
               ) AS statistics;";
        
        return $db->Query( $sql )->FetchArray();
    }

    ob_start();

    $usercount = CountUsers(); // libs/user
    echo "Total number of users: " . $usercount . "<br /><br />";

    $usercreations = AccountCreations();
    echo "Account creations this month: <br />";
    
    foreach ( $usercreations as $date => $num ) {
        echo $date . ": " . $num . "<br />";
    }
    echo "<br />";

    $pageviewsmonth = PageviewsLastMonth();
    echo "Total pageviews last month: $pageviewsmonth<br /><br />";

    $pageviewsmonths = PageviewsLastMonths( 6 );
    echo "Total pageviews last 6 months: <br />";

    foreach ( $pageviewsmonths as $month => $views ) {
        echo "$month: $views<br />";
    }
    echo "<br />";

    $userage = AverageUserAge();
    echo "Average user age: " . $userage[ 'average' ];
    echo " ( " . $userage[ 'count' ] . " of " . $usercount . " users have specified a valid age )<br /><br />";

    $usergender = UserGenderPercentage();
    echo "Percentage of male/female distribution:<br />";
    echo $usergender[ 'male' ] . "% male - " . $usergender[ 'female' ] . "% female";
    echo " ( " . $usergender[ 'count' ] . " of " . $usercount . " users have specified a valid sex )<br /><br />";
    
    Rabbit_Destruct();
	
?>
