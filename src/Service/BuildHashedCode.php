<?php

namespace App\Service;

class BuildHashedCode
{
    /**
     * Reconstruction du hashedCode Original
     *
     * @param string $hashedCode
     * @param string $hashedCode2
     * @return string
     */
    public function buildHashedCodeOrigin($hashedCode, $hashedCode2)
    {
        $hashedCode = htmlspecialchars($hashedCode);
        if ((strpos($hashedCode2, 's', 0)) or (strpos($hashedCode2, 'p', 0))) {
            $okp = FALSE;
            $tabcfs = explode('s', $hashedCode2);
            foreach ($tabcfs as $key => $value) {
                if (strpos($value, 'p', 0)) {
                    $okp = TRUE;
                    $tabcfp = explode('p', $value);
                    unset($tabcfs[$key]);
                    $lenTabcfp = count($tabcfp) - 1;
                    unset($tabcfp[$lenTabcfp]);
                }
            }
            if (!$okp) {
                $lenTabcfs = count($tabcfs) - 1;
                $tabcfp = array();
                unset($tabcfs[$lenTabcfs]);
            }
            $lenHashedCode = strlen($hashedCode) + count($tabcfs) + count($tabcfp) - 1;
            $hashedCodeOrigin = range(0, $lenHashedCode);
            foreach ($hashedCodeOrigin as $key => $value)
                $hashedCodeOrigin[$key] = 0;
            if (isset($tabcfs) && !empty($tabcfs)) {
                foreach ($tabcfs as $value)
                    $hashedCodeOrigin[$value] = '/';
            }
            if (isset($tabcfp) && !empty($tabcfp)) {
                foreach ($tabcfp as $value)
                    $hashedCodeOrigin[$value] = '.';
            }
            $hashedCode = str_split($hashedCode);
            $i = 0;
            foreach ($hashedCode as $value) {
                if ($hashedCodeOrigin[$i] === 0)
                    $hashedCodeOrigin[$i] = $value;
                else {
                    while ($hashedCodeOrigin[$i] !== 0)
                        $i++;
                    $hashedCodeOrigin[$i] = $value;
                }
                $i++;
            }
            $hashedCode = implode('', $hashedCodeOrigin);
        }
        $hashedCodeOrigin = '$2y$10$' . $hashedCode;
        return $hashedCodeOrigin;
    }
}
