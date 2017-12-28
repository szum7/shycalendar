<?php

function CompareById($a, $b) {
    $val1 = intval($a->id);
    $val2 = intval($b->id);
    if ($val1 == $val2) {
        return 0;
    }
    return ($val1 < $val2) ? -1 : 1;
}

/**
 * Class casting
 *
 * @param string|object $destination
 * @param object $sourceObject
 * @return object
 */
function Cast($sourceObject, $destination)
{
    if (is_string($destination)) {
        $destination = new $destination();
    }
    $sourceReflection = new ReflectionObject($sourceObject);
    $destinationReflection = new ReflectionObject($destination);
    $sourceProperties = $sourceReflection->getProperties();
    foreach ($sourceProperties as $sourceProperty) {
        $sourceProperty->setAccessible(true);
        $name = $sourceProperty->getName();
        $value = $sourceProperty->getValue($sourceObject);
        if ($destinationReflection->hasProperty($name)) {
            $propDest = $destinationReflection->getProperty($name);
            $propDest->setAccessible(true);
            $propDest->setValue($destination,$value);
        } else {
            $destination->$name = $value;
        }
    }
    return $destination;
}
