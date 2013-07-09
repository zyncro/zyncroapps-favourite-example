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
        $result = "ERROR";
        $id = 0;
        $user = favorite_helper::getUser($zapp);
        $type = $zapp->request->getParam("type", FAVORITE_GROUP); // default = groups
        $urn = $zapp->request->getParam("urn", NULL);

        if ($urn && $user['id'])
        {
            // check if the favourite already exists
            $where = 'id_owner = :idOwner AND id_favorite = :idFavorite';
            $params = array(':idOwner'  => $user['id'], ':idFavorite' => base64_decode($urn));
            $exist = R::find('favorite', $where, $params);

            if (empty($exist))
            {
                // build the element
                $favorite = R::dispense('favorite');
                $favorite->id_owner     = $user['id'];
                $favorite->id_favorite  = base64_decode($urn);
                $favorite->category     = $type;
                $favorite->creation_date= date('Y-m-d H:i:s');

                // add to database
                $id = R::store($favorite);

                if ($id)
                {
                    $result = "OK";
                }
            }
        }

        // resulting JSON
        echo json_encode(array('result' => $result, 'code' => $id));
	}

    /*
     * Deletes an existing favourite
     */
    function favorite_delete($zapp)
    {
        $result = "ERROR";
        $id = $zapp->request->getParam("id", NULL);

        if ($id)
        {
            // get the bean
            $favorite = R::load('favorite', $id);
            // delete the element from database
            R::trash($favorite);
            $result = "OK";
        }

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

        // build the query
        $where = 'id_owner = :idOwner';
        $params = array(':idOwner'  => $user['id']);
        if ($type != FAVORITE_ALL)
        {
            $where .= ' AND category = :type';
            $params[':type'] = $type;
        }
        // search in database
        $favorites = R::find('favorite', $where, $params);

        // format the response
        $elements = array();
        foreach ($favorites as $favorite)
        {
            $element = new stdClass();
            $element->id    = $favorite->id;
            $element->urn   = base64_encode($favorite->id_favorite);
            $element->type  = $favorite->category;
            $elements[] = $element;
        }

        // get number of groups favorites for this call
        $count = array(
            FAVORITE_GROUP => count($elements)
        );

        echo json_encode(array("counts" => $count, "elements" => $elements));
    }

    /*
     * Gets the popup with the favourites list
     */
    function favorite_getPopup($zapp)
    {
        $user = favorite_helper::getUser($zapp);
        $counts = array(
            FAVORITE_GROUP => 0,
            FAVORITE_ALL => 0
        );

        // get the number of favourites for each type (build the query)
        $elements = R::$f->begin()
            ->select('type, COUNT(1) as NUMFAV')
            ->from('favorite')
            ->where(' id_owner = ? ')->put($user['id'])
            ->addSQL(' GROUP BY type ')
            ->get();

        foreach ($elements as $el)
        {
            $counts[$el['type']]    += $el['NUMFAV'];
            $counts[FAVORITE_ALL]   += $el['NUMFAV'];
        }

        // get groups URN from database
        $where = 'id_owner = :idOwner AND category = :type ORDER BY creation_date DESC';
        $params = array(':idOwner'  => $user['id'], ':type' => FAVORITE_GROUP);
        $favoriteGroups = R::find('favorite', $where, $params);

        $groupsId = array();
        foreach ($favoriteGroups as $fav)
        {
            $groupsId[] = $fav->id_favorite;
        }

        // get groups information from Zyncro API
        $responseJSON = $zapp->oAuth->request('GET', '/api/v1/rest/groups/profiles', ['groups' => implode(',', $groupsId)]);
        $groups = favorite_helper::parseAPIGroupsResponse($responseJSON);

        // render and translate the template
        $tplFile = '/resources/tpl/favorite-popup.tpl';
        $html = $zapp->render($tplFile, array('counts' => $counts, 'groups' => $groups));

        if ($html) {
            print $html;
        } else {
            error('invalid template: '.$tplFile);
        }
    }

