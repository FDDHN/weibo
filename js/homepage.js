var bar = $('.bar');
var percent = $('.percent');
var showimg = $('#showimg');
var progress = $(".progress");
var files = $(".files");
// var btn = $(".btn span");

$(".fileupload").wrap("<form class='myupload' method='post' enctype='multipart/form-data'></form>");

$(document).on("click",".delimg",function(){
    var pic = $(this).attr("rel");
    $.post("php/action.php?act=delimg",{imagename:pic},function(msg){
        if(msg==1){
            files.html("删除成功！");
            showimg.empty();
            progress.hide();
        }else{
            alert(msg);
        }
    });
});

$('.fileupload').on("change",function(){
    button=$(this);
    str=button.parent().prev().html();
    if(str=="图片"){
        type="picture";
        path="/weibo2/php/files/images/";
    }else if(str=="视频"){
        type="vedios";
        path="/weibo2/php/files/vedios/";
    }else if(str="音乐"){
        type="musics";
        path="/weibo2/php/files/musics/";
    }
    $(this).parent().ajaxSubmit({

        dataType:  'json',
        url:"php/action.php?act="+type,
        beforeSend: function() {
            showimg.empty();
            progress.show();
            var percentVal = '0%';
            bar.width(percentVal);
            percent.html(percentVal);
            button.parent().prev().html("上传中...");
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        },
        success: function(data) {
            files.html("<b>"+data.name+"("+data.size+"k)</b> <span class='delimg' rel='"+data.pic+"'>删除</span>");
            var img = path +data.pic;
            if(type=="picture"){
                showimg.html("<img src='"+img+"'>");
            }else if(type=="musics"){
                showimg.html("<object data='"+img+"' />");
            }else if(type=="vedios"){
                showimg.html( ' <video controls="controls" autostart="false" autoplay="" name="media" width="320" height="240">\
    <source src=" '+img+'" type="video/mp4" ></video>');
            }
            
            button.parent().prev().html(str);
        },
        error:function(xhr){
            // button.parent().prev().html("上传失败");
            bar.width('0')
            files.html(xhr.responseText);
            button.parent().prev().html(str);
        }
    });
})
// 获取用户的信息
$.getJSON("php/index.php?action=homepage&h=?",function(result){
    // window.alert(username);
    // window.alert(result['attentions']);
    $('#username').html(result['username']);
    username=result['username'];
    $('#attentions').html(result['attentions']);
    $('#fans').html(result['fans']);
    $('#microblog').html(result['microblog']);
    // 拿到它发布的微博
    $('.publish').after(result['html']);
    $('#weibo_name').text(username);
    console.log(result['picture']);
    $('.myinfomation .mf_picture').find('img').attr('src',result['picture']);
    mypicture=result['picture'];
    transmit_function();
    comments_function();
});

// 检查字数
$('#publish_content').on("keyup",function(){
    var words = $('#publish_content').val();
    $('#leftwords').html(120-words.length);
	
});
// 发布信息
$('.publish_submit').on("click",function(){
    var content = $("#publish_content").val();
    if(content==""){
        window.alert("请输入发布内容！");
    }else{
        console.log("publish");
        $.getJSON("php/index?action=publish&p=?",{
            content:$("#publish_content").val()
            },function(result){
                // 显示信息
                console.log(result['center']);
                if(result['result']=="ok1" || result['result']=="ok2" || result['result']=="ok3" || result['result']=="ok4"){
                    window.alert("发布成功");
                    $('#microblog').html(result['microblog']);
                    $('.publish').after(result['html']);
                    // $('#publish_content').val("");
                    // document.getElementById("leftwords").value="120";
                    $('#leftwords').html("120");
                    document.getElementById("publish_content").value="";
                    files.html("");
                    showimg.html("");
                }else if(result['result']="failed"){
                    window.alert("发布失败");
                }
                transmit_function();
                comments_function();
        })
    }
	
});

