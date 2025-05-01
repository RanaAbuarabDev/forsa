<?php

namespace App\Enums;


enum PostType : string
{
    case Announcement = 'announcement';
    case JobCreation = 'job_creation';
}
