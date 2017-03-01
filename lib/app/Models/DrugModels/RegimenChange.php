<?php

namespace App\Models\DrugModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegimenChange extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_regimen_change';
    protected $fillable = ['last_regimen_id', 'change_reason_id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'change_reason', 'regimen'];
    protected $dates = ['deleted_at'];
    protected $appends = array('change_reason_name', 'last_regimen_name');

    public function change_reason(){
        return $this->belongsto('App\Models\ListsModels\ChangeReason');
    }

    public function regimen(){
        return $this->belongsTo('App\Models\DrugModels\Regimen');
    }

    public function getChangeReasonNameAttribute(){
        $change_reason_name = null;
        if($this->change_reason){ $change_reason_name = $this->change_reason->name; }
        return $change_reason_name;
    }

    public function getLastRegimenNameAttribute(){
        $last_regimen_name = null;
        if($this->regimen){ $last_regimen_name = $this->regimen->name; }
        return $last_regimen_name;
    }

    
}