$.fn.extend({
    import: function () {

        var $this=this;
        var loading={
            start:function(){
                $.showLoading({name: 'line-pulse'});
            },
            stop:function(){
                $.hideLoading();
            }
        };

        $this.filestyle({
            'buttonText' :$this.attr('label'),
            'iconName' : 'glyphicon-upload',
            'buttonName' : 'btn-danger flat',
            'input': false,
            'badge':false
        });

        $this.fileupload({
            acceptFileTypes: /(\.|\/)(csv)$/i,
            url: $this.attr('url'),
            done:function(e,parameters){
                var table=$('#table');
                table.html(parameters.result);
                loading.stop();
                //enable delete form
                $('[delete]').click(deleteConfirm);
            },
            error:function(e,parameters){
                if(e.responseJSON)
                    alertify.error(e.responseJSON);
                else
                    alertify.error($this.attr('error'));
                loading.stop();
            },
            progressall:function(){
                loading.start();
            },
            messages: {
                acceptFileTypes: $this.attr('accept-file')
            }
        }).on('fileuploadprocessalways', function (e, data) {
            var currentFile = data.files[data.index];
            if (data.files.error && currentFile.error) {
                var error=currentFile.error;
                alertify.error(error);
            }
        });

    }
});