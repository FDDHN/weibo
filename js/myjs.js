$(function () {
	var bar = $('.bar');
	var percent = $('.percent');
	var showimg = $('#showimg_register');
	var progress = $(".progress");
	var files = $(".files");
	var btn = $(".btn span");
	console.log("sskkk");
	$("#fileupload").wrap("<form id='myupload' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){   
		$("#myupload").ajaxSubmit({
			dataType:  'json',
			 url:"php/action.php?act=register",
			beforeSend: function() {
        		showimg.empty();
				progress.show();
        		var percentVal = '0%';
        		bar.width(percentVal);
        		percent.html(percentVal);
				btn.html("上传中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar.width(percentVal);
        		percent.html(percentVal);
    		},
			success: function(data) {
				files.html("<b>"+data.name+"("+data.size+"k)</b> <span class='delimg_REG' rel='"+data.pic+"'>删除</span>");
				var img = "/weibo2/php/files/headpictures/"+data.pic;
				showimg.html("<img src='"+img+"'>");
				btn.html("上传头像");
			},
			error:function(xhr){
				btn.html("上传失败");
				bar.width('0')
				files.html(xhr.responseText);
			}
		});
	});
	
	$(document).on("click",".delimg_REG",function(){
    var pic = $(this).attr("rel");
    $.post("php/action.php?act=delimg_register",{imagename:pic},function(msg){
        if(msg==1){
            files.html("删除成功！");
            showimg.empty();
            progress.hide();
        }else{
            alert(msg);
        }
    });
});
});
//注册
$('#register').on("click",function(){
	console.log("register");
	console.log($('.username').val());
	console.log($('.email').val());

	// 发送，获取json数据
	$.getJSON("php/index.php?action=register&c=?",{
		username : $('.username').val(),
		password:$('.password').val(),
		password_re:$('.password_re').val(),
		email:$('.email').val(),
		headpicture:$('#showimg_register').find('img').attr('src')
	},function(result){		
		$('.result').text(result['result']);
		if(result['res']=='1'){
			window.open("index.html","_self");
		}
		
	})
}); 
// 登录
$('#login').on("click",function(){
	$.getJSON("php/index.php?action=login&l=?",{username:$('.username').val(),
		password:$('.password').val()},function(result){
			window.alert(result['result']);
			if(result['result']=="登录成功"){
				window.open("homepage.html","_self");			
			}
		})
});

$('#logout').on("click",function(){
	// console.log("logout");
	$.getJSON("php/index?action=logout&lout=?",
		function(result){
			if(result['result']=="退出成功"){
				console.log("退出成功");
				window.open("index.html","_self");
			}
		})
})