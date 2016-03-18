$.getJSON("php/index.php?action=discover&d=?",function(result){
    // window.alert(username);
    // window.alert(result['attentions']);
    // $('#username').html(username);
    username=result['username'];
    $('#username').html(result['username']);
    $('#attentions').html(result['attentions']);
    $('#fans').html(result['fans']);
    $('#weibo_name').text(username);
    picture=result['picture'];
    console.log("hhhh");
    $('#microblog').html(result['microblog']);
    // 拿到它发布的微博
    console.log("hhhh");
    $('.search').after(result['html']);
    $('.myinfomation .mf_picture').find('img').attr('src',result['picture']);
    myfunction();
    private_latter()
});

function myfunction(){
    $('.attention_button').on("click",function(){
        console.log("attention_button");
        
        console.log($(this).prev().text());

        var att=$(this).prev().text();
        button = $(this);
        console.log($(this).text());
        $.getJSON("php/index.php?action=attention&att=?",{
            attentionname:att
        },function(result){
            console.log(result['result']);
            if(result['result']=="ok"){
                // console.log("关注成功");
                // 
                // $('.attention_button').text("已关注");
                // $('.attention_button').attr("disabled",true);
                $('#attentions').html(result['attentions']);
                $('#fans').html(result['fans']);
                if(button.text()=="关注"){
                    window.alert("关注成功");
                    button.text("取消关注");
                }else{
                    window.alert("取消关注成功");
                    button.text("关注");
                }
                
                // button.attr("disabled",true);

            }
        })
    });
}

function private_latter(){
    $('.latter_button').on("click",function(){
        console.log("latter_button");
        str='<div class="back_latter2">\
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
            console.log(button.parent().prev().find('.username1').text());
            //发消息发送给服务器
            $.getJSON("php/index.php?action=lattermeg&la=?",{
                username:username,
                latter_name:button.parent().prev().find('.username1').text(),
                latter_content:button.prev().val()
            },function(result){
                console.log(result['result']);
                if(result['result']=="ok"){
                    // console.log("back_latter2");
                    //  str='<div class="back_latter2">\
                    // <div class="head_picture">\
                    //     <img src="images\\headpicture.jpg"/>\
                    // </div>\
                    // <div class="username">\
                    //     <span class="username_att">'+username+'</span>\
                    // </div>\
                    // <div class="latter_content1">\
                    //     '+button.prev().val()+'</div></div>';

                    // button.parent().prev().after(str);
                    button.prev().val("");
                    button.parent().remove();
                    window.alert("私信发送成功");
                }
            });
           
        });

    });

}