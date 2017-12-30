<?php

function CompareById($a, $b) {
    $val1 = intval($a->id);
    $val2 = intval($b->id);
    if ($val1 == $val2) {
        return 0;
    }
    return ($val1 < $val2) ? -1 : 1;
}
