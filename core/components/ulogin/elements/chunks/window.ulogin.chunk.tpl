<div class="ulogin_block">
    [[+show_message:eq=`1`:then=`
    [[!uLogin.Message?&id=`[[+ul_id]]`]]
    `]]
    <a href="#" id="[[+ul_id]]" style = "border:0px;" data-ulogin="display=window;fields=[[+fields]];providers=[[+providers]];hidden=[[+hidden]];redirect_uri=[[uLogin.Urlencode?&url=`[[+redirect]]`]];callback=[[+callback]]"><img src="http://ulogin.ru/img/button.png" width=187 height=30 alt="[[+win_tip]]"/></a>
    <script>uLogin.customInit('[[+ul_id]]')</script>
    <div style="clear:both"></div>
</div>