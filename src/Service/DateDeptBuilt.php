<?php

namespace App\Service;

class DateDeptBuilt
{
    private $currentDate;
    private $format_jour;

    public function __construct()
    {
        $this->currentDate = new \Datetime();
        if (setlocale(LC_TIME, 'fr_FR') == '') {
            setlocale(LC_TIME, 'FRA');  //correction problème pour windows
            $this->format_jour = '%#d';
        }else
            $this->format_jour = '%e';
    }

	/**
     *
     * @param \Datetime $dateDept
     * @return String
     */
  	public function BuiltDateDept($dateDept)
  	{
    	$interval = $dateDept->diff($this->currentDate);
        if ($interval->format('%a') == 0 or $interval->format('%a') == 1) {
            if ($interval->format('%a') == 0) {
                if ($this->currentDate->format('Y-m-d') == $dateDept->format('Y-m-d')) {
                    if ($interval->invert)
                        $jourDeptAnnonce = 'aujourd\'hui';
                    else
                        $jourDeptAnnonce = '(Date dépassée) aujourd\'hui';
                }elseif ($interval->invert)
                    $jourDeptAnnonce = 'demain';
                else
                    $jourDeptAnnonce = "(Hier) %a $this->format_jour %h";
            }else {
                $Aujourd = strftime("%A", strtotime($this->currentDate->format('Y-m-d')));
                $jourDeptAnnonce = strftime("%A", strtotime($dateDept->format('Y-m-d')));
                $sem = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
                foreach ($sem as $key => $jourSem) {
                    if ($jourSem == $jourDeptAnnonce) {
                        foreach ($sem as $keys => $values) {
                            if ($values == $Aujourd) {
                                if ($keys + 1 == $key or ($keys == 6 && $key == 0)) 
                                    $jourDeptAnnonce = 'demain';
                                elseif ($keys - 1 == $key or ($keys == 0 && $key == 6))
                                    $jourDeptAnnonce = "(Hier) %a $this->format_jour %h";
                                else
                                    $jourDeptAnnonce = $interval->invert? "%a $this->format_jour %h" : "(Date dépassée) %a $this->format_jour %h";
                                break;
                            }
                        }
                        break;
                    }
                }
            }
            $dateDept = strftime("$jourDeptAnnonce à %Hh:%M", strtotime($dateDept->format('Y-m-d H:i')));
        }else {
            if ($interval->invert)
                $dateDept = strftime("%a $this->format_jour %h à %Hh:%M", strtotime($dateDept->format('Y-m-d H:i')));
            else
                $dateDept = strftime("(Date dépassée) %a $this->format_jour %h à %Hh:%M", strtotime($dateDept->format('Y-m-d H:i')));
        }
        $dateDept = explode(' ', $dateDept);
        foreach ($dateDept as $key => $value) {
            if (strpos($value, 'd', 0) === 0) {
                if (strpos($value, 'dép', 0) === 0)
                    continue;
                elseif (strpos($value, 'di', 0) === 0)
                    continue;
                elseif (strpos($value, 'de', 0) === 0)
                    continue;
                else {
                    $dateDept[$key] = 'déc.';
                    break;
                }
            }elseif (strpos($value, 'ao', 0) === 0) {
                $dateDept[$key] = 'août';
                break;
            }
        }
        $dateDept = implode(' ', $dateDept);
        return $dateDept;
  	}
}