# SecondHandMarket

## 项目名称
SecondHandMarket

## 项目简介
SecondHandMarket 是一个基于 **PHP + MySQL + HTML + CSS + JavaScript** 开发的二手交易平台项目，目标是实现一个简洁、实用、易操作的在线二手商品交易网站。用户可以在平台上完成注册、登录、浏览商品、发布商品、管理商品信息等基础操作，并在后续版本中逐步扩展收藏、分类筛选、搜索等功能。

本项目的核心目标是通过一个完整的 Web 项目开发过程，系统练习前端页面搭建、后端逻辑编写、数据库设计与 CRUD 功能实现，掌握基础动态网站开发的完整流程。同时，项目也强调团队协作，通过明确分工完成前端、后端、数据库和文档整理等不同模块的开发任务。

SecondHandMarket 的整体设计重点包括：

- 实现用户注册与登录功能
- 建立规范清晰的数据库结构
- 实现商品信息的增删改查（CRUD）
- 构建简洁直观的前端页面
- 提升页面交互体验和视觉效果
- 为后续收藏、分类、搜索等功能预留扩展空间

## 项目目标
本项目当前阶段的主要目标如下：

1. 完成注册页与登录页的页面结构搭建
2. 实现基础前端表单验证
3. 实现用户注册与登录的后端基础逻辑
4. 完成数据库连接及用户数据写入
5. 编写 README 初稿，整理项目基本信息
6. 逐步推进商品展示、商品管理与 CRUD 功能开发

## 技术栈

### 前端
- HTML
- CSS
- JavaScript

### 后端
- PHP

### 数据库
- MySQL

### 开发环境
- XAMPP
- VSCode
- phpMyAdmin

## 项目结构（当前规划）
SecondHandMarket/
│
├── index.php
├── login.php
├── register.php
├── products.php
├── README.md
│
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── javascript/
│   │   └── main.js
│   └── images/
│
└── backend / database / other files ...


当前阶段任务说明

成员名单：吴尚骏、史朝鲁、朱晨希

当前分工
吴尚骏
负责前端页面基础搭建、样式和交互部分，具体包括：
assets/css/style.css
index.php
products.php 的前端部分
assets/javascript/main.js

主要任务说明：
负责网站整体前端页面布局
负责页面样式设计与统一
负责首页与商品页前端展示效果
负责基础交互脚本编写
负责提升页面整体视觉效果和用户体验

史朝鲁
负责数据库与后端核心逻辑部分，具体包括：
数据库设计
后端功能实现
CRUD 核心逻辑

主要任务说明：
负责数据库结构设计与数据表规划
负责数据库连接与数据管理
负责用户注册、登录等后端数据处理
负责商品信息的增删改查核心功能
负责项目主要业务逻辑实现

朱晨希
注册页面与登录页面搭建
基础前端验证
登录 / 注册后端基础逻辑
README 初稿编写相关任务


项目后续数据库预计至少包含以下核心数据表：

1、users
用于存储用户信息，例如：
id、username、email、password、phone、created_at、categories

2、用于存储商品分类，例如：
id、category_name、products

3、用于存储商品信息，例如：
id、user_id、category_id、title、description、price、product_image、product_status、created_at、updated_at、favorites

4、用于存储用户收藏关系，例如：
id、user_id、product_id、created_at


运行环境
项目开发与运行环境如下：
Windows、XAMPP、Apache、MySQL、phpMyAdmin、VSCode
项目运行方法：
打开 XAMPP，启动 Apache 和 MySQL
将项目文件放入 htdocs 目录
使用 phpMyAdmin 创建数据库
导入项目所需数据表
在浏览器中访问项目页面，例如：
http://localhost/index.php
http://localhost/login.php
http://localhost/register.php


项目意义
通过 SecondHandMarket 项目的开发，团队成员可以系统练习并掌握：
基础 Web 项目开发流程，PHP 与 MySQL 的协同使用，前后端分工合作方式，注册 / 登录系统实现，CRUD 功能设计与开发，页面美化与用户体验优化，项目文档撰写与规范整理。

该项目不仅是一次课程实践，也为后续开发更完整的动态网站项目积累经验。

备注
当前 README 为项目开发初稿，后续会随着项目进度继续补充与更新，包括数据库结构、功能截图、模块说明、测试结果与部署说明等内容。
