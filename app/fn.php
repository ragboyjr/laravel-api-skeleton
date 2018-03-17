<?php

namespace App;

use Krak\Fn\{Curried as c, Consts as cn};
use function Krak\Fn\{reduce, pipe};

function marshalEntityRef() {
    return function($entity) {
        return ['id' => $entity->id];
    };
}

function marshalEntity(...$fns) {
    return pipe(...array_merge(
        [cn\iter, c\filterKeys(function($key) {
            return strpos($key, "_") !== 0;
        })],
        $fns,
        [dates(), cn\toArrayWithKeys]
    ));
}

function dates($format = 'r') {
    return c\map(function($v) use ($format) {
        return $v instanceof \DateTimeInterface ? $v->format($format) : $v;
    });
}
