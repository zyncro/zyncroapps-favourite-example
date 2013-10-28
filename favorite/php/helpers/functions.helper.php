<?php

    CONST FAVORITE_ALL = 0;
    CONST FAVORITE_GROUP = 1;
    CONST FAVORITE_DEPARTMENT = 2;
    CONST FAVORITE_USER = 3;
    CONST FAVORITE_THREAD = 4;
    CONST FAVORITE_FILES = 5;

    CONST FAVORITE_NUM_POPUP = 10;

    class favorite_helper {

        protected  $zapp;

        function __construct($zapp)
        {
            $this->zapp = $zapp;
        }

        public static function connectToDB($zapp)
        {
        }

        public static function getUser($zapp)
        {
            $user = $zapp->request->getParam("user", NULL);

            if (empty($user))
            {
                die("invalid user provided");
            }

            return $user;
        }

        public static function parseAPIGroupsResponse( $urnList, $numElements = 10, $shortNames = false )
        {
            $core = core();
            $baseUrl = $core->zapp->config['BASE_URI'] . $core->zapp->namespace;
            $responseJSONGroups = $core->zapp->oAuth->request('GET', '/api/v1/rest/groups/profiles', ['groups' => implode(',', $urnList)]);
            $result = json_decode($responseJSONGroups);
            $favCount = count($result->elements);
            $elements = array();
            if (isset($result->elements))
            {
                $count = 0;
                foreach ($result->elements as $el)
                {
                    $element = new stdClass();
                    if ($index = array_search($el->urn, $urnList)) $element->index = $index +1;
                    if($el->directoryIcon != ''){
                        $element->imageProfile = $el->directoryIcon;
                        $element->imageProfileList = $el->directoryIcon;
                    }else{
                        $element->imageProfile = $baseUrl . '/image/IconoListadoGrupo.png';
                        $element->imageProfileList = '/imgv2/icons/zyncro/big/folderg.png';
                    }
                    if($shortNames) $element->name = favorite_helper::shorten($el->name, 35);
                    else $element->name = $el->name;
                    $element->urn = $el->urn;
                    $element->type = 1;
                    $element->urn64 = base64_encode($el->urn);
                    $elements[] = $element;
                    if (++$count == $numElements) break;
                }
            }
            $res['favorites'] = $elements;
            $res['count'] = $favCount;
            return $res;
        }

        public static function parseAPIFilesResponse( $urnList, $numElements = 10, $shortNames = false )
        {
            $core = core();
            $user = $core->zapp->request->getParam("user", NULL);
            $responseJSONFiles = $core->zapp->oAuth->request('GET', '/api/v1/rest/documents/profiles/', ['documents' => implode(',', $urnList)]);
            $result = json_decode($responseJSONFiles);
            $favCount = count($result->elements);
            $elements = array();
            if (isset($result->elements))
            {
                $count = 0;
                foreach ($result->elements as $el)
                {
                    $element = new stdClass();
                    if ($index = array_search($el->urn, $urnList)) $element->index = $index +1;
                    $element->urlFile = '#';
                    $urnGroup = $el->groupUrn;
                    $element->class = '';
                    if( $el->documentType == 0 ){
                        $element->fileIcon = '/imgv2/icons/26x26/folder.png';
                        $element->onClick = 'javascript:$.Breadcum.addUrl("/documents/fileslist?urnGroup='.base64_encode($urnGroup).'&document='.base64_encode($el->urn).'&popup=1&orderfield=0&ordertype=0&zsection=groups&myzyncro=&reload=1","'.base64_encode($el->name).'"); $.Zyncro.changeContent("/documents/fileslist?urnGroup='.base64_encode($urnGroup).'&document='.base64_encode($el->urn).'&popup=1&orderfield=0&ordertype=0&zsection=groups&myzyncro=&reload=1", "actual-section",null,false,true); return false;';
                        $element->class = 'jqSelPopupFolderLink';
                        //$element->urlFile = '/index.php?zsection=groups&urnGroup=' . $urnGroup;
                    }elseif( $el->documentType == 1 ){
                        $element->fileIcon = '/imgv2/icons/26x26/file.png';
                        if(isset($user['session'])){
                            $sessionId = base64_decode($user['session']);
                            $element->urlFile = '/synchronizationservice/download?urnGroup=' . $urnGroup . '&urnDocument=' . $el->urn . '&sessionUrn=' . $sessionId;
                        }
                        $element->onClick = '';
                    }

                    if($shortNames) $element->name = favorite_helper::shorten($el->name, 45);
                    else $element->name = $el->name;
                    $element->urn = $el->urn;
                    $element->type = 5;
                    $element->urn64 = base64_encode( $el->urn );
                    $elements[] = $element;
                    if ( ++$count == $numElements ) break;
                }
            }
            $res['favorites'] = $elements;
            $res['count'] = $favCount;
            return $res;
        }

        public static function parseAPIThreadsResponse( $urnList, $numElements = 10, $shortNames = false )
        {
            $core = core();
            $responseJSONThreads = $core->zapp->oAuth->request('GET', '/api/v1/rest/wall/feeds', ['events' => implode(',', $urnList)]);
            $result = json_decode($responseJSONThreads);
            $favCount = count($result->elements);
            $elements = array();
            if (isset($result->elements))
            {
                $count = 0;
                foreach ($result->elements as $el)
                {
                    $element = new stdClass();
                    if ($index = array_search($el->urnEvent, $urnList)) $element->index = $index +1;
                    $element->eventType = $el->eventType;
                    $element->urn = $el->urnEvent;
                    $element->type = 4;
                    $millisecondsDate = $el->date;
                    $seconds = $millisecondsDate / 1000;
                    $date = date("d-m-Y H:i:s", $seconds);
                    $element->date = $date;
                    $element->author = $el->author->fullName;
                    $element->urn64 = base64_encode($el->urnEvent);
                    $params = favorite_helper::getCommentThread($el);
                    $element->comment = $params['comment'];
                    $element->targetLine = $params['targetLine'];
                    $urnOwner64 = $el->author->appID;
                    $element->profileImgOwner = '/user/getimgperfil?width=33&height=33&appid=' . $urnOwner64;
                    $elements[] = $element;
                    if (++$count == $numElements) break;
                }
            }
            $res['favorites'] = $elements;
            $res['count'] = $favCount;
            return $res;
        }

        public static function parseAPIusersResponse( $urnList, $numElements = 10, $shortNames = false )
        {
            $core = core();
            $responseJSONusers = $core->zapp->oAuth->request('GET', '/api/v1/rest/users/profiles', ['users' => implode(',', $urnList)]);
            $result = json_decode($responseJSONusers);
            $favCount = count($result->elements);
            $elements = array();
            if (isset($result->elements))
            {
                $count = 0;
                foreach ($result->elements as $el)
                {
                    $element = new stdClass();
                    if($shortNames) $element->name = favorite_helper::shorten($el->fullName, 45);
                    else $element->name = $el->fullName;
                    $element->urn = $el->appId;
                    $element->type = 3;
                    $element->email = $el->email;
                    $element->urn64 = base64_encode($element->urn);
                    if ($index = array_search($el->appId, $urnList)) $element->index = $index +1;
                    $element->imgProfile = '/user/getimgperfil?width=49&height=49&appid=' . $element->urn;
                    $elements[] = $element;
                    if (++$count == $numElements) break;
                }
            }
            $res['favorites'] = $elements;
            $res['count'] = $favCount;
            return $res;
        }

        public static function parseAPIDepartmentsResponse( $urnList, $numElements = 10, $shortNames = false )
        {
            $core = core();
            $baseUrl = $core->zapp->config['BASE_URI'] . $core->zapp->namespace;
            $responseJSONdepartments = $core->zapp->oAuth->request('GET', '/api/v1/rest/departments/profiles', ['departments' => implode(',', $urnList)]);
            $result = json_decode($responseJSONdepartments);
            $favCount = count($result->elements);
            $elements = array();
            if (isset($result->elements))
            {
                $count = 0;
                foreach ($result->elements as $el)
                {
                    $element = new stdClass();
                    if($el->directoryIcon != ''){
                        $element->imageProfile = $el->directoryIcon;
                        $element->imageProfileList = $el->directoryIcon;
                    }else{
                        $element->imageProfile = $baseUrl . '/image/IconoListadoDepartamento.png';
                        $element->imageProfileList = '/imgv2/empresa.png';
                    }
                    if($shortNames) $element->name = favorite_helper::shorten($el->name, 35);
                    else $element->name = $el->name;
                    $element->urn = $el->urn;
                    $element->type = 2;
                    $element->urn64 = base64_encode($el->urn);
                    if ($index = array_search($el->urn, $urnList)) $element->index = $index +1;
                    $elements[] = $element;
                    if (++$count == $numElements) break;
                }
            }
            $res['favorites'] = $elements;
            $res['count'] = $favCount;
            return $res;
        }

        public static function parseFavoriteList( $favoriteList, $numElements = 10, $shortNames = false )
        {
            $favorites = array();
            $favoriteFunctions = array(
                FAVORITE_GROUP => 'parseAPIGroupsResponse',
                FAVORITE_DEPARTMENT => 'parseAPIDepartmentsResponse',
                FAVORITE_USER => 'parseAPIusersResponse',
                FAVORITE_THREAD => 'parseAPIThreadsResponse',
                FAVORITE_FILES => 'parseAPIFilesResponse'
            );
            foreach( $favoriteList['elements'] as $element ){
                $favoriteType = $element['type'];
                if(!isset($favorites[$favoriteType])) $favorites[$favoriteType] = array();
                array_push($favorites[$favoriteType], $element['id_favorite']);
            }
            $urnListPerType = array();
            $favoriteRes = array();
            foreach ( $favorites as $key => $value ) {
                if ( $value ) {
                    $favoriteRes[$key] = favorite_helper::$favoriteFunctions[$key]( $value, $numElements, $shortNames );
                }
            }
        return $favoriteRes;
        }

        private static function getCommentThread($thread)
        {
            $comment = '';
            $targetLine = '';
            $core = core();
            $isOnWeb = $core->zapp->config['environment']['onweb'];
            switch ($thread->eventType) {
                case 0:
                    if( $thread->thread->shareGroupType == 1 ){
                        $comment = $core->zapp->language->translate('popup.list.thread.create.group.' . $thread->eventType);
                    }elseif( $thread->thread->shareGroupType == 6 ){
                        if($isOnWeb) $comment = $core->zapp->language->translate('popup.list.thread.create.doc.' . $thread->eventType);
                        else $comment = $core->zapp->language->translate('popup.list.thread.create.dept.' . $thread->eventType);
                    }
                    $target = $thread->shareGroupName;
                    $comment = sprintf($comment, $target);
                    $author = $thread->author->fullName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 1:
                    $linkUrlGroup = "<a href='index.php?zsection=zprofilecompany&company=" . base64_encode($thread->thread->shareGroupURN) . "'>".$thread->shareGroupName."</a>";
                    if( $thread->thread->shareGroupType == 1 ){
                        $comment = $core->zapp->language->translate('popup.list.thread.type.group.' . $thread->eventType);
                    }elseif( $thread->thread->shareGroupType == 6 ){
                        if($isOnWeb) $comment = $core->zapp->language->translate('popup.list.thread.type.doc.' . $thread->eventType);
                        else $comment = $core->zapp->language->translate('popup.list.thread.type.dept.' . $thread->eventType);
                    }
                    $comment = sprintf($comment, $linkUrlGroup);
                    $author = $thread->author->fullName;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 2:
                    $linkUrlGroup = "<a href='index.php?zsection=zprofilecompany&company=" . base64_encode($thread->thread->shareGroupURN) . "'>".$thread->shareGroupName."</a>";
                    if( $thread->thread->shareGroupType == 1 ){
                        $comment = $core->zapp->language->translate('popup.list.thread.type.group.' . $thread->eventType);
                    }elseif( $thread->thread->shareGroupType == 6 ){
                        if($isOnWeb) $comment = $core->zapp->language->translate('popup.list.thread.type.doc.' . $thread->eventType);
                        else $comment = $core->zapp->language->translate('popup.list.thread.type.dept.' . $thread->eventType);
                    }
                    $comment = sprintf($comment, $linkUrlGroup);
                    $author = $thread->targetUser->fullName;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 3:
                    $linkUrlFile = $thread->shareDocumentName;
                    $comment = $core->zapp->language->translate('popup.list.file.type.' . $thread->eventType);
                    $comment = sprintf($comment, $linkUrlFile);
                    $author = $thread->author->fullName;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 4:
                    $linkUrlFile = $thread->shareDocumentName;
                    $comment = $core->zapp->language->translate('popup.list.file.type.' . $thread->eventType);
                    $comment = sprintf($comment, $linkUrlFile);
                    $author = $thread->author->fullName;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 5:
                    $linkUrlFile = $thread->shareDocumentName;
                    $comment = $core->zapp->language->translate('popup.list.file.type.' . $thread->eventType);
                    $comment = sprintf($comment, $linkUrlFile);
                    $author = $thread->author->fullName;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 6:
                    $linkUrlFile = $thread->shareDocumentName;
                    $oldName = $thread->oldName;
                    $comment = $core->zapp->language->translate('popup.list.file.type.' . $thread->eventType);
                    $comment = sprintf($comment, $oldName, $linkUrlFile);
                    $author = $thread->author->fullName;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 7:
                    $comment = $thread->comment;
                    $author = $thread->author->fullName;
                    $target = favorite_helper::getThreadTarget($thread);
                    if(isset($thread->targetUser->fullName))$target = $thread->targetUser->fullName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);

                    break;
                case 10:
                    $oldName = $thread->oldName;
                    $linkUrlGroup = "<a href='index.php?zsection=zprofilecompany&company=" . base64_encode($thread->thread->shareGroupURN) . "'>".$thread->shareGroupName."</a>";
                    if( $thread->thread->shareGroupType == 1 ){
                        $comment = $core->zapp->language->translate('popup.list.thread.type.group.' . $thread->eventType);
                    }elseif( $thread->thread->shareGroupType == 6 ){
                        if($isOnWeb) $comment = $core->zapp->language->translate('popup.list.thread.type.doc.' . $thread->eventType);
                        else $comment = $core->zapp->language->translate('popup.list.thread.type.dept.' . $thread->eventType);
                    }
                    $comment = sprintf($comment, $oldName, $linkUrlGroup);
                    $author = $thread->author->fullName;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 11:
                    $linkUrlFile = $thread->shareDocumentName;
                    $fileFolders = explode("/", $linkUrlFile);
                    $fileName = $fileFolders[count($fileFolders)-1];
                    if(count($fileFolders) > 1){
                        $fileContent = $fileFolders[count($fileFolders)-2];
                        $comment = $core->zapp->language->translate('popup.list.thread.type.folder.' . $thread->eventType);
                    }else{
                        $fileContent = $thread->shareGroupName;
                        if( $thread->thread->shareGroupType == 1 ){
                            $comment = $core->zapp->language->translate('popup.list.thread.type.group.' . $thread->eventType);
                        }elseif( $thread->thread->shareGroupType == 6 ){
                            if($isOnWeb) $comment = $core->zapp->language->translate('popup.list.thread.type.doc.' . $thread->eventType);
                            else $comment = $core->zapp->language->translate('popup.list.thread.type.dept.' . $thread->eventType);
                        }
                    }
                    $comment = sprintf($comment, $fileName, $fileContent);
                    $author = $thread->author->fullName;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 50:
                case 60:
                    $comment = $thread->comment;
                    $author = $thread->payload->feedChannelTitle;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf($textTargetLine, $author, $target);
                    break;
                case 80:
                    $title = $thread->payload->task->title;
                    $target = $thread->shareGroupName;
                    $textTargetLine = $core->zapp->language->translate('popup.thread.target');
                    $targetLine = sprintf( $textTargetLine, $title, $target );
                    $comment = $core->zapp->language->translate('popup.list.thread.type.' . $thread->eventType);
                    $comment = sprintf( $comment, $title, $target );
                    break;
                default:
                    $comment = "No comment here";
            }
            $targetLine = favorite_helper::shorten($targetLine, 46);
            $comment = favorite_helper::shorten($comment, 46);
            return array('comment' => $comment, 'targetLine' => $targetLine);
        }

        private static function getThreadTarget ($thread)
        {
            switch ($thread->thread->shareGroupType) {
                case -1:
                    if(isset($thread->targetUser->fullName)) $target = $thread->targetUser->fullName;
                    else($thread->targetUsers[0]->fullName); $target = $thread->targetUsers[0]->fullName;
                    break;
                case 1:
                case 4:
                case 5:
                case 6:
                    $target = $thread->shareGroupName;
                    break;
                default:
                    $target = '';
            }
            return $target;
        }

        private static function getImageByFileName ($fileName)
        {
            $imageTypes = array('bmp', 'png', 'jpg', 'gif', 'tif', 'tiff', 'jpeg');
            $audioTypes = array('wav', 'mp3', );
            $fileTypes = array('pdf' => '/imgv2/icons/26x26/pdf.png', 'png' => '');
            $fileName = strtolower($fileName) ;
            $extension = array_pop(explode('.',$fileName));

            //return $extension;
        }

        private static function shorten($string, $width)
        {
            if(strlen($string) > $width-3) {
                $string = wordwrap($string, $width-3, "\n");
                $pos = strpos($string, "\n");
                $string = substr($string, 0, $pos);
                $string = $string . '...';
            }
            return $string;
        }

    }
