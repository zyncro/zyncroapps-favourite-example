{% if lastItem != 0 %}
    {% for favorite in favorites %}
        <li class="favorite-list-element" index="{{ favorite.index }}">
            <div class="favorite-element" urn="{{ favorite.urn64 }}">
                <a href="/index.php?zsection=zprofile&contact={{ favorite.urn64 }}">
                    <img src="{{ favorite.imgProfile }}">
                    <span class="favorite-list-user-name">{{ favorite.name|e }}</span>
                </a>
                <span class="favorite-list-user-email">{{ favorite.email }}</span>
                <a type="3" urn="{{ favorite.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
            </div>
        </li>
    {% endfor %}
    <div class="lastPaginationItem" style="display: none" lastItem="{{ lastItem }}"></div>
{% else %}
    <li class="no-more-results"></li>
{% endif %}