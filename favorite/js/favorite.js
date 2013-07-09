var logHeader = '[ZAPP '+zapp.name+'] ';
myConsole.debug(logHeader+'start');

// CSS (this is just for the development version)
zapp.css('base');

// CONST
function CONST_FAVORITES_ALL()         { return 0; }
function CONST_FAVORITES_GROUP()       { return 1; }
function CONST_FAVORITES_DEPARTMENT()  { return 2; }
function CONST_FAVORITES_USER()        { return 3; }
function CONST_FAVORITES_THREAD()      { return 4; }
function CONST_FAVORITES_FILES()       { return 5; }

// AJAX calls
zapp.request.ajax.add("addFavorite", 'favorite/add');          // adds an element to the favourites
zapp.request.ajax.add("deleteFavorite", 'favorite/delete');    // remove an element from the favourites
zapp.request.ajax.add("getFavorites", 'favorite/get');         // gets all the favourites elements of the user
zapp.request.ajax.add("getFavoritesPopup", 'favorite/getPopup', {}, 'html');  // gets all the favourites elements of the user

// attributes
zapp.favorites = [];    // stores the information of the groups consulted to extension
zapp.favorites[CONST_FAVORITES_ALL()]       = [];
zapp.favorites[CONST_FAVORITES_GROUP()]     = [];
zapp.favorites[CONST_FAVORITES_DEPARTMENT()]= [];
zapp.favorites[CONST_FAVORITES_USER()]      = [];
zapp.favorites[CONST_FAVORITES_THREAD()]    = [];
zapp.favorites[CONST_FAVORITES_FILES()]     = [];
zapp.favCount = [];    // stores the number of favorites of every type

// templates
zapp.favoriteStarTpl = '<a class="iconized favorite-star favorite-state-{{state}}" title="{{title}}" urn="{{urn}}" type="{{type}}">&nbsp</a>';
zapp.favoritesPopup = '<div id="popup-favorites" class="popup-notification-invitation display-none"><div class="popup-favorites-loading" id="fav-list-popup"><span class="msg-no-elements">'+zapp.t('popup.list.empty');+'</span></div></div>';

// methods
zapp.renderFavorites = function() {             // parses the entire page to add the required favourite icons
    zapp.parseHtml();
};

zapp.getFavorites = function(type) {   // gets all the favourites elements of the user and stores them locally
    type = typeof type !== 'undefined' ? type : CONST_FAVORITES_ALL();  // by default all the favourites

    // call to ext server
    zapp.call("getFavorites", {type: type}, function(data)
    {
        if (data && data['elements'] && data['counts'])
        {
            var elements = data['elements'];
            var counts = data['counts'];
            // add all the returned favourites to the local object
            for (var i = 0; i < elements.length; i++)
            {
                zapp.favorites[elements[i]['type']][elements[i]['urn']] = elements[i]['id'];
            }
            // recolect the counts
            for (var j in counts)
            {
                zapp.favCount[j] = counts[j];
            }
        }
        // renderize all the page with the information retrieved
        zapp.renderFavorites();
    });
};

zapp.parseHtml = function()            // parses the page looking for valid elements for the favourites
{
    zapp.parseHtmlGroups();
    zapp.buildFavoritesDropdown();
};

zapp.parseHtmlGroups = function()       // parses the page looking for groups in the DOM
{
    var type = CONST_FAVORITES_GROUP();

    // This parses the group title (if we are inside a group)
    if ($("span#group-title").length)
    {
        var urn = $.Z.request.getURLParam("urnGroup");      // get the group urn from the URL
        var status = zapp.getStatus(urn, type);
        var title = zapp.t('tooltip.favorite.'+status);

        // build the html of the favourites star
        var starHtml = zapp.buildStar(urn, type, status, title);
        // add the html of the star to the group
        $("span#group-title").after(starHtml);
    }

    // This parses the group list
    $.each($("li div.group-actions"), function()
    {
        var urn = $(this).find("a.menu-action").attr("id");
        var status = zapp.getStatus(urn, type);
        var title = zapp.t('tooltip.favorite.'+status, 'favorite');

        // build the html of the favourites star
        var starHtml = zapp.buildStar(urn, type, status, title);
        // add the html of the star to the group
        $(this).append(starHtml);
    });

    // bind events to the newly created stars
    $("body").on('click', '.favorite-star', zapp.processFavorite);

    // adds options to the contextual menu (right-click menu)
    $.Z.zyncro.menu.contextual.addElement(
        'group',
        zapp.t('tooltip.favorite.active'),
        'favoritegroup favoritegroupremove',
        'favoritesContextualMenuAction(\'deleteFavorite\');',
        'last',
        zapp.showContextualFavoriteOption
    );
    $.Z.zyncro.menu.contextual.addElement(
        'group',
        zapp.t('tooltip.favorite.inactive'),
        'favoritegroup favoritegroupadd',
        'favoritesContextualMenuAction(\'addFavorite\');',
        'last',
        zapp.showContextualFavoriteOption
    );
};

