<?php

namespace App;

enum CriterionType:string
{
    case Product = 'product';
    case Metrology = 'metrology';
    case Certificate = 'certificate';
    case Service = 'service';

}
