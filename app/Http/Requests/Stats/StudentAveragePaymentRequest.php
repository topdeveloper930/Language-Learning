<?php

namespace App\Http\Requests\Stats;


use App\Http\Requests\StatsRequest;

/**
 * Class is used in test only (Feature\StatsRequestTest).
 * Seems not needed after expanding the TemplateController on ajax responses.
 *
 * Class StudentAveragePaymentRequest
 * @package App\Http\Requests\Stats
 */
class StudentAveragePaymentRequest extends StatsRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
