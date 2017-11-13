# 代码规范

在目录设置好以后，我们接下来规定代码的规范：
MySQL的表名需小写或小写加下划线，如：item，car_orders。
模块名（Models）需用大驼峰命名法，即首字母大写，并在名称后添加Model，如：ItemModel，CarModel。
控制器（Controllers）需用大驼峰命名法，即首字母大写，并在名称后添加Controller，如：ItemController，CarController。
方法名（Action）需用小驼峰命名法，即首字母小写，如：index，indexPost。
视图（Views）部署结构为控制器名/行为名，如：item/view.php，car/buy.php。

# 目录准备
在开始开发前，让我们先来把项目建立好。

假设我们建立的项目为 project，MVC的框架命名为 fastphp，那么接下来，第一步要把目录结构设置好。

project  WEB部署目录
├─application           应用目录
│  ├─controllers        控制器目录
│  ├─models             模块目录
│  ├─views              视图目录
|  ├─common             公共目录
├─config                配置文件目录
├─wisonphp              框架核心目录
├─static                静态文件目录
├─runtime               缓存日志目录
├─index.php             入口文件
然后把Nginx或者Apache的站点根目录配置到project目录。

# 重定向
重定向的目的有两个：设置根目录为project所在位置，以及将所有请求都发送给 index.php 文件。

如果是Apache服务器，在 project 目录下新建一个 .htaccess 文件，内容为：

```
<IfModule mod_rewrite.c>
    # 打开Rerite功能
    RewriteEngine On

    # 如果请求的是真实存在的文件或目录，直接访问
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    # 如果访问的文件或目录不是真事存在，分发请求至 index.php
    RewriteRule . index.php
</IfModule>
```

如果是Nginx服务器，修改配置文件，在server块中加入如下的重定向：

```
location / {
    # 重新向所有非真是存在的请求到index.php
    try_files $uri $uri/ /index.php$args;
}
```

这样做的主要原因是：

1. 静态文件能直接访问。
如果文件或者目录真实存在，则直接访问存在的文件/目录。
比如，静态文件static/css/main.css真实存在，就可以直接访问它。

2. 程序有单一的入口。
这种情况是请求地址不是真实存在的文件或目录，这样请求就会传到 index.php 上。
例如，访问地址：localhost/item/view/1，在文件系统中并不存在这样的文件或目录。
那么，Apache或Nginx服务器会把请求发给index.php，并且把域名之后的字符串赋值给REQUEST_URI变量。
这样在PHP中用$_SERVER['REQUEST_URI']就能拿到/item/view/1；

3. 可以用来生成美化的URL，利于SEO。