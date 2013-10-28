// Load Configure button
zapp.events.configure = function() {
    var dialogTitle = zapp.language.translate('favorite.config.title');
    var params = { width: 450, height: 158 };
    var newDialog = $.Z.zyncro.objects.dialogs.load(
        'favorite',
        'favoriteConfigDialog',
        dialogTitle,
        params
    );
    //get favorite config from DB
    var organizationId = $.Zyncro_Apps.getOrganizationId();
    zapp.statusCheckBox = false;
    zapp.call("getZappConfig", { organizationId: organizationId }, function(data) {
        console.log("entra en restultado");
        if(data){
            console.log("entra dentro de data");
            newDialog.html(data);
            // show dialog
            //newDialog.show();
        }
    });
};

zapp.events.load = function() {
	zapp.debug('start');
	
	/* CONST */
	zapp.CONST_FAVORITES_TYPES = { ALL: 0, GROUP: 1};
    zapp.FAVORITES_CEILING = 10000;
    zapp.IS_ON_WEB = true;

	/* define arrays & variables */
	zapp.favCount = [];
	zapp.favorites = {};
    zapp.favoritesPerType = [];

	/* load resources */
	zapp.css('base');
	
    /* define AJAX calls */
    zapp.request.ajax.add("getFavoriteListPerType", 'favorite/getFavoriteListPerType', {}, 'html');          // gets list of a favorite type
    zapp.request.ajax.add("toggleFavorite", 'favorite/toggle');          // adds or delete an element to the favourites
    zapp.request.ajax.add("addFavorite", 'favorite/add');          // adds an element to the favourites
    zapp.request.ajax.add("deleteFavorite", 'favorite/delete');    // remove an element from the favourites
    zapp.request.ajax.add("getFavorites", 'favorite/list');         // gets all the favourites elements of the user
    zapp.request.ajax.add("getFavoritesPopup", 'favorite/getPopup', {}, 'html');  // gets all the favourites elements of the user
    zapp.request.ajax.add("changeZappConfig", 'favorite/changeConfig'); //change the zapp config on DB
    zapp.request.ajax.add("getZappConfig", 'favorite/getZappConfig', {}, 'html');

    /* gets all the favourites elements of the user and stores them locally */
    zapp.call("getFavorites", {type: zapp.CONST_FAVORITES_TYPES['ALL'], itemsPerPage: zapp.FAVORITES_CEILING}, function(data) {
        if (data && data['elements'] && data['counts']) {
            var elements = data['elements'];
            var counts = data['counts'];

            if(elements != ''){
                $("#favorites-menu a.popup-launcher").livequery( function() {
                    $(this).addClass("fav-active");
                });
            }
            /* add all the returned favourites to the local object */
            for (var i = 0; i < elements.length; i++) {
                var urn64 = $.base64Encode( elements[i]['urn'] );
				var element_type = elements[i]['type'];
                zapp.favorites[ urn64 ] = element_type;
                if(typeof(zapp.favoritesPerType[ element_type ]) === 'undefined'){
                    zapp.favoritesPerType[ element_type ] = [];
                }
                zapp.favoritesPerType[ element_type ].push(elements[i]['urn']);
            }
            /* recolect the counts */
			if (counts =! null) {
                zapp.favCount = counts;
			}
        }
        /* renderize all the page with the information retrieved */
        zapp.renderFavorites();
    });
};

/* parses the page looking for valid elements for the favourites */
zapp.renderFavorites = function() {
    zapp.changeFavoriteConfig();
    zapp.parseHtmlGroups();
    zapp.parseHtmlFiles();
    zapp.parseHtmlThreads();
    zapp.parseHtmlDepartments();
    zapp.parseHtmlUsers();
    zapp.buildFavoritesDropdown();
    zapp.bindShowWallClicks();
    zapp.bindShowListClicks();
    zapp.bindPaginationListClick();
    zapp.hideSideMenuOnLink();
};

zapp.changeFavoriteConfig = function( params ) {
    $("#jqSelFavConfWallStar").click(function(){
        var params = [];
        params['organizationId'] = $.Zyncro_Apps.getOrganizationId();
        params['wallUserStar'] = false;
        if($(this).prop( "checked" )) params['wallUserStar'] = true;
        zapp.call("changeZappConfig", params, function(data) {
            if(data.result){
                $("#jqSelMessageLog").append(data.message);
            }
        });
    });
};

