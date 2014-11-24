Ulogin.grid.Ulogin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'ulogin-grid-ulogin'
        ,url: Ulogin.config.connectorUrl
        ,baseParams: { action: 'mgr/getList' }
        ,save_action: 'mgr/updateFromGrid'
        ,fields: ['id','uloginid']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'uloginid'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: _('ulogin.uloginid')
            ,dataIndex: 'uloginid'
            ,sortable: true
            //,width: 100
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            text: _('ulogin.widget_create')
            ,handler: { xtype: 'ulogin-window-ulogin-create' ,blankValues: true }
        },'->', {
            xtype: 'textfield'
            , id: 'ulogin-search-filter'
            , emptyText: _('ulogin.search...')
            , listeners: {
                'change': {fn: this.search, scope: this}
                , 'render': {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER
                            , fn: function () {
                                this.fireEvent('change', this);
                                this.blur();
                                return true;
                            }
                            , scope: cmp
                        });
                    }, scope: this
                }
            }
        }]
    });
    Ulogin.grid.Ulogin.superclass.constructor.call(this,config)
};
Ext.extend(Ulogin.grid.Ulogin,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        return [{
            text: _('ulogin.widget_update')
            ,handler: this.updateUlogin
        },'-',{
            text: _('ulogin.widget_remove')
            ,handler: this.removeUlogin
        }];
    }
    ,updateUlogin: function(btn,e) {
        if (!this.updateUloginWindow) {
            this.updateUloginWindow = MODx.load({
                xtype: 'ulogin-window-ulogin-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.updateUloginWindow.setValues(this.menu.record);
        this.updateUloginWindow.show(e.target);
    }
    ,removeUlogin: function() {
        MODx.msg.confirm({
            title: _('ulogin.widget_remove')
            ,text: _('ulogin.widget_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('ulogin-grid-ulogin',Ulogin.grid.Ulogin);


Ulogin.window.CreateUlogin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ulogin.widget_create')
        ,url: Ulogin.config.connectorUrl
        ,baseParams: {
            action: 'mgr/create'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('ulogin.uloginid')
            ,name: 'uloginid'
            ,anchor: '100%'
        }]
    });
    Ulogin.window.CreateUlogin.superclass.constructor.call(this,config);
};
Ext.extend(Ulogin.window.CreateUlogin,MODx.Window);
Ext.reg('ulogin-window-ulogin-create',Ulogin.window.CreateUlogin);


Ulogin.window.UpdateUlogin = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('ulogin.widget_update')
        ,url: Ulogin.config.connectorUrl
        ,baseParams: {
            action: 'mgr/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('ulogin.uloginid')
            ,name: 'uloginid'
            ,anchor: '100%'
        }]
    });
    Ulogin.window.UpdateUlogin.superclass.constructor.call(this,config);
};
Ext.extend(Ulogin.window.UpdateUlogin,MODx.Window);
Ext.reg('ulogin-window-ulogin-update',Ulogin.window.UpdateUlogin);