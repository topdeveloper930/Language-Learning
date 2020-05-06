<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Evaluation (Progress Reports)
    |--------------------------------------------------------------------------
    |
    | This option controls the default evaluation levels and whatever refers to
    | the progress reports.
    |
    */

    /* Min value of total score for the level */
    'levels' => [
    	'beginner'     => 0,
	    'intermediate' => 15,
	    'advanced'     => 30,
	    'superior'     => 35
    ],
	'step'  => 20, // Number of hours logged for regular evaluation
];