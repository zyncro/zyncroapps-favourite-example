<div class="favorite-list-department-type favorite-list-type" type="2">
    <div class="favorite-list-header">
        {% if isOnWeb == 1 %}
            {t}favorite.type.documentation{/t}
        {% else %}
            {t}favorite.type.departments{/t}
        {% endif %}
    </div>
    <ul id="jqSelDeptList">
        {% for favorite in favorites %}
            <li class="favorite-list-element">
                <div class="favorite-element" urn="{{ favorite.urn64 }}">
                    <a href="/index.php?zsection=zprofilecompany&company={{ favorite.urn64 }}">
                        <div class="list-content-department-img">
                            <img src="{{ favorite.imageProfileList }}">
                        </div>
                        {{ favorite.name|e }}
                    </a>
                    <a type="2" urn="{{ favorite.urn64 }}" title="{t} tooltip.favorite.active {/t}" class="iconized favorite-star favorite-state-active">&nbsp;</a>
                </div>
            </li>
        {% endfor %}
    </ul>
</div>
<div class="more-favorite-elements" lastItem="{{ lastItem }}" type="2">
    {t}favorite.list.show.more.results{/t}
</div>

