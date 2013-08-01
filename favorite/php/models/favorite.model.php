<?php defined('workspace') OR die('restricted access');

	class favorite extends zapp_model {
		
		function __construct($path = '') {
			parent::__construct();
		}

        public function init()
        {
            // will be automatically executed
        }

        public function getUserFavoritesByType($user, $type)
        {
            // build the query
            $where = 'id_owner = :idOwner';
            $params = array(':idOwner'  => $user);
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

            return $elements;
        }

        public function countUserFavorites($user)
        {
            $counts = array(
                FAVORITE_GROUP => 0,
                FAVORITE_ALL => 0
            );

            // get the number of favourites for each type (build the query)
            $elements = R::$f->begin()
                ->select('type, COUNT(1) as NUMFAV')
                ->from('favorite')
                ->where(' id_owner = ? ')->put($user)
                ->addSQL(' GROUP BY type ')
                ->get();

            foreach ($elements as $el)
            {
                $counts[$el['type']]    += $el['NUMFAV'];
                $counts[FAVORITE_ALL]   += $el['NUMFAV'];
            }

            return $counts;
        }

        public function getUserFavoriteGroups($zapp, $user)
        {
            // get groups URN from database
            $where = 'id_owner = :idOwner AND category = :type ORDER BY creation_date DESC';
            $params = array(':idOwner'  => $user, ':type' => FAVORITE_GROUP);
            $favoriteGroups = R::find('favorite', $where, $params);

            $groupsId = array();
            foreach ($favoriteGroups as $fav)
            {
                $groupsId[] = $fav->id_favorite;
            }

            // get groups information from Zyncro API
            $responseJSON = $zapp->oAuth->request('GET', '/api/v1/rest/groups/profiles', ['groups' => implode(',', $groupsId)]);
            $groups = favorite_helper::parseAPIGroupsResponse($responseJSON);

            return $groups;
        }

        public function delete($id)
        {
            $result = "ERROR";

            if ($id)
            {
                // get the bean
                $favorite = R::load('favorite', $id);
                // delete the element from database
                R::trash($favorite);
                $result = "OK";
            }

            return $result;
        }

        public function add($user, $type, $urn)
        {
            $id = 0;

            if ($urn && $user)
            {
                // check if the favourite already exists
                $where = 'id_owner = :idOwner AND id_favorite = :idFavorite';
                $params = array(':idOwner'  => $user, ':idFavorite' => base64_decode($urn));
                $exist = R::find('favorite', $where, $params);

                if (empty($exist))
                {
                    // build the element
                    $favorite = R::dispense('favorite');
                    $favorite->id_owner     = $user;
                    $favorite->id_favorite  = base64_decode($urn);
                    $favorite->category     = $type;
                    $favorite->creation_date= date('Y-m-d H:i:s');

                    // add to database
                    $id = R::store($favorite);
                }
            }

            return $id;
        }

	}
	
?>