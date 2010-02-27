<?php

    function Resource_Init() {
		$resource = Resource_Select();
		$method = Resource_SelectMethod();
		$args = Resource_Arguments( $method );

        return array( $resource, $method, $args );
    }

    function Resource_Select() {
        $resource = '';
        if ( isset( $_GET[ 'resource' ] ) ) {
            $resource = $_GET[ 'resource' ];
            unset( $_GET[ 'resource' ] );
        }
        switch ( $resource ) {
            case 'photo': case 'session': case 'comment': case 'favourite':
                break;
            default:
                $resource = 'photo';
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

    function Resource_RenderXML() {
		list( $resource, $method, $vars ) = Resource_Init();

        $stylesheet = Resource_StylesheetAddress( $resource, $method );

        Page_XMLHead( $stylesheet );
        SocialPage_Start();
        Resource_Call( $resource, $method, $vars );
        SocialPage_End();
    }

    function Resource_StylesheetAddress( $resource, $method ) {
        global $settings;

        return $settings[ 'base' ] . "/xslt/" . $resource . "/" . $method . ".xsl";
    }

?>