function transmit_function(){
    //转发信息
    $('.transmit').on("click",function(){
        console.log("transmit");
        this_tran=$(this);
        console.log($(this).parent().parent().find('.username').text());
        console.log($(this).parent().parent().find('.main_content').text());
        $.getJSON("php/index?action=transmit&tr=?",{
            tran_content:this_tran.parent().parent().find('.main_content').find('.ccontent').text(),
            tran_username:this_tran.parent().parent().find('.username').text(),
            tran_content_pic:this_tran.parent().prev().find('img').attr('src')
        },function(result){
            console.log(result['result']);
            if(result['result']=="ok"){
                $('#microblog').html(result['microblog']);
                $('.publish').after(result['html']);
                window.alert("转发成功！！！");

            }
        });
    });

}



//评论
function comments_function(){
    $('.pub_comments').on("click",function(){

    str =' <div class="comments">\
            <div class="head_picture2">\
                <img src="'+mypicture+'"/>\
            </div>\
            <div class="com">\
                <textarea name="comments" id="com_content" cols="65" rows="2" class="com_content"></textarea>\
                <button type="button" class="com_publish">评论</button>\
            </div>\
        </div>';
    console.log($(this).parent().parent());

    $(this).parent().parent().after(str);
    // content=$(this);
    console.log($('.com_content').parent().parent().prev().find('.main_content').text());
    button = $(this);

    $.getJSON("php/index?action=show_comments&sh=?",{
            weibo_content:$('.com_content').parent().parent().prev().find('.main_content').find('.ccontent').text()
        },function(result){
            console.log(result['result']);
            if(result['result']=="ok"){
                console.log(button);
                button.parent().parent().next().append(result['html']);
            }
    });

    $('.com_publish').on("click",function(){
        console.log("com_publish"); 
        button=$(this);
        console.log($('.com_content').val());
        console.log(button.parent().parent().prev().find('.main_content').text());
        console.log(button.parent().parent().prev().find('.username').text());
       
        console.log("内容");
        console.log($(this).prev().val());
        if($(this).prev().val()==""){
            window.alert("请填写评论内容!!");
        }else{
            $.getJSON("php/index?action=comments&c=?",{
            weibo_content:button.parent().parent().prev().find('.main_content').find('.ccontent').text(),
            com_owner:button.parent().parent().prev().find('.username').text(),
            com_content:$("#com_content").val()
            },function(result){
                console.log(result['result']);
                if(result['result']=="ok"){
                    console.log(button.parent().parent());
                    button.parent().after(result['html']);

                }else{
                     console.log("failed");
                }
            });
        }
        window.alert("评论成功！");
        $('.com_content').val("");
        
    });
});
}

$('.biaoqing').bind('click',function(){
        var x = $(this).position().left,y = $(this).position().top + $(this).outerHeight();

        var html = '<div class="biao"><img src="php/imgs/开心.gif" class="bq" value="[开心]"/>\
        <img src="php/imgs/大笑.gif" class="bq" value="[大笑]"/>\
        <img src="php/imgs/大哭.gif"class="bq" value="[大哭]"/>\
        <img src="php/imgs/汗.gif" class="bq" value="[汗]"/>\
        <img src="php/imgs/好吃.gif" class="bq" value="[好吃]"/>\
        <img src="php/imgs/拜拜.gif" class="bq" value="[拜拜]"/>\
        <img src="php/imgs/不能忍.gif" class="bq" value="[不能忍]"/>\
        <img src="php/imgs/衰.gif" class="bq" value="[衰]"/>\
        <img src="php/imgs/干杯.gif" class="bq" value="[干杯]"/>\
        <img src="php/imgs/吃饭.gif" class="bq" value="[吃饭]"/>';

        $('body').append(html);
        $('.biao').css({'position':'absolute','left':'300px','top':'240px'});
        $('.biao img').css({'width':'40px','height':'40px'});

        // var arr=['开心','大笑','汗','好吃','大哭']
        // for(var i=0;i<arr.length;i++){

        // }
        $('.bq').on("click",function(){
            console.log($(this).attr('value'));
            // str=$('#publish_content').val();
            // $('#publish_content').append($(this).attr('value'));
            // $('$publish_content').val();
            var content = document.getElementById("publish_content");
            content.value += $(this).attr('value');
            // $('#publish_content').html($('#publish_content').val()+$(this).attr('value'));
        })
});

$('#publish_content').on("click",function(){
    if($('.biao').length>0){
            $('.biao').hide();
        }
})