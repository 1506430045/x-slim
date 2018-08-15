<?php

/* admin/index.html */
class __TwigTemplate_9d289bd7052fcd668655d1f53258ee647f3b10a88437c272ffba9e0b88d5988c extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>🍬Candy-Admin</title>
    <meta name=\"renderer\" content=\"webkit|ie-comp|ie-stand\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">
    <meta name=\"viewport\" content=\"width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi\" />
    <meta http-equiv=\"Cache-Control\" content=\"no-siteapp\" />

    <link rel=\"shortcut icon\" href=\"/favicon.ico\" type=\"image/x-icon\" />
    <link rel=\"stylesheet\" href=\"./css/font.css\">
    <link rel=\"stylesheet\" href=\"./css/xadmin.css\">
    <script type=\"text/javascript\" src=\"https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js\"></script>
    <script src=\"./lib/layui/layui.js\" charset=\"utf-8\"></script>
    <script type=\"text/javascript\" src=\"./js/xadmin.js\"></script>

</head>
<body>
<!-- 顶部开始 -->
<div class=\"container\">
    <div class=\"logo\"><a href=\"./index.html\">🍬Candy-Admin</a></div>
    <div class=\"left_open\">
        <i title=\"展开左侧栏\" class=\"iconfont\">&#xe699;</i>
    </div>
    <ul class=\"layui-nav right\" lay-filter=\"\">
        <li class=\"layui-nav-item\">
        </li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class=\"left-nav\">
    <div id=\"side-nav\">
        <ul id=\"nav\">
            <li>
                <a href=\"javascript:;\">
                    <i class=\"iconfont\">&#xe6b8;</i>
                    <cite>会员管理</cite>
                    <i class=\"iconfont nav_right\">&#xe697;</i>
                </a>
                <ul class=\"sub-menu\">
                    <li>
                        <a _href=\"member-list.html\">
                            <i class=\"iconfont\">&#xe6a7;</i>
                            <cite>会员列表</cite>

                        </a>
                    </li >
                </ul>
            </li>
            <li>
                <a href=\"javascript:;\">
                    <i class=\"iconfont\">&#xe723;</i>
                    <cite>订单管理</cite>
                    <i class=\"iconfont nav_right\">&#xe697;</i>
                </a>
                <ul class=\"sub-menu\">
                    <li>
                        <a _href=\"order-list.html\">
                            <i class=\"iconfont\">&#xe6a7;</i>
                            <cite>订单列表</cite>
                        </a>
                    </li >
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- <div class=\"x-slide_left\"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class=\"page-content\">
    <div class=\"layui-tab tab\" lay-filter=\"xbs_tab\" lay-allowclose=\"false\">
        <ul class=\"layui-tab-title\">
            <li class=\"home\"><i class=\"layui-icon\">&#xe68e;</i>我的桌面</li>
        </ul>
        <div class=\"layui-tab-content\">
            <div class=\"layui-tab-item layui-show\">
                <iframe src='./welcome.html' frameborder=\"0\" scrolling=\"yes\" class=\"x-iframe\"></iframe>
            </div>
        </div>
    </div>
</div>
<div class=\"page-content-bg\"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class=\"footer\">
    <div class=\"copyright\">Copyright ©2018 ThinkBit All Rights Reserved</div>
</div>
<!-- 底部结束 -->
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "admin/index.html";
    }

    public function getDebugInfo()
    {
        return array (  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/index.html", "/private/var/www/candy-api/app/view/admin/index.html");
    }
}
