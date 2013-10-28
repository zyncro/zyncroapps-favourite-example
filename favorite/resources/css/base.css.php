body.skin-legacy ul#icons-menu #favorites-menu a.iconized.popup-launcher,
body.skin-legacy a.favorite-star,
a.favorite-state-inactive ,
body.skin-legacy ul#icons-menu #favorites-menu a.iconized.fav-active,
.greyArrowFavoritePopup,
div#cnt-ctx-menu ul#mnu-ctx-group li.favorite_grouplist_remove a,
div#cnt-ctx-menu ul#mnu-ctx-group li.favorite_grouplist_add a,
div#cnt-ctx-menu ul#mnuCtxFolder li.favorite_grouplist_remove a,
div#cnt-ctx-menu ul#mnuCtxFolder li.favorite_grouplist_add a,
div#cnt-ctx-menu ul#mnuCtxFile li.favorite_grouplist_remove a,
div#cnt-ctx-menu ul#mnuCtxFile li.favorite_grouplist_add a {
    background-image: url("<?php echo $BASE_URL; ?>image/fav-sprite-v2.png") !important;
    background-repeat: no-repeat;
}

/* remove favorite group from contextual menu */

div#cnt-ctx-menu ul#mnu-ctx-group li.favorite_grouplist_add a,
div#cnt-ctx-menu ul#mnuCtxFolder li.favorite_grouplist_add a,
div#cnt-ctx-menu ul#mnuCtxFile li.favorite_grouplist_add a {
    background-position: 16px -294px;
}

div#cnt-ctx-menu ul#mnu-ctx-group li.favorite_grouplist_remove a,
div#cnt-ctx-menu ul#mnuCtxFolder li.favorite_grouplist_remove a,
div#cnt-ctx-menu ul#mnuCtxFile li.favorite_grouplist_remove a {
    background-position: 16px -268px;
}

#wall-threads .wall-thread .wall-thread-photo .simpleFavoriteStar{
    background-image: url("<?php echo $BASE_URL; ?>image/wallStar.png") !important;
    background-repeat: no-repeat;
    width: 18px;
    height: 18px;
    position: absolute;
    margin-left: -48px;
}

/* chrome styles */

@media screen and (-webkit-min-device-pixel-ratio:0) {
    #wall-threads .wall-thread .wall-thread-photo .simpleFavoriteStar{
        margin-left: 0;
        margin-top: -48px;
    }
}

.display-none{
    display: none;
}

.no-favorite-results {
    background-color: #FFFFFF;
    font-size: 0.813em;
    text-align: center;
}

a.favorite-star {
    background-repeat: no-repeat;
    background-position: 0 2px;
    width: 15px;
    height: 18px;
}

body.skin-legacy a.favorite-star {
    background-position: -2px -202px;
    width: 18px;
    height: 18px;
}

body.skin-zen a.favorite-star {
    background-image: url("<?php echo $BASE_URL; ?>image/fav-sprite-zen.png");
}

body.skin-legacy a.favorite-star {
    padding: 0;
    margin: -3px 9px 0 0;
}

a.favorite-star:hover {
    text-decoration: none;
}

a.favorite-state-inactive {
    background-position: -2px -182px !important;
    width: 18px;
    height: 18px;
}

a.favorite-state-inactive:hover {
    background-position: -2px -162px !important;
    width: 18px;
    height: 18px;
}

#left-header-group span a.favorite-star {
    margin-left: 10px;
}

body.skin-legacy #left-header-group span a.favorite-star {
    margin: 8px 0 0 10px;
    display: inline-block;
    height: 6px;
    padding: 12px 0 0;
    float: left;
}

/*ul#group-list span.title-groups-name {
    width: 42%;
}

ul#group-list span.title-groups-actions {
    width: 18%;
}*/

/*ul#group-list div.group-information {
    width: 34%;
}*/

/*ul#group-list div.group-actions {*/
    /*width: 21%;*/
/*}*/

ul#group-list div.group-actions a.favorite-star {
    opacity: 1;
}

.greyArrowFavoritePopup{
    background-position: 0 -244px;
    width: 10px;
    height: 14px;
    float: left;
    position: relative;
    margin-top: 23px;
    margin-left: 5px;
}

