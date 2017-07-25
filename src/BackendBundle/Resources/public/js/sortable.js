/*table sortable*/
jQuery.fn.extend({
    table_sortable: function () {
        return this.each(function () {
            var $this = $(this),
                url = $this.data('url')
                ;
            $(this).find('tbody').sortable({
                update: function (event, ui) {
                    var data = {id: [], position: []};
                    $this.find('tbody tr').each(function (i) {
                        data.id.push($(this).data('id'));
                        data.position.push($(this).data('position'));
                    });
                    resort(data);
                }
            });
            $(this).find('tbody').disableSelection();

            var resort = function (data) {
                loader.start();
                $.ajax({
                    type: 'post',
                    url: url,
                    data: {data: data},
                    success: function (data) {
                        $this.find('tbody tr').each(function (i) {
                            var value = data[i];
                            $(this).attr('data-position', value);
                        });
                        loader.stop();
                    },
                    error: function () {
                        loader.stop();
                    }

                });
            };

            var loader = {
                start:function(){
                    $.showLoading({name: 'line-pulse'});
                },
                stop:function(){
                    $.hideLoading();
                }
            };
        });
    }
});