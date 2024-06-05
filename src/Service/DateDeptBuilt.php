<?php

namespace App\Service;

class DateDeptBuilt
{
    /**
     * @param \Datetime $dateDept
     * @return String
     */
    public function BuiltDateDept($dateDept)
    {
        $currentDate = new \Datetime();
        $interval = $dateDept->diff($currentDate);
        $sem = [
            'Mon.' => 'Lun.', 'Tue.' => 'Mar.', 'Wed.' => 'Mer.', 'Thu.' => 'Jeu.', 'Fri.' => 'Ven.', 'Sat.' => 'Sam.', 'Sun.' => 'Dim.',
            'Jan.' => 'Jan.', 'Feb.' => 'Fév.', 'Mar.' => 'Mar.', 'Apr.' => 'Avr.', 'May.' => 'Mai.', 'Jun.' => 'Juin.', 'Jul.' => 'Juil.',
            'Aug.' => 'Août.', 'Sep.' => 'Sep.', 'Oct.' => 'Oct.', 'Nov.' => 'Nov.', 'Dec.' => 'Déc.'
        ];
        if ($interval->format('%a') == 0 or $interval->format('%a') == 1) {
            if ($interval->format('%a') == 0) {
                if ($currentDate->format('Y-m-d') == $dateDept->format('Y-m-d')) {
                    if ($interval->invert)
                        $jourDeptTrajet = 'Aujourd\'hui ';
                    else
                        $jourDeptTrajet = '(Date dépassée) aujourd\'hui ';
                } elseif ($interval->invert)
                    $jourDeptTrajet = 'Demain ';
                else
                    $jourDeptTrajet = '(Date dépassée) hier ';
            } else {
                $i = $j = 0;
                $Aujourd = $currentDate->format('D.');
                $jourDeptTrajet = $dateDept->format('D.');
                foreach ($sem as $day => $jourSem) {
                    if ($day == $jourDeptTrajet) {
                        foreach ($sem as $days => $values) {
                            if ($days == $Aujourd) {
                                if ($j + 1 == $i or ($j == 6 && $i == 0))
                                    $jourDeptTrajet = 'Demain ';
                                elseif ($j - 1 == $i or ($j == 0 && $i == 6))
                                    $jourDeptTrajet = '(Date dépassée) hier ';
                                else
                                    $jourDeptTrajet = $interval->invert ? '' : '(Date dépassée) ';
                                break;
                            }
                            $j++;
                        }
                        break;
                    }
                    $i++;
                }
            }
            $dateDept = $jourDeptTrajet . $dateDept->format('D. d M. Y à H:i');
        } else {
            if ($interval->invert)
                $dateDept = $dateDept->format('D. d M. Y à H:i');
            else
                $dateDept = '(Date dépassée) ' . $dateDept->format('D. d M. Y à H:i');
        }
        $local = 'fr';
        if ($local == 'fr') {
            $dateDept = explode(' ', $dateDept);
            foreach ($dateDept as $key => $value) {
                if (!is_numeric($value))
                    if (array_key_exists($value, $sem))
                        $dateDept[$key] = $sem[$value];
            }
            $dateDept = implode(' ', $dateDept);
        }
        return $dateDept;
    }
}
