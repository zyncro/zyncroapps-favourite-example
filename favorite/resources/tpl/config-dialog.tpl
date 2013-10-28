<div class="config-favorite-zapp">
    <div class="config-image-favorite-config">
        <img src="/zyncroapps/v2/favorite/image/avatar_favorito.png">
    </div>
    {% if checkBox == true %}
        <input id="jqSelFavonfWallStar" type="checkbox" checked="checked">
    {% else %}
        <input id="jqSelFavonfWallStar" type="checkbox">
    {% endif %}
    <p class="favorite-config-text">{t}favorite.config.text{/t}</p>
    <div style="clear: both"></div>
    <div class="popup-favorite-config-buttons">
        {{ button('accept', 'jqSelBtnAcceptFavconf', '{t}favorite.button.accept{/t}')|raw }}
        {{ button('cancel', 'jqSelBtnCancelFavconf', '{t}favorite.button.cancel{/t}')|raw }}
    </div>
</div>