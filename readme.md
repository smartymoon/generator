# Laravel 文件自动生成工具

## 安装
```composer require smartymoon/generator --dev```

打开 http://yourdomain.test/lee 即可使用

## 一览
![Snipaste_2021-07-14_10-30-17](https://user-images.githubusercontent.com/12489528/125551336-0bfb6ed5-0c4c-4e67-969a-42a13975140a.png)

## 项目由来
做过的开发越多越能发现大量的时间都用在了重复性的工作上。比如创建 Model Migration Controller 这类文件，它们有极高的雷同性。作为程序员，我们需要把宝贵的时间放在业务上，重复性的工作交给软件自动生成就好。Laravel 官方明显意识到这点 `php artisan make` 系列命令提供了很多方便。但毕竟千人千面，官方只能提供最基数的内容，显然这满足不了个性化需求

网络上有很多类似的自动生成工具，每发现一个我都如获至宝，遗憾的是深入了解后都不适用自己的情况。于是便萌生子自己造轮子的想法，好处也显而易见，随着知识的积累与技术的发展，我可以轻松升级生成工具，让它分分钟生成我想要的东西

## 特性
- 有 blade api Inertia 三种模板
- 支持模块化, 让模型更有条理 /App/Models/House/Rent.php
- 支持汉字友好的假数据生成
- 浏览器表单操作，方便简单
- 浏览器实时保存数据，即使中途刷新，数据也不会丢
- 每次生成文件自动 git commit，方便回滚
- 支持 Enum 自动生成, 依赖 spatie laravel-enum

## 使用建议
我深知每个团队的规范都不同，我的习惯和你的大概率是不同的。所以建议 Fork 后二次加工成适合自己的模板

希望你能给我一个 Star ，感谢
