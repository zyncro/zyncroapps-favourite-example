<div class="favorite-list-group-type favorite-list-type" type="1">
    <div class="favorite-list-header">
        {t}favorite.type.group{/t}
    </div>
    <ul id="jqSelGroupList">
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
    </ul>
</div>
<div class="more-favorite-elements" lastItem="{{ lastItem }}" type="1">
    {t}favorite.list.show.more.results{/t}
</div>

