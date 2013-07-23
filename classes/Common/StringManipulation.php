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
            $hours = $nbMinutes / 60;
            $minutes = $nbMinutes % 60;
            while (strlen($hours) < 2) $hours = "0" . $hours;
            while (strlen($minutes) < 2) $minutes = "0" . $minutes;

            return $hours . ":" . $minutes;
        }
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
