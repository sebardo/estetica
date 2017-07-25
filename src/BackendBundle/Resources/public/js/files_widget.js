jQuery.fn.extend({
    widgetFiles: function (accept) {
		var $this=this,
			buttonAdd=$('#'+this.attr('id')+'_add'),
			element='<div class="col-xs-12 well flat" id="__id"><a data-target="#__id" class="close" href="#">×</a><div class="row" id="deleted___id"><div class="col-xs-5 form-group no-margin"><input name="__id" id="file___id" type="file"/></div></div><div class="row" id="result___id"></div></div>',
			result='<input type="text" value="__value" name="__name" style="padding:0; border: 0; margin: 0; opacity: 0;width: 0; max-width: 0; height: 0; max-height: 0;"><div class="col-xs-5 form-group no-margin"><a target="_blank" href="__href">__value</a></div>',
            container=$('#'+this.attr('id')+'_container'),
            url_upload=$this.data('url-upload'),
            path_copy=$this.data('path-copy'),
            url_file=$this.data('url-file'),
            full_name=$this.data('name')
			;
			
		var closeElement=function(e){
			e.preventDefault();
			var target=$(this).data('target');
			$(target).remove();
		}

        var loader={
            start:function(){
                $.showLoading({name: 'line-pulse'});
            },
            stop:function(){
                $.hideLoading();
            }
        };
		
		var bindFileUpload=function() {

            $this.find('input[type=file]').fileupload(
                {
                    acceptFileTypes: accept,
                    url: url_upload,
                    formData: {
                        'path_copy': path_copy
                    },
                    done: function (e, data) {
                        for (var i = 0; i < data.result.length; i++) {
                            var name_aux = full_name + '[' + (container.find('.well').length - 1) + ']';
                            var result_aux = result.replace(/__value/g, data.result[i][1]);
                            result_aux = result_aux.replace(/__name/g, name_aux);
                            result_aux = result_aux.replace(/__href/g, url_file + data.result[i][1]);

                            $('#deleted_' + data.result[i][0]).remove();
                            $('#result_' + data.result[i][0]).html(result_aux);
                        }
                        loader.stop();
                    },
                    error: function () {
                        loader.stop();
                    },
                    progressall: function () {
                        loader.start();
                    }
                }
            ).on('fileuploadprocessalways', function (e, data) {
                    var currentFile = data.files[data.index];
                    if (data.files.error && currentFile.error) {
                        var modal=$('#'+$this.attr('id')+'_modal_error');
                        modal.modal('show');
                    }
                });
        }
		
		buttonAdd.on('click',function(){
			var element_aux=element;
			var id=$this.attr('id')+'_'+(container.find('.well').length+1);
			element_aux=element_aux.replace(/__id/g,id);
			container.append(element_aux);
			$('.close').on('click',closeElement);
			
			$this.find('input[type=file]').filestyle({
                buttonText: ''
            });
			bindFileUpload();
		});

        $('.close').on('click',closeElement);
		
		
		
		
		
		
    }
});