/* parses the page looking for groups in the DOM */
zapp.parseHtmlGroups = function() {
    var type = zapp.CONST_FAVORITES_TYPES['GROUP'];
    /* This parses the group title (if we are inside a group) */
    if ($("span#group-title").length) {
        var urn = $.Z.request.getURLParam("urnGroup");
        var status = zapp.getStatus(urn, type);
        var title = zapp.language.translate('tooltip.favorite.'+status);
        /* build the html of the favourites star */
        var starHtml = zapp.buildStar(urn, type, status, title);
        /* add the html of the star to the group */
        $("span#group-title").after(starHtml);
    }

    /* This parses the group list */
	zapp.events.addListener('zyncro_group', function() {
		$.each($('ul#group-list li div.group-actions'), function() {
            if($(this).find(".favorite-star").length < 1){
                var urn = $(this).find('a.dorpdown').attr('id');
                var status = zapp.getStatus(urn, type);
                var title = zapp.language.translate('tooltip.favorite.'+status, 'favorite');
                /* build the html of the favourites star */
                var starHtml = zapp.buildStar(urn, type, status, title);
                /* add the html of the star to the group */
                $(this).append(starHtml);
            }
		});
	}, true);
	
    /* bind events to the newly created stars */
    $('body').on('click', '.favorite-star', zapp.processFavorite);
    $("ul#wall-threads li.wall-thread .wall-thread-read .wall-thread-photo .favorite-star").unbind("click");

	/* contextual menu: add group to favorites */
	$.Z.zyncro.menu.contextual.groups.list('favorite_grouplist_add', zapp.language.translate('tooltip.favorite.inactive'), null, function(data) {
        /* onclick event */
        var urn64 = $.base64Encode( data );
        var type = zapp.CONST_FAVORITES_TYPES['GROUP'];
        zapp.proccesFavoriteContextual( urn64, type );
	}, function( data ) {
        /* onvisible event */
        var visible = true;
        var urn64 = $.base64Encode( data );
        if( typeof zapp.favorites[urn64] !== 'undefined' ){
            visible = false;
        }
		return visible;
	});

	/* contextual menu: remove group to favorites */
	$.Z.zyncro.menu.contextual.groups.list('favorite_grouplist_remove', zapp.language.translate('tooltip.favorite.active'), null, function(data) {
		/* onclick event */
        var urn64 = $.base64Encode( data );
        var type = zapp.CONST_FAVORITES_TYPES['GROUP'];
        zapp.proccesFavoriteContextual( urn64, type );
	}, function(data) {
		/* onvisible event */
        var visible = true;
        var urn64 = $.base64Encode( data );
        if( typeof zapp.favorites[urn64] === 'undefined' ){
            visible = false;
        }
		return visible;
	});
};

/* builds the html for the favourites star */
zapp.buildStar = function(urn, type, status, title) {
    var arguments = { type: type, urn: urn, state: status, title: title };
    return zapp.jsview.get( 'favoriteStar', arguments );
};

zapp.proccesFavoriteContextual = function( urn64, type ){
    var status = zapp.getStatus(urn64, type);
    var isFavorite = false;
    var urn = $.base64Decode(urn64);
    if( status == 'active' ) isFavorite = true;
    var params = { isFavorite : isFavorite, urn: urn, favoriteType: type };
    var action = 'toggleFavorite';
    zapp.processAction( action, params );
};

/* treats the element of the event */
zapp.processFavorite = function(event) {
    event.preventDefault();
    /* get the type, urn and actual state */
    var element = $(this);
    var urn = $.base64Decode(element.attr('urn'));
    var params = {
        isFavorite : element.hasClass('favorite-state-active'),
        urn: urn,
        favoriteType: element.attr('type')
    };
    var action = 'toggleFavorite';
    /* send action to extension server */
    zapp.processAction(action, params);
    event.stopPropagation();
};

/* sends the action and treats the resulting element */
zapp.processAction = function(action, params) {
    zapp.call(action, params, function(data) {
        if (data && data.result == "OK") {
            var urn = $.base64Encode( params.urn );
            var type = params.favoriteType;
            var isFollowing = !params.isFavorite;
			/* update the list of favourites & html */
            if (isFollowing) {
                zapp.favorites[urn] = [type];
                zapp.favoritesPerType[type].push($.base64Decode(urn));
            } else {
                delete zapp.favorites[urn];
                zapp.removeFromArray(zapp.favoritesPerType[type], $.base64Decode(urn));
            }
			zapp.starStatus(urn, isFollowing);
        }
    });
};

/* gets the status of the favourite */
zapp.getStatus = function( urn, type ) {
    return ( typeof zapp.favorites[ urn ] !== 'undefined' ) ? 'active' : 'inactive';
};

