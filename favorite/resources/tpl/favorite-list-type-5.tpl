<div class="favorite-list-file-type favorite-list-type" type="5">
    <div class="favorite-list-header">
        {t}favorite.type.files{/t}
    </div>
    <ul id="jqSelFileList">
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
    </ul>
</div>
<div class="more-favorite-elements" lastItem="{{ lastItem }}" type="5">
    {t}favorite.list.show.more.results{/t}
</div>
