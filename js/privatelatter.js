// var getParam = function(name){
//     var search = document.location.search;
//     var pattern = new RegExp("[?&]"+name+"\=([^&]+)", "g");
//     var matcher = pattern.exec(search);
//     var items = null;
//     if(null != matcher){
//         try{
//             items = decodeURIComponent(decodeURIComponent(matcher[1]));
//         }catch(e){
//             try{
//                 items = decodeURIComponent(matcher[1]);
//             }catch(e){
//                 items = matcher[1];
//             }
//         }
//     }
//     return items;
// };
// private_latter();
// username=getParam('username');

$.getJSON("php/index.php?action=private&p=?",function(result){
    // window.alert(username);
    // window.alert(result['attentions']);
    $('#username').html(result['username']);
    $('#attentions').html(result['attentions']);
    $('#fans').html(result['fans']);
    $('#microblog').html(result['microblog']);
    username=result['username'];
   $('.discover_info .mf_picture').find('img').attr('src',result['picture']);
    $('#weibo_name').text(result['username']);
    picture=result['picture'];
    // 拿到它发布的微博
    if(result['html']==""){
        $('.discover_info').after("你没有收到私信！");
    }else{
        $('.discover_info').after(result['html']);
    }

    private_latter();

});




function private_latter(){
    $('.latter_button').on("click",function(){
        console.log("latter_button");
        str='<div class="back_latter">\
                    <div class="head_picture">\
                        <img src="'+picture+'"/>\
                    </div>\
                    <div class="username">\
                        <span class="username_att">'+username+'</span>\
                    </div>\
                        <textarea name="latter_content" cols="38" rows="2" class="latter_cont"></textarea>\
                        <button class="latter_buttonc">确认</button>\
                </div>';
        $(this).parent().after(str);

        $('.latter_buttonc').on("click",function(){
            console.log("latter_buttonc");
            console.log($(this).prev().val());
            button=$(this);
            
            //发消息发送给服务器
            $.getJSON("php/index.php?action=lattermeg&la=?",{
                latter_name:button.parent().parent().find('.username1').text(),
                latter_content:button.prev().val()
            },function(result){
                console.log(result['result']);
                if(result['result']=="ok"){
                     str='<div class="back_latter">\
                    <div class="head_picture">\
                        <img src="'+picture+'"/>\
                    </div>\
                    <div class="username">\
                        <span class="username_att">'+username+'</span>\
                    </div>\
                    <div class="latter_content1">\
                        '+button.prev().val()+'</div></div>';

                button.parent().prev().after(str);
                button.prev().val("");
                }
            });
           
        });

    });

}