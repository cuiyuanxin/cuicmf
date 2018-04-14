/**
 * 后台公用js文件
 * @author 崔元欣 <745454106@qq.com>
 */
$(function () {
    //全选的实现
    $(".check-all").click(function () {
        $(".ids").prop("checked", this.checked);
    });
    $(".ids").click(function () {
        var option = $(".ids");
        option.each(function (i) {
            if (!this.checked) {
                $(".check-all").prop("checked", false);
                return false;
            } else {
                $(".check-all").prop("checked", true);
            }
        });
    });
    //iCheck控件全选
    var checkAll = $('input.check-all');
    var checkboxes = $('input.ids');
    checkAll.on('ifChecked ifUnchecked', function (event) {
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });
    //ajax get请求
    $('.ajax-get').click(function () {
        var target;
        var that = this;
        if ($(this).hasClass('confirm')) {
            layer.confirm('确认要执行该操作吗？', {
                btn: ['确定', '取消']
            }, function (index) {
                layer.close(index);
                if ((target = $(that).attr('href')) || (target = $(that).attr('url'))) {
                    ajax_get(target);
                }
            }, function (index) {
                layer.close(index);
            });
        } else {
            if ((target = $(this).attr('href')) || (target = $(this).attr('url'))) {
                ajax_get(target);
            }
        }
        return false;
    });

    //ajax post submit请求
    $('.ajax-post').click(function () {
        var that = this;
        var target, query, form;
        var target_form = $(that).attr('target-form');
        var nead_confirm = false;
        if (($(that).hasClass('submit')) || (target = $(that).attr('href')) || (target = $(that).attr('url'))) {
            form = $('.' + target_form);
            var options = {
                type: 'post',
                dataType: "json", //json格式
                cache: false,
                async: false, //同步返回
                success: function (data) {
                    if (data.code == 1) {
                        if (data.url) {
                            layer.msg(data.msg, {time: 1500, icon: 6}, function (index) {
                                $(that).removeClass('disabled').prop('disabled', false);
                                window.location.href = data.url;
                            });
                        } else {
                            layer.msg(data.msg, {time: 1500, icon: 6}, function (index) {
                                $(that).removeClass('disabled').prop('disabled', false);
                            });
                        }
                    } else {
                        if (data.url) {
                            layer.alert(data.msg, {icon: 5}, function (index) {
                                layer.close(index);
                                window.location.href = data.url;
                            });
                        } else {
                            layer.alert(data.msg, {icon: 5}, function (index) {
                                layer.close(index);
                                if (data.data == -10) {
                                    $('#verify_img').click();
                                    $('#verify').val('').focus();
                                }
                                $(that).removeClass('disabled').prop('disabled', false);
                            });
                        }
                    }
                },
                error: function (xhr, status, err) {
                    if (xhr.status == 403) {
                        layer.alert('没有操作权限！', {icon: 5}, function (index) {
                            layer.close(index);
                            $(that).removeClass('disabled').prop('disabled', false);
                            return false;
                        });
                    }
                }
            };
            if (form.get(0) == undefined) {
                return false;
            } else if (form.get(0).nodeName == 'FORM') {
                if ($(this).attr('url') !== undefined) {
                    target = $(this).attr('url');
                } else {
                    target = form.get(0).action;
                }
            } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA') {
                form.each(function (k, v) {
                    if (v.type == 'checkbox' && v.checked == true) {
                        nead_confirm = true;
                    }
                });
//                options.data = decodeURIComponent(form.fieldSerialize(),true);
                options.data = form.serializeArray();
            } else {
                options.data = form.find('input,select,textarea').serialize();
            }
            if (options.data && options.data.length == 0) {
                return false;
            }
            if ((nead_confirm === true || nead_confirm === false) && $(this).hasClass('confirm')) {
                layer.confirm('确认要执行该操作吗？', {
                    btn: ['确定', '取消']
                }, function (index) {
                    layer.close(index);
                    $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
                    options.url = target;
                    form.ajaxSubmit(options);
                }, function (index) {
                    layer.close(index);
                });
            } else {
                $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
                options.url = target;
                form.ajaxSubmit(options);
            }
        }
        return false;
    });
    /* 数据库备份、优化、修复 */
    //单表优化
    $("a[id^=optimize_]").click(function () {
        $.get(this.href, function (data) {
            if (data.code == 1) {
                layer.alert(data.msg, {icon: 6});
            } else {
                layer.alert(data.msg, {icon: 5});
            }
        });
        return false;
    });
    //单表修复
    $("a[id^=repair_]").click(function () {
        $.get(this.href, function (data) {
            if (data.code == 1) {
                layer.alert(data.msg, {icon: 6});
            } else {
                layer.alert(data.msg, {icon: 5});
            }
        });
        return false;
    });
//
    var $form = $("#export-form"), $export = $("#export"), tables
    $optimize = $("#optimize"), $repair = $("#repair");
    $optimize.add($repair).click(function () {
        $.post(this.href, $form.serialize(), function (data) {
            if (data.code == 1) {
                layer.alert(data.msg, {icon: 6}, function (index) {
                    layer.close(index);
                });
            } else {
                layer.alert(data.msg, {icon: 5}, function (index) {
                    layer.close(index);
                });
            }
        }, "json");
        return false;
    });
    $export.click(function () {
//        $export.children().addClass("disabled");
//        $export.children().text("正在发送备份请求...");
        $export.addClass("disabled");
        $export.text("正在发送备份请求...");
        $.post($form.attr("action"), $form.serialize(), function (data) {
            if (data.code == 1) {
                tables = data.tables;
//                $export.children().text(data.msg + "开始备份，请不要关闭本页面！");
                $export.text(data.msg + "开始备份，请不要关闭本页面！");
                backup($form, $export, tables, data.tab);
                window.onbeforeunload = function () {
                    return "正在备份数据库，请不要关闭！"
                }
            } else {
                layer.alert(data.msg, {icon: 5});
//                $export.children().removeClass("disabled");
//                $export.children().text("立即备份");
                $export.removeClass("disabled");
                $export.text("立即备份");
            }
        }, "json");
        return false;
    });
    /* 数据库备份、优化、修复*/
    /*清空缓存*/
    $('.clear').on('click', function(){
        var $url = $(this).attr('href');
        $.get($url, function (data) {
            if (data.code == 1) {
                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
                layer.msg(data.msg, {icon: 6});
            } else {
                layer.msg(data.msg, {icon: 5});
            }
        });
        return false;
    });
    /*清空缓存*/
});

