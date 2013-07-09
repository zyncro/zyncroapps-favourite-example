{%  if counts[0] == 0 %}
    <span class="msg-no-elements">
        {t} popup.list.empty {/t}
    </span>
{% else %}
    <ul class="favorite-popup-list">
        <li>
            <a href="#" title="{t} popup.list.groups {/t}" onclick="return false;">
                <div class="fav-group-name">
                    {{ counts[1] }} {t} popup.list.groups {/t}
                </div>
                <div class="fav-group-link">
                    {t} popup.list.wall.groups {/t}
                </div>
            </a>
            <!-- submenu -->
            <ul class="favorite-popup-group-list none">
                <li>
                    <a href="#" title="{t} popup.list.groups.all {/t}" onclick="return false;">
                        <div class="fav-group-name">
                            {t} popup.list.groups.all {/t}
                        </div>
                    </a>
                </li>
                <!-- list all the groups -->
                {% for group in groups %}
                    <li>
                        <a href="#" title="{{ group.name|e }}" onclick="return false;">
                            {{ group.name|e }}
                        </a>
                    </li>
                {% endfor %}
            </ul>
        </li>
    </ul>
{% endif %}


