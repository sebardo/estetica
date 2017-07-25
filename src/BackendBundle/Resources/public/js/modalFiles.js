$.fn.extend({
    modalFiles: function () {
        return this.each(function () {
            var $this = $(this),
                $modal = $($this.attr('modal')),
                $url = $this.attr('url')
                ;

            var loading={
                start:function(){
                    $.showLoading({name: 'line-pulse'});
                },
                stop:function(){
                    $.hideLoading();
                }
            };

            var showModal = function () {
                loading.start();
                $.getJSON($url).done(function (answers) {
                    loading.stop();
                    $modal.find('.modal-body').html(answers);
                    $modal.modal('show');
                });
            }
            $this.on('click', showModal);
        })
    }
});