function ajax_get(target) {
    $.get(target, function (data) {
        if (data.code == 1) {
            if (data.url) {
                layer.msg(data.msg, {time: 1500, icon: 6}, function (index) {
                    window.location.href = data.url;
                });
            } else {
                layer.msg(data.msg, {time: 1500, icon: 6});
            }
        } else {
            if (data.url) {
                layer.alert(data.msg, {icon: 5}, function (index) {
                    layer.close(index);
                    window.location.href = data.url;
                });
            } else {
                layer.alert(data.msg, {icon: 5});
            }
        }
    });
}

/**
 * 自定义上传控件
 * 加载视图
 */
function fileUpload(dom) {
    //类型
    var $type = $(dom).attr('data-type');
    //是否多选
    var $multi = $(dom).attr('data-multi-selection');
    //隐藏域name
    var $name = $(dom).attr('data-name');
    //获取value值
    var $value = $(dom).attr('data-value');
    //获取预览图
    var $previewUrl = $(dom).attr('data-preview');
    var $text = '';
    var $preview = '';
    var $html = '<div class="input-group input-group-sm">' +
            '<input class="form-control" type="text" value="" placeholder="' + $text + '" disabled>' +
            '<span class="input-group-btn"><button type="button" class="btn btn-info btn-flat" onclick="showImageDialog(this);">' + $text + '</button></span>' +
            '</div>';
    $(dom).html($html);
    switch ($type) {
        case ('images'):
            if ($multi) {
                $text = '选择多图';
                $name += '[]';
            } else {
                $text = '选择单图';
            }
            $preview = '<div class="input-group piclist"><ul class="ace-thumbnails clearfix"';
            if ($value) {
                $preview += ' style="display: block;">';
                $.post($previewUrl, {val: $value}, function (data) {
                    if (data.code == 1) {
                        for (var i = 0; i < data.data.length; i++) {
                            $preview += '<li>' +
                                    '<a href="' + data.data[i].path + '" data-rel="colorbox' + $name + '" class="cboxElement">' +
                                    '<img src="' + data.data[i].path + '" onerror="this.src=\'' + window['img'] + '/nopic.jpg\'; this.title=\'图片未找到.\'" class="img-responsive img-thumbnail" />' +
                                    '</a><div class="tools tools-top in"><a href="javascript:;" onclick="deleteImage(this)"><i class="fa fa-times red"></i></a></div>' +
                                    '<input name="' + $name + '" value="' + data.data[i].id + '" type="hidden">' +
                                    '</li>';
                        }
                        $preview += '</ul></div>';
                        $(dom).append($preview);
                        colorbox($(dom), $name);
                    }
                });
                if (!$multi) {
                    $(dom).find('.input-group-sm button').addClass('disabled');
                }
            } else {
                $preview = '></ul></div>';
                $(dom).append($preview);
            }
            break;
        case ('audio'):
            $text = '选择音频文件';
            if ($value) {
                $.post($previewUrl, {val: $value}, function (data) {
                    if (data.code == 1) {
                        for (var i = 0; i < data.data.length; i++) {
                            $(dom).find('.input-group-sm input').val(data.data[i].name).before('<span class="input-group-addon jpaudio" id="jpaudio' + $name + '" data-url="' + data.data[i].path + '" data-ext="' + data.data[i].ext + '"><i class="fa fa-play"></i></span>');
                            $(dom).append('<input name="' + $name + '" value="' + data.data[i].id + '" type="hidden">');
                            jplayer($(dom).find('#jpaudio' + $name));
                        }
                    }
                });
            }
            break;
        case ('video'):
            $text = '选择视频文件';
            if ($value) {
                $.post($previewUrl, {val: $value}, function (data) {
                    if (data.code == 1) {
                        for (var i = 0; i < data.data.length; i++) {
                            $(dom).find('.input-group-sm input').val(data.data[i].name).before('<span class="input-group-addon jpvideo" id="video_play_' + $name + '" data-url="' + data.data[i].path + '" data-ext="' + data.data[i].ext + '"><i class="fa fa-play"></i></span>');
                            $(dom).append('<input name="' + $name + '" value="' + data.data[i].id + '" type="hidden">');
                        }
                    }
                });
            }
            break;
        default:
            if ($multi) {
                $text = '选择多文件';
                $name += '[]';
            } else {
                $text = '选择单文件';
            }
            $preview = '<div class="preview_table"';
            if ($value) {
                $preview += ' style="display: block;">';
                $.post($previewUrl, {val: $value}, function (data) {
                    if (data.code == 1) {
                        for (var i = 0; i < data.data.length; i++) {
                            $preview += '<div class="input-group">' +
                                    '<span class="input-group-addon"><i class="' + Icon(data.data[i].ext) + '"></i></span>' +
                                    '<input class="form-control" type="text" value="' + data.data[i].name + '" disabled>' +
                                    '<span class="input-group-addon" onclick="deleteFile(this)" style="cursor: pointer;"><i class="fa fa-times red"></i></span>' +
                                    '<input name="' + $name + '" value="' + data.data[i].id + '" type="hidden">' +
                                    '</div>';
                        }
                        $preview += '</div>';
                        $(dom).append($preview);
                    }
                });
                if (!$multi) {
                    $(dom).find('.input-group-sm button').addClass('disabled');
                }
            } else {
                $preview += '></div>';
                $(dom).append($preview);
            }
            break;
    }
    $(dom).find('.input-group-sm input').attr('placeholder', $text);
    $(dom).find('.input-group-sm button').text($text);
}
//上传触发
function showImageDialog(dom) {
    if (!$(dom).hasClass('disabled')) {
        $dom = $(dom).parents('.cuiuplode');
        //类型
        $type = $(dom).parents('.cuiuplode').attr('data-type');
        //是否多选
        $multi = $(dom).parents('.cuiuplode').attr('data-multi-selection');
        //后缀
        $extensions = $(dom).parents('.cuiuplode').attr('data-extensions');
        //参数
        $formData = $(dom).parents('.cuiuplode').attr('data-form-data');
        //设置文件上传域的name
        $fileVal = $(dom).parents('.cuiuplode').attr('data-file-val');
        //验证文件总数量, 超出则不允许加入队列
        $fileNumLimit = Number($(dom).parents('.cuiuplode').attr('data-file-num-limit'));
        //验证文件总大小是否超出限制, 超出则不允许加入队列
        $fileSizeLimit = Number($(dom).parents('.cuiuplode').attr('data-file-size-limit'));
        //验证单个文件大小是否超出限制, 超出则不允许加入队列
        $fileSingleSizeLimit = Number($(dom).parents('.cuiuplode').attr('data-file-single-size-limit'));
        //提交地址
        $server = $(dom).parents('.cuiuplode').attr('data-server');
        //隐藏域name
        $name = $(dom).parents('.cuiuplode').attr('data-name');
        //获取value值
        $value = $(dom).attr('data-value');
        $text = '';
        switch ($type) {
            case ('images'):
                if ($multi) {
                    $text = '选择多图';
//                $name += '[]';
                } else {
                    $text = '选择单图';
                }
                break;
            case ('audio'):
                $text = '选择音频文件';
                break;
            case ('video'):
                $text = '选择视频文件';
                break;
            default:
                if ($multi) {
                    $text = '选择多文件';
                } else {
                    $text = '选择单文件';
                }
                break;
        }
        layer.open({
            type: 2,
            title: '上传组件',
            shadeClose: true,
            shade: false,
            maxmin: true,
            area: ['893px', '600px'],
            content: window['app_path'] + '?type=' + $type
        });
    } else {
        return false;
    }
}
//删除图片
function deleteImage(obj) {
    var $multi = $(obj).parents('.cuiuplode').attr('data-multi-selection');
    var $name = $(obj).parents('.cuiuplode').attr('data-name');
    if ($multi) {
        var $li = $(obj).parents('.ace-thumbnails').find('li').length;
        if ($li > 1) {
            $(obj).parents('li').remove();
        } else {
            $(obj).parents('.ace-thumbnails').parent().append('<input name="' + $name + '" value="" type="hidden">');
            $(obj).parents('.ace-thumbnails').remove();
        }
    } else {
        $(obj).parents('.cuiuplode').find('.input-group-sm button').removeClass('disabled');
        $(obj).parents('.ace-thumbnails').parent().append('<input name="' + $name + '" value="" type="hidden">');
        $(obj).parents('.ace-thumbnails').remove();
    }
}
//删除文件
function deleteFile(obj) {
    var $multi = $(obj).parents('.cuiuplode').attr('data-multi-selection');
    var $name = $(obj).parents('.cuiuplode').attr('data-name');
    if ($multi) {
        var $li = $(obj).parents('.preview_table').find('.input-group').length;
        if ($li > 1) {
            $(obj).parents('.input-group').remove();
        } else {
            $(obj).parents('.preview_table').empty().append('<input name="' + $name + '" value="" type="hidden">').hide();
        }
    } else {
        $(obj).parents('.cuiuplode').find('.input-group-sm button').removeClass('disabled');
        $(obj).parents('.preview_table').empty().append('<input name="' + $name + '" value="" type="hidden">').hide();
    }
}
//图片预览弹出
function colorbox(obj, $name) {
    var colorbox_params = {
        rel: 'colorbox' + $name,
        reposition: true,
        scalePhotos: true,
        scrolling: false,
        previous: '<i class="ace-icon fa fa-arrow-left"></i>',
        next: '<i class="ace-icon fa fa-arrow-right"></i>',
        close: '&times;',
        current: '{current} of {total}',
        maxWidth: '100%',
        maxHeight: '100%',
        onOpen: function () {
            $overflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
        },
        onClosed: function () {
            document.body.style.overflow = $overflow;
        },
        onComplete: function () {
            $.colorbox.resize();
        }
    };
    obj.find('.ace-thumbnails [data-rel="colorbox' + $name + '"]').colorbox(colorbox_params);
}
//点击播放音乐
function jplayer(obj) {
    var $ext = obj.attr('data-ext');
    var $url = obj.attr('data-url');
    var $player = obj.parents('.cuiuplode').find("#player");
    if ($player[0]) {
        if (obj.attr('data-ext')) {
            var c = $("#player");
            if (obj.find('i').hasClass('fa-play')) {
                c.jPlayer("play");
            } else {
                c.jPlayer("stop");
            }
        } else {
            layer.msg('不支持该格式的音频播放', {icon: 6});
        }
    } else {
        var c = $('<div id="player"></div>');
        obj.parents('.cuiuplode').append(c)
        if ($ext == 'ogg') {
            $ext = 'oga';
        }
        eval('var $setMedia = {' + $ext + ': "' + $url + '"}');
        var c = obj.parents('.cuiuplode').find("#player");
        var set = {
            playing: function () {
                obj.find('i').removeClass("fa-play").addClass("fa-stop")
            },
            pause: function (a) {
                obj.find('i').removeClass("fa-stop").addClass("fa-play")
            },
            ready: function (event) {
                c.jPlayer("setMedia", $setMedia);
            },
            swfPath: window['static'] + '/jplayer/jquery.jplayer.swf',
            supplied: $ext,
            solution: "html, flash",
        };
        c.jPlayer(set);
    }
}
//视图icon
function Icon(ico) {
    var $icon = {
        xls: 'fa fa-file-excel-o',
        xlsx: 'fa fa-file-excel-o',
        doc: 'fa fa-file-word-o',
        docx: 'fa fa-file-word-o',
        bmp: 'fa fa-picture-o',
        jpg: 'fa fa-picture-o',
        jpeg: 'fa fa-picture-o',
        png: 'fa fa-picture-o',
        gif: 'fa fa-picture-o',
        mpg: 'fa fa-file-video-o',
        mlv: 'fa fa-file-video-o',
        mpe: 'fa fa-file-video-o',
        mpeg: 'fa fa-file-video-o',
        dat: 'fa fa-file-video-o',
        m2v: 'fa fa-file-video-o',
        vob: 'fa fa-file-video-o',
        tp: 'fa fa-file-video-o',
        ts: 'fa fa-file-video-o',
        avi: 'fa fa-file-video-o',
        mov: 'fa fa-file-video-o',
        asf: 'fa fa-file-video-o',
        mp4: 'fa fa-file-video-o',
        wmv: 'fa fa-file-video-o',
        rm: 'fa fa-file-video-o',
        ra: 'fa fa-file-video-o',
        ram: 'fa fa-file-video-o',
        rmvb: 'fa fa-file-video-o',
        swf: 'fa fa-file-video-o',
        flv: 'fa fa-file-video-o',
        qt: 'fa fa-file-video-o',
        sgp: 'fa fa-file-video-o',
        mp3: 'fa fa-file-audio-o',
        wav: 'fa fa-file-audio-o',
        ogg: 'fa fa-file-audio-o',
        rar: 'fa fa-file-archive-o',
        zip: 'fa fa-file-archive-o',
        qz: 'fa fa-file-archive-o',
        tar: 'fa fa-file-archive-o',
        gz: 'fa fa-file-archive-o',
        bz2: 'fa fa-file-archive-o',
        pdf: 'fa fa-file-pdf-o',
        java: 'fa fa-file-code-o',
        h: 'fa fa-file-code-o',
        c: 'fa fa-file-code-o',
        exe: 'fa fa-file-code-o',
        dll: 'fa fa-file-code-o',
        lib: 'fa fa-file-code-o',
        dsp: 'fa fa-file-code-o',
        dsw: 'fa fa-file-code-o',
        cpp: 'fa fa-file-code-o',
        cs: 'fa fa-file-code-o',
        asp: 'fa fa-file-code-o',
        aspx: 'fa fa-file-code-o',
        php: 'fa fa-file-code-o',
        jsp: 'fa fa-file-code-o',
        css: 'fa fa-file-code-o',
        js: 'fa fa-file-code-o',
        html: 'fa fa-file-code-o',
        py: 'fa fa-file-code-o',
        txt: 'fa fa-file-text-o',
        apk: 'fa fa-android',
        ios: 'glyphicon glyphicon-apple',
        ios: 'glyphicon glyphicon-apple',
    };
    if (ico == '3gp') {
        ico = 'sgp';
    }
    if (ico == '7z') {
        ico = 'qz';
    }
    if ($icon[ico]) {
        return $icon[ico];
    }
    return 'fa fa-file';
}
//数据库备份
function backup($form, $export, tables, tab, status) {
    status && showmsg($form, $export, tables, tab.id, "开始备份...(0%)");
    $.get($form.attr("action"), tab, function (data) {
        if (data.code == 1) {
            showmsg($form, tables, tab.id, data.msg);
            if (!$.isPlainObject(data.tab)) {
                $export.removeClass("disabled");
                $export.text("备份完成，点击重新备份");
                window.onbeforeunload = null;
            }
            if (data.tab) {
                backup($form, $export, tables, data.tab, tab.id != data.tab.id);
            }
        } else {
            layer.alert(data.msg, {icon: 5}, function (index) {
                $export.removeClass("disabled");
                $export.text("立即备份");
                layer.close(index);
            });
        }
    }, "json");
}
function showmsg($form, tables, id, msg) {
    $tr = $form.find("input[value=" + tables[id] + "]").closest("tr");
    $tr.find(".green").html("").hide();
    $tr.find(".info").html("").hide();
    $tr.find(".backup").html(msg).show();
}