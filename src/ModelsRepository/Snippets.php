<?php

namespace Dirim\BeginningPackage\ModelsRepository;

trait Snippets
{
    public function convertCryptID(String $data)
    {
        return crypt($data, 'axc10bstPwQds2');
    }
}
