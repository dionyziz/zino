<?php
	return;
	
	set_include_path( '../:./' );
	
	global $page;
	global $libs;
    
	require_once 'libs/rabbit/rabbit.php';
    Rabbit_Construct( 'HTML' );
    $req = $_GET;
    
	Rabbit_ClearPostGet();
	
    global $db;

    function CountUsers() {
        global $db;

        $sql = "SELECT COUNT( * ) AS usercount FROM `merlin_users`;";

        $fetched = $db->Query( $sql )->FetchArray();

        return $fetched[ 'usercount' ];
    }

    function UserSexPercentage() {
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
            $stat[ $row[ 'user_gender' ] ] = (int)$row[ 'user_count' ];
            $sum += $row[ 'user_gender' ];
        }

        return array(
            'male' => $stat[ 'male' ] * 100 / $sum,
            'female' => $stat[ 'female' ] * 100 / $sum,
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

    $usercount = CountUsers();
    echo "Total number of users: " . $usercount . "<br />";

    /*
    $usercreations = AccountCreations();

    $pageviewsmonth = PageviewsLastMonth();

    $pageviewsmonths = PageviewsLastMonths( 6 );
    */

    $userage = AverageUserAge();
    echo "Average user age: " . $userage[ 'average' ];
    echo " ( " . $userage[ 'count' ] . " of " . $usercount . " have specified a valid user age<br /><br />";

    $usersex = UserSexPercentage();
    echo "Percentage of male/female distribution:<br />";
    echo $usersex[ 'male' ] . "% male - " . $usersex[ 'female' ] . "%female";
    echo " ( " . $usersex[ 'count' ] . " of " . $usercount . " have specified a vlid sex<br /><br />";
    
    $page->Output();

    Rabbit_Destruct();
	
?>
