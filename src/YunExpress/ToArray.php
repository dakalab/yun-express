<?php

namespace Dakalab\YunExpress;

trait ToArray
{
    /**
     * Convert object properties to array recursively
     *
     * @return array
     */
    public function toArray()
    {
        $properties = get_object_vars($this);
        $result = [];
        foreach ($properties as $key => $value) {
            if (is_object($value)) {
                $result[$key] = $value->toArray();
            } elseif (is_array($value)) {
                foreach ($value as $v) {
                    $result[$key][] = $v->toArray();
                }
            } elseif (!is_null($value)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