zapp.buildFavoritesDropdown = function ()       // builds the notification icon in the nav bar and the popup with the list
{
    // adds an icon in the navbar to load the favourites
    $.Z.zyncro.menu.navbar.addNotificationIcon(
        zapp.t('tooltip.favorite.list'),
        'favorite-menu'
    );

    // add popup to the icon
    $('#favorite-menu').livequery(function () {
        // add popup html
        $('li#favorite-menu').append(zapp.favoritesPopup);
        zapp.call('getFavoritesPopup', {}, function(data) {
            $('#popup-favorites #fav-list-popup').html(data);
            $('#fav-list-popup > ul').hover(function () {
                $(this).find('ul').removeClass('none');
            }, function () {
                $(this).find('ul').addClass('none');
            });
        });
        // bind click event
        $('li#favorite-menu').click(function (event) {
            event.stopPropagation();
            $(this).addClass('hover');
            $(this).find('#popup-favorites').removeClass('display-none');
        });
        // if the page is clicked outside the popup, hide it
        $('html').click(function () {
            $('li#favorite-menu').removeClass('hover');
            $('li#favorite-menu #popup-favorites').addClass('display-none');
        });
    });
};

zapp.getStatus = function(urn, type)            // gets the status of the favourite
{
    var status = "inactive";

    // if the group is in the favourites list of the user
    if (typeof zapp.favorites[type][urn] !== "undefined")
    {
        status = "active";
    }

    return status;
};

zapp.buildStar = function(urn, type, status, title)     // builds the html for the favourites star
{
    // renders the favourite star template
    return $.Z.helper.parser.renderize(
        zapp.favoriteStarTpl,
        {
            type: type,
            urn: urn,
            state: status,
            title: title
        }
    );
};

zapp.processFavorite = function()           // treats the element of the event
{
    // get the type, urn and actual state
    var element = $(this);
    var type = element.attr('type');
    var urn = element.attr('urn');
    var isFollowing = element.hasClass('favorite-state-active');
    var action = '';
    var params = {
        urn: urn,
        type: type
    };
    // call to extension server
    if (!isFollowing)
    {
        action = 'addFavorite';
    }
    else
    {
        action = 'deleteFavorite';
        params["id"] = zapp.favorites[type][urn];
    }
    // send action to extension server
    zapp.processAction(action, params, element, type, urn);
};

zapp.processAction = function(action, params, element, type, urn)         // sends the action and treats the resulting element
{
    zapp.call(action, params, function(data) {
        if (data && data.result == "OK")
        {
            var isFollowing = element.hasClass('favorite-state-active');
            // update the list of favourites & html
            if (!isFollowing)
            {
                zapp.favorites[type][urn] = data.code;
                element.removeClass('favorite-state-inactive');
                element.addClass('favorite-state-active');
                element.attr('title', zapp.t('tooltip.favorite.active'));
            }
            else
            {
                delete zapp.favorites[type][urn];
                element.removeClass('favorite-state-active');
                element.addClass('favorite-state-inactive');
                element.attr('title', zapp.t('tooltip.favorite.inactive'));
            }
        }
    });
};

zapp.showContextualFavoriteOption = function()              // show the desired option in the contextual menu
{
    var info = $('.contextual-menu-information-layer');
    var urn = info.attr('urn');
    var element = $('body').find('a.favorite-star[urn='+urn+']');
    var isFollowing = element.hasClass('favorite-state-active');
    if (isFollowing)
    {
        $('#mnu-ctx-group .favoritegroupadd').addClass('none');
        $('#mnu-ctx-group .favoritegroupremove').removeClass('none');
    }
    else
    {
        $('#mnu-ctx-group .favoritegroupremove').addClass('none');
        $('#mnu-ctx-group .favoritegroupadd').removeClass('none');
    }
};

favoritesContextualMenuAction = function (action)           // Process the action from de contextual menu
{
    var info = $('.contextual-menu-information-layer');
    var urn = info.attr('urn');
    var element = $('body').find('a.favorite-star[urn='+urn+']');
    if (element.length)
    {
        var type = element.attr('type');
        var params = {
            urn: urn,
            type: type
        };
        if (action == 'deleteFavorite') params["id"] = zapp.favorites[type][urn];
        // send action to extension server
        zapp.processAction(action, params, element, type, urn);
    }
};

zapp.showFavorites = function()             // collapsable menu with the different types of favourites
{

};

// initialization
zapp.getFavorites();

