<?php

namespace App\Enums;

enum EventApprovalStatus: string
{
  case DRAFT = 'draft';
  case APPROVED = 'approved';
  case REJECTED = 'rejected';
}