/* NAVBAR MENU ICON */

body.skin-legacy ul#icons-menu #favorites-menu a.popup-launcher {
    background-position: -2px -54px;
    background-repeat: no-repeat;
    margin: 2px 0 5px 5px;
    height: 11px;
    width: 5px;
}

body.skin-legacy ul#icons-menu #favorites-menu a.fav-active {
    background-position: -2px -108px !important;
}

body.skin-legacy ul#icons-menu #favorites-menu a.popup-launcher {

}

/* CONTEXTUAL MENU MENU ICON */

#cnt-ctx-menu ul li.favoritegroup a {
    background-image: url("<?php echo $BASE_URL; ?>image/blue_star.png");
    background-repeat: no-repeat;
    background-position: 0 2px;
}

/* FAVORITES POPUP */

#favorites-menu .popup-content {
    border: 1px solid #CFCFCF;
    border-top: none;
    clear: both;
    padding: 0;
    position: relative;
    right: 265px;
    width: 300px;
}

#favorites-menu .popup-content ul.favorite-popup-list {
    width: 100%;
    background: #fff;
    padding: 0;
    margin: 0;
    overflow: hidden;
    border-bottom: 1px solid #CFCFCF;
}

#favorites-menu .popup-content ul.favorite-popup-list li > a {
    color: #333;
    text-decoration: none;
    display: block;
    line-height: 20px;
    /*background: url(<?php echo $BASE_URL; ?>image/popup_sprites.jpg) no-repeat;*/
    outline: 0;
    padding: 10px 15px 10px 80px;
    font-size: 0.813em;
    font-weight: normal;
    width: 210px;
    height: 40px;
}

.jqSelActionLinks{
    margin: -16px 0 0 221px;
    padding: 0;
    position: absolute;
    width: 19%;
    height: 19px;
}

.jqSelLinkToWall,
.jqSelLinkToFiles {
    background-image: url("/imgv2/iconos-menu.png");
    height: 18px;
    margin: 0;
    padding: 0;
    width: 20px;
}

.jqSelLinkToFiles{
    background-position: -70px -27px;
    display: block;
    float: left;
    margin: 0 5px 0 0;
}

.jqSelLinkToWall{
    display: block;
    float: left;
    background-position: -70px -2px;
}


#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-group-name,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-file-name,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-thread-name,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-department-name,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-user-name{
    color: #3f3f3f;
    font-weight: bold;
    font-size: 1em;
}

#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-group-name-all,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-file-name-all,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-thread-name-all,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-department-name-all,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-user-name-all {
    color: #005CE6;
}

#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-group-link,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-file-link,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-thread-link,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-department-link,
#favorites-menu .popup-content ul.favorite-popup-list li > a > div.fav-user-link{
    font-weight: normal;
    font-size: 0.85em;
    color: #0066ff;
}

#favorites-menu .popup-content ul.favorite-popup-list li > a:hover {
    background-color: #f7f7f7;
}

#favorites-menu .popup-content ul.favorite-popup-list ul li > a {
    background-color: #f7f7f7;
    padding-bottom: 9px;
    font-size: 0.7em;
}

#favorites-menu .popup-content ul.favorite-popup-group-list,
#favorites-menu .popup-content ul.favorite-popup-file-list,
#favorites-menu .popup-content ul.favorite-popup-thread-list,
#favorites-menu .popup-content ul.favorite-popup-department-list,
#favorites-menu .popup-content ul.favorite-popup-user-list{
    width: 300px;
    border: 1px solid #ccc;
    position: absolute;
    background: white;
    left: -302px;
    overflow: hidden;
    margin-top: -60px;
}

#favorites-menu .popup-content ul.favorite-popup-group-list > li > a,
#favorites-menu .popup-content ul.favorite-popup-file-list > li > a,
#favorites-menu .popup-content ul.favorite-popup-thread-list > li > a,
#favorites-menu .popup-content ul.favorite-popup-department-list > li > a,
#favorites-menu .popup-content ul.favorite-popup-user-list > li > a{
    background: white;
    padding: 4px 4px 4px 10px;
    font-size: 0.813em;
    width: 100%;
    height: 14px;
}

