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
                        <li><a href="#schema">3.架构原理</a></li>
                        <li><a href="#configure">4.项目配置</a></li>
                        <li><a href="#module">5.模块化开发</a></li>
                        <li><a href="#mvcpattern">6.MVC模式</a></li>
                        <li><a href="#controller">7.控制器</a></li>
                        <li><a href="#view">8.视图</a></li>
                        <li><a href="#model">9.模型</a></li>
                        <li><a href="#cookie">10.Cookie</a></li>
                        <li><a href="#cookie">11.Session</a></li>
                        <li><a href="#cache">12.Cache</a></li>
                        <li><a href="#section-5">13.路由与重写</a></li>
                        <li><a href="#section-5">14.扩展</a></li>
                        <li><a href="#section-5">15.第三方类库</a></li>
                        <li><a href="#section-5">16.独立文件服务器</a></li>
                        <li><a href="#section-5">17.多库操作</a></li>
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
                        <p>从<a href="https://github.com/bearlord/kantphp/releases" target="_blank">Github</a>获取KantPHP的发行版或者<a href="https://github.com/bearlord/kantphp/">Git Clone</a>最新版，解压并复制到WEB服务器根目录，如/srv/www/htdocs/kantphp，并赋予目录kantphp写权限和可执行权限。如果是开发环境，粗暴的设置为 <em>0777</em> 是不错的选择。我们会在后续署章节中再讨论权限。</p>
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
                    <div class="help-post" id="schema">
                        <div class="page-header">
                            <h2 class="help-post-title">3. 架构原理</h2>
                        </div>
                        <h3>3.1 入口</h3>
                        <p>程序的入口文件是index.php。</p>
                        <p>流程图完善中。</p>
                    </div><!-- /.help-post -->
                    <div class="help-post" id="configure">
                        <div class="page-header">
                            <h2 class="help-post-title">4. 项目配置</h2>
                        </div>
                        <h3>4.1 路径</h3>
                        <p>配置文件位于/Applcation/Config/。</p>
                        <h3>4.2 环境</h3>
                        <p>为方便项目开发与生产配置，可在入口文件选择环境。以最小改动的代价，有利于迭代开发与版本更替。</p>
                        <blockquote>
                            <p><em>Kant::createApplication('Development')->boot();</em> 表示开发环境，加载的配置文件为：<em>/Applcation/Config/Deveplopment/Config.php</em></p>
                            <p><em>Kant::createApplication('Production')->boot(); </em> 表示生产环境，加载的配置文件为：<em>/Applcation/Config/Deveplopment/Config.php</em></p>
                        </blockquote>
                        <h3>4.3 说明</h3>
                        <blockquote>
                            <p>配置文件以key=>value的数组形式保存</p>
                            <p><code> 'module' => 'demo'</code> 默认的模块Moduel</p>
                            <p><code> 'ctrl' => 'index',</code> 默认的控制器Controller</p>
                            <p><code> 'act' => 'index',</code> 默认的动作Action</p>
                            <p><code> 'data' => array('GET' => array()),</code> 默认的参数Parameter</p>
                            <p><code> 'route_rules' => array(
                                    '|topic/id,(\d+)|i' => 'blog/detail/index/id,$1/c,$2'
                                    ),</code> Rewrite规则，可用正则表达式</p>
                            <p><code> 'path_info_repair' => false,</code> 是否开启Pathinfo修复。如果你的Web服务器不支持Pathinfo，开启此设置。</p>
                            <p><code> 'debug' => true,</code> 是否开启调试模式</p>
                            <p><code> 'url_suffix' => '.html',</code> URL后缀</p>
                            <p><code> 'redirect_tpl' => 'dispatch/redirect',</code> 页面跳转模板</p>
                            <p><code> 'lang' => 'zh_CN',</code> 默认语言。对应的语言包文件是<em>/Application/Locale/zh_CN/App.php。</em></p>
                            <p><code> 'charset' => 'utf-8',</code> 默认编码</p>
                            <p><code> 'default_timezone' => 'Etc/GMT-8',</code> 默认时区</p>
                            <p><code> 'database' => array('deault'=>array()...),</code> 数据库配置。可配置多个数据库，通过模型Model来操作。</p>
                            <p><code> 'cookie_domain' => '',</code> Cookie作用域</p>
                            <p><code> 'cookie_path' => '/',</code> Cookie路径</p>
                            <p><code> 'cookie_pre' => 'kantphp_',</code> Cookie前缀</p>
                            <p><code> 'cookie_ttl' => 0,</code> Cookie失效时间</p>
                            <p><code> 'session' => array(
                                    'default' =>array()...),</code> Session配置。Session可以默认保存，也可以保存到指定路径，数据库等。</p>
                            <p><code> 'cache' => array(
                                    'defalut'=>array()...),</code> 缓存配置。选项有文件缓存，Memcache，Redis缓存。</p>
                            <p>如果你希望自定义配置，继续写入key=>value键值对。<code></code></p>
                        </blockquote>
                        <h3>4.4 读取配置</h3>
                        <p>为提高执行效率，项目初始化时，配置文件以注册模式载入了内存，不需要再读取文件。而且在开发和生产环境之间的来回切换，直接读取文件有不可预知的问题。</p>
                        <blockquote>
                            <p><code>$config = KantRegistry::get('config');$lang = $config['lang']; </code>读取项目配置的默认语言。</code></p>
                        </blockquote>
                    </div><!-- /.help-post -->
                    <div class="help-post" id="module">
                        <div class="page-header">
                            <h2 class="help-post-title">5. 模块化开发</h2>
                        </div>
                        <p>通俗一点讲，模块就是把源文件进行分割。模块化由小块的、分散的代码块组成，每一块都是独立的。这些代码块可以由不同的团队进行开发，而他们都有各自的生命周期和时间表。最终将模块进行集成。</p>
                        <h3>5.1 优点</h3>
                        <ol>
                            <li>思路清晰。水平分割项目后，大项目变小项目，容易理清思路，避免遗漏和陷阱。</li>
                            <li>减少开发时间。分布式开发，减少与其他模块开发者的交流与等待时间。</li>
                            <li>维护灵活。一个模块出现问题，不会影响到其他模块。单独调试此模块解决问题。</li>
                            <li>管理方便。升级时粗暴覆盖全目录，不用花很长时间整理更新文件。</li>
                        </ol>
                        <h3>5.2 创建</h3>
                        <p>KantPHP Framework 没有通过Shell或者PHP Cli创建模块的自动化工具。</p>
                        <p><em>/Application/Module/</em>是模块根目录。<em>/Application/Module/Demo</em> 就代表Demo模块。</p>
                        <p>如果要创建新的模块，手动创建一个文件夹即可。<small>【文绉绉的说了很多，其实操作太简单。】</small></p>
                        <p>尽量避免跨模块调用代码。</p>
                        <p><b class="color-emphasize">为了下文举例方便，假设现在我们创建了一个Blog的模块。</b></p>
                    </div><!-- /.help-post -->
                    <div class="help-post" id="mvcpattern">
                        <div class="page-header">
                            <h2>6. MVC模式</h2>
                        </div>
                        <p>MVC 是一种使用 MVC（Model View Controller 模型-视图-控制器）设计创建 Web 应用程序的模式:</p>
                        <ol>
                            <li>Model（模型）表示应用程序核心（比如数据库记录列表）。是应用程序中用于处理应用程序数据逻辑的部分。通常模型对象负责在数据库中存取数据。</li>
                            <li>View（视图）是应用程序中处理数据显示的部分。通常视图是依据模型数据创建的。</li>
                            <li>Controller（控制器）是应用程序中处理用户交互的部分。通常控制器负责从视图读取数据，控制用户输入，并向模型发送数据。</li>
                        </ol>
                        <p>MVC 分层同时也简化了分组开发。不同的开发人员可同时开发视图、控制器逻辑和业务逻辑。</p>
                        <p>KantPHP Framework在设计之初就考虑了多库操作，同时考虑到数据库连接数，执行效率等因素，采用了比较严格的MVC模式。控制器或者模板里不能直接数据库datebase，只能通过模型来访问。</p>
                        <p>优点：维护升级方便，几乎所有的操作都封装在Model中。缺点：多敲代码封装函数，同时新手需要一个适应的过程。</p>
                    </div><!-- /.help-post -->
                    <div class="help-post" id="controller">
                        <div class="page-header">
                            <h2>7. 控制器</h2>
                        </div>
                        <p>控制器就是一个类，处理浏览器请求和响应，操作模型，赋值到视图，渲染视图等操作。</p>
                        <p>用户通过浏览器访问应用，URL发送的请求会通过入口文件生成一个应用实例，应用控制器会管理整个用户执行的过程，并负责模块的调度和动作的执行，并且在最后销毁该应用实例。任何一个URL访问都可以认为是某个模块的某个操作，例如：</p>
                        <blockquote>
                            <p>http://localhost/kantphp/blog/list/category/id,8.html</p>
                            <p>http://localhost/kantphp/blog/detail/index/id,100.html</p>
                        </blockquote>
                        <p>系统会根据当前的URL来分析要执行的模块和操作。这个分析工作由URL调度器（Dispatcher）来实现，并且都分析成下面的规范：</p>
                        <blockquote>
                            <p>http://域名/项目名/模块名/控制器名/动作名/其他参数/URL后缀</p>
                            <p>Dispatcher会根据URL地址来获取当前需要执行的项目名、模块名，控制器名，动作名以及其他参数，在某些情况下，项目名可能不会出现在URL地址中。</p>
                            <p>控制器类名就是控制器名加上Controller后缀，例如ListContoller类就表示了List控制器。而category动作其实就是ListController类的一个公共方法。</p>
                            <p>所以我们在浏览器里面输入URL：<em>http://localhost/kantphp/blog/list/category/id,8.html</em>其实就是执行了ListControoler类的【category加Action后缀】（公共）方法。</p>
                        </blockquote>
                        <h3>7.1 定义</h3>
                        <p>控制器定义规则为【控制器名+Contrller后缀】，如IndexController。同时要继承BaseController。
                        <p>在<a href="#module">【5. 模块化开发】</a>中我们已经创建了Blog模块，文件路径是<em>/Application/Moduel/Blog/</em>。现在我们要创建一个IndexController的。进入Blog目录下创建一个Controller文件夹，进入Controoler文件夹，创建文件<em>IndexController.php</em>。内容如下：</p>                      
                        <blockquote>
                            <p>class ListController extends BaseController{}</p>
                            <p></p>
                        </blockquote></p>
                        <h3>7.2 空控制器</h3>
                        <p>如果当前系统找不到指定的控制器，会尝试定位空控制器。我们可以定义错误页面和用户体验的优化。文件名为EmptyController.php。内容如下：</p>
                        <blockquote>
                            <p>class EmptyController extends BaseController{}</p>
                        </blockquote>
                        <h3>7.3 动作</h3>
                        <p>控制器可以有多个动作Action，正如一个类可以有多个方法。方法名的定义为【小写的动作名称加Action后缀】，如：</p>
                        <blockquote>
                            <ol class="linenums">
                                <li><code>class ListController extends BaseController{</code><br /></li>
                                <li><span class="pln">&nbsp;&nbsp;&nbsp;&nbsp;</span><code>public function categoryAction(){}</code></li>
                                <li><span class="pln">&nbsp;&nbsp;&nbsp;&nbsp;</span><code>public function orderbydescAction(){}</code></li>
                                <li><span class="pln">&nbsp;&nbsp;&nbsp;&nbsp;</span><code>...</code></li>
                                <li><code>}</code></li>
                                <p></p>
                        </blockquote>
                        <h3>7.4 常用方法</h3>
                        <p>控制器继承父类BaseContorller类与Base类，父类的公共方法，动作中均可调用。</p>
                        <blockquote>
                            <ol>
                                <li>处理URL请求的数据。$this->input对象包含的方法。如：
                                    <ol class="linenums">
                                        <li><code>$id = $this->input->get('id', 'intval', 10);</code> 等同于<br /><code>$id = !empety($_GET['id']) ? intval($_GET['id']) : 10</code> 。</li>
                                        <li><code>$username = $this->input->post('username', 'trim');</code> 等同于<br /><code>if(!empty($_POST['username'])) {</code><br /><code><span class="pln">&nbsp;&nbsp;&nbsp;&nbsp;</span>$username = trim($_POST['username']); </code><br /><code>} </code>。</li>
                                    </ol>
                                <li>缓存数据。$this->cache对象包含的方法。如：
                                    <ol class="linenums">
                                        <li><code>$this->cache->set('var', 'hello world); </code> 缓存字符串'hello world'，查找的键为'var'。</li>
                                        <li><code>$this->cache->get('var');</code> 查找键为'var'的缓存内容。</li>
                                    </ol>
                                <li>多语言输出。$this->lang方法。如
                                    <ol class="linenums">
                                        <li><code>echo $this->lang('USERNAME_IS_EMPTY');</code> 输出项目当前语言【如zh_CN】翻译过的'USERNAME_IS_EMPTY'。翻译的文件位于：<em>/Application/Locale/zh_CN/App.php</em>。如之前已追加过 <code>$LANG['USERNAME_IS_EMPTY'] = '用户名为空！'</code>则会输出【用户名为空！】。如果没有此键值对，则会原样输出【USERNAME_IS_EMPTY】。</li>
                                        <li>如果想增加其他语言，如英语。新建文件<em>/Application/Locale/en_US/App.php</em>，并在配置文件中更改<code>'lang' => 'en_US'</code>即可</li>
                                    </ol>
                                </li>
                                <li>加载模型。$this->loadModel()方法。如：
                                    <ol class="linenums">
                                        <li><code>$memberModel = $this->loadModel('Member');</code> 等同于<br /><code>require_once [当前模块]/Model/MemberModel.php;</code><br /><code>$memberModel = new MemberModel();</code></li>
                                        <li><b>不推荐跨模块加载模型。</b></li>
                                    </ol>
                                </li>
                                <li>生成URL连接。$this->url()方法。如：</li>
                            </ol>
                        </blockquote>
                        <h3>7.5 赋值到视图</h3>
                        <p>要在视图中输出变量，必须在控制器类中把变量传递给视图。用公共属性赋值即可。如：</p>
                        <blockquote>
                            <p><code>$this->view->helloString = 'Hello world';</code></p>
                            <p><code>$this->view->listArray = array(1,3,5,7,9)</code></p>
                            <p><code>$this->view->infoArray = array('name' => 'KantPHP', 'address' => '太行山')</code></p>
                        </blockquote>
                        <p><b>不推荐传递实例化对象。可在视图中实例化。</b></p>
                    </div>
                    <div class="help-post" id="view">
                        <div class="help-header">
                            <h2>8. 视图</h2>
                            <p>视图就是模板文件，就是一个网页。控制器把要输出的数据通过模板变量赋值的方式传递到视图类，视图输出内容到浏览器。</p>
                            <p>一般来说，视图都带有模板引擎。模板引擎把模板的伪代码解析成PHP原生态代码，才可正常运行。为避免重复解析，原生态代码一般写入缓存成文件，触发更新。当目标文件无法写入或者需要间接的写入Memcache/Redis内存曲线保存时，一则影响到效率，二则不适合环境迁移。三是恼人的回调函数问题可轻松通过原生态 PHP 函数解决。</p>
                            <p>KantPHP Framework不带模板引擎，初衷是为了适应新浪SAE，老版本的百度BAE等代码目录没有写入权限的空间和运行环境。开发者可以修改<em>/Application/Kantphp/View/View.php</em>，自行加载模板引擎。</p>
                            <h3>8.1 定义</h3>
                            <p>视图可选择主题，默认的主题是【default】。视图的根目录是/Application/View/default/。如果开发者想更换主题，比如换为【blue】，修改配置文件 <em>'theme' => 'blue'</em>，此时视图的根目录则变为<em>/Application/View/blue</em>。</p>
                            <p>视图定义规则为：</p>
                            <blockquote>
                                <p>视图根目录/模型名/控制器名/动作名/+模板后缀。比如：</p>
                                <p><em>/Application/View/default/blog/comment/apply.php</em></p>
                            </blockquote>
                            <h3>8.2 视图赋值</h3>
                            <p>视图的数据，需要控制器类把变量传递给视图。通过视图类的公开属性赋值。例如：</p>
                            <blockquote>
                                <p>在控制器中：</p>
                                <p><code>$this->view->hello = 'Hello World';</code></p>
                                <p><code>$this->view->userInfo = array('name' => '欢乐的洞主', 'address' => '河南郑州');</code></p>
                                <p>在视图中：</p>
                                <p><code>echo $hello;</code> 解析后是【Hello World】</p>
                                <p><code>echo $userInfo['name'];  echo $userInfo['address'];</code> 解析后是【欢乐的洞主 河南郑州】</p>
                            </blockquote>
                            <h3>8.3 视图输出</h3>
                            <p>视图变量赋值后，需要调用模板文件来输出相关的变量，视图调用通过display方法来实现。我们在控制的动作方法的最后使用：</p>
                            <blockquote>
                                <p><code>$this->view->display();</code> 调用默认视图</p>
                                <p><code>$this->view->display('list/ajaxpage');</code> 调用指定视图</p>
                                <p><code>$this->view->display('list/ajaxpage', 'blog');</code> 调用指定模块的指定视图</p>
                            </blockquote>
                            <p>就可以输出模板。根据前面的视图定义规则，系统会按照默认规则自动定位视图文件，通常display方法无需带任何参数即可输出对应的视图。</p>
                            <h3>8.4 获取内容</h3>
                            <p>如果开发者不想直接输出模板内容，而是存入变量，可以使用fetch方法来获取视图内容</p>
                            <blockquote>
                                <p><code>$content = $this->view->fetch();</code></p>
                                <p>fetch的参数用法和display方法基本一致。</p>
                            </blockquote>
                            <h3>8.5 替代控制结构</h3>
                            <p>视图文件中使用原始 PHP 代码。要使 PHP 代码达到最精简并使其更容易辨认，因此建议你使用 PHP 替代语法控制结构。如</p>
                            <blockquote>
                                <p><code><?php echo htmlspecialchars("<?php");?> foreach ($todo as $item): <?php echo htmlspecialchars("?>");?></code></p>
                                <p><span class="pln">&nbsp;&nbsp;&nbsp;&nbsp;</span><code><?php echo htmlspecialchars("<?php");?> echo $item; <?php echo htmlspecialchars("?>");?></code></p>
                                <p><code><?php echo htmlspecialchars("<?php");?> endforeach; <?php echo htmlspecialchars("?>");?></code></p>
                            </blockquote>
                        </div>
                    </div>
                    <div class="help-post" id="model">
                        <div class="page-header">
                            <h2>9 模型</h2>
                        </div>
                    </div>
                </div>
            </div><!-- /.container -->

            <footer class="help-footer">
                <p>Copyright By<a href="http://www.kantphp.com"> KantPHP Framework Studio </a></p>
                <p>
                    <a href="#">Back to top</a>
                </p>
            </footer>
    </body>
</html>