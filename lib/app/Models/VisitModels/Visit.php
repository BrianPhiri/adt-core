<?php

namespace App\Models\VisitModels;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $table = 'tbl_visit';
    protected $fillable = ['current_height', 'current_weight', 'visit_date', 'appointment_adherence', 'facility_id', 'user_id', 'purpose_id', 'last_regimen_id', 'current_regimen_id', 'change_reason_id', 'non_adherence_reason_id', 'appointment_id'];
}