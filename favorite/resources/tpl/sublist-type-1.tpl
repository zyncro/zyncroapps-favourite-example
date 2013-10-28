{% if lastItem != 0 %}
    {% for favorite in favorites %}
        <li class="favorite-list-element">
            <div class="favorite-element" urn="{{ favorite.urn64 }}">
                <a href="/index.php?zsection=groups&urnGroup={{ favorite.urn64 }}">
                    <div class="list-content-group-img">
                        <img src="{{ favorite.imageProfileList }}">
                    </div>
                    {{ favorite.name|e }}
                </a>
                <a type="1" urn="{{ favorite.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
            </div>
        </li>
    {% endfor %}
    <div class="lastPaginationItem" style="display: none" lastItem="{{ lastItem }}"></div>
{% else %}
    <li class="no-more-results"></li>
{% endif %}
