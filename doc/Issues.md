# Issues

## 是否选用fpm-alpine作为基础镜像

alpine是最简系统  
但需要单独安装许多依赖, 安装依赖后的容器也不小。

## php扩展安装方式

1. 编译： 麻烦
2. pecl： 简单易用，支持版本多
3. docker-php-ext-install  
    1. 需要手动下载扩展比较麻烦（相应的，能够简短实例化的时间）。
    2. 会有蛋疼的问题 `Cannot find config.m4； Make sure that you run '/usr/local/bin/phpize' in the top level source directory of the module`

## 设置docker代理

`docker->deamon->add->https://h3j9xv2v.mirror.aliyuncs.com`

## 守护进程的dockerfile（phpfpm, nginx），CMD或ENTRYPOINT是主进程执行，执行后直接退出

解决:

1. 脚本的最后再开启一次守护进程？

## 多个服务之间的bin不互通

问题:

项目php代码中出现了`exec mysqldump`，由于php，mysql耦合，但php服务环境中又无法执行mysql服务的mysqldump。

解决:

1. phpfpm与mysql合二为一
2. phpfpm中安装mysql-client，但要注意与mysql服务中版本相同。

## mysql port: 0 不可用

```log
tk_mysql_1  | 2019-09-11T16:32:24.780380Z 0 [Warning] Insecure configuration for --pid-file: Location '/var/run/mysqld' in the path is accessible to all OS users. Consider choosing a different directory.
tk_mysql_1  | 2019-09-11T16:32:24.785494Z 0 [Note] InnoDB: Buffer pool(s) load completed at 190911 16:32:24
tk_mysql_1  | 2019-09-11T16:32:24.792758Z 0 [Note] Event Scheduler: Loaded 0 events
tk_mysql_1  | 2019-09-11T16:32:24.792960Z 0 [Note] mysqld: ready for connections.
tk_mysql_1  | Version: '5.7.27'  socket: '/var/run/mysqld/mysqld.sock'  port: 0  MySQL Community Server (GPL)
tk_mysql_1  | Warning: Unable to load '/usr/share/zoneinfo/iso3166.tab' as time zone. Skipping it.
tk_mysql_1  | Warning: Unable to load '/usr/share/zoneinfo/leap-seconds.list' as time zone. Skipping it.
tk_mysql_1  | Warning: Unable to load '/usr/share/zoneinfo/zone.tab' as time zone. Skipping it.
tk_mysql_1  | Warning: Unable to load '/usr/share/zoneinfo/zone1970.tab' as time zone. Skipping it.
```

## mac 无法10.14无法安装扩展

由于/usr/include不可见，安装header头文件sdk即可。

```shell
cd /Library/Developer/CommandLineTools/Packages/
open macOS_SDK_headers_for_macOS_10.14.pkg
```

## mac SPI保护

- 禁掉SIP保护机制:
  - 重启系统
  - 按住Command + R   （重新亮屏之后就开始按，象征地按几秒再松开，出现苹果标志，ok）
  - 菜单“实用工具” ==>> “终端” ==>> 输入csrutil disable；执行后会输出：Successfully disabled System Integrity Protection. Please restart the machine for the changes to take effect.
  - 重启系统
