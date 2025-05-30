<?php

namespace App\Enums;


enum PostType : string
{
    case Announcement = 'jop_request';
    case JobCreation = 'job_creation';
}
