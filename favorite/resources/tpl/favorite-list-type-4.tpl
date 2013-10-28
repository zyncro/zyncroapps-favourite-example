<div class="favorite-list-thread-type favorite-list-type" type="4">
    <div class="favorite-list-group-header">
        {t}favorite.type.group{/t}
    </div>
    <ul id="jqSelThreadList">
        {% for favorite in favorites %}
            <li class="favorite-list-element">
                <div class="favorite-element" urn="{{ favorite.urn64 }}">
                    <img style="float: left; margin-right: 5px; width: 28px;" src="{{ favorite.profileImgOwner }}">
                    <span class="targetThread">{{ favorite.targetLine }}</span></BR>
                    <span class="commentThread">{{ favorite.comment|raw }}</span></BR>
                    <span class="dateThread">{{ favorite.date }}</span></BR>
                    <a type="4" urn="{{ favorite.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
                </div>
            </li>
        {% endfor %}
    </ul>
</div>
<div class="more-favorite-elements" lastItem="{{ lastItem }}" type="4">
    {t}favorite.list.show.more.results{/t}
</div>

