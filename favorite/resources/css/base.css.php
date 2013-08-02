/**
 * Favorite Star image position:
 * normal => 0 0
 * selected => -15px 0
 * inactive => -62px 0
 */

a.favorite-star {
    background-repeat: no-repeat;
    background-position: 0 2px;
    width: 15px;
    height: 18px;
}

body.skin-legacy a.favorite-star {
    background-image: url(<?php echo $BASE_URL; ?>image/fav-sprite.png);
}

body.skin-zen a.favorite-star {
    background-image: url(<?php echo $BASE_URL; ?>image/fav-sprite-zen.png);
}

body.skin-legacy a.favorite-star {
    padding: 0;
    margin: -3px 9px 0 0;
}

a.favorite-star:hover {
    text-decoration: none;
}

a.favorite-state-inactive {
    background-position: -62px 2px;
}

a.favorite-state-inactive:hover {
    background-position: -15px 2px;
}

a.favorite-star-inactive-hover {
    background-position: -15px 2px;
}

#left-header-group span a.favorite-star {
    margin-left: 10px;
}

body.skin-legacy #left-header-group span a.favorite-star {
    margin: 8px 0 0 10px;
    display: inline-block;
    height: 6px;
    padding: 12px 0 0;
}

ul#group-list span.title-groups-name {
    width: 42%;
}

ul#group-list span.title-groups-actions {
    width: 18%;
}

ul#group-list div.group-information {
    width: 34%;
}

ul#group-list div.group-actions {
    width: 18%;
}

/* NAVBAR MENU ICON */

ul#icons-menu #favorite-menu a.popup-launcher {
    background-image: url(<?php echo $BASE_URL; ?>image/fav-sprite.png);
    background-repeat: no-repeat;
    background-position: 0 2px;
    width: 15px;
    height: 18px;
}

body.skin-legacy ul#icons-menu #favorite-menu a.popup-launcher {
    padding: 0;
    margin: 4px 10px 10px 10px;
}

/* CONTEXTUAL MENU MENU ICON */

#cnt-ctx-menu ul li.favoritegroup a {
    background-image: url(<?php echo $BASE_URL; ?>image/blue_star.png);
    background-repeat: no-repeat;
    background-position: 0 2px;
}

/* FAVORITES POPUP */

#favorite-menu #fav-list-popup {
    width: 300px;
    padding: 0;
}

#popup-favorites ul.favorite-popup-list {
    width: 100%;
    background: #fff;
    padding: 0;
    margin: 0;
    overflow: hidden;
}

#popup-favorites ul.favorite-popup-list li > a {
    color: #333;
    text-decoration: none;
    display: block;
    line-height: 20px;
    background: url(<?php echo $BASE_URL; ?>image/popup_sprites.jpg) no-repeat;
    outline: 0;
    padding: 10px 15px 10px 80px;
    font-size: .8em;
    font-weight: normal;
    width: 210px;
    height: 40px;
}

#popup-favorites ul.favorite-popup-list li > a > div.fav-group-name {
    font-weight: bold;
    font-size: .9em;
}

#popup-favorites ul.favorite-popup-list li > a > div.fav-group-link {
    font-weight: normal;
    font-size: .7em;
    color: #0066ff;
}

#popup-favorites ul.favorite-popup-list li > a:hover {
    background-color: #f7f7f7;
}

#popup-favorites ul.favorite-popup-group-list {
    width: 200px;
    border: 1px solid #ccc;
    position: absolute;
    background: white;
    top: -1px;
    left: -202px;
    overflow: hidden;
}

#popup-favorites ul.favorite-popup-group-list > li > a {
    background: white;
    padding: 4px 4px 4px 10px;
    font-size: .8em;
    width: 210px;
    height: 14px;
}