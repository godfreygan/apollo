## apollo客户端

> 配置管理困难，使用阿波罗进行配置管理


### 一、下载代码


### 二、配置 .env文件 ，参考 .env.example文件 

```text
# 环境代号（如果是开发机环境多开发者，使用more-dev）
APP_ENV=local

#日志保存目录
LOG_DIR=/data/logs/app/

APP_NAME=apollo-client

#common redis 配置
COMMON_REDIS_HOST = 127.0.0.1
COMMON_REDIS_PORT = 6379
COMMON_REDIS_DATABASE = 0

# apollo服务的地址
DOMAIN_APOLLO_SERVICE_HOST = 127.0.0.1:8070

# apollo授权Token
APOLLO_AUTH_TOKEN = fd1a4334d3db108014f3ffc476a43a2f32d3f981

# .env输出目录，默认会添加app（如，/data/www/order-service/.env）
OUTPUT_APPLICATION_PATH = /data/www/
```


### 三、artisan获取

1)指定app的配置
php artisan apollo:apollo_sync --env={环境代号} --app_id={App名称} --cluster_name={集群名称} --namespace_name={命名空间} --project_name={项目目录的名称}

示例：php artisan apollo:apollo_sync --env=dev --app_id=order-service --cluster_name=ganqixin --namespace_name=application --project_name=order-service


2)获取所有的配置
php artisan apollo:apollo_sync_all



### Apollo服务使用

参考：https://github.com/ctripcorp/apollo/wiki/Apollo%E5%BC%80%E6%94%BE%E5%B9%B3%E5%8F%B0