<?php

namespace App\Twig\Runtime;

use App\Service\DateDeptBuilt;
use Twig\Extension\RuntimeExtensionInterface;

class DateDeptBuiltRuntime implements RuntimeExtensionInterface
{
    private $dateDeptBuilt;

    public function __construct(DateDeptBuilt $dateDeptBuilt)
    {
        $this->dateDeptBuilt = $dateDeptBuilt;
    }

    public function doSomething($value)
    {
        $value = $this->dateDeptBuilt->BuiltDateDept($value);
        return $value;
    }
}
