{% if lastItem != 0 %}
    {% for favorite in favorites %}
        <li class="favorite-list-element">
            <div class="favorite-element" urn="{{ favorite.urn64 }}">
                <a href="{{ favorite.urlFile|raw }}" onclick="{{ favorite.onClick }}">
                    <img src="{{ favorite.fileIcon }}">
                    {{ favorite.name|e }}
                </a>
                <a type="5" urn="{{ favorite.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
            </div>
        </li>
    {% endfor %}
    <div class="lastPaginationItem" style="display: none" lastItem="{{ lastItem }}"></div>
{% else %}
    <li class="no-more-results"></li>
{% endif %}