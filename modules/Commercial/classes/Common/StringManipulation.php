<?php
namespace Igestis\Modules\Commercial\Common;

/**
 * Description of StringManipulation
 *
 * @author Gilles Hemmerlé <giloux@gmail.com>
 */
class StringManipulation {
    /**
     * Convert X minutes to string format 
     * Ex: 130 will return 02:10
     * @param type $nbMinutes Number of minutes (example : 120 for 2 hours)
     * @return string Time with format HH:II
     */
    public static function convertDecimalToTimeFormat($nbMinutes) {
        if($nbMinutes == 0) return "00:00";
        else {
            $hours = (int)($nbMinutes / 60);
            $minutes = abs($nbMinutes % 60);
            while (strlen($hours) < 2) $hours = "0" . $hours;
            while (strlen($minutes) < 2) $minutes = "0" . $minutes;

            return $hours . ":" . $minutes;
        }
    }

    /**
     * Return the number of minutes from the HH:ii format
     * @param  string $hourFormat Hour with format HH:ii
     * @return int                Number of minutes
     */
    public static function convertTimeToDecimalFormat($hourFormat) {
        if ($hourFormat != (string)(int)$hourFormat) {
            if (preg_match("/^-?[0-9]+\:[0-9]+(\:[0-9]+)?$/", $hourFormat)) {
                $aTime = explode(":", $hourFormat);
                $hourFormat = $aTime[0] * 60 + $aTime[1];
            }
        }
        
        return $hourFormat;
    }
    
    /**
     * Convert a _get string to a associated array
     * @param string $query well formed html _GET query string
     * @return array associated array
     */
    public static function convertUrlQuery($query) { 
        $queryParts = explode('&', $query); 
        
        $params = array(); 
        foreach ($queryParts as $param) { 
            $item = explode('=', $param); 
            $key = urldecode($item[0]);
            $value = urldecode($item[1]);
            if(preg_match("/\[\]$/", $key)) {
                $key = substr($key, 0, strlen($key) - 2);
                $params[$key][] = $value;   
            }
            else {
                $params[$key] = $value;
            }
            
        } 
        
        return $params; 
    } 
}
