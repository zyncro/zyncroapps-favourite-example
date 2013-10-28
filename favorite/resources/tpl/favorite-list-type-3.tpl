<div class="favorite-list-user-type favorite-list-type" type="3">
    <div class="favorite-list-header">
        {t}favorite.type.users{/t}
    </div>
    <ul id="jqSelUserList">
        {% for favorite in favorites %}
            <li class="favorite-list-element" index="{{ favorite.index }}">
                <div class="favorite-element" urn="{{ favorite.urn64 }}">
                    <a href="/index.php?zsection=zprofile&contact={{ favorite.urn64 }}">
                        <div class="favorite-list-user-profile-img">
                            <img src="{{ favorite.imgProfile }}">
                        </div>
                        <span class="favorite-list-user-name">{{ favorite.name|e }}</span>
                    </a>
                    <div class="favorite-list-user-email">{{ favorite.email }}</div>
                    <a type="3" urn="{{ favorite.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
                </div>
            </li>
        {% endfor %}
    </ul>
</div>
<div class="more-favorite-elements" lastItem="{{ lastItem }}" type="3">
    {t}favorite.list.show.more.results{/t}
</div>

