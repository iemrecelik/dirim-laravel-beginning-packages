<?php
return function($params){

extract($params);

return '<?php

namespace App\ModelsRepository'.$modelPrefix.';

use Dirim\BeginningPackage\ModelsRepository\GlobalRepository;

trait '.$modelName.'Repository
{
    use GlobalRepository;
    
    //Repository content...
}
';
};
