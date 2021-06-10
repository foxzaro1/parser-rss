<?

namespace App\Classes\Base;

class DateDiff
{

    private $dates = [];

    public function addDate(\DateTime $date)
    {
        $this->dates[] = $date;
    }

    public function getCommonDiff($dates = false, $format = "%H:%I:%S")
    {
        if ($dates === false) {
            $dates = $this->dates;
        }
        $arrFormattedDates = [];
        $sorted  = $this->compareDate($dates);
        foreach ($sorted as $key => $value) {
            if ($sorted[$key + 1]) {
                $interval = date_diff($value, $sorted[$key + 1]);
                $arrFormattedDates[] = $interval->format("%H:%I:%S");
            }
        }

        $average = $this->averageSeconds($arrFormattedDates);

        return $this->secondsToTime($average, $format);
    }

    private function averageSeconds($dates)
    {

        $seconds = 0;
        foreach ($dates as $date) {
            $exp = explode(':', strval($date));
            $seconds += $exp[0] * 60 * 60 + $exp[1] * 60 + $exp[2];
        }

        return round($seconds / count($dates));
    }
    private function secondsToTime($seconds, $format = "%H:%I:%S")
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format($format);
    }

    public function compareDate($dates)
    {
        usort($dates, function ($a, $b) {
            return $a <=> $b;
        });
        return $dates;
    }
}
