<?php

namespace app\components;

use \yii\db\ActiveRecord;

/**
 * Class SimpleCache
 * @package app\components
 */
class SimpleCache
{
    const DIR = __DIR__ . '/../runtime/cache/simple';

    /**
     * @todo use modern engine (memcache or redis)
     */
    const ENGINE = 'file';

    /**
     * @todo implement Strategy Pattern
     */
    public function __construct()
    {
        //$this->engine = $engine;
    }

    public function flush()
    {
        if ('file' == self::ENGINE) {
            $this->removeDir(self::DIR);
        } else {
            $this->implementMePlease();
        }

        return $this;
    }

    public function rebuild()
    {
        $this->flush();

        if ('file' == self::ENGINE) {
            mkdir(self::DIR, 0755, true);
        } else {
            $this->implementMePlease();
        }

        return $this;
    }

    protected function removeDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $this->removeDir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }

    public function setAllModels($ar)
    {
        /** @var $ar ActiveRecord */
        $this->set($ar, self::mapModels($ar::find()->all()));

        return $this;
    }

    public function getAllModels($ar)
    {
        return $this->get($ar);
    }

    public function setModels($k, $v)
    {
        /** @var ActiveRecord[] $models */
        $models = self::normalizeValue($v);

        $this->set($k, self::mapModels($models));

        return $this;
    }

    protected static function mapModels(array $models)
    {
        $tmp = array();

        foreach ($models as $model) {
            /** @var ActiveRecord $model */
            $tmp[$model->id] = $model->getAttributes();
        }

        return $tmp;
    }

    public function set($k, $v)
    {
        $v = self::normalizeValue($v);

        if ('file' == self::ENGINE) {
            file_put_contents(self::getCacheFileName($k), '<?php return ' . var_export($v, true) . ';');
        } else {
            $this->implementMePlease();
        }

        return $this;
    }

    protected function normalizeValue($v)
    {
        return $v instanceof \Closure ? $v() : $v;
    }

    public function call($k, \Closure $v)
    {
        if ($this->test($k)) {
            return $this->get($k);
        }

        $this->set($k, $r = $v());
        return $r;
    }

    public function test($k)
    {
        if ('file' == self::ENGINE) {
            return file_exists(self::getCacheFileName($k));
        }

        $this->implementMePlease();
    }

    public function get($k)
    {
        if ('file' == self::ENGINE) {
            $tmp = self::getCacheFileName($k);
            /** @noinspection PhpIncludeInspection */
            return require($tmp);
        }

        $this->implementMePlease();
    }

    /**
     * @throws \Exception
     */
    protected function implementMePlease()
    {
        throw new \Exception('Implement me please');
    }

    protected static function getCacheFileName($k)
    {
        return self::DIR . '/' . md5($k) . '.php';
    }
}

