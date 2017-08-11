<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>在线报修</title>

    <!-- Bootstrap -->
    <link href="./Public/Home/static/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./Public/Home/static/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .main{margin-bottom: 60px;}
        .indexLabel{padding: 10px 0; margin: 10px 0 0; color: #fff;}
    </style>
    
    
</head>
<body>
<div class="main">
    
        <!--导航部分-->
        <nav class="navbar navbar-default navbar-fixed-bottom">
            <div class="container-fluid text-center">
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="index.html" class="navbar-link">首页</a></p>
                </div>
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="#" class="navbar-link">服务</a></p>
                </div>
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="#" class="navbar-link">发现</a></p>
                </div>
                <div class="col-xs-3">
                    <p class="navbar-text"><a href="<?php echo U('User/index');?>" class="navbar-link">我的</a></p>
                </div>
            </div>
        </nav>
        <!--导航结束-->
    

    
  <section class="container-fluid">
    <h2>修改密码</h2>
	<div class="span12">
        <form class="login-form" action="/index.php?s=/Home/User/profile.html" method="post">
          <div class="control-group form-group">
            <label class="control-label" for="inputPassword">原密码</label>
            <div class="controls form-group">
              <input type="password" id="inputPassword"  class="span3 form-control" placeholder="请输入密码"  errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="old">
            </div>
          </div>
          <div class="control-group form-group">
            <label class="control-label" for="inputPassword">新密码</label>
            <div class="controls form-group">
              <input type="password" id="inputPassword"  class="span3 form-control" placeholder="请输入密码"  errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="password">
            </div>
          </div>
          <div class="control-group form-group">
            <label class="control-label" for="inputPassword">确认密码</label>
            <div class="controls form-group">
              <input type="password" id="inputPassword" class="span3 form-control" placeholder="请再次输入密码" recheck="password" errormsg="您两次输入的密码不一致" nullmsg="请填确认密码" datatype="*" name="repassword">
            </div>
            <div class="controls Validform_checktip text-warning"></div>
          </div>
          <div class="control-group form-group">
            <div class="controls form-group">
              <button type="submit" class="btn btn-primary onlineBtn">提 交</button>
            </div>
          </div>
        </form>
	</div>
</section>

</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="./Public/Home/static/jquery-1.11.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./Public/Home/static/bootstrap/js/bootstrap.min.js"></script>


	<script type="text/javascript">

    	$(document)
	    	.ajaxStart(function(){
	    		$("button:submit").addClass("log-in").attr("disabled", true);
	    	})
	    	.ajaxStop(function(){
	    		$("button:submit").removeClass("log-in").attr("disabled", false);
	    	});

    	$("form").submit(function(){
    		var self = $(this);
    		$.post(self.attr("action"), self.serialize(), success, "json");
    		return false;

    		function success(data){
    			if(data.status){
    				window.location.href = data.url;
    			} else {
    				self.find(".Validform_checktip").text(data.info);
    			}
    		}
    	});
	</script>

</body>
</html>