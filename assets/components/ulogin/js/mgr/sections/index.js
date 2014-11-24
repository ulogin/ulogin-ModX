Ext.onReady(function() {
    MODx.load({ xtype: 'ulogin-page-home'});
});

Ulogin.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'ulogin-panel-home'
            ,renderTo: 'ulogin-panel-home-div'
        }]
    });
    Ulogin.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(Ulogin.page.Home,MODx.Component);
Ext.reg('ulogin-page-home',Ulogin.page.Home);