<?php

namespace App\Twig\Runtime;

use App\Service\DateDeptBuilt;
use Twig\Extension\RuntimeExtensionInterface;

class AppTwigRuntime implements RuntimeExtensionInterface
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

    public function pluralize(int $nbrElement, $singulier, $pluriel)
    {
        if ($singulier == 'trajet')
            $nbrElement .= $nbrElement === 1 ? ' ' . $singulier . ' disponible' : ' ' . $pluriel . ' disponibles';
        elseif ($singulier == 'place')
            $nbrElement .= $nbrElement === 1 ? ' ' . $singulier . ' libre' : ' ' . $pluriel . ' libres';
        return "$nbrElement";
    }
}
