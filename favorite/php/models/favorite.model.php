<?php defined('workspace') OR die('restricted access');


	class favorite extends zapp_model {

		function __construct($path = '') {
			parent::__construct();
		}

        public function getFavoritesByType( $user, $ownerType = 1, $favoriteType = 0, $itemsPerPage = 30, $pageNumber = 1, $lastItem = 0 )
        {
            $pageNumber--;
            $firstReg = $itemsPerPage * $pageNumber;
            if($favoriteType == 0) $favoriteType = array(1,2,3,4,5);
            //dependiendo de si nos llega lastItem, formamos un where para seleccionar los elementos necesarios
            if($lastItem == 0){
                $lastItemWhere = 'id > {value}';
            }else{
                $lastItemWhere = 'id < {value}';
            }

            $object = $this->main->db->select()
                            ->from('favorite')
                            ->where("id_owner = '{value}'", array( 'value' => $user['id']))
                            ->where('category = {value}', array( 'value' => $ownerType))
                            ->where('type IN ({value})', array( 'value' => $favoriteType))
                            ->where($lastItemWhere, array('value' => $lastItem))
                            ->limit($firstReg, $itemsPerPage)
                            ->order('creation_date', $desc = false)
                            ->fetch();
            $res['elements'] = $object->rows;
            $res['totalItems'] = count($object->rows);
            $res['pageNumber'] = ++$pageNumber;
            return $res;
        }

        public function addFavorite( $user, $type, $urn, $category = 1 )
        {
			$core = core();
			$query = $core->db->select('favorite')
				->where("id_owner = '{idOwner}'", array('idOwner' => $user['id']))
				->where("id_favorite = '{idFavorite}'", array('idFavorite' => base64_decode($urn)))
				->limit(1)->fetch();
			
			$result = "KO";

            if (!count($query->rows)) {
				$query = $core->db->create('favorite')->set(array(
					'id_owner' => $user['id'],
					'id_favorite' => $urn,
					'category' => $category,
					'type' => $type,
					'creation_date' => date('Y-m-d H:i:s')
				))->save();
				$id = (int)$query->insert_id[0];
                $result = "OK";
            }
            return json_encode(array('result' => $result, 'code' => $id));
        }

        public function deleteFavorite( $id = 0, $user )
        {
            $result = "KO";
			$core = core();
            if ($id) {
                // get the bean
				$favorite = $core->db->select('favorite')
				->where('id = {id}', array('id' => $id))->fetch();
				if (isset($favorite->rows[0])) $type = $favorite->rows[0]['type'];

                if ($type == FAVORITE_FILES) {
                    $urnFile = $favorite['id_favorite'];
                    //delete relation group file
                    $core->db->query("DELETE FROM `{table}` WHERE idOwner='{idOwner}' AND `idFavorite` => '{idFavorite}';", array('table' => 'groupfilesrel', 'idOwner' => $user['id'], 'idFavorite' => $urnFile));
                }
                // delete the element from database
                $result = "OK";
            }
            return json_encode(array('result' => $result, 'code' => $id));
        }

        public function deleteFavoriteByUrnAndUser($urn, $user)
        {
			$core = core();
            $where = ' id_favorite = :id_favorite AND id_owner = :id_owner ';
			$core->db->query("DELETE FROM `{table}` WHERE idOwner='{idOwner}' AND `idFavorite` => '{idFavorite}';", array('table' => 'favorite', 'idOwner' => $user['id'], 'idFavorite' => $urn));
            return $urn;
        }

        public function getItemsCount( $user, $ownerType )
        {
            $object = $this->main->db->select()
                ->from('favorite')
                ->fields('type')
                ->count('*', 'count')
                ->where("id_owner = '{value}'", array( 'value' => $user['id']))
                ->where('category = {value}', array( 'value' => $ownerType))
                ->group('type')
                ->fetch();
            return $object->rows;
        }

        public function getOldFavorites( $itemsPerPage = null )
        {
            if( $itemsPerPage == null ){
                $object = $this->main->db->select()
                    ->from('favoriteGroups')
                    ->fetch();
            }else{
                $object = $this->main->db->select()
                    ->from('favoriteGroups')
                    ->limit(0, $itemsPerPage)
                    ->fetch();
            }
            return $object->rows;
        }

        public function migrateFavorites ()
        {
            $sql = 'INSERT INTO favorite(id_owner, id_favorite, category, creation_date, type, order_pos)  SELECT id_owner, id_favorite, category, creation_date, type, order_pos FROM favoriteGroups';
            $result = $this->main->db->query( $sql );
            return $result;
        }

        public function changeFavoriteConfig ( $organizationId, $showFavoriteUserOnWall )
        {
            if($showFavoriteUserOnWall){
                $showFavoriteUserOnWall = 1;
            }else{
                $showFavoriteUserOnWall = 0;
            }
            //check if organization id exist on DB
            $object = $this->main->db->select()
                ->from('zapp_conf')
                ->where("org_id = '{value}'", array( 'value' => $organizationId ))
                ->fetch();
            if($object->rows != null){
                //update config
                $sql = 'UPDATE zapp_conf SET star_user_thread = "'.$showFavoriteUserOnWall.'" WHERE org_id = "' . $organizationId . '"';
                $result = $this->main->db->query( $sql );
            }else{
                //create row with new organization id
                //$result = $this->main->db->create('zapp_conf')->set('org_id' => $oranizationId, 'star_user_thread' => $showFavoriteUserOnWall)->save();
                $sql = 'INSERT INTO zapp_conf(org_id, star_user_thread) VALUES("'.$organizationId.'", "'.$showFavoriteUserOnWall.'")';
                $result = $this->main->db->query( $sql );
            }
            return $result;
        }

        public function getZappConfig ( $organizationId )
        {
            $object = $this->main->db->select()
                ->from('zapp_conf')
                ->where("org_id = '{value}'", array( 'value' => $organizationId ))
                ->fetch();
            if($object->rows){
                $res = $object->rows[0]['star_user_thread'];
                if ($res == '1') $res = true;
                elseif($res == '0') $res = false;
            }else{
                $res =  false;
            }
            return $res;
        }

	}
	
?>