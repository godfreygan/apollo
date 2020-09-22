<?php

namespace App\Apollo\Modules;

use Exception;
use Illuminate\Support\Arr;
use Log;
use Illuminate\Support\Facades\Storage;
use App\Apollo\Library\Util\ApolloClient;

class Env extends Base
{
    protected $server;
    protected $save_dir;
    protected $app;
    protected $namespace;
    protected $clusterName;
    protected $error = "";
    
    public function __construct($app, $server = 'http://127.0.0.1:8180', $clusterName = "default")
    {
        $dir = env("OUTPUT_ENV_PATH", \App::storagePath("config/"));
        $this->server = $server;
        $this->save_dir = $dir . $app;
        $this->app = $app;
        $this->isDir($this->save_dir);
        $this->clusterName = $clusterName;
    }
    
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        
        return $this;
    }
    
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @title: 生成.env文件
     * @author: ganqixin <godfrey.gan@handeson.com>
     * @return mixed
     * @throws Exception
     */
    public function create()
    {
        $apollo = new ApolloClient($this->server, $this->app, $this->getNamespace());
        $apollo->setCluster($this->clusterName);

        //从apollo上拉取的配置默认保存在脚本目录，可自行设置保存目录
        $apollo->save_dir = $save_dir = $this->save_dir;

        return $apollo->start(function () use ($save_dir) {
            //读取里面的文件,然后进行合并
            $list = glob($save_dir . DIRECTORY_SEPARATOR . 'apolloConfig.*');
            $apollo = [];
            foreach ($list as $l) {
                $config = require $l;
                if (is_array($config) && isset($config['configurations'])) {
                    $apollo = array_merge($apollo, $config['configurations']);
                    if (Arr::get($config, "namespaceName") == 'application') {
                        $apollo['releaseKey'] = Arr::get($config, "releaseKey");
                    }
                }
            }
            if (!$apollo) {
                Log::warning('Load Apollo Config Failed, no config available', [$this->app, $this->getNamespace()]);
                throw new Exception('Load Apollo Config Failed, no config available');
            }
            //删除所有文件
            $fs = Storage::disk("config");
            $r = $fs->deleteDirectory($save_dir);
            if (!$r) {
                throw new Exception('文件删除失败');
            }
            ksort($apollo);
            return $this->save($apollo);
        });
    }
    
    /**
     * 保存为env文件
     *
     * @param $data
     * @return bool
     */
    public function save($data)
    {
        if (is_array($data)) {
            $releaseKey = Arr::pull($data, "releaseKey");
            $newArray = [
                'desc' => "#get env from apollo releaseKey:$releaseKey at " . date("Y-m-d H:i:s"),
            ];
            $c = 0;
            foreach ($data as $key => $value) {
                if (preg_match('/\s/', $value) > 0 && (strpos($value, '"') > 0 && strpos($value, '"', -0) > 0)) {
                    $value = '"' . $value . '"';
                }
                
                $newArray[$c] = $key . "=" . $value;
                $c++;
            }
            
            $newArray = implode("\n", $newArray);
            
            return $newArray;
        }
        
        return false;
    }
    
    /**
     * 保存.env文件
     *
     * @param $data
     * @param $app
     * @return string
     * @throws Exception
     */
    public function saveEnv($data, $app)
    {
        $save_dir = env("OUTPUT_APPLICATION_PATH");
        $dir = $save_dir . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR;
        //判断path是否可写,
        if (!is_dir($dir)) {
            Log::error("{$dir}目录不存在", [$app, $dir]);
            $this->error = "{$dir}目录不存在[{$app}]";
        
            return false;
        } elseif (!is_writable($dir)) {
            Log::error("{$dir}目录不可写", [$app, $dir]);
            $this->error = "{$dir}目录不可写[{$app}]";
        
            return false;
        }
        file_put_contents($dir . ".env", $data);
    
        return $dir;
    }
    
    public function getError()
    {
        return $this->error;
    }
    
    private function isDir($save_dir)
    {
        return !(!is_dir($save_dir) && !@mkdir($save_dir, 0777, true) && !is_dir($save_dir));
    }
}
