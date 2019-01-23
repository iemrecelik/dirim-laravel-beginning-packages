<?php
return function(){

return '
    private function convertToCrop($crop)
    {
        $cropFilt = [];

        foreach ($crop as $key => $val) {
            if (isset($val)) {
                $cropFilt[$key][\'crop\'] = explode(\'*?*\', $val);
            }
        }
        
        return $cropFilt;
    }
';
};