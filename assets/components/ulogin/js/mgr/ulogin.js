var Ulogin = function(config) {
    config = config || {};
    Ulogin.superclass.constructor.call(this,config);
};
Ext.extend(Ulogin,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('ulogin',Ulogin);

Ulogin = new Ulogin();