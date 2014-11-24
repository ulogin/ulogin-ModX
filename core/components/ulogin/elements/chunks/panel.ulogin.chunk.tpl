<div class="ulogin_block">
    [[+show_message:eq=`1`:then=`
        [[!uLogin.Message?&id=`[[+ul_id]]`]]
    `]]
    [[!+script]]
    <div id="[[+ul_id]]" [[+uloginid:notempty=`data-uloginid="[[+uloginid]]"`]] data-ulogin="[[+uloginid:eq=``:then=`display=[[+display]];fields=[[+fields]];providers=[[+providers]];hidden=[[+hidden]];`]]redirect_uri=[[uLogin.Urlencode?&url=`[[+redirect]]`]];callback=[[+callback]]"></div>
    <div style="clear:both"></div>
</div>