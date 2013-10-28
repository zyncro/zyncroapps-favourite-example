<div style="clear: both"></div>
{%  if counts[0] == 0 %}
    <div class="no-favorite-results">
        {t} popup.list.empty {/t}
    </div>
{% else %}
    {#groups section#}
    {% if counts[1] > 0 %}
        {#<div style="clear: both"></div>#}
        <ul class="favorite-popup-list groups-section">
            <li>
                <div class="greyArrowFavoritePopup"></div>
                <a href="#" title="{t} popup.list.groups {/t}" onclick="return false;">
                    <div class="fav-group-name">
                        <span id="favorites-groups-count">{{ counts[1] }}</span> {t} popup.list.groups {/t}
                    </div>
                    <div class="fav-group-link jqSelShowWallType" type="1">
                        {t} popup.list.wall.groups {/t}
                    </div>
                </a>
                <!-- submenu groups -->
                <ul class="favorite-popup-group-list favorite-popup-type-1 none">
                    <li>
                        <a href="#" title="{t} popup.list.groups.all {/t}" onclick="return false;">
                            <div class="fav-group-name-all jqSelShowListType" type="1">
                                {t} popup.list.groups.all {/t}
                            </div>
                        </a>
                    </li>
                    <!-- list all the groups -->
                    {% for group in groups %}
                        <li>
                            <div class="favorite-element" urn="{{ group.urn64 }}">
                                <a href="/index.php?zsection=groups&urnGroup={{ group.urn64 }}">
                                    <div class="popup-content-group-img">
                                        <img src="{{ group.imageProfile }}">
                                    </div>
                                    {#<div style="float:left;"></div>#}
                                    {{ group.name|e }}
                                </a>
                                <div class="jqSelActionLinks">
                                    <a class="jqSelLinkToFiles" href="/index.php?zsection=groups&urnGroup={{ group.urn64 }}&tab={{ fileTab }}" style="display: block;"></a>
                                    <a class="jqSelLinkToWall" href="/index.php?zsection=groups&urnGroup={{ group.urn64 }}&tab={{ messagesTab }}" style="display: block;"></a>
                                </div>
                                <a style="display: none;" type="1" urn="{{ group.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
                            </div>
                        </li>
                    {% endfor %}
                </ul>
            </li>
        </ul>
    {% endif %}
    {#files section#}
    {% if counts[5] > 0 %}
        <ul class="favorite-popup-list files-section">
            <li>
                <div class="greyArrowFavoritePopup"></div>
                <a href="#" title="{t} popup.list.files {/t}" onclick="return false;">
                    <div class="fav-file-name">
                        <span id="favorites-files-count">{{ counts[5] }}</span> {t} popup.list.files {/t}
                    </div>
                    <div class="fav-file-link jqSelShowWallType" type="5">
                        {t} popup.list.wall.files {/t}
                    </div>
                </a>
                <!-- submenu files -->
                <ul class="favorite-popup-file-list favorite-popup-type-5 none">
                    <li>
                        <a href="#" title="{t} popup.list.files.all {/t}" onclick="return false;">
                            <div class="fav-file-name-all jqSelShowListType" type="5">
                                {t} popup.list.files.all {/t}
                            </div>
                        </a>
                    </li>
                    <!-- list all the files -->
                    {% for file in files %}
                        <li>
                            <div class="favorite-element" urn="{{ file.urn64 }}">
                                <a class="{{ file.class }}" href="{{ file.urlFile }}" onclick="{{ file.onClick }}">
                                    <img width="16" src="{{ file.fileIcon }}">
                                    {{ file.name|e }}
                                </a>
                                <a style="display: none;" type="5" urn="{{ file.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
                            </div>
                        </li>
                    {% endfor %}
                </ul>
            </li>
        </ul>
    {% endif %}
    {#threads section#}
    {% if counts[4] > 0 %}
        <ul class="favorite-popup-list threads-section">
            <li>
                <div class="greyArrowFavoritePopup"></div>
                <a href="#" title="{t} popup.list.threads {/t}" onclick="return false;">
                    <div class="fav-thread-name">
                        <span id="favorites-threads-count">{{ counts[4] }}</span> {t} popup.list.threads {/t}
                    </div>
                    <div class="fav-thread-link jqSelShowWallType" type="4">
                        {t} popup.list.wall.threads {/t}
                    </div>
                </a>
                <!-- submenu threads -->
                <ul class="favorite-popup-thread-list favorite-popup-type-4 none">
                    <li>
                        <a href="#" title="{t} popup.list.threads.all {/t}" onclick="return false;">
                            <div class="fav-thread-name-all jqSelShowWallType" type="4">
                                {t} popup.list.threads.all {/t}
                            </div>
                        </a>
                    </li>
                    <!-- list all the threads -->
                    {% for thread in threads %}
                        <li>
                            <div class="favorite-element" urn="{{ thread.urn64 }}">
                                <a class="jqSelShowWallEvent" href="#" onclick="javascript:$.Z.apps.items.favorite.showFavoriteThread( '{{ thread.urn }}' );">
                                    <div style="width: 35px; height: 35px; float:left;">
                                        <img style="float: left; margin-right: 5px; width: 33px;" src="{{ thread.profileImgOwner }}">
                                    </div>
                                    <span class="targetThread">{{ thread.targetLine }}</span></BR>
                                    <span class="commentThread">{{ thread.comment|raw }}</span></BR>
                                    <span class="dateThread">{{ thread.date }}</span></BR>
                                </a>
                                <a style="display: none;" type="4" urn="{{ thread.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
                            </div>
                        </li>
                    {% endfor %}
                </ul>
            </li>
        </ul>
    {% endif %}
    {#departments section#}
    {% if counts[2] > 0 %}
        <ul class="favorite-popup-list departments-section">
            <li>
                <div class="greyArrowFavoritePopup"></div>

                {% if onweb == 1 %}
                    <a href="#" title="{t} popup.list.documentations {/t}" onclick="return false;">
                {% else %}
                        <a href="#" title="{t} popup.list.departments {/t}" onclick="return false;">
                {% endif %}

                    <div class="fav-department-name">
                        <span id="favorites-departments-count">{{ counts[2] }}</span>
                        {% if onweb == 1 %}
                            {t} popup.list.documentations {/t}
                        {% else %}
                            {t} popup.list.departments {/t}
                        {% endif %}
                    </div>
                    <div class="fav-department-link jqSelShowWallType" type="2">
                        {% if onweb == 1 %}
                            {t} popup.list.wall.documentations {/t}
                        {% else %}
                            {t} popup.list.wall.departments {/t}
                        {% endif %}
                    </div>
                </a>
                <!-- submenu departments -->
                <ul class="favorite-popup-department-list favorite-popup-type-2 none">
                    <li>
                        {% if onweb == 1 %}
                            <a href="#" title="{t} popup.list.documentations.all {/t}" onclick="return false;">
                        {% else %}
                            <a href="#" title="{t} popup.list.departments.all {/t}" onclick="return false;">
                        {% endif %}

                            <div class="fav-department-name-all jqSelShowListType" type="2">
                                {% if onweb == 1 %}
                                    {t} popup.list.documentations.all {/t}
                                {% else %}
                                    {t} popup.list.departments.all {/t}
                                {% endif %}

                            </div>
                        </a>
                    </li>
                    <!-- list all the departments -->
                    {% for department in departments %}
                        <li>
                            <div class="favorite-element" urn="{{ department.urn64 }}">
                                <a href="/index.php?zsection=zprofilecompany&company={{ department.urn64 }}">
                                    <div class="popup-content-department-img">
                                        <img src="{{ department.imageProfile }}">
                                    </div>
                                    {{ department.name|e }}
                                </a>
                                <div class="jqSelActionLinks">
                                    <a class="jqSelLinkToFiles" href="/index.php?zsection=zprofilecompany&company={{ department.urn64 }}&t={{ fileTab }}" style="display: block;"></a>
                                    <a class="jqSelLinkToWall" href="/index.php?zsection=zprofilecompany&company={{ department.urn64 }}&tab={{ messagesTab }}" style="display: block;"></a>
                                </div>
                                <a style="display: none;" type="2" urn="{{ department.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
                            </div>
                        </li>
                    {% endfor %}
                </ul>
            </li>
        </ul>
    {% endif %}
    {#users section#}
    {% if counts[3] > 0 %}
        <ul class="favorite-popup-list users-section">
            <li>
                <div class="greyArrowFavoritePopup"></div>
                <a href="#" title="{t} popup.list.users {/t}" onclick="return false;">
                    <div class="fav-department-name">
                        <span id="favorites-users-count">{{ counts[3] }}</span> {t} popup.list.users {/t}
                    </div>
                    <div class="fav-user-link jqSelShowWallType" type="3">
                        {t} popup.list.wall.users {/t}
                    </div>
                </a>
                <!-- submenu users -->
                <ul class="favorite-popup-user-list favorite-popup-type-3 none">
                    <li>
                        <a href="#" title="{t} popup.list.users.all {/t}" onclick="return false;">
                            <div class="fav-user-name-all jqSelShowListType" type="3">
                                {t} popup.list.users.all {/t}
                            </div>
                        </a>
                    </li>
                    <!-- list all the users -->
                    {% for user in users %}
                        <li>
                            <div class="favorite-element" urn="{{ user.urn64 }}">
                                <a href="/index.php?zsection=zprofile&contact={{ user.urn64 }}">
                                    <img width="16" height="16" src="{{ user.imgProfile }}">
                                    {{ user.name|e }}
                                </a>
                                <a style="display: none;" type="3" urn="{{ user.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
                            </div>
                        </li>
                    {% endfor %}
                </ul>
            </li>
        </ul>
    {% endif %}
{% endif %}