@extends('admin.legacy.stats')

@section('center_col')
    <div id="app"
         data-languages="{{ json_encode( $language_list ) }}"
    >
        <input type="hidden" id="translation" value="{{ json_encode( __( $translation ) ) }}">

        <div class="row">
            <div class="control-group span">
                <label class="control-label" for="year">@lang( $translation . '.select_year' )</label>
                <div class="controls">
                    <select id="year" name="year" v-model="year" @change="reload">
                        <option v-for="y in [{{ implode(', ', array_reverse( range( 2014, date('Y') ) )) }}]" :value="y" :selected="y == year">@{{ y }}</option>
                    </select>
                </div>
            </div>
            <div class="control-group span">
                <label class="control-label" for="year">@lang( $translation . '.select_language' )</label>
                <div class="controls">
                    <select id="language" name="language" v-model="language" @change="reload">
                        <option v-for="l in [''].concat(language_list)" :value="l" :selected="l == language">@{{ l ? l : lang.all_languages }}</option>
                    </select>
                </div>
            </div>
            <div class="control-group span">
                <label class="control-label" for="country">@lang( $translation . '.country' )</label>
                <div class="controls">
                    <input id="country" type="text" v-model="country" @keyup.enter="reload" @focusout="reload">
                </div>
            </div>
            <div class="control-group span">
                <label class="control-label" for="age">@lang( $translation . '.age' )</label>
                <div class="controls">
                    <select id="age" name="age" v-model="age" @change="reload">
                        <option v-for="l in age_list" :value="l" :selected="l == age">@{{ l }}</option>
                    </select>
                </div>
            </div>
            <div class="control-group span">
                <label class="control-label" for="year">@lang( $translation . '.gender' )</label>
                <div class="controls">
                    <select id="gender" name="gender" v-model="gender" @change="reload">
                        <option v-for="(l, i) in gender_list" :value="i" :selected="i == gender">@{{ l }}</option>
                    </select>
                </div>
            </div>
        </div>

        <vdtnet-table
                :id="table_id"
                :opts="opts"
                :fields="fields"
                :hideFooter="hideFooter"
        ></vdtnet-table>

        <GChart
                type="ColumnChart"
                :data="chartData"
                :options="chartOptions"
                style="margin: 45px 0"
        />
    </div>

    <style >
        .controls select {
            max-width: 150px;
        }
    </style>
@endsection