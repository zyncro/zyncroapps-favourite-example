<?php defined('workspace') OR die('restricted access');

    CONST FAVORITE_ALL          = 0;
    CONST FAVORITE_GROUP        = 1;
    CONST FAVORITE_DEPARTMENT   = 2;
    CONST FAVORITE_USER         = 3;
    CONST FAVORITE_THREAD       = 4;
    CONST FAVORITE_FILES        = 5;

    /*
     * If this function exists will, it will be called before any other action
     */
    function favorite_preDispatch($zapp)
    {
        $zapp->load->helper('functions');
        $zapp->load->library('redbeanphp');
        $zapp->load->model('favorite');
        favorite_helper::connectToDB($zapp);
    }

    /*
     * Default action (will execute if no action is provided)
     */
    function favorite_default($zapp)
    {
		error("invalid action");
	}

    /*
     * Adds a new favourite
     */
	function favorite_add($zapp)
    {
        $user = favorite_helper::getUser($zapp);
        $type = $zapp->request->getParam("type", FAVORITE_GROUP); // default = groups
        $urn = $zapp->request->getParam("urn", NULL);

        // add favorite
        $id = $zapp->model->favorite->add($user['id'], $type, $urn);

        // resulting JSON
        echo json_encode(array('result' => ( ($id) ? "OK" : "ERROR" ), 'code' => $id));
	}

    /*
     * Deletes an existing favourite
     */
    function favorite_delete($zapp)
    {
        $id = $zapp->request->getParam("id", NULL);

        // delete favorite
        $result = $zapp->model->favorite->delete($id);

        // resulting JSON
        echo json_encode(array('result' => $result, 'code' => $id));
    }

    /*
     * Gets all the favourites for the user and the specified type
     */
    function favorite_get($zapp)
    {
        //R::debug(true);
        $user = favorite_helper::getUser($zapp);
        $type = $zapp->request->getParam("type", FAVORITE_ALL); // default = ALL

        // get user favorites for this type
        $elements = $zapp->model->favorite->getUserFavoritesByType($user['id'], $type);

        echo json_encode(array("counts" => array( FAVORITE_GROUP => count($elements) ), "elements" => $elements));
    }

    /*
     * Gets the popup with the favourites list
     */
    function favorite_getPopup($zapp)
    {
        $user = favorite_helper::getUser($zapp);

        // counts favorites for the user
        $counts = $zapp->model->favorite->countUserFavorites($user['id']);

        // get user favourite groups
        $groups = $zapp->model->favorite->getUserFavoriteGroups($zapp, $user['id']);

        // render and translate the template
        $tplFile = '/resources/tpl/favorite-popup.tpl';
        $html = $zapp->render($tplFile, array('counts' => $counts, 'groups' => $groups));

        if ($html) {
            print $html;
        } else {
            error('invalid template: '.$tplFile);
        }
    }