#favorites-menu .popup-content .favorite-popup-list li {
    width: 300px !important;
}

#favorites-menu .popup-content .favorite-popup-star{
    float: right;
    margin-right: 27px;
    width: 15px;
    height: 15px;
}

#favorites-menu .popup-content .favorite-popup-active {
    background-image: url("<?php echo $BASE_URL; ?>image/blue_star.png");
}

#favorites-menu .popup-content .favorite-popup-inactive {
    background-image: url("<?php echo $BASE_URL; ?>image/blue_star_hover.png");
}

#favorites-menu .popup-content .favorite-element {
    font-size:0.75em;
    padding: 3px;
    padding-left: 5px;
}

#favorites-menu .popup-content .favorite-element > a {
    text-decoration: none;
    color: #3f3f3f;
}

#favorites-menu .popup-content .favorite-element:hover {
    background-color: #f7f7f7;
}

#favorites-menu .popup-content .favorite-star {
    float: right;
    margin-right: 0;
}

ul#wall-threads li.wall-thread a.favorite-star{
    position: absolute;
    margin-left: -30px;
    margin-top: 0;
}

ul#wall-threads li.wall-thread a.favorite-star:hover{
    text-decoration: none;
}

/* Departments */

ul#enterprises-list li.contact-item a.favorite-star{
    margin-left: 135px;
    /*margin-top: 14px;*/
    float: right;
}

#department .department-info #department-name a.favorite-star{
    position: absolute;
    margin-left: 5px;
}

/* Users */

ul#contact-list li.contact-item a.favorite-star{
    float: right;
    margin-right: 20px;
    margin-top: 2px;
}

body.zprofile #profile p.profile-info a.favorite-star{
    margin-left: 9px;
    margin-top: 2px;
    position: absolute;
}

/* folders */

#actual-section .file-folder-information{
    width: 456px;
}

ul#files-list .group-actions{
    width: 152px;
}

/* popup images */

.popup-content .groups-section > li > a {
    background: url("<?php echo $BASE_URL; ?>image/SeccionGrupos.png") no-repeat scroll 25px center;
}

.popup-content .files-section > li > a {
    background: url("<?php echo $BASE_URL; ?>image/SeccionArchivosCarpetas.png") no-repeat scroll 25px center;
}

.popup-content .threads-section > li > a {
    background: url("<?php echo $BASE_URL; ?>image/SeccionMensajes.png") no-repeat scroll 25px center;
}

.popup-content .departments-section > li > a {
    background: url("<?php echo $BASE_URL; ?>image/SeccionDepartamentos.png") no-repeat scroll 25px center;
}

.popup-content .users-section > li > a{
    background: url("<?php echo $BASE_URL; ?>image/SeccionPersonas.png") no-repeat scroll 25px center;
}

.favorite-popup-thread-list .targetThread {
    font-size: 0.95em;
    font-weight: bold;
}

.favorite-popup-thread-list .commentThread {
    font-size: 0.95em;
}

.favorite-popup-thread-list .dateThread {
    font-size: 0.88em;
    color: #62627E;
}

.favorite-popup-thread-list .favorite-star {
    margin-top: -31px !important;
}

/* favorite lists per type */
.favorite-list-type{
    width: 100%;
    margin-bottom: 25px;
}

#actual-section .more-favorite-elements,
#actual-section .favorite-no-more-results{
    background-color: #F7F7F7;
    border: 1px solid #E8E8E8;
    margin-top: 15px;
    padding: 2px 5px 5px;
    color: #3e3e3e;
    font-size: 0.75em;
}

#actual-section a{
    text-decoration: none;
}

#actual-section .favorite-star  {
    float: right;
}

ul#enterprises-list li.contact-item a.favorite-star{
    position: relative;
    top: 6px;
}

#actual-section li.favorite-list-element {
    min-height: 32px;
    padding: 5px;
    border-bottom: 1px solid #E6E6E6;
}

#actual-section ul#jqSelGroupList li.favorite-list-element .favorite-element{
    padding-top: 2px;
}

#actual-section .favorite-list-type .favorite-element img {
    margin-right: 5px;
}

#actual-section .more-favorite-elements {
    cursor: pointer;
}

