<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="it">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<title>Retwis - Example Twitter clone based on the Redis Key-Value DB</title>
<link href="/static/retwis/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="page">
<div id="header">
<a href="javascript:void(0)"><img style="border:none" src="/static/retwis/images/logo.png" width="192" height="85" alt="Retwis"></a>
<div id="navbar">
<a href="/home/retwis/index">主页</a>
| <a href="/home/retwis/timeline">热点</a>
    <!--此处需要优化   include进来的内容暂时无法解析  只能输出原生php代码-->
    <?php if ($this->_vars['login_status']) { ?>
| <a href="/home/retwis/loginOut">退出</a>
    <?php
    }
    ?>

</div>
</div>