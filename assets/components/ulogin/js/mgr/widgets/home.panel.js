Ulogin.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('ulogin.management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [{
                title: _('ulogin')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('ulogin.management_desc')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    layout: 'form'
                    ,bodyStyle: 'padding: 15px;'
                    ,labelWidth: 200
                    ,items: [{
                        layout: 'form'
                        ,bodyStyle: 'padding: 15px;'
                        ,labelWidth: 200
                        ,items: [{
                            xtype: 'ulogin-combo-groups'
                            ,fieldLabel: _('ulogin.group')
                            ,name: 'group_id'
                            ,hiddenName: 'group_id'
                            ,anchor: '100%'
                            ,listeners: {
                                'select': {
                                    fn:this.groupSelect
                                    ,scope:this}
                            }
                        }]
                    },{
                        layout: 'form'
                        ,bodyStyle: 'padding: 15px;'
                        ,labelWidth: 200
                        ,items: [{
                            xtype: 'ulogin-combo-roles'
                            ,fieldLabel: _('ulogin.role')
                            ,name: 'role_id'
                            ,hiddenName: 'role_id'
                            ,anchor: '100%'
                            ,listeners: {
                                'select': {
                                    fn:this.groupSelect
                                    ,scope:this}
                            }
                        }]
                    }]
                },{
                    xtype: 'ulogin-grid-ulogin'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
                }]
            }]
        }]
    });
    Ulogin.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(Ulogin.panel.Home,MODx.Panel,{
    groupSelect: function(data) {
        MODx.Ajax.request({
            url: Ulogin.config.connectorUrl
            ,params: {
                action: 'mgr/updateFromCombos'
                ,key: 'ulogin.' + data.name
                ,value: data.value
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('ulogin-panel-home',Ulogin.panel.Home);


Ulogin.combo.Groups = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        url: Ulogin.config.connectorUrl
        ,baseParams: {
           action: 'mgr/getGroups'
        }
        ,fields: ['id', 'name']
        ,name: 'group_id'
        ,displayField: 'name'
        ,valueField: 'id'
        ,value: Ulogin.config.uGroup
    });

    Ulogin.combo.Groups.superclass.constructor.call(this,config);
};
Ext.extend(Ulogin.combo.Groups,MODx.combo.ComboBox);
Ext.reg('ulogin-combo-groups',Ulogin.combo.Groups);


Ulogin.combo.Roles = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: Ulogin.config.connectorUrl
        ,baseParams: {
            action: 'mgr/getRoles'
        }
        ,fields: ['id','name']
        ,name: 'role_id'
        ,displayField: 'name'
        ,valueField: 'id'
        ,value: Ulogin.config.uRole
    });
    Ulogin.combo.Roles.superclass.constructor.call(this,config);
};
Ext.extend(Ulogin.combo.Roles,MODx.combo.ComboBox);
Ext.reg('ulogin-combo-roles',Ulogin.combo.Roles);