zapp.starStatus = function( urn, status ) {
	if (status) {
		$('a.favorite-star[urn="'+urn+'"]').removeClass('favorite-state-inactive').addClass('favorite-state-active');
	} else {
		$('a.favorite-star[urn="'+urn+'"]').removeClass('favorite-state-active').addClass('favorite-state-inactive');
	}
};

/* builds the notification icon in the nav bar and the popup with the list */
zapp.buildFavoritesDropdown = function () {
    /* adds an icon in the navbar to load the favourites */
	$.Z.zyncro.notifications.add('favorites', zapp.language.translate('tooltip.favorite.list'), function(event) {
        var loadingtext = zapp.language.translate('favorite.loading.text');
        $('#favorites-menu .popup-content').html('<div id="favorite-popup-layer-loading"><img src="/imgv2/loading_b.gif"><div style="clear: both;"></div>'+loadingtext+'</div>').removeClass('none').removeClass('display-none');
		var me = $(this);
		zapp.call('getFavoritesPopup', {}, function(data) {
			$('#favorites-menu .popup-content').html(data).removeClass('none');
            $("#favorites-menu").addClass('hover');
			$('#favorites-menu .popup-content > ul').hover(function () {
				$(this).find('ul').removeClass('none');
			}, function () {
				$(this).find('ul').addClass('none');
			});
			
			/* bind function to show star on favorite hover */
			$('.favorite-popup-list ul li > .favorite-element').hover(function () {
				$(this).find('.favorite-star').show();
			}, function () {
				$(this).find('.favorite-star').hide();
			});

			/* bind click favorite icon on element to remove of favorites */
			$(".favorite-popup-active").click( function(){
				var me = $(this);
				var urn = $(this).attr("data-urn");
				var type = $(this).attr("type");
				var favoriteId = zapp.favorites[type][urn];
				
				zapp.call('deleteFavorite', {'id': favoriteId }, function(data) {
					if(data.result == 'OK'){
						me.removeClass("favorite-popup-active");
						me.addClass("favorite-popup-inactive");
						var count = $("#favorites-groups-count").html();
						count--;
						$("#favorites-groups-count").html(count);
					}
				});
			});
			
			$('#favorites-menu .popup-content').removeClass('display-none');
		});
		
		/* if the page is clicked outside the popup, hide it */
		$('html').click(function (event) {
			if (!$(event.target).hasClass('prevent-default')) {
				$('li#favorites-menu').removeClass('hover');
				$('li#favorites-menu .popup-content').addClass('display-none');
			}
		});	
	});
};

zapp.activeStar = function(urn)
{
    $.each($(".favorite-state-inactive"), function(i, val) {
        if($(this).attr("urn") == urn){
            $(this).removeClass("favorite-state-inactive");
            $(this).addClass("favorite-state-active");
            $(this).attr('title', zapp.language.translate('tooltip.favorite.active'));
        }
    });
};

zapp.showDinamicWallPerType = function ( type )
{
    var urnList = [];
    if(typeof(zapp.favoritesPerType[ type ]) !== 'undefined' ){
        urnList = zapp.favoritesPerType[ type ];
    }
    type = parseInt( type );

    if(zapp.IS_ON_WEB){
        var title = zapp.language.translate( 'favorite.wall.title.' + type + '.documents');
    }else{
        var title = zapp.language.translate( 'favorite.wall.title.' + type );
    }
    if($("#general-section #main-content-header p.main-content-header-title").length < 1){
        $("#general-section #main-content-header").append('<p class="main-content-header-title"></p>');
    }
    $("#general-section #main-content-header p.main-content-header-title").html(title);

    var params = {
        urnsEvent: [],
        urnsGroup: [],
        urnsDepartment: [],
        urnsDocument: [],
        appIds: [],
        urnsPersonalFeed: [],
        eventTypes: [],
        orderField: 1,
        eventsCount: 12,
        childEventsCount: 5,
        dateFilter: null,
        pageNumber: 1,
        hideCommentBox: 1
    };

    switch( type )
    {
        case 1:
            params.urnsGroup = urnList;
            break;
        case 2:
            params.urnsDepartment = urnList;
            break;
        case 3:
            params.appIds = urnList;
            break;
        case 4:
            params.urnsEvent = urnList;
            break;
        case 5:
            params.urnsDocument = urnList;
            break;
        default:
            return false;
    }
    zapp.clearZyncroHeaders();
    $.Zyncro.buildWallWithFilters('#actual-section', params);    
};

zapp.getParamFromUrl = function ( paramName, url ) {
    paramName = paramName.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+paramName+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    if( results == null ){
        return "";
    }else{
        return results[1];
    }
};

