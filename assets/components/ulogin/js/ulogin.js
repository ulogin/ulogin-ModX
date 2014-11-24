if ( (typeof jQuery === 'undefined') && !window.jQuery ) {
    document.write(unescape("%3Cscript type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js'%3E%3C/script%3E%3Cscript type='text/javascript'%3EjQuery.noConflict();%3C/script%3E"));
} else {
    if((typeof jQuery === 'undefined') && window.jQuery) {
        jQuery = window.jQuery;
    } else if((typeof jQuery !== 'undefined') && !window.jQuery) {
        window.jQuery = jQuery;
    }
}

function uloginCallback(token){
    var url = document.URL.replace(/#.*$/g, ''),
        param = 'ulogin_action=callback';
    url = url + (location.search ? '&'+param : '?'+param);
    jQuery.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {token: token},
        success: function (data) {
            switch (data.answerType) {
                case 'error':
                    uloginMessage(data.messId, data.title, data.msg, data.answerType);
                    break;
                case 'success':
                    if (jQuery('.ulogin_accounts').length > 0){
                        adduLoginNetworkBlock(data.networks, data.messId, data.title, data.msg);
                    } else {
                        location.reload();
                    }
                    break;
                case 'verify':
                    // Верификация аккаунта
                    uLogin.mergeAccounts(token);
                    break;
                case 'merge':
                    // Синхронизация аккаунтов
                    uLogin.mergeAccounts(token, data.existIdentity);
                    break;
            }
        }
    });
}

function uloginMessage(messId, title, msg, answerType) {
    var messagePanel = jQuery('.messagePanel');
    messagePanel.removeClass('error success');
    if (messagePanel.length > 0) {
        messagePanel.addClass(answerType);
        messagePanel.find('.title').html(title);
        messagePanel.find('.message').html(msg);
        messagePanel.show();
    }
}

function adduLoginNetworkBlock(networks, messId, title, msg) {
    var uAccounts = jQuery('.ulogin_accounts');
    uAccounts.each(function(){
        for (var uid in networks) {
            var network = networks[uid],
                uNetwork = jQuery(this).find('[data-ulogin-network='+network+']');

            if (uNetwork.length == 0) {
                var onclick = '';
                if (jQuery(this).hasClass('can_delete')) {
                    onclick = ' onclick="uloginDeleteAccount(\'' + network + '\')"';
                }
                jQuery(this).append(
                    '<div data-ulogin-network="' + network + '" class="ulogin_provider big_provider ' + network + '_big"' + onclick + '></div>'
                );
                uloginMessage(messId, title, '', 'success');
            } else {
                if (uNetwork.is(':hidden')) {
                    uloginMessage(messId, title, '', 'success');
                }
                uNetwork.show();
            }
        }
    });

}

function uloginDeleteAccount(network){
    var url = document.URL.replace(/#.*$/g, ''),
        param = 'ulogin_action=deleteaccount';
    url = url + (location.search ? '&'+param : '?'+param);
    jQuery.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        cache: false,
        data: {
            network: network
        },
        error: function (data, textStatus, errorThrown) {
            alert('Не удалось выполнить запрос');
            //console.log(textStatus);
            //console.log(errorThrown);
            //console.log(data);
        },
        success: function (data) {
            switch (data.answerType) {
                case 'error':
                    uloginMessage(data.messId, data.title, data.msg, 'error');
                    break;
                case 'success':
                    var nw = jQuery('.ulogin_accounts').find('[data-ulogin-network='+network+']');
                    if (nw.length > 0) nw.hide();
                    uloginMessage(data.messId, data.title, data.msg, 'success');
                    break;
            }
        }
    });
}