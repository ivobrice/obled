<?php
namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CreateCode
{
    #[ORM\Column(nullable: true)]
    private ?bool $publish = null;
    
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $codeUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hashedCode = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $hashedCode2 = null;

  	public function traitCreateCodeUser()
    {
        $codeUser = uniqid();
        $codeUser = substr($codeUser, 7);
        $hashedCodeUser = password_hash($codeUser, PASSWORD_DEFAULT);
        $hashedCodeUser = substr($hashedCodeUser, 7);
        $hashedCodeUser2 = null;
        $pos = 0; $chart = '/'; $tabpt = array(); $tabpos = array(); $tabCode = array();
        do {
            $pos = strpos($hashedCodeUser, $chart, $pos);
            if ($pos or $pos === 0) {
                if ($chart == '/')
                $tabpos[] = $pos.'s';
            else
                $tabpt[] = $pos.'p';
            $pos ++;
            }elseif ($chart == '/') {
                $pos = 0; $chart = '.';
                $pos = strpos($hashedCodeUser, $chart, $pos);
                if ($pos or $pos === 0) {
                    $tabpt[] = $pos.'p';
                    $pos ++;
                }
            }
        } while ($pos);
        if (!empty($tabpos) or !empty($tabpt)) {
            if (!empty($tabpos)) {
                $hashedCodeUser = str_replace('/', '', $hashedCodeUser);
                $hashedCodeUser2 = implode('', $tabpos);
                unset($tabpos);
            }
            if (!empty($tabpt)) {
                $hashedCodeUser = str_replace('.', '', $hashedCodeUser);
                $hashedCodeUser2 .= implode('', $tabpt);
                unset($tabpt);
            }
        }else
            $hashedCodeUser2 = strlen($hashedCodeUser).'d';
        $this->codeUser = $codeUser;
        $this->hashedCode = $hashedCodeUser;
        $this->hashedCode2 = $hashedCodeUser2;
    }

    public function getCodeUser(): ?string
    {
        return $this->codeUser;
    }

    public function getHashedCode(): ?string
    {
        return $this->hashedCode;
    }

    public function getHashedCode2(): ?string
    {
        return $this->hashedCode2;
    }

    public function isPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(?bool $publish): static
    {
        $this->publish = $publish;

        return $this;
    }
}