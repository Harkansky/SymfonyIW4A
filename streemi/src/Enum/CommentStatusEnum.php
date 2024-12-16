<?php

namespace App\Enum;

enum CommentStatusEnum : string

{
    public const VALIDATED = 'validated';
    case ACCEPTED = 'accepted';
    case PENDING = 'pending';
    case REJECTED = 'rejected';

}

