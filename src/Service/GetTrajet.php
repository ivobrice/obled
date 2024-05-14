<?php

namespace App\Service;

use App\Entity\Trajet;
use App\Service\BuildHashedCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class GetTrajet
{
    private $em;
    private $buildCode;

    public function __construct(EntityManagerInterface $em, BuildHashedCode $buildCode)
    {
        $this->em = $em;
        $this->buildCode = $buildCode;
    }

    public function execute(Request $request): Trajet
    {
        if ($request->request->get('email')) {
            if ($trajet = $this->em->getRepository(Trajet::class)->findOneBy(['email' => $request->request->get('email')])) {
                $codeUser = (strlen($request->request->get('codeTrajet') > 6)) ? $trajet->getCodeUser() : $request->request->get('codeTrajet');
                $hashedCode = (strlen($request->request->get('codeTrajet') > 6)) ? $request->request->get('codeTrajet') : $trajet->getHashedCode();
                $hashedCodeOrigin = $this->buildCode->buildHashedCodeOrigin($hashedCode, $trajet->getHashedCode2());
                if (password_verify($codeUser, $hashedCodeOrigin)) {
                    if (empty($request->request->all('trajet'))) {
                        $villeDept = ($trajet->paysDept()) ? $trajet->getVilleDept() . ', ' . $trajet->getPaysDept() : $trajet->getVilleDept();
                        $villeArrv = ($trajet->paysArrv()) ? $trajet->getVilleArrv() . ', ' . $trajet->getPaysArrv() : $trajet->getVilleArrv();
                        $trajet->setVilleDept($villeDept);
                        $trajet->setVilleArrv($villeArrv);
                        $trajet->setDateDept($trajet->getDateDept()->format('d/m/Y'));
                        $trajet->setHeureDept($trajet->getDateDept()->format('H'));
                        $trajet->setMinuteDept($trajet->getDateDept()->format('i'));
                        if ($trajet->getAnneeNaiss())
                            $trajet->setAnneeNaiss($trajet->getAnneeNaiss()->format('Y'));
                    }
                    return $trajet;
                } else
                    return 'Pas de trajet trouvé avec ce code.';
            } else
                return 'Pas de trajet trouvé avec cet email.';
        }
        return 'Pas de trajet trouvé avec cet email.';
    }
}
