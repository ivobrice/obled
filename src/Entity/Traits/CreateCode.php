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
        $this->codeUser = uniqid();
        $this->codeUser = substr($this->codeUser, 7);
        $this->hashedCode = password_hash($this->codeUser, PASSWORD_DEFAULT);
        $this->hashedCode = substr($this->hashedCode, 7);
        $this->hashedCode2 = null;
        $pos = 0;
        $chart = '/';
        $tabpt = array();
        $tabpos = array();
        do {
            $pos = strpos($this->hashedCode, $chart, $pos);
            if ($pos or $pos === 0) {
                if ($chart == '/')
                    $tabpos[] = $pos . 's';
                else
                    $tabpt[] = $pos . 'p';
                $pos++;
            } elseif ($chart == '/') {
                $pos = 0;
                $chart = '.';
                $pos = strpos($this->hashedCode, $chart, $pos);
                if ($pos or $pos === 0) {
                    $tabpt[] = $pos . 'p';
                    $pos++;
                }
            }
        } while ($pos);
        if (!empty($tabpos) or !empty($tabpt)) {
            if (!empty($tabpos)) {
                $this->hashedCode = str_replace('/', '', $this->hashedCode);
                $this->hashedCode2 = implode('', $tabpos);
                unset($tabpos);
            }
            if (!empty($tabpt)) {
                $this->hashedCode = str_replace('.', '', $this->hashedCode);
                $this->hashedCode2 .= implode('', $tabpt);
                unset($tabpt);
            }
        } else
            $this->hashedCode2 = strlen($this->hashedCode) . 'd';
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
