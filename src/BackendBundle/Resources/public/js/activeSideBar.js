$.fn.extend({
    activesidebar: function () {
        var $this=this,
            active=this.data('active');
        if(active && active!='#') {
            active=active.split(' ');
            var element = $this.find(active[0]);
            element.addClass('active');
            if (element.hasClass('treeview')) {
                element.addClass('menu-open active');
                element.find('.treeview-menu').addClass('menu-open');
                element.find('.treeview-menu').css({display: 'block'});
                element.find('#'+active[1]).addClass('active');
            }else{
                element.addClass('active');
            }
        }
    }
});
