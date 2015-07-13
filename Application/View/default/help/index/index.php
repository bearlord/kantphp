<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="KantPHP帮助文档">
        <meta name="author" content="KantPHP帮助文档">
        <link rel="icon" href="../../favicon.ico">

        <title>KantPHP帮助文档</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo PUBLIC_URL; ?>help/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>help/css/style.css">
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?php echo PUBLIC_URL; ?>help/js/jquery.min.js"></script>
        <script src="<?php echo PUBLIC_URL; ?>help/js/bootstrap.min.js"></script>

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo PUBLIC_URL; ?>help/js/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body data-spy="scroll" data-target="#myScrollspy">
        <div class="help-masthead">
            <div class="container">
                <nav class="help-nav">
                    <a class="help-nav-item " href="#">KantPHP</a>
                    <a class="help-nav-item active" href="#">帮助文档</a>
                </nav>
            </div>
        </div>

        <div class="container">
            <div class="help-header">
                <h1 class="help-title">KantPHP帮助文档</h1>
            </div>
            <div class="row">
                <div class="col-xs-3" id="myScrollspy">
                    <ul class="nav nav-tabs nav-stacked" data-spy="affix" data-offset-top="125">
                        <li class="active"><a href="#welcome">1.欢迎使用KantPHP</a></li>
                        <li><a href="#faststart">2.快速开始</a></li>
                        <li><a href="#architecture">3.架构原理</a></li>
                        <li><a href="#section-3">4.项目配置说明</a></li>
                        <li><a href="#section-4">5.MVC模式快速开发</a></li>
                        <li><a href="#section-5">6.模块化开发流程</a></li>
                        <li><a href="#section-5">7.控制器</a></li>
                        <li><a href="#section-5">8.视图</a></li>
                        <li><a href="#section-5">9.模型</a></li>
                        <li><a href="#section-5">10.路由与重写</a></li>
                        <li><a href="#section-5">11.扩展</a></li>
                        <li><a href="#section-5">12.第三方类库</a></li>
                        <li><a href="#section-5">13.独立文件服务器</a></li>
                        <li><a href="#section-5">14.多库操作</a></li>
                    </ul>
                </div>
                <div class="col-xs-9 col-sm-8 help-main">
                    <div class="help-post" id="welcome">
                        <div class="page-header">
                            <h2 class="help-post-title">1. 欢迎使用KantPHP</h2>
                        </div>
                        <p>感谢您使用KantPHP Framework!如果您遇到其他在此文档中没有提及的问题，请有发送邮件到【洞主】邮箱zhenqiang.zhang@hotmail.com，会收到尽快回复。</p>
                        <hr>
                        <p>KantPHP Framework是一个快速、基于PHP5.3+的PHP开发框架。作为开源软件，可以自由下载和使用，遵循 BSD 3-Clause license 。</p>
                        <p><a href="https://github.com/bearlord/kantphp/releases" target="_blank">前往Github下载</a></p>
                        <blockquote>
                            <p>Kantphp Framework起初是洞主为学习PHP框架而写的一个实验室产品，至2015年有3年的开发周期，共发布了8个版本，虽然仅仅是默默无闻。KantPHP Framework 有很多国内外出名的PHP框架的影子,如：<b>Zend Framework</b>, <b>CodeIgniter Web Framework</b>, <b>CakePHP Framework</b>, <b>ThinkPHP</b> 和其他的开源系统如：<b>PHPCMS</b>, <b>Discuz!</b> 借鉴前辈和同行的很多思想和思路，同时也引用了在工作和项目中收集的一些开源类库。</p>
                            <p>洞主是一个除了研究《西游记》积极，其他方面都比较懒且有拖延症的程序员，感谢Unix/Linux运维群里诸多群友的肯定和热情的鼓励并敦促我写KantPHP帮助文档和示例代码。</p>
                        </blockquote>
                        <p>KantPHP Framework采用模块化的MVC开发模式，简洁易懂。开发者如果学过其他框架，会发现很容易学会KantPHP Framework。对于初学者，也很容易入门。</p>
                        <p>版本1.2中暂未引入命名空间【namespace】，在未来版本中会加入此功能。</p>
                    </div><!-- /.help-post -->

                    <div class="help-post" id="faststart">
                        <div class="page-header">
                            <h2 class="help-post-title">2. 快速开始</h2>
                        </div>
                        <h3>2.1 环境要求</h3>
                        <ul>
                            <li>Web服器：Apache,Nginx,LightHttpd</li>
                            <li>PHP5.3+</li>
                            <li>数据库：PostgreSQL,Mysql,Sqilte</li>
                        </ul>
                        <blockquote>
                            <p>Apache和Nginx要开启Rewrite，Nginx要设置支持Pathinfo。</p>
                            <p>本文档的示例代码以Aapache作为WEB服务器</p>
                            <p>PHP打开常用扩展如：php_gd,php_mbstring,php_curl以及连接数据库的扩展，如果连接PostgreSQL,建议采用PDO扩展连接。</p>
                        </blockquote>
                        <h3>2.2 获取KantPHP Framework</h3>
                        <p>从<a href="https://github.com/bearlord/kantphp/releases" target="_blank">Github</a>获取KantPHP的发行版或者<a href="https://github.com/bearlord/kantphp/">Git Clone</a>最新版，解压并复制到WEB服务器根目录，如/srv/htdocs/kantphp，并赋予目录kantphp写权限和可执行权限。如果是开发环境，粗暴的设置为 <em>0777</em> 是不错的选择。我们会在后续署章节中再讨论权限。</p>
                        <p>打开浏览器，输入<em>http://localhost/kantphp/</em>，如果你见到页面上显示：</p>
                        <blockquote>
                            <p>Welcome to KantPHP Framework</p>
                        </blockquote>
                        <p>说明开发环境配置成功。</p>
                        <p>你可以继续浏览 </p>
                        <blockquote>
                            <p><em>http://localhost/kantphp/demo/index/display</em></p>
                            <p><em>http://localhost/kantphp/demo/index/displayfunc</em></p>
                            <p><em>http://localhost/kantphp/demo/index/get/var,abc</em></p>
                            <p><em>http://localhost/kantphp/demo/index/get/var,abc.html</em></p>
                        </blockquote>
                        <p><em>http://localhost/kantphp</em>是网站的根目录，<em>demo/index/get/var,foo.html</em>则是参数，demo是Moduel Name,index是Controller Name,get是Action Name。var,abc等同&var=abc，是parse_url的query部分。html表示一个网页的后缀，可有可无。</p>
                        <p>一个完整的URL访问规则是：</p>
                        <blockquote>
                            <p>http://localhost/kantphp/[模型名称]/[控制器名称]/[操作名称]/[参数名,参数值]/...[.html]</p>
                        </blockquote>
                    </div><!-- /.help-post -->
                    <div class="help-post" id="architecture">
                        <div class="page-header">
                            <h2 class="help-post-title">3. 架构原理</h2>
                        </div>
                        <h3>3.1 入口</h3>
                        <p>程序的入口文件是index.php。</p>
                    </div><!-- /.help-post -->
                </div>
            </div><!-- /.container -->

            <footer class="help-footer">
                <p>Copyleft By<a href="http://www.kantphp.com"> KantPHP Framework </a></p>
                <p>
                    <a href="#">Back to top</a>
                </p>
            </footer>
    </body>
</html>