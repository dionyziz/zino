<?php

	function Resource_List() {
		return array(
			'photo',
			'session',
			'comment',
			'favourite'
		);
	}

    function Resource_Init() {
		$list = Resource_List();
		$resource = Resource_Select( $list );
		$method = Resource_SelectMethod();
		$args = Resource_Arguments( $method );

        return array( $resource, $method, $args );
    }

    function Resource_Select( $list ) {
        $resource = '';

        if ( isset( $_GET[ 'resource' ] ) ) {
            $resource = $_GET[ 'resource' ];
            unset( $_GET[ 'resource' ] );
        }

		if ( !in_array( $resource, $list ) ) {
			$resource = $list[ 0 ];
		}

		return $resource;
    }

	function Resource_SelectMethod() {
        $method = '';
        if ( isset( $_GET[ 'method' ] ) ) {
            $method = $_GET[ 'method' ];
            unset( $_GET[ 'method' ] );
        }
        switch ( $method ) {
            case 'view': case 'listing': case 'create': case 'delete': case 'update':
                break;
            default:
                $method = 'listing';
        }
		return $method;
	}

	function Resource_Arguments( $method ) {
        if ( $method != 'listing' && $method != 'view' ) {
            $_SERVER[ 'REQUEST_METHOD' ] == 'POST' or die;
            $vars = $_POST;
        }
        else {
            $vars = $_GET;
        }
	}

    function Resource_Call( $resource, $method, $vars ) {
        include 'controllers/' . $resource . '.php';
        call_user_func_array( $method, $vars );
    }

	// include models/page !
    function Resource_RenderXML() {
		header( 'Content-type: application/xml' );

		list( $resource, $method, $vars ) = Resource_Init();

        $stylesheet = Resource_StylesheetAddress( $resource, $method );

        Page_Start( $stylesheet );
        Resource_Call( $resource, $method, $vars );
        Page_End();
    }

    function Resource_StylesheetAddress( $resource, $method ) {
        global $settings;

        return $settings[ 'base' ] . "/xslt/" . $resource . "/" . $method . ".xsl";
    }

?>
