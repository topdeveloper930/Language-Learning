<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationLevel extends Model
{
    public $timestamps = false;

	protected $table = 'evaluationLevels';

	protected $primaryKey = 'evaluationLevelsID';
}
