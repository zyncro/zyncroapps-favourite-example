<?php defined('workspace') OR die('restricted access');
/*
 * If this function exists will, it will be called before any other action
 */
function favorite_preDispatch($zapp)
{
    $zapp->load->model('favorite');
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

function favorite_external($zapp) {
    $action = trim($zapp->request->getParam('action'));
    $method = str_replace('_external', '_'.$action, debug_backtrace()[0]['function']);
    if (function_exists($method)) call_user_func_array($method, func_get_args());
}

/*
 * Toggle for add or delete favourite
 */
function favorite_toggle($zapp)
{
    $isFavorite = $zapp->request->getParam("isFavorite");
    $favoriteType = $zapp->request->getParam("favoriteType");
    $favoriteUrn = $zapp->request->getParam("urn");
    $category = $zapp->request->getParam("ownerType");
    if(!$category) $category = 1;
    $user = favorite_helper::getUser($zapp);

    $result = "OK";
    if( $isFavorite == 'true' ){
        $message = "favorite removed";
    }elseif( $isFavorite == 'false' ){
        $message = "favorite added";
    }

    if( !$favoriteType ){
        $result = "KO";
        $message =  "'favoriteType' parameter is mandatory";
    }elseif( !$favoriteUrn ){
        $result = "KO";
        $message = "'favoriteUrn' parameter is mandatory";
    }
    if( $result == "OK" ){
        if( $isFavorite == 'true' ){
            $queryRes = $zapp->model->favorite->deleteFavoriteByUrnAndUser($favoriteUrn, $user);
        }elseif( $isFavorite == 'false' ){
            $queryRes = $zapp->model->favorite->addFavorite($user, $favoriteType, $favoriteUrn, $category);
        }
        if( isset($queryRes) && !$queryRes ){
            $result = "KO";
            $message = "favorite can't be added, bbdd problem";
        }
    }
    echo json_encode( array('message' => $message, 'result' => $result) );
}

function favorite_list ($zapp)
{
    $itemsPerPage = $zapp->request->getParam("itemsPerPage", 10);
    $pageNumber = $zapp->request->getParam("pageNumber", 1);
    $ownerType = $zapp->request->getParam("ownerType", 1);
    $favoriteType = $zapp->request->getParam("favoriteType", 0); // 0 = ALL FAVORITES
    $user = favorite_helper::getUser($zapp);

    $favoritesBBDD = $zapp->model->favorite->getFavoritesByType( $user, $ownerType, $favoriteType, $itemsPerPage*3, $pageNumber );
    $favorites = favorite_helper::parseFavoriteList($favoritesBBDD, $itemsPerPage);
    $resFavorites = array();
    $totalItems = 0;

    foreach($favorites as $key => $favoritesPerType){
        $totalItems += count( $favoriteType );
        foreach($favoritesPerType['favorites'] as $favorite){
            array_push($resFavorites, $favorite);
        }
    }
    $counts = $zapp->model->favorite->getItemsCount( $user, $ownerType );
    echo json_encode(array('elements' =>$resFavorites, 'counts' => $counts, 'totalItems' => $totalItems));
}

/*
 * Gets the popup with the favourites list
 */
function favorite_getPopup($zapp)
{
    $user = favorite_helper::getUser($zapp);
    $counts = array();
    $totalItems = 0;
    $favoriteTypeName = array( 1 => 'groups', 2 => 'departments', 3 => 'users', 4 => 'threads', 5 => 'files' );
    for($favoriteType = 1; $favoriteType < 6; $favoriteType++){
        $favoritesPerType = $zapp->model->favorite->getFavoritesByType($user, 1, $favoriteType, 50, 1);
        if(count($favoritesPerType['elements']) > 0){
            $favoriteTypeParsed = favorite_helper::parseFavoriteList($favoritesPerType, 10, true);
            //$totalItems += count( $favoriteTypeParsed[$favoriteType] );
            //$counts[$favoriteType] = count( $favoriteTypeParsed[$favoriteType] );
            $totalItems += $favoriteTypeParsed[$favoriteType]['count'];
            $counts[$favoriteType] = $favoriteTypeParsed[$favoriteType]['count'];
            $favorites[$favoriteTypeName[$favoriteType]] = $favoriteTypeParsed[$favoriteType]['favorites'];
        }
    }
    $counts[FAVORITE_ALL] = $totalItems;

    if (isset($favorites)) $res = $favorites;
    $res['counts'] = $counts;
    $res['baseUrl'] = $zapp->config['BASE_URI'] . $zapp->namespace;
    $res['onweb'] = $zapp->config['environment']['onweb'];
    $res['fileTab'] = base64_encode($zapp->language->translate('favorite.tab.file'));
    $res['messagesTab'] = base64_encode($zapp->language->translate('favorite.tab.messages'));
    // render and translate the template
    $tplFile = '/resources/tpl/favorite-popup.tpl';
    $html = $zapp->render( $tplFile, $res );

    if ($html) {
        print $html;
    } else {
        error('invalid template: ' . $tplFile);
    }
}

function favorite_getFavoriteListPerType($zapp)
{
    $itemsPerPage = $zapp->request->getParam("itemsPerPage", 10);
    $pageNumber = $zapp->request->getParam("page", 1);
    $ownerType = $zapp->request->getParam("ownerType", 1);
    $favoriteType = $zapp->request->getParam("type");
    $lastId = $zapp->request->getParam("lastItem", 0);
    $isOnWeb = $zapp->config['environment']['onweb'];
    //if(!$lastId) $lastId = 0;
    $error = false;
    $favoriteTypeParsed = array();
    $lastItem = 0;
    if(!isset($favoriteType)) $error = true;
    if(!$error){
        $user = favorite_helper::getUser($zapp);
        $favoritesPerType = $zapp->model->favorite->getFavoritesByType($user, $ownerType, $favoriteType, $itemsPerPage, $pageNumber, $lastId);
        if(count($favoritesPerType['elements'])){
            $lastItem = $favoritesPerType['elements'][count($favoritesPerType['elements'])-1]['id'];
            $favoriteTypeParsed = favorite_helper::parseFavoriteList($favoritesPerType, $itemsPerPage);
        }
    }
    if( !isset($favoriteTypeParsed[$favoriteType]['favorites']) ) $favoriteTypeParsed[$favoriteType]['favorites'] = array();

    if($lastId == 0){
        $tplFile = '/resources/tpl/favorite-list-type-'.$favoriteType.'.tpl';
    }else{
        $tplFile = '/resources/tpl/sublist-type-'.$favoriteType.'.tpl';
    }

    $html = $zapp->render( $tplFile, array('favorites' => $favoriteTypeParsed[$favoriteType]['favorites'], 'baseUrl' => $zapp->config['BASE_URI'] . $zapp->namespace, 'lastItem' => $lastItem, 'isOnWeb' => $isOnWeb) );

    if ($html) {
        print $html;
    } else {
        error('invalid template: ' . $tplFile);
    }
}

function favorite_add ($zapp)
{
    $zapp->request->setParam("isFavorite", 'false');
    call_user_func_array('favorite_toggle', func_get_args());
}

function favorite_delete ($zapp)
{
    $zapp->request->setParam("isFavorite", 'true');
    call_user_func_array('favorite_toggle', func_get_args());
}

function favorite_migration ($zapp)
{
    $validationToken = $zapp->config['migration']['token'];
    $token = $zapp->request->getParam("token", false);
    if( $token == $validationToken){
        //$user = favorite_helper::getUser($zapp);
        $res = $zapp->model->favorite->migrateFavorites();
        if( $res ) echo "Se ha realizado correctamente.";
        else echo "Token correcto, fallo en la operación.";
    }else{
        echo "El token no es correcto.";
    }
}

function favorite_changeConfig ($zapp)
{
    $user = favorite_helper::getUser($zapp);
    $wallUserStar = $zapp->request->getParam("wallUserStar");
    $res = 'KO';
    if($wallUserStar == 'true'){
        $wallUserStar = true;
    }elseif($wallUserStar == 'false') {
        $wallUserStar = false;
    }
    if(isset( $wallUserStar )){
        $result = $zapp->model->favorite->changeFavoriteConfig($user['organization'], $wallUserStar);
        if($result){
            $message = 'Change succefull';
            $res = 'OK';
        }else{
            $message = 'Error';
        }
    }else{
        $message = 'Params are mandatory';
    }
    echo json_encode( array('message' => $message, 'result' => $res) );
}

function favorite_getZappConfig ($zapp)
{
    $organizationId = $zapp->request->getParam("organizationId");
    $res['checkBox'] = $zapp->model->favorite->getZappConfig( $organizationId );
    $tplFile = '/resources/tpl/config-dialog.tpl';
    $html = $zapp->render( $tplFile, $res );

    if ($html) {
        print $html;
    } else {
        error('invalid template: ' . $tplFile);
    }
}