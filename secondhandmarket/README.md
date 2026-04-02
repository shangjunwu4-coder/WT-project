assets/ 这是放前端资源的文件夹
assets/css/style.css 放网站的样式代码
页面颜色
商品卡片样式
按钮样式  
 导航栏
响应式布局
手机端适配

assets/javascript/main.js 放前端交互代码
表单前端验证
图片预览
按钮点击提示
搜索框交互
分类筛选交互  
 AJAX 加入购物车

includes/ includes/dbconnect.php 数据库连接文件
连接 MySQL 让其他 PHP 页面都能复用这段连接代码
避免每个页面都重复写连接数据库的代码

sql/database.sql 数据库初始化脚本。
创建数据库
创建表
插入默认分类数据

uploads/ 这是上传文件夹
存用户上传的商品图片

index.php 网站入口页
展示最新商品、热门商品、欢迎语
放导航栏、搜索框、分类入口

register.php 用户填写用户名、邮箱、密码等信息
提交注册表单 做前端和后端验证
把新用户写进数据库

login.php 用户输入邮箱/用户名和密码
验证身份
登录成功后创建 session

logout.php 清除
session 让用户退出账号

products.php 展示所有商品
分类筛选  
 搜索
排序
分页

product_detail.php 商品详情页
展示某一个商品的详细信息
商品图片
商品名称、价格、描述、分类、卖家信息
收藏按钮 / 加入购物车按钮

add_product.php 发布商品页面
已登录用户填写商品标题、价格、分类、描述、图片  
 提交表单后把商品存到数据库
这个是 CRUD 里的 Create。

edit_product.php 编辑商品页面
让商品发布者修改自己的商品信息
改标题、改价格、改描述、改分类、改图片等
这个是 CRUD 里的 Update。

delete_product.php 删除商品处理文件
删除商品
或者把商品状态改成 removed
这个是 CRUD 里的 Delete。
通常这个文件不一定有完整页面，很多时候是一个处理逻辑文件。

my_products.php 我的商品页面
登录用户查看自己发布过的商品
可以在这里编辑、删除、下架商品
这个页面答辩时很适合展示权限控制：
“用户只能管理自己的商品”。

cart.php 购物车页面
展示加入购物车的商品
修改数量
删除商品
统计总价
你们前期可以先用 session 做，不一定落数据库。

吴尚骏负责文件
assets/css/style.css
页面整体风格
导航栏
商品卡片
按钮
表单样式
响应式布局
assets/javascript/main.js
表单前端验证
图片预览
搜索交互
筛选交互
提示框
加购物车按钮动态反馈
AJAX
首页布局
轮播/推荐区（如果做）
商品展示卡片样式
index.php
商品列表的页面排版
搜索框和筛选区外观
分页按钮样式
products.php
商品详情展示布局
加入购物车按钮
收藏按钮样式
product_detail.php
商品详情展示布局
加入购物车按钮
收藏按钮样式
cart.php
购物车页面展示
数量加减的前端交互
总价显示样式
my_products.php
我的商品页布局
编辑/删除按钮样式
页面整合

史朝鲁负责文件
sql/database.sql
创建数据库
建表
插入默认分类
includes/dbconnect.php
连接 MySQL
设置 PDO 或 mysqli
add_product.php
接收表单数据
验证
图片上传处理
插入商品到数据库
edit_product.php
读取原商品信息
更新数据库
delete_product.php
删除商品或修改状态
products.php 里的后端查询部分
从数据库读取商品列表
搜索查询
分类筛选查询
分页查询
product_detail.php 里的后端读取部分
按商品 id 读取详情
my_products.php 里的后端读取部分
读取当前用户发布的商品

朱晨希负责文件
register.php
注册页面表单
用户输入验证
提交注册
错误提示显示
login.php
登录页面表单
登录验证
session 创建
logout.php
session 销毁
跳转回首页/登录页
README.md
