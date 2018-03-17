<?php

namespace App\Model;

use function App\marshalEntity;

trait EntityMarshal
{
    public function marshal() {
        return marshalEntity()($this);
    }
}
