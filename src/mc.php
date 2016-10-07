<?php
namespace ptcms\extend;

use ptcms\Cache;
use ptcms\Config;
use ptcms\Model;

class Mc
{
    
    public static function get($model, $id, $field = null)
    {
        $key  = md5(Config::get('app.mode', 'web') . '-' . $model . '-' . $id);
        $data = Cache::get($key . Config::get('app.debug') == true ? '1' : '', function () use ($model, $id, $key) {
            /* @var $dbModel Model */
            $dbModel = new $model();
            $data    = $dbModel->get('*', ['AND' => ['id' => $id]]);
            if ($data && method_exists($dbModel, 'dataAppend')) {
                $data = $dbModel->dataAppend($data);
            }
            Cache::set($key, $data, Config::get('cache.time', 1800));
            return $data;
        });
        if ($field !== null) {
            return isset($data[$field]) ? $data[$field] : null;
        } else {
            return $data;
        }
    }
    
    public static function set($model, $id, array $data = [])
    {
        $key = md5(Config::get('app.mode', 'web') . '-' . $model . '-' . $id);
        if($data){
            /* @var $dbModel Model */
            $dbModel = new $model();
            $dbModel->update($data, ['id' => $id]);
        }
        Cache::remove($key);
    }
    
    public static function remove($model, $id)
    {
        $key = md5(Config::get('app.mode', 'web') . '-' . $model . '-' . $id);
        Cache::remove($key);
    }
}