#actual-section .favorite-list-type .favorite-list-element:hover {
    background-color: #F7F7F7;
}

/* user favorite list */

#actual-section .favorite-list-user-name {
    position: absolute;
}

#actual-section .favorite-list-user-email {
    color: #3e3e3e;
    font-size: 0.688em;
    margin-left: 54px;
    margin-top: 37px;
    position: absolute;
}

.favorite-list-user-profile-img{
    float: left;
}

#wall-threads .wall-thread .wall-thread-photo a.favorite-star {
    margin-left: -48px;
    margin-top: 0;
}

.config-favorite-zapp{
    padding: 10px;
    margin-top: 15px;
}

#jqSelFavConfWallStar{
    margin-left: 11px;
    margin-top: 10px;
}

/* styles for favorite groups list */
.favorite-list-group-type .favorite-list-element .favorite-element .favorite-star{
    margin-top: 5px;
}
/* styles for favorite departments list */
#jqSelDeptList .favorite-list-element .favorite-element .favorite-star{
    margin-top: 8px;
}
/* styles for favorite user list */
#jqSelUserList .favorite-list-element .favorite-element .favorite-star{
    margin-top: 17px;
}

#jqSelUserList .favorite-list-element .favorite-element{
    min-height: 52px;
}
/* styles for favorite files list */
#jqSelFileList .favorite-list-element .favorite-element .favorite-star {
    margin-top: 9px;
}
/*#jqSelFileList .favorite-list-element .favorite-element{
    padding-top: 11px;
}*/

#favorite-element .favorite-list-element .favorite-element .favorite-star{
    margin-top: 5px;
}

/* styles for files on popup */

.favorite-popup-file-list .favorite-element .favorite-star{
    margin-top: 3px;
}

.popup-favorite-config-buttons{
    margin-top: 20px;
}

.popup-favorite-config-buttons input{
    border-radius: 2px 2px 2px 2px;
    cursor: pointer;
    float: right;
    font-size: .8em;
    font-weight: bold;
    height: 15px;
    width: 51px;
    float: right;
}

.config-favorite-zapp > input{
    margin-right: 10px;
    margin-top: 8px;
}

.popup-favorite-config-buttons a{
    float: right;
    font-size: 0.9em;
    margin-right: 13px;
    padding-top: 3px;
    text-decoration: none;
}

.config-image-favorite-config{
    width: 40px;
    height: 40px;
    float: left;
    margin: 0 16px 5px 5px;
}

.favorite-config-text{
    float: right;
    width: 345px;
    font-size: 0.8em;
    margin-top: 7px;
}

#favorite-popup-layer-loading {
    background-color: #FFFFFF;
    width: 100%;
    height: 50px;
    text-align: center;
    font-size: 0.7em;
    padding: 5px 0 5px 0;
}

#favorite-popup-layer-loading img{
    width: 30px;
}

.favorite-list-header{
    background-color: #F7F7F7;
    border-bottom: 1px solid #E6E6E6;
    color: #A9A9A9;
    font-size: 0.7em;
    padding-bottom: 8px;
    padding-top: 3px;
    width: 100%;
    margin-top: 8px;
}

.favorite-list-type .favorite-element a {
    color: #046CBC;
    font-size: 0.813em;
    font-weight: bold;
}

.popup-content-group-img{
    float: left;
    margin-right: 3px;
    min-width: 20px;
}

.popup-content-group-img > img {
    max-height: 13px;
    max-width: 23px;
    height: 22px;
}

.popup-content-department-img{
    float: left;
    margin-right: 3px;
    min-width: 20px;
}

.popup-content-department-img > img {
    max-height: 13px;
    max-width: 23px;
    height: 22px;
}

popup-content-user-img {

}

popup-content-user-img img {

}

/* tamañp de iconos en listados */

.list-content-group-img{
    float: left;
    margin-right: 3px;
    min-width: 35px;
}

.list-content-group-img > img {
    max-height: 35px;
    max-width: 35px;
    height: 23px;
}

.list-content-department-img{
    float: left;
    margin-right: 3px;
    min-width: 35px;
}

.list-content-department-img > img {
    max-height: 35px;
    max-width: 35px;
    height: 35px;
}