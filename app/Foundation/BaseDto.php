<?php

namespace App\Foundation;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;

abstract class BaseDto implements Jsonable, Arrayable
{
    /**
     * @param array $data
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $datum) {
            $this->set($key, $datum);
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function toArray(): array
    {
        $arr = [];
        foreach ($this->getProperties() as $key) {
            if (isset($this->$key)) {
                $ar[Str::snake($key)] = $this->get($key);
            }
        }
        return $arr;
    }

    /**
     * @param $options
     * @return false|string
     * @throws \Exception
     */
    public function toJson($options = 0): false|string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function get($name): mixed
    {
        $property = Str::camel($name);
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        throw new \Exception('Property ' . $property . ' does not exist on ' . get_class($this));
    }

    /**
     * @param $name
     * @param $value
     * @return null
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * @param $name
     * @param $value
     * @return void
     * @throws \Exception
     */
    public function set($name, $value): void
    {
        $property = Str::camel($name);
        if (!property_exists($this, $property)) {
            throw new \Exception('Can\'t set property ' . $property . ' that does not exist on ' . get_class($this));
        }
        $this->{$property} = $value;
    }

    /**
     * @return int[]|string[]
     */
    private function getProperties(): array
    {
        return array_keys(get_object_vars($this));
    }
}