zapp.showFavoriteListPerType = function ( type )
{
    if ( typeof itemsPerPage == 'undefined' ) var itemsPerPage = 10;
    if ( typeof page == 'undefined' ) var page = 1;
    type = parseInt( type );
    var title = 'favorite.list.title.' + type;
    if( zapp.IS_ON_WEB  && type == 2 ) title = title + '.doc';

    zapp.call('getFavoriteListPerType',
        {
            type: type,
            itemsPerPage:itemsPerPage,
            page: page
        }, function(data) {
        if ( data ) {
            if($("#general-section #main-content-header p.main-content-header-title").length < 1){
                $("#general-section #main-content-header").append('<p class="main-content-header-title"></p>');
            }
            $("#general-section #main-content-header p.main-content-header-title").html(zapp.language.translate( title ));
            $("#actual-section").html(data);
            //$.Z.zyncro.section.html( zapp.language.translate( title ), data );
            zapp.clearZyncroHeaders();
        }
    });
};

zapp.bindShowWallClicks = function ()
{
    $("#favorites-menu").on("click", ".jqSelShowWallType", function(e){
        e.preventDefault();
        var type = $(this).attr("type");
        zapp.showDinamicWallPerType(type);
    });
};

zapp.bindShowListClicks = function ()
{
    $("#favorites-menu").on("click", ".jqSelShowListType", function(e){
        e.preventDefault();
        var type = $(this).attr("type");
        zapp.showFavoriteListPerType(type);
    });
};

zapp.hideSideMenuOnLink = function ()
{
    $("#favorites-menu").on("click", ".jqSelPopupFolderLink", function(){
        $("#main-content #sidebar").hide();
    });
    $("#favorites-menu").on("click", ".jqSelShowWallEvent", function(){
        $("#main-content #sidebar").hide();
    });
};

zapp.bindPaginationListClick = function ()
{
    $("#general-section").on("click", ".more-favorite-elements", function(){
        var me = $(this);
        var type = me.attr("type");
        var lastItem = me.attr("lastItem");
        zapp.call('getFavoriteListPerType',
            {
                type: type,
                itemsPerPage:10,
                lastItem: lastItem
            }, function(data) {
                if ( data ) {
                    $(".favorite-list-type ul").append(data);
                    if($(".favorite-list-type ul li:last-child").hasClass('no-more-results')){
                        $(".more-favorite-elements").html(zapp.language.translate('favorite.list.no.more.results'));
                        $(".more-favorite-elements").removeClass('more-favorite-elements')
                            .addClass('favorite-no-more-results');
                    }else{
                        var newLastItem = $("#actual-section .favorite-list-type ul .lastPaginationItem:last").attr("lastItem");
                        $(".more-favorite-elements").attr("lastItem", newLastItem);
                    }
                }
            });
    })
};

zapp.showFavoriteThread = function ( urnThread )
{
    zapp.clearZyncroHeaders();
    var params = {
        urnsEvent: urnThread,
        orderField: 1,
        eventsCount: 12,
        childEventsCount: 5,
        dateFilter: null,
        pageNumber: 1,
        hideCommentBox: 1
    };
    $.Zyncro.buildWallWithFilters('#actual-section', params);
};

zapp.clearZyncroHeaders = function ()
{
    /* delete breadcum, especific buttons, and sidebar menu */
    if( $("#sidebar").length > 0 ) $("#sidebar").hide();
    if( $("#main-content-header ul.breadcum").length > 0 ) $("#main-content-header ul.breadcum").hide();
    if( $("#main-content-header a.button").length > 0 ) $("#main-content-header a.button").hide();
    if( $("#main-content-header #profile").length > 0 ) $("#main-content-header #profile").hide();
    if( $("#main-content-header #department").length > 0 ) $("#main-content-header #department").hide();
};

$("body").on("click", "#jqSelBtnCancelFavconf", function(e){
    e.preventDefault();
    // $.Z.zyncro.objects.dialogs.items["favorite_favoriteConfigDialog"].hide();
    var newDialog = $.Z.zyncro.objects.dialogs.items["favorite_favoriteConfigDialog"];
    newDialog.hide();
});

$("body").on("click", "#jqSelBtnAcceptFavconf", function(){
    var params = [];
    var newDialog = $.Z.zyncro.objects.dialogs.items["favorite_favoriteConfigDialog"];
    params['wallUserStar'] = false;
    if($(".config-favorite-zapp #jqSelFavonfWallStar").prop( "checked" )) params['wallUserStar'] = true;
    zapp.call("changeZappConfig", params, function(data) {
        if(data.result){
            newDialog.hide();
        }
    });
});

zapp.removeFromArray = function (arr, item) {
    for(var i = arr.length; i--;) {
        if(arr[i] === item) {
            arr.splice(i, 1);
        }
    }
};