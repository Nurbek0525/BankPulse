@extends('layouts.app')

@section('content')
            <div class="content-w">
                <div class="content-i">
                    <div class="content-box">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <div class="element-wrapper compact pt-4">
                                <h5 class="element-header">{{ trans('app.Top mainbanks in rating') }}</h5>
                                    <div class="element-box-tp">
                                        <div class="inline-profile-tiles">
                                            <div class="row">
                                                <div class="col-4 col-md-12 col-xl-12">
                                                    <div class="row">
                                                    @foreach ($top_mainbanks as $key => $item)
                                                        <div class="col-4 col-lg-4 col-xl-2">
                                                            <div class="profile-tile profile-tile-inlined"  {{-- style="border-radius: 6px; background-color: #fff;
                                                                box-shadow: 0px 2px 4px rgba(126, 142, 177, 0.12);
                                                                text-decoration: none;
                                                                height: 105px" --}}>
                                                                <a class="profile-tile-box" style="border-radius: 6px; background-color: #fff;
                                                                box-shadow: 0px 2px 4px rgba(126, 142, 177, 0.12);
                                                                text-decoration: none;
                                                                height: 105px" href="{!! url('/bank/list/view/'.$item->mainbank_id.'/main') !!}">
                                                                    <div class="pt-avatar-w">
                                                                        <img 
                                                                         alt="" src="{{ (!empty($item->logo))?URL::asset('mainbanks/'.$item->logo):''}}" />
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>    
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="element-wrapper">
                                <div class="element-header">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-6"> <h5 style="margin-bottom: 0"> {{ trans('app.activity banks') }} </h5></div>
                                        <div class="col-md-6 col-xl-6" style="text-align: right;"> <span class="bar-chart-label"> {{ "01.".($date_last_balance->month+1).".".$date_last_balance->year }} {{ trans('app.last balance information') }} </span>
                                        </div>
                                    </div>
                                </div>
                                    <div class="element-content">
                                        <div class="row">
                                            <div class="col-sm-4 col-xl-2">
                                                @php
                                                    $whole_active = number_formatting($whole_active);
                                                @endphp
                                                <a class="element-box el-tablo centered trend-in-corner smaller" href="javascript:void(0)">
                                                    <div class="label">{{ trans('app.whole active') }}</div>
                                                    <div class="value">{{ number_digiting($whole_active->number) }}  <span class="short-sum">{{ $whole_active->text }} UZS</span></div>
                                                    <div class="trending trending-{{($active_percent < 0)?'down':'up'}}">
                                                        <span>{{ number_format($active_percent, 1) }}% </span>
                                                        <i class="os-icon os-icon-arrow-{{($active_percent < 0)?'down':'up2'}}"></i>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 col-xl-2">
                                                @php
                                                    $whole_likvid_a = number_formatting($whole_likvid_a);
                                                @endphp
                                                <a class="element-box el-tablo centered trend-in-corner smaller" href="javascript:void(0)">
                                                    <div class="label">{{ trans('app.whole likvid assets') }} </div>
                                                    <div class="value">{{ number_digiting($whole_likvid_a->number) }} <span class="short-sum">{{ $whole_likvid_a->text }} UZS</span></div>
                                                    <div class="trending trending-{{($likvid_a_percent < 0)?'down':'up'}}">
                                                        <span>{{ number_format($likvid_a_percent, 1) }}% </span>
                                                        <i class="os-icon os-icon-arrow-{{($likvid_a_percent < 0)?'down':'up2'}}"></i>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 col-xl-2">
                                                @php
                                                    $whole_credits = number_formatting($whole_credits);
                                                @endphp
                                                <a class="element-box el-tablo centered  trend-in-corner smaller" href="javascript:void(0)">
                                                    <div class="label">{{ trans('app.whole loans') }} </div>
                                                    <div class="value">{{ number_digiting($whole_credits->number) }} <span class="short-sum">{{ $whole_credits->text }} UZS</span></div>
                                                    <div class="trending trending-{{($credit_percent < 0)?'down':'up'}}">
                                                        <span>{{ number_format($credit_percent, 1) }}% </span>
                                                        <i class="os-icon os-icon-arrow-{{($credit_percent < 0)?'down':'up2'}}"></i>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 col-xl-2">
                                                @php
                                                    $whole_problem_c = number_formatting($whole_problem_c);
                                                @endphp
                                                <a class="element-box el-tablo centered  centered trend-in-corner smaller" href="javascript:void(0)">
                                                    <div class="label">{{ trans('app.whole problem loans') }} </div>
                                                    <div class="value">{{ number_digiting($whole_problem_c->number) }} <span class="short-sum">{{ $whole_problem_c->text }} UZS</span></div>
                                                    <div class="trending trending-{{($problem_c_percent < 0)?'down':'up'}}">
                                                        <span>{{ number_format($problem_c_percent, 1) }}% </span>
                                                        <i class="os-icon os-icon-arrow-{{($problem_c_percent < 0)?'down':'up2'}}"></i>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 col-xl-2">
                                                @php
                                                    $whole_deposit = number_formatting($whole_deposit);
                                                @endphp
                                                <a class="element-box el-tablo centered trend-in-corner smaller" href="javascript:void(0)">
                                                    <div class="label">{{ trans('app.whole deposit') }} </div>
                                                    <div class="value">{{ number_digiting($whole_deposit->number) }} <span class="short-sum">{{ $whole_deposit->text }} UZS</span></div>
                                                    <div class="trending trending-{{($deposit_percent < 0)?'down':'up'}}">
                                                        <span>{{ number_format($deposit_percent, 1) }}% </span>
                                                        <i class="os-icon os-icon-arrow-{{($deposit_percent < 0)?'down':'up2'}}"></i>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4 col-xl-2">
                                                @php
                                                    $whole_people_d = number_formatting($whole_people_d);
                                                @endphp
                                                <a class="element-box el-tablo centered trend-in-corner smaller" href="javascript:void(0)">
                                                    <div class="label">{{ trans('app.whole people deposit') }} </div>
                                                    <div class="value">{{ number_digiting($whole_people_d->number) }} <span class="short-sum">{{ $whole_people_d->text }} UZS</span></div>
                                                    <div class="trending trending-{{($people_d_percent < 0)?'down':'up'}}">
                                                        <span>{{ number_format($people_d_percent, 1) }}% </span>
                                                        <i class="os-icon os-icon-arrow-{{($people_d_percent < 0)?'down':'up2'}}"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- start here Tab bar for Linear chart of Information of region  --}}
                        <div class="row">
                            <div class="col-sm-12 col-xl-12">
                                <div class="os-tabs-w">
                                    <div class="os-tabs-controls os-tabs-complex">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a aria-expanded="false" class="nav-link active" data-toggle="tab" href="#tab_credits_actives" url="credits_actives">
                                                <span class="tab-label">{{ trans('app.monthly credit and bank actives title short') }}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a aria-expanded="false" class="nav-link" data-toggle="tab" href="#tab_passives_deposit" url="passives_deposit">
                                                    <span class="tab-label">{{ trans('app.monthly passive and deposit title short') }}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item d-none d-xl-block">
                                                <a aria-expanded="false" class="nav-link" data-toggle="tab" href="#tab_actives_likvids" url="actives_likvids">
                                                    <span class="tab-label">{{ trans('app.monthly actives and likvid actives title short') }}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item d-none d-xl-block">
                                                <a aria-expanded="true" class="nav-link" data-toggle="tab" href="#tab_deposit_pdeposit" url="deposit_pdeposit">
                                                    <span class="tab-label">{{ trans('app.monthly deposit and people deposit title short') }}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item d-none d-xl-block">
                                                <a aria-expanded="true" class="nav-link" data-toggle="tab" href="#tab_credits_problem" url="credits_problem">
                                                    <span class="tab-label">{{ trans('app.monthly credit and problem credit title short') }}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item d-none d-xl-block">
                                                <a aria-expanded="true" class="nav-link" data-toggle="tab" href="#tab_income_expense" url="income_expense">
                                                    <span class="tab-label margin-top-dash-actives">{{ trans('app.monthly income and expense title short') }}</span>
                                                </a>
                                            </li>
                                            <li class="nav-item d-none d-xl-block">
                                                <a aria-expanded="true" class="nav-link" data-toggle="tab" href="#tab_kirim_chiqim" url="kirim_chiqim">
                                                    <span class="tab-label margin-top-dash-actives">{{ trans('app.monthly kirim and chiqim title short') }}</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="element-wrapper" >
                                        <div class="os-tabs-w">
                                            <div class="tab-content" style="align-content: center;">
                                                <div class="element-box tab-pane active" id="tab_credits_actives" style="padding-bottom: 3rem;">
                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ trans('app.monthly credit and bank actives title') }} ({{ trans('app.mlrd') }} UZS)</div>
                                                    <div id="credits_actives" class="chat-container container-h-400 padding-chart"></div>
                                                    <div class="credits_actives padding-chart-div">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                                <div class="element-box tab-pane" id="tab_passives_deposit" style="padding-bottom: 3rem;">
                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ trans('app.monthly passive and deposit title') }} ({{ trans('app.mlrd') }} UZS)</div>
                                                    <div id="passives_deposit" class="chat-container container-h-400 padding-chart"></div>
                                                    <div class="passives_deposit padding-chart-div">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                                <div class="element-box tab-pane" id="tab_actives_likvids" style="padding-bottom: 3rem;">
                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ trans('app.monthly actives and likvid actives title') }} ({{ trans('app.mlrd') }} UZS)</div>
                                                    <div id="actives_likvids" class="chat-container container-h-400 padding-chart"></div>
                                                    <div class="actives_likvids padding-chart-div">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                                <div class="element-box tab-pane" id="tab_deposit_pdeposit" style="padding-bottom: 3rem;">
                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ trans('app.monthly deposit and people deposit title') }} ({{ trans('app.mlrd') }} UZS)</div>
                                                    <div id="deposit_pdeposit" class="chat-container container-h-400 padding-chart"></div>
                                                    <div class="deposit_pdeposit padding-chart-div">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                                <div class="element-box tab-pane" id="tab_credits_problem" style="padding-bottom: 3rem;">
                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ trans('app.monthly credit and problem credit title') }} ({{ trans('app.mlrd') }} UZS)</div>
                                                    <div id="credits_problem" class="chat-container container-h-400 padding-chart"></div>
                                                    <div class="credits_problem padding-chart-div">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                                <div class="element-box tab-pane" id="tab_income_expense" style="padding-bottom: 3rem;">
                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ trans('app.monthly income and expense title') }} ({{ trans('app.mlrd') }} UZS)</div>
                                                    <div id="income_expense" class="chat-container container-h-400 padding-chart"></div>
                                                    <div class="income_expense padding-chart-div">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                                <div class="element-box tab-pane" id="tab_kirim_chiqim" style="padding-bottom: 3rem;">
                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ trans('app.monthly kirim and chiqim title') }} ({{ trans('app.mlrd') }} UZS)</div>
                                                    <div id="kirim_chiqim" class="chat-container container-h-400 padding-chart"></div>
                                                    <div class="kirim_chiqim padding-chart-div">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- </div> --}}
                                </div>
                            </div>
                        </div>
                        {{-- end here Tab bar for Linear chart of Information of region  --}}
                        {{-- start here Tab bar for Bar chart of Information of whole loans and whole problem loans in different way  --}}
                        <div class="row">
                            <div class="col-sm-12 col-xl-12">
                                {{-- <div class="element-wrapper"> --}}
                                {{-- <div class="element-header"> --}}
                                    <div class="row">
                                        <div class="col-md-8" style="padding: 0">
                                            <div class="os-tabs-w">
                                                <div class="os-tabs-controls os-tabs-complex">
                                                    <ul class="nav nav-tabs">
                                                        <li class="nav-item">
                                                            <a aria-expanded="false" class="nav-link active-title title-balance-portfell" style="font-weight: 600">
                                                                <span class="tab-label">{{ trans('app.title from loan portfolio analyse:') }}</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a aria-expanded="false" class="nav-link active" data-toggle="tab" href="#top_10_portfolio_fillials" url="top_10_fillials">
                                                                <span class="tab-label">{{ trans('app.Last information of top fillial in portfolio') }}</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a aria-expanded="false" class="nav-link" data-toggle="tab" href="#top_10_loan_species" url="top_10_loan_species">
                                                                <span class="tab-label">{{ trans('app.Last information of top loan species in portfolio') }}</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a aria-expanded="false" class="nav-link" data-toggle="tab" href="#top_10_activity_species" url="top_10_activity_species">
                                                                <span class="tab-label">{{ trans('app.Last information of top activity species in portfolio') }}</span>
                                                            </a>
                                                        </li>  
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="padding: 0">
                                             <div class="os-tabs-w">
                                                <div class="os-tabs-controls os-tabs-complex">
                                                    <div class="nav nav-tabs" style="margin-top: 13px; padding-left: 65%;" >
                                                        <span class="bar-chart-label">{{ $date_last_top_port }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {{-- </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xl-12">
                                <div class="element-wrapper">
                                        <div class="os-tabs-w">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="top_10_portfolio_fillials">
                                                    <div class="row">
                                                        <div class="col-xl-6">
                                                            <div class="element-wrapper">
                                                                <div class="element-box less-padding">
                                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ $top_safe_fillial_title }}</div>
                                                                    <div class="el-chart-w">
                                                                        <div id="top_safe_fillial" class="chat-container container-h-350"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6">
                                                            <div class="element-wrapper">
                                                                <div class="element-box less-padding">
                                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ $top_problem_fillial_title }}</div>
                                                                    <div class="el-chart-w">
                                                                        <div id="top_problem_fillial" class="chat-container container-h-350"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="top_10_loan_species">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xl-6">
                                                            <div class="element-wrapper">
                                                                <div class="element-box less-padding">
                                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ $top_safe_loan_species_title }}</div>
                                                                    <div class="el-chart-w">
                                                                        <div id="top_safe_goal" class="chat-container container-h-350"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-xl-6">
                                                            <div class="element-wrapper">
                                                                <div class="element-box less-padding">
                                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ $top_problem_loan_species_title }}</div>
                                                                    <div class="el-chart-w">
                                                                        <div id="top_problem_goal" class="chat-container container-h-350"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="top_10_activity_species">
                                                    <div class="row">
                                                        <div class="col-md-12 col-xl-6">
                                                            <div class="element-wrapper">
                                                                <div class="element-box less-padding">
                                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ $top_safe_activity_species_title }}
                                                                    </div>
                                                                    <div class="el-chart-w">
                                                                        <div id="top_safe_activity" class="chat-container container-h-350"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-xl-6">
                                                            <div class="element-wrapper">
                                                                <div class="element-box less-padding">
                                                                    <div class="form-header bar-chart-label" style="margin-bottom: -21px">{{ $top_problem_activity_species_title }}</div>
                                                                    <div class="el-chart-w">
                                                                        <div id="top_problem_activity" class="chat-container container-h-350"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        {{-- start here Tab bar for Bar chart of Information of whole loans and whole problem loans in different way  --}}
                        <div class="row">
                            {{-- <div class="d-none d-xxxl-block col-xxxl-2">
                                <div class="element-wrapper">
                                    <div class="element-box less-padding">
                                        <div class="el-chart-w"><canvas height="120" id="donutChart1" width="120"></canvas>
                                            <div class="inside-donut-chart-label"><strong>1,248 </strong><span>Visitors </span></div>
                                        </div>
                                        <div class="el-legend condensed">
                                            <div class="row">
                                                <div class="col-auto col-xxxxl-6 ml-sm-auto mr-sm-auto">
                                                    <div class="legend-value-w">
                                                        <div class="legend-pin legend-pin-squared" style="background-color: #6896f9;"></div>
                                                        <div class="legend-value"><span>Safari </span>
                                                            <div class="legend-sub-value">17%, 12 Visits </div>
                                                        </div>
                                                    </div>
                                                    <div class="legend-value-w">
                                                        <div class="legend-pin legend-pin-squared" style="background-color: #85c751;"></div>
                                                        <div class="legend-value"><span>Chrome </span>
                                                            <div class="legend-sub-value">22%, 763 Visits </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 d-none d-xxxxl-block">
                                                    <div class="legend-value-w">
                                                        <div class="legend-pin legend-pin-squared" style="background-color: #806ef9;"></div>
                                                        <div class="legend-value"><span>Firefox </span>
                                                            <div class="legend-sub-value">3%, 7 Visits </div>
                                                        </div>
                                                    </div>
                                                    <div class="legend-value-w">
                                                        <div class="legend-pin legend-pin-squared" style="background-color: #d97b70;"></div>
                                                        <div class="legend-value"><span>Explorer </span>
                                                            <div class="legend-sub-value">15%, 45 Visits </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-none d-xxxl-block col-xxxl-5">
                                <div class="element-wrapper">
                                    <div class="element-box less-padding">
                                        <div class="el-chart-w">
                                            <canvas height="120" id="donutChart1" width="120"></canvas>
                                            <div class="inside-donut-chart-label">
                                                <strong>1,248 </strong>
                                                <span>Visitors </span>
                                            </div>
                                        </div>
                                        <div class="el-legend condensed">
                                            <div class="row">
                                                <div class="col-auto col-xxxxl-6 ml-sm-auto mr-sm-auto">
                                                    <div class="legend-value-w">
                                                        <div class="legend-pin legend-pin-squared" style="background-color: #6896f9;"></div>
                                                        <div class="legend-value"><span>Safari </span>
                                                            <div class="legend-sub-value">17%, 12 Visits </div>
                                                        </div>
                                                    </div>
                                                    <div class="legend-value-w">
                                                        <div class="legend-pin legend-pin-squared" style="background-color: #85c751;"></div>
                                                        <div class="legend-value"><span>Chrome </span>
                                                            <div class="legend-sub-value">22%, 763 Visits </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 d-none d-xxxxl-block">
                                                    <div class="legend-value-w">
                                                        <div class="legend-pin legend-pin-squared" style="background-color: #806ef9;"></div>
                                                        <div class="legend-value"><span>Firefox </span>
                                                            <div class="legend-sub-value">3%, 7 Visits </div>
                                                        </div>
                                                    </div>
                                                    <div class="legend-value-w">
                                                        <div class="legend-pin legend-pin-squared" style="background-color: #d97b70;"></div>
                                                        <div class="legend-value"><span>Explorer </span>
                                                            <div class="legend-sub-value">15%, 45 Visits </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        
                        <!-- <div class="row">
                            
                            <div class="col-lg-6 col-xxl-6">
                                <div class="element-wrapper">
                                    <div class="element-box">
                                        <div class="element-actions">
                                            <div class="form-group">
                                                <select class="form-control form-control-sm">
                                                    <option selected="true" />Last 30 days
                                                    <option />This Week
                                                    <option />This Month
                                                    <option />Today 
                                                </select>
                                            </div>
                                        </div>
                                        <h5 class="element-box-header">Balance History </h5>
                                        <div class="el-chart-w"><canvas data-chart-data="13,28,19,24,43,49,40,35,42,46" height="90" id="liteLineChartV2" width="300"></canvas></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xxxl-6">
                                <div class="element-wrapper">
                                    <div class="element-box">
                                        <div class="os-tabs-w">
                                            <div class="os-tabs-controls">
                                                <ul class="nav nav-tabs smaller">
                                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab_overview">Overview </a></li>
                                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_sales">Sales </a></li>
                                                </ul>
                                                <ul class="nav nav-pills smaller d-none d-md-flex">
                                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#">Today </a></li>
                                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#">7 Days </a></li>
                                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#">14 Days </a></li>
                                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#">Last Month </a></li>
                                                </ul>
                                            </div>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_overview">
                                                    <div class="el-tablo bigger">
                                                        <div class="label">Unique Visitors </div>
                                                        <div class="value">12,537 </div>
                                                    </div>
                                                    <div class="el-chart-w"><canvas height="150px" id="lineChart" width="600px"></canvas></div>
                                                </div>
                                                <div class="tab-pane" id="tab_sales"></div>
                                                <div class="tab-pane" id="tab_conversion"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --> 
                        
                    </div>
                    <div class="content-panel compact color-scheme-dark">
                        <div class="element-wrapper element-wrapper-display">
                        </div>
                        <div class="content-panel-close"><i class="os-icon os-icon-close"></i></div>
                        <div class="element-wrapper">
                            <div class="element-actions actions-only"><a class="element-action element-action-fold" href="#"><i class="os-icon os-icon-minus-circle"></i>
                            </a></div>
                            <h6 class="element-header" style="padding-top: 12px">{{ trans('app.calculator') }} </h6>
                            <div class="element-box-tp">
                                <?php  $currencies = get_currency(date('d-m-Y')) ?>
                                <form action="javascript:void(0)">
                                    <div class="row">
                                        <div class="col-4" style="margin-bottom: 10px">
                                            <div class="form-group">
                                                <select name="from_calc" class="selectpicker form-control">
                                                    <option value="UZS">UZS</option>
                                                    @foreach($currencies as $currency)
                                                        <option {{$currency->Ccy == 'USD'?"selected":""}} value="{{ $currency->Rate }}">{{ $currency->Ccy }}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-1" style="margin-bottom: 10px">
                                            <div class="form-group conversion-icon">
                                                <span><i class="os-icon os-icon-repeat icon-separator"></i></span>
                                                
                                            </div>
                                        </div>
                                        <div class="col-7" style="margin-bottom: 10px">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input class="form-control-input" name="from_calc" placeholder="{{ trans('app.amount currency') }}" value="" />
                                                    <div class="input-group-append">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4" style="margin-bottom: 10px">
                                            <div class="form-group">
                                                <select name="to_calc" class="selectpicker form-control">
                                                    <option value="UZS" selected >UZS</option>
                                                    @foreach($currencies as $currency)
                                                        <option value="{{ $currency->Rate }}">{{ $currency->Ccy }}</option>
                                                    @endforeach     
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-1" style="margin-bottom: 10px">
                                            <div class="form-group conversion-icon">
                                                <span><i class="os-icon os-icon-repeat icon-separator"></i></span>
                                                
                                            </div>
                                        </div>
                                        <div class="col-7" style="margin-bottom: 10px">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input name="to_calc" class="form-control-input" value="" disabled />
                                                    <div class="input-group-append">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="element-wrapper compact">
                            <div class="element-actions actions-only">
                                <a class="element-action element-action-fold" href="#">
                                    <i class="os-icon os-icon-minus-circle"></i>
                                </a>
                            </div>
                            <h6 class="element-header">{{ trans('app.currency history') }} </h6>
                            <div class="element-box-tp">
                                <table class="table table-compact smaller text-faded mb-0">
                                    <tbody>
                                        @foreach($currencies as $currency)
                                            <tr style="border-color: #ecaf4069">
                                                <td  class = "display-dash-slider"><span>{{ $currency->Ccy }} </span>
                                                    <i class="os-icon os-icon-repeat icon-separator padding-slider-icon"></i>
                                                    <span>UZS </span>
                                                </td>
                                                <td class="text-center">{{ date('d.m.Y', strtotime($currency->Date)) }} </td>
                                                <td class="text-right text-bright">{{ $currency->Rate }} </td>
                                                <td class="text-right text-{{ ($currency->Diff > 0)?'danger':'success' }}">{{ $currency->Diff }} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="element-wrapper compact">
                            <div class="element-actions actions-only">
                                <a class="element-action element-action-fold" href="#">
                                    <i class="os-icon os-icon-minus-circle"></i>
                                </a>
                            </div>
                            <h6 class="element-header">{{ trans('app.Bets of banks') }}</h6>
                            <div class="element-box-tp">
                                <div class="todo-list">
                                    <a class="todo-item" href="javascript:void(0)">
                                        <div class="ti-info">
                                            <div class="ti-header">{{ trans('app.Main bet') }}</div>
                                        <div class="ti-sub-header">{{ trans('app.from that day') }}</div>
                                        </div>
                                        <div class="ti-icon">14%</div>
                                    </a>
                                    <a class="todo-item" href="javascript:void(0)">
                                        <div class="ti-info">
                                            <div class="ti-header">{{ trans('app.Annual inflation') }}</div>
                                        </div>
                                        <div class="ti-icon">11.5%</div>
                                    </a>
                                    <a class="todo-item" href="javascript:void(0)">
                                        <div class="ti-info">
                                            <div class="ti-header">{{ trans('app.Infilation target') }}</div>
                                            <div class="ti-sub-header">{{ trans('app.end of year') }} </div>
                                        </div>
                                        <div class="ti-icon">5%</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="element-wrapper compact">
                            <div class="element-actions actions-only">
                                <a class="element-action element-action-fold" href="#">
                                    <i class="os-icon os-icon-minus-circle"></i>
                                </a>
                            </div>
                            <h6 class="element-header">{{ trans('app.Interbank market rates') }}</h6>
                            <div class="element-box-tp">
                                <div class="row">
                                    <div class="col-6 col-md-6 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.1 day') }} </div>
                                            <div class="value sidebar-value">13.84% </div>
                                            <div class="trending trending-down">
                                                <span>-0.30% </span>
                                                <i class="os-icon os-icon-arrow-down6"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-3 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.2-14 days') }} </div>
                                            <div class="value sidebar-value">14.13% </div>
                                            <div class="trending trending-up">
                                                <span>0.02% </span>
                                                <i class="os-icon os-icon-arrow-up6"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-6 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.15-30 days') }} </div>
                                            <div class="value sidebar-value">15.00% </div>
                                            <div class="trending trending-zero">
                                                <span>0.00% </span>
                                                <i class="os-icon os-icon-arrow-zero"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-6 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.31-90 days') }} </div>
                                            <div class="value sidebar-value">15.00% </div>
                                            <div class="trending trending-zero">
                                                <span>0.00% </span>
                                                <i class="os-icon os-icon-arrow-zero"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-6 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.91-180 days') }} </div>
                                            <div class="value sidebar-value">14.00% </div>
                                            <div class="trending trending-zero">
                                                <span>0.00% </span>
                                                <i class="os-icon os-icon-arrow-zero"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-6 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.181 days - 1 year') }} </div>
                                            <div class="value sidebar-value">15.00% </div>
                                            <div class="trending trending-zero">
                                                <span>0.00% </span>
                                                <i class="os-icon os-icon-arrow-zero"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="element-wrapper compact folded">
                            <div class="element-actions actions-only">
                                <a class="element-action  custom-action-fold" href="#">
                                    <i class="os-icon os-icon-plus-circle"></i>
                                </a>
                            </div>
                            <h6 class="element-header">{{ trans('app.Percents of operation in Central bank') }}</h6>
                            <div class="element-box-tp" style="display: none;">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-xxl-12">
                                        <div class="element-box-tp">
                                            <table class="table table-clean">
                                                <tr>
                                                    <td>
                                                        <div class="value sidebar-table">{{ trans('app.max bet for country securities auction') }}</div>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="value sidebar-percent">15% </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="value sidebar-table">{{ trans('app.min bet for REPO and currency auction') }}</div>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="value sidebar-percent">15% </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="value sidebar-table">{{ trans('app.bet for over REPO and currency operation') }}</div>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="value sidebar-percent">16% </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="value sidebar-table">{{ trans('app.max bet for deposit auction') }}</div>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="value sidebar-percent">15% </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="value sidebar-table">{{ trans('app.max bet for over deposit operation') }}</div>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="value sidebar-percent">14% </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="element-wrapper compact folded">
                            <div class="element-actions actions-only">
                                <a class="element-action custom-action-fold" href="#">
                                    <i class="os-icon os-icon-plus-circle"></i>
                                </a>
                            </div>
                            <h6 class="element-header">{{ trans('app.mandatory backup norm') }}</h6>
                            <div class="element-box-tp" style="display: none;">
                                <div class="row">
                                    <div class="col-6 col-md-6 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height"  href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.national currency') }} </div>
                                            <div class="value sidebar-value">4% </div>
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-6-3 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.foreign currency') }} </div>
                                            <div class="value sidebar-value">14% </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="element-wrapper compact folded">
                            <div class="element-actions actions-only">
                                <a class="element-action custom-action-fold" href="#">
                                    <i class="os-icon os-icon-plus-circle"></i>
                                </a>
                            </div>
                            <h6 class="element-header">{{ trans('app.souviner coins') }}</h6>
                            <div class="element-box-tp" style="display: none;">
                                <div class="row">
                                    <div class="col-6 col-md-6-3 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.gold coin') }} </div>
                                            <div class="value sidebar-value">
                                                {{ number_digiting(number_formatting(20200000)->number) }} 
                                                <span class="short-sum">{{ number_formatting(20200000)->text }} UZS</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 col-md-6 col-xl-6 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.silver coin') }} </div>
                                            <div class="value sidebar-value">
                                                {{ number_digiting(number_formatting(506000)->number) }} 
                                                <span class="short-sum">{{ number_formatting(506000)->text }} UZS</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="element-wrapper compact folded">
                            <div class="element-actions actions-only">
                                <a class="element-action custom-action-fold" href="#">
                                    <i class="os-icon os-icon-plus-circle"></i>
                                </a>
                            </div>
                            <h6 class="element-header">{{ trans('app.average percent bets in national currency') }}</h6>
                            <div class="element-box-tp" style="display: none;">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-xl-12 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.whole deposit in 1 year') }} </div>
                                            <div class="value sidebar-value">17.3%</div>
                                            <div class="trending trending-down">
                                                <span>-0.60% </span>
                                                <i class="os-icon os-icon-arrow-down6"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-12 col-xl-12 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.whole deposit bigger than 1 year') }} </div>
                                            <div class="value sidebar-value">18.3%</div>
                                            <div class="trending trending-down">
                                                <span>-0.30% </span>
                                                <i class="os-icon os-icon-arrow-down6"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="element-wrapper compact folded">
                            <div class="element-actions actions-only">
                                <a class="element-action custom-action-fold" href="#">
                                    <i class="os-icon os-icon-plus-circle"></i>
                                </a>
                            </div>
                            <h6 class="element-header">{{ trans('app.average percent bets in foreign currency') }}</h6>
                            <div class="element-box-tp" style="display: none;">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-xl-12 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.whole deposit in 1 year') }} </div>
                                            <div class="value sidebar-value">3.2%</div>
                                            <div class="trending trending-zero">
                                                <span>0.0% </span>
                                                <i class="os-icon os-icon-arrow"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-12 col-xl-12 display-table">
                                        <a class="element-box el-tablo centered trend-in-corner smaller min-height" href="#">
                                            <div class="label sidebar-text-square color-black">{{ trans('app.whole deposit bigger than 1 year') }} </div>
                                            <div class="value sidebar-value">5.5%</div>
                                            <div class="trending trending-up">
                                                <span>+0.2% </span>
                                                <i class="os-icon os-icon-arrow-up6"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>

        
        @if(!empty($data))
            <?php $data = json_decode($data) ?>
        @endif
        <script>
                if($(window).width() < 1400){
                    var width = $('.button-width');
                    var children = width.children();
                    children.removeClass('col-xl-2').addClass('col-xl-3');
                }

            $('document').ready(function(){
                
                $('select[name="from_calc"]').on('change', function(){
                    $('input[name="from_calc"]').val("");
                    $('input[name="to_calc"]').val("");
                    var from = $('select[name="from_calc"]').val();
                    var to = $('select[name="to_calc"]').val();
                    if(from == 'UZS'){
                        $('select[name="to_calc"] option[value="UZS"]').attr('selected', false);
                        $('select[name="to_calc"] option[value="UZS"]').siblings('option:first').attr('selected', true);
                        $('select[name="to_calc"]').selectpicker('refresh');
                    }else{
                        $('select[name="to_calc"] option:selected').attr('selected', false);
                        $('select[name="to_calc"] option[value="UZS"]').attr('selected', true);
                        $('select[name="to_calc"]').selectpicker('refresh');
                    }
                });
                $('select[name="to_calc"]').on('change', function(){
                    $('input[name="from_calc"]').val("");
                    $('input[name="to_calc"]').val("");
                    var from = $('select[name="from_calc"]').val();
                    var to = $('select[name="to_calc"]').val();
                    if(to == 'UZS'){
                        $('select[name="from_calc"] option[value="UZS"]').attr('selected', false);
                        $('select[name="from_calc"] option[value="UZS"]').siblings('option:first').attr('selected', true);
                        $('select[name="from_calc"]').selectpicker('refresh');
                    }else{
                        $('select[name="from_calc"] option:selected').attr('selected', false);
                        $('select[name="from_calc"] option[value="UZS"]').attr('selected', true);
                        $('select[name="from_calc"]').selectpicker('refresh');
                    }
                });
                $('input[name="from_calc"]').on('keyup', function(){
                    var amount = $(this).val();
                    var from_type = $('select[name="from_calc"]').val();
                    var to_type = $('select[name="to_calc"]').val();
                    var final_result = 0;
                    if(from_type == 'UZS'){
                        final_result = amount/to_type;
                    }else{
                        final_result = amount*from_type;
                    }
                    $('input[name="to_calc"]').val(final_result.toFixed(2).toLocaleString(undefined, {minimumFractionDigits: 2}));
                });
                var top_safe_legend_fillial = [];
                var top_problem_legend_fillial = [];
                var top_safe_legend_activity = [];
                var top_problem_legend_activity = [];
                var top_safe_legend_goal = [];
                var top_problem_legend_goal = [];
                @php 
                    $color = array("#047bf8","#24b314","#fbe4a0","#fd2785","#5bc0de","#9453fa","#1b3999","#7e6fff","#ffcc29","#ff2c53");
                @endphp
                var top_safe_source_fillial = [
                    @for($i=count($top_safe_fillial)-1; $i>=0; $i--)
                        {
                            "value": "{{$top_safe_fillial[$i]->safe_credit}}",
                            "name": '{{ getBankName($top_safe_fillial[$i]->mfo_id, 'fill') }}',
                            "itemStyle": {
                                "color": "{{ $top_safe_fillial[$i]->color }}"
                            }
                        },
                    @endfor
                ];

                @for($i=count($top_safe_fillial)-1; $i>=0; $i--)
                top_safe_legend_fillial.push("{{ getBankName($top_safe_fillial[$i]->mfo_id, 'fill') }}");
                @endfor

                var top_problem_source_fillial = [
                    @for($i=count($top_problem_fillial)-1; $i>=0; $i--)
                        {
                            "value": "{{$top_problem_fillial[$i]->problem_credit}}",
                            "name": '{{ getBankName($top_problem_fillial[$i]->mfo_id, 'fill') }}',
                            "itemStyle": {
                                "color": "{{ $top_problem_fillial[$i]->color }}"
                            }
                        },
                    @endfor
                ];

                @for($i=count($top_problem_fillial)-1; $i>=0; $i--)
                top_problem_legend_fillial.push("{{ getBankName($top_problem_fillial[$i]->mfo_id, 'fill') }}");
                @endfor

                var top_safe_source_activity = [
                    @for($i=count($top_safe_activity)-1; $i>=0; $i--)
                        {
                            "value": "{{$top_safe_activity[$i]->safe_credit}}",
                            "name": '{{$top_safe_activity[$i]->name}}',
                            "tooltip": {
                                "useHTML": true,
                                "formatter": function(params){
                                    return  "<div class = 'custom-tooltip'><p>" + params.data.name+": "+(params.value*"{{ $top_safe_activity[5]->safe_dividing }}").toLocaleString() + "</p></div>";
                                }
                            },
                            "itemStyle": {
                                "color": "{{ $top_safe_activity[$i]->color }}"
                            }
                        },
                    @endfor
                ];

                @for($i=count($top_safe_activity)-1; $i>=0; $i--)
                    top_safe_legend_activity.push("{{ $top_safe_activity[$i]->code }}");
                @endfor
                
                var top_problem_source_activity = [
                    @for($i=count($top_problem_activity)-1; $i>=0; $i--)
                        {
                            "value": "{{$top_problem_activity[$i]->problem_credit}}",
                            "name": '{{$top_problem_activity[$i]->name}}',
                            "tooltip": {
                                "useHTML": true,
                                "formatter": function(params){
                                    return "<div class = 'custom-tooltip'><p>" + params.data.name+": "+(params.value*"{{ $top_problem_activity[5]->problem_dividing }}").toLocaleString() + "</p></div>";
                                }
                            },
                            "itemStyle": {
                                "color": "{{ $top_problem_activity[$i]->color }}"
                            }
                        },
                    @endfor
                ];

                @for($i=count($top_problem_activity)-1; $i>=0; $i--)
                    top_problem_legend_activity.push("{{ $top_problem_activity[$i]->code }}");
                @endfor

                var top_safe_source_goal = [
                    @for($i=count($top_safe_goal)-1; $i>=0; $i--)
                        {
                            "value": "{{$top_safe_goal[$i]->safe_credit}}",
                            "name": '{{$top_safe_goal[$i]->name}}',
                            "tooltip": {
                                "useHTML": true,
                                "formatter": function(params){
                                    return "<div class = 'custom-tooltip'><p>" + params.data.name+": "+(params.value*"{{ $top_safe_goal[5]->safe_dividing }}").toLocaleString() + "</p></div>";
                                }
                            },
                            "itemStyle": {
                                "color": "{{ $top_safe_goal[$i]->color }}"
                            }
                        },
                    @endfor
                ];

                @for($i=count($top_safe_goal)-1; $i>=0; $i--)
                    top_safe_legend_goal.push("{{ $top_safe_goal[$i]->code }}");
                @endfor

                var top_problem_source_goal = [
                    @for($i=count($top_problem_goal)-1; $i>=0; $i--)
                        {
                            "value": "{{$top_problem_goal[$i]->problem_credit}}",
                            "name": '{{$top_problem_goal[$i]->name}}',
                            "tooltip": {
                                "useHTML": true,
                                "formatter": function(params){
                                    return "<div class = 'custom-tooltip'><p>" + params.data.name+": "+(params.value*"{{ $top_problem_goal[5]->problem_dividing }}").toLocaleString() +
                                    "</p></div>";
                                }
                            },
                            "itemStyle": {
                                "color": "{{ $top_problem_goal[$i]->color }}"
                            }
                        },
                    @endfor
                ];

                @for($i=count($top_problem_goal)-1; $i>=0; $i--)
                    top_problem_legend_goal.push("{{ $top_problem_goal[$i]->code }}");
                @endfor
                
                function top_safe_credit_fillial() {
                    var width = $(window).width();
                    if(width < 1400){
                        var font_size = 10;
                        var bar_width = 15; 
                    }else{
                        var font_size = 12;
                        var bar_width = 20;
                    }
                    if ($('#top_safe_fillial').length) {
                        var myChart = echarts.init(document.getElementById('top_safe_fillial'));
                        var options = {
                            tooltip:{
                                show: true,
                                formatter: function(params){
                                    var data = (params.value*"{{ $top_safe_fillial[0]->safe_dividing }}");
                                    // var name = params.name +"  "+  data.toLocaleString();
                                    return data.toLocaleString();
                                }
                            },
                            toolbox: {
                                feature: {
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ $top_safe_fillial_title."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    },
                                },
                                right: "5%" 
                            },
                            xAxis: {},
                            yAxis: {
                                data:top_safe_legend_fillial,
                                axisLabel:{
                                    fontSize: font_size,
                                    fontFamily: '"Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                                }
                            },
                            grid: {
                                left:170,
                            },
                            series: [
                                {
                                    name: '{{trans("app.Portfolio")}}',
                                    type: 'bar',
                                    label:{
                                        show: true,
                                        position: 'right',
                                        formatter: function(params){
                                            var value = params.value;
                                            return (value*1).toFixed(2);
                                        }
                                    },
                                    barWidth: bar_width,
                                    data: top_safe_source_fillial
                                }
                            ]
                        };

                        myChart.setOption(options);
                        setTimeout(function() { myChart.resize() }, 200);
                    }
                };

                function top_problem_credit_fillial() {
                    var width = $(window).width();
                    if(width < 1400){
                        var font_size = 10;
                        var bar_width = 15; 
                    }else{
                        var font_size = 12;
                        var bar_width = 20;
                    }
                    if ($('#top_problem_fillial').length) {
                        var myChart = echarts.init(document.getElementById('top_problem_fillial'));
                        var options = {
                            tooltip:{
                                show: true,
                                formatter: function(params){
                                    var data = (params.value*"{{ $top_problem_fillial[5]->problem_dividing }}");
                                    // var name = params.name +"  "+  data.toLocaleString();
                                    return data.toLocaleString();
                                }
                            },
                            toolbox: {
                                feature: {
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ $top_problem_fillial_title."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    },
                                },
                                right: "5%" 
                            },
                            xAxis: {},
                            yAxis: {
                                data:top_problem_legend_fillial,
                                axisLabel:{
                                    fontSize: font_size,
                                    fontFamily: '"Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                                }
                            },
                            grid: {
                                left: 170,
                            },
                            series: [
                                {
                                    name: '{{trans("app.Portfolio")}}',
                                    type: 'bar',
                                    label:{
                                        show: true,
                                        position: 'right',
                                        formatter: function(params){
                                            var value = params.value;
                                            return (value*1).toFixed(2);
                                        }
                                    },
                                    barWidth: bar_width,
                                    data: top_problem_source_fillial
                                   
                                }
                            ]
                        };

                        myChart.setOption(options);
                        setTimeout(function() { myChart.resize() }, 200);
                    }
                };

                function top_safe_credit_goal() {
                    var width = $(window).width();
                    if(width < 1400){
                        var font_size = 10;
                        var bar_width = 15; 
                    }else{
                        var font_size = 12;
                        var bar_width = 20;
                    }
                    if ($('#top_safe_goal').length) {
                        var myChart = echarts.init(document.getElementById('top_safe_goal'));
                        var options = {
                            tooltip:{
                                show: true
                            },
                            toolbox: {
                                feature: {
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ $top_safe_activity_species_title."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    },
                                },
                                right: "5%" 
                            },
                            xAxis: {},
                            yAxis: {
                                // data:top_safe_legend_goal.slice(0, 3) + '\n' + top_safe_legend_goal.slice(3, top_safe_legend_goal.length),
                                data:top_safe_legend_goal,
                                axisLabel:{
                                    fontSize: font_size,
                                    fontFamily: '"Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                                }
                            },
                            grid: {
                                left: 100,
                            },
                            series: [
                                {
                                    name: '{{trans("app.Portfolio")}}',
                                    type: 'bar',
                                    barWidth: bar_width,
                                    label:{
                                        show: true,
                                        // position: 'insideleft',
                                        position: 'right',
                                        // distance: -20,
                                        formatter: function(params){
                                            var value = params.value;
                                            return (value*1).toFixed(2);
                                        }
                                    },
                                    data: top_safe_source_goal,
                                   
                                }
                            ]
                        };

                        myChart.setOption(options);
                        setTimeout(function() { myChart.resize() }, 200);
                    }
                };

                function top_problem_credit_goal() {
                    var width = $(window).width();
                    if(width < 1400){
                        var font_size = 10;
                        var bar_width = 15; 
                    }else{
                        var font_size = 12;
                        var bar_width = 20;
                    }
                    if ($('#top_problem_goal').length) {
                        var myChart = echarts.init(document.getElementById('top_problem_goal'));
                        var options = {
                            tooltip:{
                                show: true
                            },
                            toolbox: {
                                feature: {
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ $top_problem_activity_species_title."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    },
                                },
                                right: "5%" 
                            },
                            xAxis: {},
                            yAxis: {
                                data:top_problem_legend_goal,
                                axisLabel:{
                                    fontSize: font_size,
                                    fontFamily: '"Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                                }
                            },
                            grid: {
                                left: 100,
                            },
                            series: [
                                {
                                    name: '{{trans("app.Portfolio")}}',
                                    type: 'bar',
                                    barWidth: bar_width,
                                    label:{
                                        show: true,
                                        position: 'right',
                                        formatter: function(params){
                                            var value = params.value;
                                            return (value*1).toFixed(2);
                                        }
                                    },
                                    data: top_problem_source_goal,
                                   
                                }
                            ]
                        };

                        myChart.setOption(options);
                        setTimeout(function() { myChart.resize() }, 200);
                    }
                };

                function top_safe_credit_activity() {
                    var width = $(window).width();
                    if(width < 1400){
                        var font_size = 10;
                        var bar_width = 15; 
                    }else{
                        var font_size = 12;
                        var bar_width = 20;
                    }
                    if ($('#top_safe_activity').length) {
                        var myChart = echarts.init(document.getElementById('top_safe_activity'));
                        var options = {
                            tooltip:{
                                show: true,
                                position: function(pt){
                                    return [pt[0], pt[1]];
                                }
                            },
                            toolbox: {
                                feature: {
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ $top_safe_loan_species_title."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    },
                                },
                                right: "5%" 
                            },
                            xAxis: {},
                            yAxis: {
                                data:top_safe_legend_activity,
                                axisLabel:{
                                    fontSize: font_size,
                                    fontFamily: '"Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                                }
                            },
                            grid: {
                                left: 100,
                            },
                            series: [
                                {
                                    name: '{{trans("app.Portfolio")}}',
                                    type: 'bar',
                                    barWidth: bar_width,
                                    data: top_safe_source_activity,
                                    label:{
                                        show: true,
                                        position: 'right',
                                        formatter: function(params){
                                            var value = params.value;
                                            return (value*1).toFixed(2);
                                        }
                                    }
                                }
                            ]
                        };

                        myChart.setOption(options);
                        setTimeout(function() { myChart.resize() }, 100);
                    }
                };

                function top_problem_credit_activity() {
                    var width = $(window).width();
                    if(width < 1400){
                        var font_size = 10;
                        var bar_width = 15; 
                    }else{
                        var font_size = 12;
                        var bar_width = 20;
                    }
                    if ($('#top_problem_activity').length) {
                        var myChart = echarts.init(document.getElementById('top_problem_activity'));
                        var options = {
                            tooltip:{
                                show: true
                            },
                            toolbox: {
                                feature: {
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ $top_problem_loan_species_title."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    },
                                },
                                right: "5%" 
                            },
                            xAxis: {},
                            yAxis: {
                                data:top_problem_legend_activity,
                                axisLabel:{
                                    fontSize: font_size,
                                    fontFamily: '"Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                                }
                            },
                            grid: {
                                left: 100,
                            },
                            series: [
                                {
                                    name: '{{trans("app.Portfolio")}}',
                                    type: 'bar',
                                    barWidth: bar_width,
                                    data: top_problem_source_activity,
                                    label:{
                                        show: true,
                                        position: 'right',
                                        formatter: function(params){
                                            var value = params.value;
                                            return (value*1).toFixed(2);
                                        }
                                    }
                                }
                            ]
                        };

                        myChart.setOption(options);
                        setTimeout(function() { myChart.resize() }, 100);
                    }
                };
                

                var data_current_monthyear = [];
                var sxema_current_monthyear = [];
                var data_current_a_credit = [];
                var data_current_p_credit = [];
                var data_current_actives = [];
                var data_current_deposit = [];
                var data_current_p_deposit = [];
                var data_current_a_likvid = [];
                var data_current_income = [];
                var data_current_expense = [];
                var data_current_kirim = [];
                var data_current_chiqim = [];

                var data_last_monthyear = [];
                var sxema_last_monthyear = [];
                var data_last_a_credit = [];
                var data_last_p_credit = [];
                var data_last_actives = [];
                var data_last_deposit = [];
                var data_last_p_deposit = [];
                var data_last_a_likvid = [];
                var data_last_income = [];
                var data_last_expense = [];
                var data_last_kirim = [];
                var data_last_chiqim = [];

                var data_current_percent_loan_problem = [];
                var data_last_percent_loan_problem = [];


                @foreach ($data->data_current_monthyear as $item)
                    data_current_monthyear.push('{{ $item }}');
                @endforeach
                @foreach ($data->sxema_current_monthyear as $item)
                    sxema_current_monthyear.push('{{ $item }}');
                @endforeach
                @foreach ($data->data_current_a_credit as $loan => $loan_item)
                    data_current_a_credit.push('{{ $loan_item/1000000000 }}');
                    @foreach ($data->data_current_p_credit as $problem => $problem_item)
                        @if($loan == $problem)
                            data_current_percent_loan_problem.push('{{ number_format(($problem_item/$loan_item)*100, 2) }}');
                        @endif
                    @endforeach
                @endforeach
                @foreach ($data->data_current_p_credit as $item)
                    data_current_p_credit.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_current_actives as $item)
                    data_current_actives.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_current_deposit as $item)
                    data_current_deposit.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_current_p_deposit as $item)
                    data_current_p_deposit.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_current_a_likvid as $item)
                    data_current_a_likvid.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_current_income as $item)
                    data_current_income.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_current_expense as $item)
                    data_current_expense.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_current_kirim as $item)
                    data_current_kirim.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_current_chiqim as $item)
                    data_current_chiqim.push('{{ $item/1000000000 }}');
                @endforeach


                @foreach ($data->data_last_monthyear as $item)
                    data_last_monthyear.push('{{ $item }}');
                @endforeach
                @foreach ($data->sxema_last_monthyear as $item)
                    sxema_last_monthyear.push('{{ $item }}');
                @endforeach
                @foreach ($data->data_last_a_credit as $loan => $loan_item)
                    data_last_a_credit.push('{{ $loan_item/1000000000 }}');
                    @foreach ($data->data_last_p_credit as $problem => $problem_item)
                        @if($loan == $problem)
                            @if($loan_item == 0 || empty($loan_item))
                                <?php $loan_item=1; ?>
                            @endif
                            data_last_percent_loan_problem.push('{{ number_format(($problem_item/$loan_item)*100, 2) }}');
                        @endif
                    @endforeach
                @endforeach
                @foreach ($data->data_last_p_credit as $item)
                    data_last_p_credit.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_last_actives as $item)
                    data_last_actives.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_last_deposit as $item)
                    data_last_deposit.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_last_p_deposit as $item)
                    data_last_p_deposit.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_last_a_likvid as $item)
                    data_last_a_likvid.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_last_income as $item)
                    data_last_income.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_last_expense as $item)
                    data_last_expense.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_last_kirim as $item)
                    data_last_kirim.push('{{ $item/1000000000 }}');
                @endforeach
                @foreach ($data->data_last_chiqim as $item)
                    data_last_chiqim.push('{{ $item/1000000000 }}');
                @endforeach
                var bgcolor = '#2d56d7';

                // credits_actives
                $(".credits_actives").on("click", "ul > li", function(e){
                    $(this).toggleClass("strike");
                    var id  = $(this).attr('data-uid');
                    var length = myChart._chartsViews.length;
                    for(var i = 0; i < length; i++){
                        uid = myChart._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChart.dispatchAction({
                                type: 'legendToggleSelect',
                                name: myChart._chartsViews[i].__model.name                
                            }); 
                        }
                    }  
                });

                $(".credits_actives").on("mouseenter", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChart._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChart._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChart.dispatchAction({
                                type: 'highlight',
                                seriesName: myChart._chartsViews[i].__model.name
                            });
                        }
                    }
                }).on("mouseleave", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChart._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChart._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChart.dispatchAction({
                                type: 'downplay',
                                seriesName: myChart._chartsViews[i].__model.name
                            });
                        }
                    }
                });
                var myChart = null;

                // credits_problem
                $(".credits_problem").on("click", "ul > li", function(e){
                    $(this).toggleClass("strike");
                    var id  = $(this).attr('data-uid');
                    var length = myChartCreditsproblem._chartsViews.length;
                    for(var i = 0; i < length; i++){
                        uid = myChartCreditsproblem._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartCreditsproblem.dispatchAction({
                                type: 'legendToggleSelect',
                                name: myChartCreditsproblem._chartsViews[i].__model.name                
                            }); 
                        }
                    }
                });
                $(".credits_problem").on("mouseenter", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartCreditsproblem._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartCreditsproblem._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartCreditsproblem.dispatchAction({
                                type: 'highlight',
                                seriesName: myChartCreditsproblem._chartsViews[i].__model.name
                            });
                        }
                    }
                }).on("mouseleave", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartCreditsproblem._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartCreditsproblem._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartCreditsproblem.dispatchAction({
                                type: 'downplay',
                                seriesName: myChartCreditsproblem._chartsViews[i].__model.name
                            });
                        }
                    }
                });
                var myChartCreditsproblem = null;
                
                // passives_deposit
                $(".passives_deposit").on("click", "ul > li", function(e){
                    $(this).toggleClass("strike");
                    var id = $(this).attr('data-uid');
                    var length = myChartPassives._chartsViews.length;
                    for(var i = 0; i < length; i++){
                        uid = myChartPassives._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartPassives.dispatchAction({
                                type: 'legendToggleSelect',
                                name: myChartPassives._chartsViews[i].__model.name                
                            }); 
                        }
                    }
                    
                });

                $(".passives_deposit").on("mouseenter", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartPassives._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartPassives._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartPassives.dispatchAction({
                                type: 'highlight',
                                seriesName: myChartPassives._chartsViews[i].__model.name
                            });
                        }
                    }
                }).on("mouseleave", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartPassives._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartPassives._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartPassives.dispatchAction({
                                type: 'downplay',
                                seriesName: myChartPassives._chartsViews[i].__model.name
                            });
                        }
                    }
                });

                var myChartPassives = null;

                // deposit_pdeposit
                $(".deposit_pdeposit").on("click", "ul > li", function(e){
                    $(this).toggleClass("strike");
                    var id  = $(this).attr('data-uid');
                    var length = myChartDepositPd._chartsViews.length;
                    for(var i = 0; i < length; i++){
                        uid = myChartDepositPd._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartDepositPd.dispatchAction({
                                type: 'legendToggleSelect',
                                name: myChartDepositPd._chartsViews[i].__model.name                
                            }); 
                        }
                    }
                });

                $(".deposit_pdeposit").on("mouseenter", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartDepositPd._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartDepositPd._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartDepositPd.dispatchAction({
                                type: 'highlight',
                                seriesName: myChartDepositPd._chartsViews[i].__model.name
                            });
                        }
                    }
                }).on("mouseleave", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartDepositPd._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartDepositPd._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartDepositPd.dispatchAction({
                                type: 'downplay',
                                seriesName: myChartDepositPd._chartsViews[i].__model.name
                            });
                        }
                    }
                });
                var myChartDepositPd = null;

                // actives_likvids
                $(".actives_likvids").on("click", "ul > li", function(e){
                    $(this).toggleClass("strike");
                    var id  = $(this).attr('data-uid');
                    var length = myChartActivesLikv._chartsViews.length;
                    for(var i = 0; i < length; i++){
                        uid = myChartActivesLikv._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartActivesLikv.dispatchAction({
                                type: 'legendToggleSelect',
                                name: myChartActivesLikv._chartsViews[i].__model.name                
                            }); 
                        }
                    }
                });

                $(".actives_likvids").on("mouseenter", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartActivesLikv._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartActivesLikv._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartActivesLikv.dispatchAction({
                                type: 'highlight',
                                seriesName: myChartActivesLikv._chartsViews[i].__model.name
                            });
                        }
                    }
                }).on("mouseleave", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartActivesLikv._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartActivesLikv._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartActivesLikv.dispatchAction({
                                type: 'downplay',
                                seriesName: myChartActivesLikv._chartsViews[i].__model.name
                            });
                        }
                    }
                });

                var myChartActivesLikv = null;

                // income_expense
                $(".income_expense").on("click", "ul > li", function(e){
                    $(this).toggleClass("strike");
                    var id  = $(this).attr('data-uid');
                    var length = myChartIncome._chartsViews.length;
                    for(var i = 0; i < length; i++){
                        uid = myChartIncome._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartIncome.dispatchAction({
                                type: 'legendToggleSelect',
                                name: myChartIncome._chartsViews[i].__model.name                
                            }); 
                        }
                    }
                });

                $(".income_expense").on("mouseenter", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartIncome._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartIncome._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartIncome.dispatchAction({
                                type: 'highlight',
                                seriesName: myChartIncome._chartsViews[i].__model.name
                            });
                        }
                    }
                }).on("mouseleave", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartIncome._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartIncome._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartIncome.dispatchAction({
                                type: 'downplay',
                                seriesName: myChartIncome._chartsViews[i].__model.name
                            });
                        }
                    }
                });

                var myChartIncome = null;

                // kirim_chiqim
                $(".kirim_chiqim").on("click", "ul > li", function(e){
                    $(this).toggleClass("strike");
                    var id  = $(this).attr('data-uid');
                    var length = myChartKirim._chartsViews.length;
                    for(var i = 0; i < length; i++){
                        uid = myChartKirim._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartKirim.dispatchAction({
                                type: 'legendToggleSelect',
                                name: myChartKirim._chartsViews[i].__model.name                
                            }); 
                        }
                    }
                });

                $(".kirim_chiqim").on("mouseenter", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartKirim._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartKirim._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartKirim.dispatchAction({
                                type: 'highlight',
                                seriesName: myChartKirim._chartsViews[i].__model.name
                            });
                        }
                    }
                }).on("mouseleave", "ul > li", function(e){
                    var id  = $(this).attr('data-uid');
                    var length = myChartKirim._chartsViews.length;
                    var uid = null;
                    for(var i = 0; i < length; i++){
                        uid = myChartKirim._chartsViews[i].__model.name;
                        uid = uid.replace(/ /g,'');
                        if(uid == id){
                            myChartKirim.dispatchAction({
                                type: 'downplay',
                                seriesName: myChartKirim._chartsViews[i].__model.name
                            });
                        }
                    }
                });

                var myChartKirim = null;

                function credits_actives() {
                    var parent = $('#credits_actives').children().length;
                    if ($('#credits_actives').length && !(parent>0)) {

                        myChart = echarts.init(document.getElementById('credits_actives'));

                        var options = {
                            tooltip: {
                                trigger: 'item',
                                axisPointer: {
                                    type: 'cross'
                                },
                                formatter: function(params){
                                    bgcolor = params.color;
                                    if(params.seriesIndex == 0 || params.seriesIndex == 2){
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            }
                                        ];
                                    }else{
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            }
                                        ];
                                    }
                                    
                                    myChart.setOption({
                                        tooltip: {
                                            backgroundColor:bgcolor
                                        },
                                        xAxis: showing_month
                                    });
                                    
                                    var data = params.value*1000000000;
                                    var name = params.seriesName +"  "+  data.toLocaleString(undefined, {minimumFractionDigits: 2});
                                    return name;
                                 
                                },

                            },
                            legend: {
                                show: false,
                            },
                            toolbox: {
                                feature: {
                                    myTool2: {
                                        show: true,
                                        title: '{{ trans('app.to see line chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/line-chart.png') }}',
                                        onclick: function(){
                                            myChart.setOption({
                                                xAxis: [
                                                  {
                                                    boundaryGap: false
                                                  },
                                                  {
                                                    boundaryGap: false
                                                  }
                                                ],
                                                series: [
                                                    {
                                                        name: '{{trans("app.last year all actives")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        data: data_last_actives,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        } 
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all actives")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        data: data_current_actives,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.last year all credits")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle',
                                                        xAxisIndex: 1,
                                                        symbolSize: 4,
                                                        data: data_last_a_credit,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all credits")}}',
                                                        type: 'line',
                                                        symbol: 'circle',
                                                        smooth: true,
                                                        symbolSize: 4,
                                                        data: data_current_a_credit,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    }  
                                                ]
                                            });

                                                var alive = myChart._chartsViews;
                                                var alives = [];
                                                for (var i = 0; i < alive.length; i++) {
                                                    if(alive[i].__alive == false){
                                                        alives.push(alive[i].__model.name.replace(/ /g,''));
                                                    }
                                                    if(alive[i].__model.option.symbol == 'emptyCircle'){
                                                        var icon = $('.credits_actives ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                        icon.removeClass('fa-square').addClass('fa-circle-o');
                                                    }  
                                                }
                                                for(var i=0; i < alives.length; i++)
                                                {
                                                    var child_legend = $('.credits_actives ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                                }
                                            
                                        }
                                        
                                    },
                                    myTool1: {
                                        show: true,
                                        title: '{{ trans('app.to see bar chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/bar-chart.png') }}',
                                        onclick: function(){
                                            myChart.setOption({
                                                xAxis: [
                                                  {
                                                    boundaryGap: true
                                                  },
                                                  {
                                                    boundaryGap: true
                                                  }
                                                ],
                                                series: [
                                                    
                                                    {
                                                        name: '{{trans("app.last year all actives")}}',
                                                        type: 'bar',
                                                        smooth: true,
                                                        symbol: 'square',
                                                        symbolSize: 4,
                                                        barWidth: 20,
                                                        itemStyle: {
                                                            color: "rgba(239,62,54, 0.8)"
                                                        },
                                                        large: true,
                                                        data: data_last_actives 
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all actives")}}',
                                                        type: 'bar',
                                                        smooth: true,
                                                        symbol: 'square',
                                                        symbolSize: 4,
                                                        barWidth: 20,
                                                        itemStyle: {
                                                            color: "rgba(51,108,251, 0.8)"
                                                        },
                                                        large: true,
                                                        data: data_current_actives
                                                    },
                                                    {
                                                        name: '{{trans("app.last year all credits")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle',
                                                        symbolSize: 10,
                                                        areaStyle: {
                                                            color: "rgba(255, 255, 255, 0)"
                                                        },
                                                        data: data_last_a_credit
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all credits")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle',
                                                        symbolSize: 10,
                                                        areaStyle: {
                                                            color: "rgba(255, 255, 255, 0)"
                                                        },
                                                        data: data_current_a_credit
                                                    }
                                                    
                                                ]
                                            });
                                            var alive = myChart._chartsViews;
                                            var alives = [];
                                            var symbol = null;
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }  
                                                if(alive[i].__model.option.symbol == 'square'){
                                                    var icon = $('.credits_actives ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-circle-o').addClass('fa-square');
                                                }
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.credits_actives ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                    
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ trans('app.monthly credit and bank actives title')."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    },
                                },
                                right: "5%" 
                            },
                            grid: {
                                left: 60,
                                right: 30,
                                top: 100,
                                bottom: 50,
                                containerLabel: true
                            },
                            xAxis: [
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    show: true,
                                    interval: 0,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                
                                },
                                data: data_current_monthyear
                              },
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    show: true,
                                    interval: 0,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                    }
                                },
                                data: data_last_monthyear
                              }

                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisPointer: {
                                        label: {
                                            formatter: function(value){
                                                return value.value.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    }
                                }
                            ],
                            series: [
                                {
                                    name: '{{trans("app.last year all actives")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: data_last_actives,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                {
                                    name: '{{trans("app.current year all actives")}}',
                                    type: 'line',
                                    smooth: true,
                                    data: data_current_actives,
                                    itemStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                {
                                    name: '{{trans("app.last year all credits")}}',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'circle',
                                    xAxisIndex: 1,
                                    symbolSize: 4,
                                    data: data_last_a_credit,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    },
                                    areaStyle: {}
                                },
                                {
                                    name: '{{trans("app.current year all credits")}}',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'circle',
                                    symbolSize: 4,
                                    data: data_current_a_credit,
                                    itemStyle: {
                                        color: '#336cfb'
                                    },
                                    areaStyle: {}
                                }
                            ]
                        };

                        myChart.setOption(options);
                        setTimeout(function() { myChart.resize() }, 200);
                    }
                    var child = $('.credits_actives ul').children().length;
                    if(!(child > 0)){
                        var legend = $('.credits_actives'+' ul');
                        legend.html('');
                        var length = myChart._chartsViews.length;
                        var name_legend = 'null';
                        var uid = null;
                        var symbol = null;

                        for(var i = 0; i < length; i++){
                            uid = myChart._chartsViews[i].__model.name;
                            uid = uid.replace(/ /g,'');
                            if(myChart._chartsViews[i].__model.option.symbol == 'emptyCircle'){
                                symbol = 'circle-o';
                            }else{
                                symbol = myChart._chartsViews[i].__model.option.symbol;
                            }
                            name_legend = "<li data-uid='"+ uid +"' style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-"+symbol+"' style='margin-right: 5px; color: "+ myChart._chartsViews[i].__model.option.itemStyle.color +"'></i>"
                            + myChart._chartsViews[i].__model.name +"</li>";
                            legend.append(name_legend);
                        }
                    }
                };

                function credits_problem() {
                    var parent = $('#credits_problem').children().length;
                    if ($('#credits_problem').length && !(parent>0)) {

                        myChartCreditsproblem = echarts.init(document.getElementById('credits_problem'));
                        
                        var options = {
                            tooltip: {
                                trigger: 'item',
                                axisPointer: {
                                    type: 'cross'
                                },
                                formatter: function(params){
                                    bgcolor = params.color;
                                    if(params.seriesIndex == 0 || params.seriesIndex == 2){
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            }
                                        ];
                                    }else{
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            }
                                        ];
                                    }
                                    
                                    myChartCreditsproblem.setOption({
                                        tooltip: {
                                            backgroundColor:bgcolor
                                        },
                                        xAxis: showing_month
                                    });
                                    
                                    
                                   

                                    if(params.seriesIndex > 3){
                                        var data = params.value;
                                        var name = params.seriesName +"  "+  data.toLocaleString(undefined, {minimumFractionDigits: 2})+ "%";
                                        return name;
                                    }else{
                                        var data = params.value*1000000000;
                                        var name = params.seriesName +"  "+  data.toLocaleString(undefined, {minimumFractionDigits: 2});
                                        return name;
                                    }
                                    
                                },

                            },
                            legend: {
                                show: false
                                
                            },
                            toolbox: {
                                feature: {
                                    myTool2: {
                                        show: true,
                                        title: '{{ trans('app.to see line chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/line-chart.png') }}',
                                        onclick: function(){
                                            myChartCreditsproblem.setOption({
                                                xAxis: [
                                                  {
                                                    boundaryGap: false
                                                  },
                                                  {
                                                    boundaryGap: false
                                                  }
                                                ],
                                                series: [
                                                    {
                                                        name: '{{trans("app.last year all credits")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle-o',
                                                        xAxisIndex: 1,
                                                        symbolSize: 4,
                                                        data: data_last_a_credit,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all credits")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle-o',
                                                        symbolSize: 4,
                                                        data: data_current_a_credit,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.last year all problem credits")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        symbolSize: 4,
                                                        data: data_last_p_credit,
                                                        areaStyle: {
                                                            color: null
                                                        },
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all problem credits")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbolSize: 4,
                                                        data: data_current_p_credit,
                                                        areaStyle: {
                                                            color: null
                                                        },
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.last year percent of problem loans")}}',
                                                        type: 'line',
                                                        symbol: 'triangle',
                                                        symbolSize: 15,
                                                        smooth: true,
                                                        yAxisIndex: 1,
                                                        data: data_last_percent_loan_problem,
                                                    },
                                                    {
                                                        name: '{{trans("app.current year percent of problem loans")}}',
                                                        type: 'line',
                                                        symbol: 'triangle',
                                                        symbolSize: 15,     
                                                        smooth: true,
                                                        yAxisIndex: 1,
                                                        data: data_current_percent_loan_problem,
                                                    }
                                                    
                                                ]
                                            });
                                            // if(($('.credits_problem ul').has('li'))){
                                                var alive = myChartCreditsproblem._chartsViews;
                                                var alives = [];
                                                for (var i = 0; i < alive.length; i++) {
                                                    if(alive[i].__alive == false){
                                                        alives.push(alive[i].__model.name.replace(/ /g,''));
                                                    }
                                                    if(alive[i].__model.option.symbol == 'circle-o'){
                                                        var icon = $('.credits_problem ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                        icon.removeClass('fa-square').addClass('fa-circle-o');
                                                    }  
                                                }
                                                for(var i=0; i < alives.length; i++)
                                                {
                                                    var child_legend = $('.credits_problem ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                                }
                                            // }
                                            
                                        }
                                    },
                                	myTool1: {
                                        show: true,
                                        title: '{{ trans('app.to see bar chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/bar-chart.png') }}',
                                        onclick: function(){
                                            myChartCreditsproblem.setOption({
                                            	xAxis: [
					                              {
					                                boundaryGap: true
					                              },
					                              {
					                                boundaryGap: true
					                              }
					                            ],
                                            	series: [
					                                {
					                                    name: '{{trans("app.last year all credits")}}',
					                                    type: 'bar',
					                                    symbolSize: 4,
                                                        symbol: 'square',
					                                    barWidth: 20,
					                                    itemStyle: {
					                                    	color: "rgba(239,62,54, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_last_a_credit 
					                                },
					                                {
					                                    name: '{{trans("app.current year all credits")}}',
					                                    type: 'bar',
					                                    symbolSize: 4,
                                                        symbol: 'square',
					                                    barWidth: 20,
					                                    itemStyle: {
					                                    	color: "rgba(51,108,251, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_current_a_credit
					                                },
					                                {
					                                    name: '{{trans("app.last year all problem credits")}}',
					                                    type: 'line',
                                                        symbol: 'circle',
					                                    symbolSize: 10,
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_last_p_credit
					                                },
					                                {
					                                    name: '{{trans("app.current year all problem credits")}}',
					                                    type: 'line',
                                                        symbol: 'circle',
					                                    symbolSize: 10,
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_current_p_credit
					                                },
                                                    {
                                                        name: '{{trans("app.last year percent of problem loans")}}',
                                                        type: 'line',
                                                        symbol: 'triangle',
                                                        symbolSize: 15,
                                                        yAxisIndex: 1,
                                                        data: data_last_percent_loan_problem,
                                                    },
                                                    {
                                                        name: '{{trans("app.current year percent of problem loans")}}',
                                                        type: 'line',
                                                        symbol: 'triangle',
                                                        symbolSize: 15,
                                                        yAxisIndex: 1,
                                                        data: data_current_percent_loan_problem,
                                                    }
                                                    
					                            ]
                                            });
                                            var alive = myChartCreditsproblem._chartsViews;
                                            var alives = [];
                                            var symbol = null;
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }  
                                                if(alive[i].__model.option.symbol == 'square'){
                                                    var icon = $('.credits_problem ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-circle-o').addClass('fa-square');
                                                }
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.credits_problem ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                    
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ trans('app.monthly credit and problem credit title')."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    }
                                },
                                right: "5%"
                            },
                            grid: {
                                left: 60,
                                right: 60,
                                top: 100,
                                bottom: 50,
                                containerLabel: true
                            },
                            xAxis: [
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    show:true,
                                    interval: 0,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_current_monthyear
                              },
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    interval: 0,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                boundaryGap: false,
                                data: data_last_monthyear
                              }

                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisPointer: {
                                        label: {
                                            formatter: function(value){
                                                return value.value.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    },
                                    position: 'left'
                                },
                                {
                                    type: 'value',
                                    axisLabel: {
                                        show:true,
                                        interval: 0,
                                        formatter: function(value){
                                            return value+"%";
                                        
                                        }
                                    },
                                    axisPointer: {
                                        label: {
                                            formatter: function(value){
                                                return value.value.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    },
                                    splitLine:{
                                        show:false
                                    },
                                    position: 'right'
                                }
                            ],
                            series: [
                                {
                                    name: '{{trans("app.last year all credits")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: data_last_a_credit,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                {
                                    name: '{{trans("app.current year all credits")}}',
                                    type: 'line',
                                    smooth: true,
                                    data: data_current_a_credit,
                                    itemStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                {
                                    name: '{{trans("app.last year all problem credits")}}',
                                    type: 'line',
                                    symbol: 'circle',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: data_last_p_credit,
                                    areaStyle: {},
                                    itemStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                {
                                    name: '{{trans("app.current year all problem credits")}}',
                                    type: 'line',
                                    symbol: 'circle',
                                    smooth: true,
                                    data: data_current_p_credit,
                                    areaStyle: {},
                                    itemStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                {
                                    name: '{{trans("app.last year percent of problem loans")}}',
                                    type: 'line',
                                    symbol: 'triangle',
                                    symbolSize: 15,
                                    smooth: true,
                                    yAxisIndex: 1,
                                    data: data_last_percent_loan_problem,
                                    lineStyle: {
                                        type: 'dashed',
                                    },
                                    itemStyle: {
                                        color: '#24b314'
                                    }
                                },
                                {
                                    name: '{{trans("app.current year percent of problem loans")}}',
                                    type: 'line',
                                    symbol: 'triangle',
                                    symbolSize: 15,     
                                    smooth: true,
                                    yAxisIndex: 1,
                                    data: data_current_percent_loan_problem,
                                    lineStyle: {
                                        type: 'dashed',
                                    },
                                    itemStyle: {
                                        color: '#ecaf40'
                                    }
                                }
                            ]
                        };

                        myChartCreditsproblem.setOption(options);
                        setTimeout(function() { myChartCreditsproblem.resize() }, 200);
                    }
                    var child = $('.credits_problem ul').children().length;
                    if(!(child > 0)){
                        var legend = $('.credits_problem'+' ul');
                        legend.html('');
                        var length = myChartCreditsproblem._chartsViews.length;
                        var name_legend = 'null';
                        var uid = null;
                        var symbol = null;
                        var font_size = null;
                        var top_icon = null;


                        for(var i = 0; i < length; i++){
                            uid = myChartCreditsproblem._chartsViews[i].__model.name;
                            uid = uid.replace(/ /g,'');
                            if(myChartCreditsproblem._chartsViews[i].__model.option.symbol == 'triangle'){
                                symbol = 'caret-up';
                                font_size = 25;
                                top_icon = 10;
                            }else if(myChartCreditsproblem._chartsViews[i].__model.option.symbol == 'emptyCircle'){
                                symbol = 'circle-o';
                            }else{
                                symbol = myChartCreditsproblem._chartsViews[i].__model.option.symbol;
                            }
                            name_legend = "<li data-uid='"+ uid +"' style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-"+symbol+"' style='margin-right: 5px; margin-top:"+top_icon+"px; font-size:"+font_size+"px; color: "+ myChartCreditsproblem._chartsViews[i].__model.option.itemStyle.color +"'></i>"
                            + myChartCreditsproblem._chartsViews[i].__model.name +"</li>";
                            legend.append(name_legend);
                        }
                    }
                    
                };
                
                function passives_deposit() {
                    var parent = $('#passives_deposit').children().length;

                    if ($('#passives_deposit').length && !(parent>0)) {
                        myChartPassives = echarts.init(document.getElementById('passives_deposit'));

                        var options = {
                            tooltip: {
                                trigger: 'item',
                                axisPointer: {
                                    type: 'cross'
                                },
                                formatter: function(params){
                                    bgcolor = params.color;
                                    if(params.seriesIndex == 0 || params.seriesIndex == 2){
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            }
                                        ];
                                    }else{
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            }
                                        ];
                                    }
                                    
                                    myChartPassives.setOption({
                                        tooltip: {
                                            backgroundColor:bgcolor
                                        },
                                        xAxis: showing_month
                                    });
                                    var data = params.value*1000000000;
                                    var name = params.seriesName +"  "+  data.toLocaleString(undefined, {minimumFractionDigits: 2});
                                    return name;
                                    
                                },

                            },
                            legend: {
                                show: false
                            },
                            toolbox: {
                                feature: {
                                    myTool2: {
                                        show: true,
                                        title: '{{ trans('app.to see line chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/line-chart.png') }}',
                                        onclick: function(){
                                            myChartPassives.setOption({
                                                xAxis: [
                                                  {
                                                    boundaryGap: false
                                                  },
                                                  {
                                                    boundaryGap: false
                                                  }
                                                ],
                                                series: [
                                                    {
                                                        name: '{{trans("app.last year all passive")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        symbolSize: 4,
                                                        data: data_last_actives,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        } 
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all passive")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbolSize: 4,
                                                        data: data_current_actives,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.last year all deposit")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        symbolSize: 4,
                                                        data: data_last_deposit,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all deposit")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbolSize: 4,
                                                        data: data_current_deposit,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    }
                                                ]
                                            });

                                                var alive = myChartPassives._chartsViews;
                                                var alives = [];
                                                for (var i = 0; i < alive.length; i++) {
                                                    if(alive[i].__alive == false){
                                                        alives.push(alive[i].__model.name.replace(/ /g,''));
                                                    }
                                                    if(alive[i].__model.option.symbol == 'emptyCircle'){
                                                        var icon = $('.credits_actives ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                        icon.removeClass('fa-square').addClass('fa-circle-o');
                                                    }  
                                                }
                                                for(var i=0; i < alives.length; i++)
                                                {
                                                    var child_legend = $('.credits_actives ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                                }
                                        }
                                    },
                                	myTool1: {
                                        show: true,
                                        title: '{{ trans('app.to see bar chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/bar-chart.png') }}',
                                        onclick: function(){
                                            myChartPassives.setOption({
                                            	xAxis: [
					                              {
					                                boundaryGap: true
					                              },
					                              {
					                                boundaryGap: true
					                              }
					                            ],
                                            	series: [
					                                {
					                                    name: '{{trans("app.last year all passive")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    symbolSize: 4,
					                                    barWidth: 20,
					                                    itemStyle: {
					                                    	color: "rgba(239,62,54, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_last_actives 
					                                },
					                                {
					                                    name: '{{trans("app.current year all passive")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    symbolSize: 4,
					                                    barWidth: 20,
					                                    itemStyle: {
					                                    	color: "rgba(51,108,251, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_current_actives
					                                },
					                                {
					                                    name: '{{trans("app.last year all deposit")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    symbolSize: 10,
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_last_deposit
					                                },
					                                {
					                                    name: '{{trans("app.current year all deposit")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    symbolSize: 10,
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_current_deposit
					                                }
					                            ]
                                            });
                                            var alive = myChartPassives._chartsViews;
                                            var alives = [];
                                            var symbol = null;
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }  
                                                if(alive[i].__model.option.symbol == 'square'){
                                                    var icon = $('.credits_actives ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-circle-o').addClass('fa-square');
                                                }
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.credits_actives ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                    
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ trans('app.monthly passive and deposit title')."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    }
                                },
                                right: "5%"
                            },
                            grid: {
                                left: 60,
                                right: 30,
                                top: 100,
                                bottom: 50,
                                containerLabel: true
                            },
                            xAxis: [
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    show: true,
                                    interval: 0,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_current_monthyear
                              },
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    show: true,
                                    interval: 0,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_last_monthyear
                              }

                            ],
                            yAxis: [
                                {
                                    
                                    type: 'value',
                                    axisPointer: {
                                        label: {
                                            formatter: function(value){
                                                return value.value.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    }
                                }
                            ],
                            series: [
                                {
                                    name: '{{trans("app.last year all passive")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: data_last_actives,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    } 
                                },
                                {
                                    name: '{{trans("app.current year all passive")}}',
                                    type: 'line',
                                    smooth: true,
                                    data: data_current_actives,
                                    itemStyle: {
                                        color: '#336cfb'
                                    }
                                    
                                },
                                {
                                    name: '{{trans("app.last year all deposit")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    symbol: 'circle',
                                    smooth: true,
                                    data: data_last_deposit,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    },
                                    areaStyle: {}
                                },
                                {
                                    name: '{{trans("app.current year all deposit")}}',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'circle',
                                    data: data_current_deposit,
                                    itemStyle: {
                                       color: '#336cfb' 
                                   },
                                    areaStyle: {}
                                }
                            ]
                        };

                        myChartPassives.setOption(options);
                        setTimeout(function() { myChartPassives.resize() }, 200);
                    }

                    var child = $('.passives_deposit ul').children().length;
                    if(!(child > 0)){
                        var legend = $('.passives_deposit'+' ul');
                        legend.html('');
                        var length = myChartPassives._chartsViews.length;
                        var name_legend = 'null';
                        var uid = null;
                        var symbol = null;
                        
                        for(var i = 0; i < length; i++){
                            uid = myChartPassives._chartsViews[i].__model.name;
                            uid = uid.replace(/ /g,'');
                            if(myChartPassives._chartsViews[i].__model.option.symbol == 'emptyCircle'){
                                symbol = 'circle-o';
                            }else{
                                symbol = myChartPassives._chartsViews[i].__model.option.symbol;
                            }
                            name_legend = "<li data-uid='"+ uid +"' style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-"+symbol+"' style='margin-right: 5px; color: "+ myChartPassives._chartsViews[i].__model.option.itemStyle.color +"'></i>"
                            + myChartPassives._chartsViews[i].__model.name +"</li>";
                            legend.append(name_legend);
                        }
                    }
                };

                function deposit_pdeposit() {
                    var parent = $('#deposit_pdeposit').children().length;

                    if ($('#deposit_pdeposit').length && !(parent>0)) {
                        
                        myChartDepositPd= echarts.init(document.getElementById('deposit_pdeposit'));
                        var options = {
                            tooltip: {
                                trigger: 'item',
                                axisPointer: {
                                    type: 'cross'
                                },
                                formatter: function(params){
                                    bgcolor = params.color;
                                    if(params.seriesIndex == 0 || params.seriesIndex == 2){
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            }
                                        ];
                                    }else{
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            }
                                        ];
                                    }
                                    
                                    myChartDepositPd.setOption({
                                        tooltip: {
                                            backgroundColor:bgcolor
                                        },
                                        xAxis: showing_month
                                    });
                                    var data = params.value*1000000000;
                                    var name = params.seriesName +"  "+  data.toLocaleString(undefined, {minimumFractionDigits: 2});
                                    return name;
                                },

                            },
                            legend: {
                                show: false
                            },
                            toolbox: {
                                feature: {
                                    myTool2: {
                                        show: true,
                                        title: '{{ trans('app.to see line chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/line-chart.png') }}',
                                        onclick: function(){
                                            myChartDepositPd.setOption({
                                                xAxis: [
                                                  {
                                                    boundaryGap: false
                                                  },
                                                  {
                                                    boundaryGap: false
                                                  }
                                                ],
                                                series: [
                                                    {
                                                        name: '{{trans("app.last year all deposit")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        data: data_last_deposit,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        } 
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all deposit")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        data: data_current_deposit,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        }

                                                    },
                                                    {
                                                        name: '{{trans("app.last year all people deposit")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle',
                                                        xAxisIndex: 1,
                                                        data: data_last_p_deposit,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all people deposit")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle',
                                                        data: data_current_p_deposit,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    }
                                                ]
                                            });

                                            var alive = myChartDepositPd._chartsViews;
                                            var alives = [];
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }
                                                if(alive[i].__model.option.symbol == 'emptyCircle'){
                                                    var icon = $('.deposit_pdeposit ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-square').addClass('fa-circle-o');
                                                }  
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.deposit_pdeposit ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                	myTool1: {
                                        show: true,
                                        title: '{{ trans('app.to see bar chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/bar-chart.png') }}',
                                        onclick: function(){
                                            myChartDepositPd.setOption({
                                            	xAxis: [
					                              {
					                                boundaryGap: true
					                              },
					                              {
					                                boundaryGap: true
					                              }
					                            ],
                                            	series: [
					                                {
					                                    name: '{{trans("app.last year all deposit")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    itemStyle: {
					                                    	color: "rgba(239,62,54, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_last_deposit 
					                                },
					                                {
					                                    name: '{{trans("app.current year all deposit")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    itemStyle: {
					                                    	color: "rgba(51,108,251, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_current_deposit
					                                },
					                                {
					                                    name: '{{trans("app.last year all people deposit")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_last_p_deposit
					                                },
					                                {
					                                    name: '{{trans("app.current year all people deposit")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_current_p_deposit
					                                }
					                            ]
                                            });

                                            var alive = myChartDepositPd._chartsViews;
                                            var alives = [];
                                            var symbol = null;
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }  
                                                if(alive[i].__model.option.symbol == 'square'){
                                                    var icon = $('.deposit_pdeposit ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-circle-o').addClass('fa-square');
                                                }
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.deposit_pdeposit ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }

                                        }
                                    },
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ trans('app.monthly deposit and people deposit title')."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    }
                                },
                                right: "5%"
                            },
                            grid: {
                                left: 60,
                                right: 30,
                                top: 80,
                                bottom: 50
                            },
                            xAxis: [
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    interval: 0,
                                    show: true,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_current_monthyear
                              },
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    show: true,
                                    interval: 0,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_last_monthyear
                              }

                            ],
                            yAxis: [
                                {
                                    
                                    type: 'value',
                                    axisPointer: {
                                        label: {
                                            formatter: function(value){
                                                return value.value.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    }
                                }
                            ],
                            series: [
                                {
                                    name: '{{trans("app.last year all deposit")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: data_last_deposit,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    } 
                                },
                                {
                                    name: '{{trans("app.current year all deposit")}}',
                                    type: 'line',
                                    smooth: true,
                                    data: data_current_deposit,
                                    itemStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                {
                                    name: '{{trans("app.last year all people deposit")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    symbol: 'circle',
                                    smooth: true,
                                    data: data_last_p_deposit,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    },
                                    areaStyle: {}
                                },
                                {
                                    name: '{{trans("app.current year all people deposit")}}',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'circle',
                                    data: data_current_p_deposit,
                                    itemStyle: {
                                        color: '#336cfb'
                                    },
                                    areaStyle: {}
                                }
                            ]
                        };

                        myChartDepositPd.setOption(options);
                        setTimeout(function() { myChartDepositPd.resize() }, 200);
                    }

                    var child = $('.deposit_pdeposit ul').children().length;
                    if(!(child > 0)){
                        var legend = $('.deposit_pdeposit'+' ul');
                        legend.html('');
                        var length = myChartDepositPd._chartsViews.length;
                        var name_legend = 'null';
                        var uid = null;
                        var symbol = null;
                        
                        for(var i = 0; i < length; i++){
                            uid = myChartDepositPd._chartsViews[i].__model.name;
                            uid = uid.replace(/ /g,'');
                            if(myChartDepositPd._chartsViews[i].__model.option.symbol == 'emptyCircle'){
                                symbol = 'circle-o';
                            }else{
                                symbol = myChartDepositPd._chartsViews[i].__model.option.symbol;
                            }
                            name_legend = "<li data-uid='"+ uid +"' style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-"+symbol+"' style='margin-right: 5px; color: "+ myChartDepositPd._chartsViews[i].__model.option.itemStyle.color +"'></i>"
                            + myChartDepositPd._chartsViews[i].__model.name +"</li>";
                            legend.append(name_legend);
                        }
                    }
                };

                function actives_likvids() {
                    var parent = $('#actives_likvids').children().length;

                    if ($('#actives_likvids').length && !(parent>0)) {
                        myChartActivesLikv = echarts.init(document.getElementById('actives_likvids'));

                        var options = {
                            tooltip: {
                                trigger: 'item',
                                axisPointer: {
                                    type: 'cross'
                                },
                                formatter: function(params){
                                    bgcolor = params.color;
                                    if(params.seriesIndex == 0 || params.seriesIndex == 2){
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            }
                                        ];
                                    }else{
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            }
                                        ];
                                    }
                                    
                                    myChartActivesLikv.setOption({
                                        tooltip: {
                                            backgroundColor:bgcolor
                                        },
                                        xAxis: showing_month
                                    });
                                    var data = params.value*1000000000;
                                    var name = params.seriesName +"  "+  data.toLocaleString(undefined, {minimumFractionDigits: 2});
                                    return name;
                                    
                                },

                            },
                            legend: {
                                show: false
                            },
                            toolbox: {
                                feature: {
                                    myTool2: {
                                        show: true,
                                        title: '{{ trans('app.to see line chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/line-chart.png') }}',
                                        onclick: function(){
                                            myChartActivesLikv.setOption({
                                                xAxis: [
                                                  {
                                                    boundaryGap: false
                                                  },
                                                  {
                                                    boundaryGap: false
                                                  }
                                                ],
                                                series: [
                                                    {
                                                        name: '{{trans("app.last year all actives")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        data: data_last_actives,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        }  
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all actives")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        data: data_current_actives,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.last year all likvid actives")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        symbol: 'circle',
                                                        data: data_last_a_likvid,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        }, 
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.current year all likvid actives")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle',
                                                        data: data_current_a_likvid,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    }
                                                ]
                                            });
                                            var alive = myChartActivesLikv._chartsViews;
                                            var alives = [];
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }
                                                if(alive[i].__model.option.symbol == 'emptyCircle'){
                                                    var icon = $('.actives_likvids ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-square').addClass('fa-circle-o');
                                                }  
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.actives_likvids ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                	myTool1: {
                                        show: true,
                                        title: '{{ trans('app.to see bar chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/bar-chart.png') }}',
                                        onclick: function(){
                                            myChartActivesLikv.setOption({
                                            	xAxis: [
					                              {
					                                boundaryGap: true
					                              },
					                              {
					                                boundaryGap: true
					                              }
					                            ],
                                            	series: [
					                                {
					                                    name: '{{trans("app.last year all actives")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    itemStyle: {
					                                    	color: "rgba(239,62,54, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_last_actives 
					                                },
					                                {
					                                    name: '{{trans("app.current year all actives")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    itemStyle: {
					                                    	color: "rgba(51,108,251, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_current_actives
					                                },
					                                {
					                                    name: '{{trans("app.last year all likvid actives")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_last_a_likvid
					                                },
					                                {
					                                    name: '{{trans("app.current year all likvid actives")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_current_a_likvid
					                                }
					                            ]
                                            });
                                            var alive = myChartActivesLikv._chartsViews;
                                            var alives = [];
                                            var symbol = null;
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }  
                                                if(alive[i].__model.option.symbol == 'square'){
                                                    var icon = $('.actives_likvids ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-circle-o').addClass('fa-square');
                                                }
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.actives_likvids ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                    
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ trans('app.monthly actives and likvid actives title')."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    }
                                },
                                right: "5%"
                            },
                            grid: {
                                left: 60,
                                right: 30,
                                top: 80,
                                bottom: 50
                            },
                            xAxis: [
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    interval: 0,
                                    show: true,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_current_monthyear
                              },
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    interval: 0,
                                    show: true,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_last_monthyear
                              }

                            ],
                            yAxis: [
                                {
                                    
                                    type: 'value',
                                    axisPointer: {
                                        label: {
                                            formatter: function(value){
                                                return value.value.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    }
                                }
                            ],
                            series: [
                                {
                                    name: '{{trans("app.last year all actives")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: data_last_actives,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    } 
                                },
                                {
                                    name: '{{trans("app.current year all actives")}}',
                                    type: 'line',
                                    smooth: true,
                                    data: data_current_actives,
                                    itemStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                {
                                    name: '{{trans("app.last year all likvid actives")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    symbol: 'circle',
                                    data: data_last_a_likvid,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    } ,
                                    areaStyle: {}
                                },
                                {
                                    name: '{{trans("app.current year all likvid actives")}}',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'circle',
                                    data: data_current_a_likvid,
                                    itemStyle: {
                                        color: '#336cfb'
                                    },
                                    areaStyle: {}
                                }
                            ]
                        };

                        myChartActivesLikv.setOption(options);
                        setTimeout(function() { myChartActivesLikv.resize() }, 200);
                    }
                    var child = $('.actives_likvids ul').children().length;
                    if(!(child > 0)){
                        var legend = $('.actives_likvids'+' ul');
                        legend.html('');
                        var length = myChartActivesLikv._chartsViews.length;
                        var name_legend = 'null';
                        var uid = null;
                        var symbol = null;
                        
                        for(var i = 0; i < length; i++){
                            uid = myChartActivesLikv._chartsViews[i].__model.name;
                            uid = uid.replace(/ /g,'');
                            if(myChartActivesLikv._chartsViews[i].__model.option.symbol == 'emptyCircle'){
                                symbol = 'circle-o';
                            }else{
                                symbol = myChartActivesLikv._chartsViews[i].__model.option.symbol;
                            }
                            name_legend = "<li data-uid='"+ uid +"' style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-"+symbol+"' style='margin-right: 5px; color: "+ myChartActivesLikv._chartsViews[i].__model.option.itemStyle.color +"'></i>"
                            + myChartActivesLikv._chartsViews[i].__model.name +"</li>";
                            legend.append(name_legend);
                        }
                    }
                };

                function income_expense() {
                    var parent = $('#income_expense').children().length;

                    if ($('#income_expense').length && !(parent>0)) {
                        myChartIncome = echarts.init(document.getElementById('income_expense'));
                        var options = {
                            tooltip: {
                                trigger: 'item',
                                axisPointer: {
                                    type: 'cross'
                                },
                                formatter: function(params){
                                    bgcolor = params.color;
                                    if(params.seriesIndex == 0 || params.seriesIndex == 2){
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            }
                                        ];
                                    }else{
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            }
                                        ];
                                    }
                                    
                                    myChartIncome.setOption({
                                        tooltip: {
                                            backgroundColor:bgcolor
                                        },
                                        xAxis: showing_month
                                    });

                                    var data = params.value*1000000000;
                                    var name = params.seriesName +"  "+  data.toLocaleString(undefined, {minimumFractionDigits: 2});
                                    return name;
                                },

                            },
                            legend: {
                                show: false
                            },
                            toolbox: {
                                feature: {
                                    myTool2: {
                                        show: true,
                                        title: '{{ trans('app.to see line chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/line-chart.png') }}',
                                        onclick: function(){
                                            myChartIncome.setOption({
                                                xAxis: [
                                                  {
                                                    boundaryGap: false
                                                  },
                                                  {
                                                    boundaryGap: false
                                                  }
                                                ],
                                                series: [
                                                    {
                                                        name: '{{trans("app.last year incomes")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        data: data_last_income,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        }  
                                                    },
                                                    {
                                                        name: '{{trans("app.current year incomes")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        data: data_current_income,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.last year expense")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        symbol: 'circle',
                                                        data: data_last_expense,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        }, 
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.current year expense")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle',
                                                        data: data_current_expense,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    }
                                                ]
                                            });
                                            var alive = myChartIncome._chartsViews;
                                            var alives = [];
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }
                                                if(alive[i].__model.option.symbol == 'emptyCircle'){
                                                    var icon = $('.income_expense ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-square').addClass('fa-circle-o');
                                                }  
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.income_expense ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                	myTool1: {
                                        show: true,
                                        title: '{{ trans('app.to see bar chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/bar-chart.png') }}',
                                        onclick: function(){
                                            myChartIncome.setOption({
                                            	xAxis: [
					                              {
					                                boundaryGap: true
					                              },
					                              {
					                                boundaryGap: true
					                              }
					                            ],
                                            	series: [
					                                {
					                                    name: '{{trans("app.last year incomes")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    itemStyle: {
					                                    	color: "rgba(239,62,54, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_last_income 
					                                },
					                                {
					                                    name: '{{trans("app.current year incomes")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    itemStyle: {
					                                    	color: "rgba(51,108,251, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_current_income
					                                },
					                                {
					                                    name: '{{trans("app.last year expense")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_last_expense
					                                },
					                                {
					                                    name: '{{trans("app.current year expense")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_current_expense
					                                }
					                            ]
                                            });
                                            var alive = myChartIncome._chartsViews;
                                            var alives = [];
                                            var symbol = null;
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }  
                                                if(alive[i].__model.option.symbol == 'square'){
                                                    var icon = $('.income_expense ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-circle-o').addClass('fa-square');
                                                }
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.income_expense ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                    
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ trans('app.monthly income and expense title')."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    }
                                },
                                right: "5%"
                            },
                            grid: {
                                left: 60,
                                right: 30,
                                top: 80,
                                bottom: 50
                            },
                            xAxis: [
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    interval: 0,
                                    show: true,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_current_monthyear
                              },
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    interval: 0,
                                    show: true,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_last_monthyear
                              }

                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisPointer: {
                                        label: {
                                            formatter: function(value){
                                                return value.value.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    }
                                }
                            ],
                            series: [
                                {
                                    name: '{{trans("app.last year incomes")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: data_last_income,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    }  
                                },
                                {
                                    name: '{{trans("app.current year incomes")}}',
                                    type: 'line',
                                    smooth: true,
                                    data: data_current_income,
                                    itemStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                {
                                    name: '{{trans("app.last year expense")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    symbol: 'circle',
                                    data: data_last_expense,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    }, 
                                    areaStyle: {}
                                },
                                {
                                    name: '{{trans("app.current year expense")}}',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'circle',
                                    data: data_current_expense,
                                    itemStyle: {
                                        color: '#336cfb'
                                    },
                                    areaStyle: {}
                                }
                            ]
                        };

                        myChartIncome.setOption(options);
                        setTimeout(function() { myChartIncome.resize() }, 200);
                    }

                    var child = $('.income_expense ul').children().length;
                    if(!(child > 0)){
                        var legend = $('.income_expense'+' ul');
                        legend.html('');
                        var length = myChartIncome._chartsViews.length;
                        var name_legend = 'null';
                        var uid = null;
                        var symbol = null;
                        
                        for(var i = 0; i < length; i++){
                            uid = myChartIncome._chartsViews[i].__model.name;
                            uid = uid.replace(/ /g,'');
                            if(myChartIncome._chartsViews[i].__model.option.symbol == 'emptyCircle'){
                                symbol = 'circle-o';
                            }else{
                                symbol = myChartIncome._chartsViews[i].__model.option.symbol;
                            }
                            name_legend = "<li data-uid='"+ uid +"' style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-"+symbol+"' style='margin-right: 5px; color: "+ myChartIncome._chartsViews[i].__model.option.itemStyle.color +"'></i>"
                            + myChartIncome._chartsViews[i].__model.name +"</li>";
                            legend.append(name_legend);
                        }
                    }
                };

                function kirim_chiqim() {
                    var parent = $('#kirim_chiqim').children().length;

                    if ($('#kirim_chiqim').length && !(parent>0)) {
                        myChartKirim = echarts.init(document.getElementById('kirim_chiqim'));

                        var options = {
                            tooltip: {
                                trigger: 'item',
                                axisPointer: {
                                    type: 'cross'
                                },
                                formatter: function(params){
                                    bgcolor = params.color;
                                    if(params.seriesIndex == 0 || params.seriesIndex == 2){
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            }
                                        ];
                                    }else{
                                        var showing_month = [
                                            {
                                                axisPointer: {
                                                    show: true
                                                }
                                            },
                                            {
                                                axisPointer: {
                                                    show: false
                                                }
                                            }
                                        ];
                                    }
                                    
                                    myChartKirim.setOption({
                                        tooltip: {
                                            backgroundColor:bgcolor
                                        },
                                        xAxis: showing_month
                                    });

                                    var data = params.value*1000000000;
                                    var name = params.seriesName +"  "+  data.toLocaleString(undefined, {minimumFractionDigits: 2});
                                    return name;
                                },

                            },
                            legend: {
                                show: false
                            },
                            toolbox: {
                                feature: {
                                    myTool2: {
                                        show: true,
                                        title: '{{ trans('app.to see line chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/line-chart.png') }}',
                                        onclick: function(){
                                            myChartKirim.setOption({
                                                xAxis: [
                                                  {
                                                    boundaryGap: false
                                                  },
                                                  {
                                                    boundaryGap: false
                                                  }
                                                ],
                                                series: [
                                                    {
                                                        name: '{{trans("app.last year kirim")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        },
                                                        data: data_last_kirim 
                                                    },
                                                    {
                                                        name: '{{trans("app.current year kirim")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbolSize: 4,
                                                        data: data_current_kirim,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.last year chiqim")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        xAxisIndex: 1,
                                                        symbol: 'circle',
                                                        data: data_last_chiqim,
                                                        itemStyle: {
                                                            color: '#ef3e36'
                                                        }, 
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    },
                                                    {
                                                        name: '{{trans("app.current year chiqim")}}',
                                                        type: 'line',
                                                        smooth: true,
                                                        symbol: 'circle',
                                                        data: data_current_chiqim,
                                                        itemStyle: {
                                                            color: '#336cfb'
                                                        },
                                                        areaStyle: {
                                                            color: null
                                                        }
                                                    }
                                                ]
                                            });
                                            var alive = myChartKirim._chartsViews;
                                            var alives = [];
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }
                                                if(alive[i].__model.option.symbol == 'emptyCircle'){
                                                    var icon = $('.kirim_chiqim ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-square').addClass('fa-circle-o');
                                                }  
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.kirim_chiqim ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                	myTool1: {
                                        show: true,
                                        title: '{{ trans('app.to see bar chart') }}',
                                        icon: 'image://{{ URL::asset('assets/img/bar-chart.png') }}',
                                        onclick: function(){
                                            myChartKirim.setOption({
                                            	xAxis: [
					                              {
					                                boundaryGap: true
					                              },
					                              {
					                                boundaryGap: true
					                              }
					                            ],
                                            	series: [
					                                {
					                                    name: '{{trans("app.last year kirim")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    itemStyle: {
					                                    	color: "rgba(239,62,54, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_last_kirim 
					                                },
					                                {
					                                    name: '{{trans("app.current year kirim")}}',
					                                    type: 'bar',
					                                    smooth: true,
                                                        symbol: 'square',
					                                    itemStyle: {
					                                    	color: "rgba(51,108,251, 0.8)"
					                                    },
					                                    large: true,
					                                    data: data_current_kirim
					                                },
					                                {
					                                    name: '{{trans("app.last year chiqim")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_last_chiqim
					                                },
					                                {
					                                    name: '{{trans("app.current year chiqim")}}',
					                                    type: 'line',
					                                    smooth: true,
                                                        symbol: 'circle',
					                                    areaStyle: {
					                                    	color: "rgba(255, 255, 255, 0)"
					                                    },
					                                    data: data_current_chiqim
					                                }
					                            ]
                                            });
                                            var alive = myChartKirim._chartsViews;
                                            var alives = [];
                                            var symbol = null;
                                            for (var i = 0; i < alive.length; i++) {
                                                if(alive[i].__alive == false){
                                                    alives.push(alive[i].__model.name.replace(/ /g,''));
                                                }  
                                                if(alive[i].__model.option.symbol == 'square'){
                                                    var icon = $('.kirim_chiqim ul li[data-uid="'+alive[i].__model.name.replace(/ /g,'')+'"] i');
                                                    icon.removeClass('fa-circle-o').addClass('fa-square');
                                                }
                                            }
                                            for(var i=0; i < alives.length; i++)
                                            {
                                                var child_legend = $('.kirim_chiqim ul li[data-uid="'+alives[i]+'"]').addClass('strike');
                                            }
                                        }
                                    },
                                    
                                    saveAsImage: {
                                        title: '{{ trans('app.save chart as image') }}',
                                        name: '{{ trans('app.monthly kirim and chiqim title')."-".trans('app.month'.intval(date('m')))."-".date('Y') }}',
                                        icon: 'image://{{ URL::asset('assets/img/download.png') }}'
                                    }
                                },
                                right: "5%"
                            },
                            grid: {
                                left: 60,
                                right: 30,
                                top: 80,
                                bottom: 50
                            },
                            xAxis: [
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    interval: 0,
                                    show:  true,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_current_monthyear
                              },
                              {
                                type: 'category',
                                axisLine: {
                                    onZero: false,
                                    lineStyle: {
                                        color: '#ef3e36'
                                    }
                                },
                                boundaryGap: false,
                                splitLine: {
                                    show: true
                                },
                                axisLabel: {
                                    interval: 0,
                                    show: true,
                                    formatter: function(value){
                                        var width = $( window ).width();
                                        if(width < 1400){
                                            return value.substr(0,3) +'\n'+ value.substr(3); 
                                        }else{
                                            return value;
                                        }
                                       
                                    }
                                },
                                data: data_last_monthyear
                              }

                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisPointer: {
                                        label: {
                                            formatter: function(value){
                                                return value.value.toLocaleString(undefined, {minimumFractionDigits: 2});
                                            }
                                        }
                                    }
                                }
                            ],
                            series: [
                                {
                                    name: '{{trans("app.last year kirim")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    data: data_last_kirim,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    }  
                                },
                                {
                                    name: '{{trans("app.current year kirim")}}',
                                    type: 'line',
                                    smooth: true,
                                    data: data_current_kirim,
                                    itemStyle: {
                                        color: '#336cfb'
                                    }
                                },
                                {
                                    name: '{{trans("app.last year chiqim")}}',
                                    type: 'line',
                                    xAxisIndex: 1,
                                    smooth: true,
                                    symbol: 'circle',
                                    data: data_last_chiqim,
                                    itemStyle: {
                                        color: '#ef3e36'
                                    }, 
                                    areaStyle: {}
                                },
                                {
                                    name: '{{trans("app.current year chiqim")}}',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'circle',
                                    data: data_current_chiqim,
                                    itemStyle: {
                                        color: '#336cfb'
                                    },
                                    areaStyle: {}
                                }
                            ]
                        };

                        myChartKirim.setOption(options);
                        setTimeout(function() { myChartKirim.resize() }, 200);
                    }

                    var child = $('.kirim_chiqim ul').children().length;
                    if(!(child > 0)){
                        var legend = $('.kirim_chiqim'+' ul');
                        legend.html('');
                        var length = myChartKirim._chartsViews.length;
                        var name_legend = 'null';
                        var uid = null;
                        var symbol = null;
                        
                        for(var i = 0; i < length; i++){
                            uid = myChartKirim._chartsViews[i].__model.name;
                            uid = uid.replace(/ /g,'');
                            if(myChartKirim._chartsViews[i].__model.option.symbol == 'emptyCircle'){
                                symbol = 'circle-o';
                            }else{
                                symbol = myChartKirim._chartsViews[i].__model.option.symbol;
                            }
                            name_legend = "<li data-uid='"+ uid +"' style='list-item:none; cursor:pointer; display:inline-block; margin-right:10px'><i class='fa fa-"+symbol+"' style='margin-right: 5px; color: "+ myChartKirim._chartsViews[i].__model.option.itemStyle.color +"'></i>"
                            + myChartKirim._chartsViews[i].__model.name +"</li>";
                            legend.append(name_legend);
                        }
                    }
                };

                $('.nav-link').on('click', function(){
                    var url = $(this).attr('url');
                    if(url == 'credits_actives'){
                        credits_actives();
                    }else if(url == 'income_expense'){
                        income_expense();
                    }else if(url == 'actives_likvids'){
                        actives_likvids();
                    }else if(url == 'deposit_pdeposit'){
                        deposit_pdeposit();
                    }else if(url == 'passives_deposit'){
                        passives_deposit();
                    }else if(url == 'credits_problem'){
                        credits_problem();
                    }else if(url == 'kirim_chiqim'){
                        kirim_chiqim();
                    }
                    if(url == 'top_10_fillials'){
                        top_safe_credit_fillial();
                        top_problem_credit_fillial();
                    }else if(url == 'top_10_loan_species'){
                        top_safe_credit_goal();
                        top_problem_credit_goal();
                    }else if(url == 'top_10_activity_species'){
                        top_safe_credit_activity();
                        top_problem_credit_activity();
                    }
                })
                top_safe_credit_fillial();
                top_problem_credit_fillial();
                credits_actives();
                
                
            });
        </script>
@endsection
