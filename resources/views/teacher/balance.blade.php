@extends('layout.zedalabs.teacher')

@php
    $tplConfig->page_meta_title = trans('teacher_balance.balance_sheets');
    $currentMonth = trans('calendar_common.months')[date('n') - 1];
    $currentYear = date('Y');
    $startYear = (int) substr( $user->createDate, 0, 4 );
    $startMonth = substr( $user->createDate, 5, 2 ) - 1;
    $commentDisabled = (int) (intval(date('d')) > config('main.teacher_can_comment_salary_till'));

    $salaryComment = $user->salaryComments()->where([
    	['year', date('Y', strtotime('-1 month'))],
    	['month', date('F', strtotime('-1 month'))]
    ])->pluck('comments')->pop(0);
@endphp

@section('head')
    @parent

    <script>
        var teacherSalary = {!! $user->salary->toJson() !!},
            teacherStart = {year: {{ $startYear}}, month: "{{ $startMonth }}"},
            currentDate = {year: {{ $currentYear}}, month: "{{ $currentMonth }}"};
    </script>
@endsection

@section('main')
    <main>

        <div id="root" class="container" v-cloak>

            <div class="columns">

                <div class="column col-3 hide-lg sticky-sidebar">

                    <header class="dashboard-header mb-xs">
                        <h1 class="dashboard-title">@lang('teacher_balance.balance_sheets')</h1>
                    </header>

                    <div class="nav-menu">
                        <a href="#" class="mb-sm" @click.prevent="setYearMonth({year: '', month: ''})">@lang('teacher_balance.show_all_time')</a>

                        <div class="form-group">
                            <select v-model="sYear">
                                @for($i = $currentYear; $i >= $startYear; $i--)
                                    <option>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <select v-model="sMonth">
                                <option value="">{{ __('teacher_balance.all') }}</option>
                                <optgroup label="{{ __('teacher_balance.select_month') }}">
                                    <option v-for="(lbl, indx) in sh.__('months')"
                                            :disabled="({{ $currentYear }} === sYear && indx > {{ date('n') - 1 }}) || (sYear === {{ $startYear }} && indx < {{ $startMonth }})"
                                    >@{{ lbl }}</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="column col-9 col-12-lg">

                    <div class="columns stats mb-md">
                        <div class="column col-4 col-12-sm">
                            <div class="stat">
                                <span class="stat-label">@lang('teacher_balance.hours_taught')</span>
                                <span class="stat-figure" v-text="hoursTaught"></span>
                            </div>
                        </div>
                        <div class="column col-4 col-12-sm">
                            <div class="stat">
                                <span class="stat-label">@lang('teacher_balance.students_taught')</span>
                                <span class="stat-figure" v-text="studentsTaught"></span>
                            </div>
                        </div>
                        <div class="column col-4 col-12-sm">
                            <div class="stat">
                                <span class="stat-label">@lang('teacher_balance.income')</span>
                                <span class="stat-figure">$@{{ income }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="shorten mb-md" data-length="150" v-if="isCurrentMonthSelected">@lang('teacher_balance.current_month_pay', ['month' => $currentMonth, 'year' => $currentYear, 'next_month' => trans('calendar_common.months')[date('n', strtotime('+1 month')) - 1]])</p>
                    <p class="shorten mb-md" data-length="150" v-else>@lang('teacher_balance.past_records')</p>

                    <vdtnet-table :id="table_ref"
                                  :ref="table_ref"
                                  :opts="opts"
                                  :fields="fields"
                                  class-name="table mb-lg"
                    ></vdtnet-table>

                    <div class="mb-sm" v-if="showAdminChange">
                        <button type="button" :class="{button:true, tooltip: true, caution: (admin_change.totalPay < 0), primary: (admin_change.totalPay > 0)}" :data-tooltip="admin_change.adminNotes">
                            @lang('teacher_balance.admin_change') <span v-text="admin_change.totalPay"></span>
                        </button>
                    </div>

                    <table class="table mb-sm">
                        <thead>
                        <tr>
                            <th>@lang('teacher_balance.total_for_month')</th>
                            <th>SD</th>
                            <th>G2</th>
                            <th>G3</th>
                            <th>S1</th>
                            <th>S2</th>
                            <th>S3</th>
                            <th>@lang('teacher_balance.sum')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width:150px;"><b>@lang('teacher_balance.hours')</b></td>
                            <td v-for="v in summaryTblHours" v-text="v"></td>
                            <th v-text="hoursTaught"></th>
                        </tr>
                        <tr>
                            <td style="width:150px;"><b>@lang('teacher_balance.pay')</b></td>
                            <td v-for="v in summaryTblSum">$@{{ v.toFixed(2) }}</td>
                            <td><b>$@{{ income }}</b></td>
                        </tr>
                        </tbody>
                    </table>

                    <form action="" id="saveComments" type="post" novalidate="novalidate">
                        <div class="form-group ruled">
                            <label for="salaryComments"><strong>@lang('teacher_balance.notes')</strong></label>
                            <p class="field-description">@lang('teacher_balance.note_description')</p>
                            <textarea maxlength="5000"
                                      rows="5"
                                      name="salaryComments"
                                      id="salaryComments"
                                      :disabled="{{ $commentDisabled }} || !isCurrentMonthSelected"
                            >{{ $salaryComment }}</textarea>
                        </div>
                        <div class="form-group" v-if="!{{ $commentDisabled }} && isCurrentMonthSelected">
                            <button type="submit" class="button primary" @click.prevent="submitComment"><i v-if="spin" class="fal fa-spinner fa-spin"></i> @lang('teacher_balance.save_comments')</button>
                        </div>
                    </form>

                    <hr>

                    <p>
                        @foreach( trans('teacher_balance.agenda') as $key => $note )
                        <strong>{{ $key }}</strong> {!! str_replace( ':terms_url', route('teachers', [ 'controller' => 'terms-conditions' ] ), $note )  !!}<br>
                        @endforeach
                    </p>

                    <p><small>@lang('teacher_balance.notice')</small></p>

                </div>

            </div> <!--div.columns-->

        </div> <!--div#container-->

    </main>

@endsection