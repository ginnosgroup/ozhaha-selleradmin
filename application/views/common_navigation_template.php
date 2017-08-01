<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">哈哈外卖商家后台</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="logout"><i class="fa fa-sign-out fa-fw"></i> 退出</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="show-logo">
                    <img src="{logo_url}" alt="">
                </li>
                <li>
                    <a href="welcome"><i class="fa fa-dashboard fa-fw"></i> 后台首页</a>
                </li>
                <li>
                    <a href="order"><i class="fa fa-table fa-fw"></i> 订单列表</a>
                </li>
                <li>
                    <a href="shop"><i class="fa fa-edit fa-fw"></i> 店铺设置</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-files-o fa-fw"></i> 本店商品<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="items_test">商品列表</a></li>
                        <!--<li><a href="add_item">新增商品</a></li>-->
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-sitemap fa-fw"></i> 商品类目<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="item_category">分类管理</a>
                        </li>
                        <li>
                            <a href="add_item_category">新增分类</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-gear fa-fw"></i> 密码设置<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="change_passwd">修改密码</a>
                        </li>                        
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>