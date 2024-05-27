<?php

namespace App\Service;

use App\Entity\Trajet;
use App\Service\BuildHashedCode;
use Doctrine\ORM\EntityManagerInterface;

class GetTrajet
{
    private $buildCode;

    public function __construct(BuildHashedCode $buildCode)
    {
        $this->buildCode = $buildCode;
    }

    public function execute($post, EntityManagerInterface $em)
    {
        if ((count(array_keys($post)) < 5 && !empty($post['email']) && !empty($post['codeUser'])) or (count($post) > 5 && !empty($post['hashedCode']))) {
            if (!empty($post['hashedCode'])) {
                $info = 'hashedCode';
                $val = $post['hashedCode'];
            } else {
                $info = 'email';
                $val = $post['email'];
            }
            if ($trajet = $em->getRepository(Trajet::class)->findOneBy([$info => $val])) {
                $codeUser = (!empty($post['codeUser'])) ? $post['codeUser'] : $trajet->getCodeUser();
                $hashedCode = (!empty($post['hashedCode'])) ? $post['hashedCode'] : $trajet->getHashedCode();
                $hashedCodeOrigin = $this->buildCode->buildHashedCodeOrigin($hashedCode, $trajet->getHashedCode2());
                if (password_verify($codeUser, $hashedCodeOrigin)) {
                    $returnTrajet = true;
                    if (count(array_keys($post)) < 5 && !empty($post['codeUser']) && $post['email']) {
                        $villeDept = ($trajet->getPaysDept()) ? $trajet->getVilleDept() . ', ' . $trajet->getPaysDept() : $trajet->getVilleDept();
                        $villeArrv = ($trajet->getPaysArrv()) ? $trajet->getVilleArrv() . ', ' . $trajet->getPaysArrv() : $trajet->getVilleArrv();
                        $trajet->setVilleDept($villeDept);
                        $trajet->setVilleArrv($villeArrv);
                    }
                    if (!empty($post['hashedCode']) && !empty($post['id'])) {
                        if ($trajet->getId() != $post['id'])
                            $returnTrajet = false;
                    }
                    if ($returnTrajet)
                        return $trajet;
                }
                return 'Pas de trajet trouvé.';
            }
            return 'Pas de trajet trouvé.';
        }
        return 'Pas de trajet trouvé';
    }
}
