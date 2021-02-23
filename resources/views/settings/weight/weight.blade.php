@extends('layouts.app')

@section('content')

<div class="content-w">
<div class="content-i">
<div class="content-box">
<div class="row justify-content-md-center">
<div class="col-lg-12">
    <div class="element-wrapper">
        <h6 class="element-header">{{ $title }}</h6>
        <div class="element-box">
            <form method="POST" action="/settings/weight/add">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-12">
                        <ul class="tree horizontal">
                            <li>
                                <div class="card" >
                                    <div class="card-header">
                                        {{ trans('app.Jami reyting uchun') }}
                                    </div>
                                </div>
                                <ul>
                                    <li>
                                        <div class="card">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class = "card-body-label">
                                                        <div class="form-group">
                                                            <input style="width: 70px" name="inspeksiya" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->inspeksiya:'' }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class = "card-body-label">
                                                        {{ trans('app.inspeksiya') }}
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <ul>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width:70px" name="i_out_of" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_out_of:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class = "card-body-label">{{ trans('app.problem loans') }} </label> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input style="width:70px" name="i_likvid_active" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_likvid_active:'' }}" />
                                                        </div>
                                                        <div class="col-md-9">
                                                            <label class = "card-body-label">{{ trans('app.liquidity assessment(Deposit and aktive)') }}</label> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input style="width:70px" name="i_likvid_credit" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_likvid_credit:'' }}" />
                                                        </div>
                                                        <div class="col-md-9">
                                                            <label class = "card-body-label">{{ trans('app.liquidity assessment(Deposit and kredit)') }}</label> 
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </li>

                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input style="width:70px" name="i_b_liability" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_b_liability:'' }}" />
                                                        </div>
                                                        <div class="col-md-9">
                                                            <label class = "card-body-label">{{ trans('app.bank liabilities(Population and total deposits)') }}</label> 
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </li>

                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <input style="width:70px" name="i_b_liability_demand" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_b_liability_demand:'' }}" />
                                                        </div>
                                                        <div class="col-md-10">
                                                            <label class = "card-body-label">{{ trans('app.bank liabilities(request and total deposits)') }}</label> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width:70px" name="i_net_profit" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_net_profit:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class = "card-body-label">{{ trans('app.Bank profitability') }}</label> 
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width:70px" name="i_active_likvid" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_active_likvid:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class = "card-body-label">{{ trans('app.Liquid assets') }}</label> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width:70px" name="i_income_expense" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_income_expense:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class = "card-body-label">{{ trans('app.Expenses and income') }} </label> 
                                                        </div>
                                                    </div>  
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input style="width:70px" name="i_work_lost" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_work_lost:'' }}" />
                                                        </div>
                                                        <div class="col-md-9">
                                                            <label class = "card-body-label">{{ trans('app.Zarar bilan ishlaydigan') }}</label> 
                                                        </div>
                                                    </div>  
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <div class="card">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class = "card-body-label">
                                                        <div class="form-group">
                                                            <input  style="width: 70px" name="business" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->business:'' }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class = "card-body-label">
                                                        {{ trans('app.business') }}
                                                    </div>   
                                                </div>
                                            </div>
                                        </div>
                                        <ul>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input style="width:70px" name="b_home" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_home:'' }}" />
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label class = "card-body-label">{{ trans('app.Uy joylar') }} </label> 
                                                        </div>
                                                    </div>  
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input style="width:70px" name="b_kontur" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_kontur:'' }}" />
                                                        </div>
                                                        <div class="col-md-6">
                                                                <label class = "card-body-label">{{ trans('app.2-Kontur') }} </label>
                                                        </div>
                                                    </div>     
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                    <div class="col-md-4">
                                                        <input style="width:70px" name="b_guarantee" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_guarantee:'' }}" />
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class = "card-body-label">{{ trans('app.b guarantee') }} </label>
                                                    </div> 
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input style="width:70px" name="b_m_report" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_m_report:'' }}" />
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label class = "card-body-label">{{ trans('app.b monthly report') }} </label>
                                                        </div>
                                                    </div>   
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                    <div class="col-md-5">
                                                        <input style="width:70px" name="b_execution" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_execution:'' }}" />
                                                    </div>
                                                    <div class="col-md-7">
                                                        <label class = "card-body-label">{{ trans('app.Ijro intizom') }} </label>
                                                    </div>
                                                    </div>   
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                    <div class="col-md-4">
                                                        <input style="width:70px" name="b_family" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_family:'' }}" />
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class = "card-body-label">{{ trans('app.b family') }}</label> 
                                                    </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                    <div class="col-md-4">
                                                        <input style="width:70px" name="b_past" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_past:'' }}" />
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class = "card-body-label">{{ trans('app.Otgan yilga nisbati') }}</label> 
                                                    </div>
                                                    </div> 
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <div class="card">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class = "card-body-label">
                                                        <div class="form-group">
                                                            <input style="width:70px"  name="cash" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash:'' }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class = "card-body-label1">
                                                        {{ trans('app.cash') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width:70px" name="cash_tushum" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash_tushum:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class = "card-body-label">{{ trans('app.cash tushum') }} </label>
                                                        </div>
                                                    </div>    
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width:70px" name="cash_qaytish" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash_qaytish:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class = "card-body-label">{{ trans('app.cash qaytish') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width:70px" name="cash_m_report" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash_m_report:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class = "card-body-label">{{ trans('app.Hisobot topshirilishi') }}</label>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5 card-body-label" >
                                                            <input style="width:70px" name="cash_execution" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash_execution:'' }}" />
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label class = "card-body-label">{{ trans('app.Ijro intizom') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li> 
                                        </ul>
                                    </li>
                                    <li>
                                        <div class="card">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class = "card-body-label">
                                                        <div class="form-group">
                                                            <input  style="width: 70px" name="currency" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->currency:'' }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body-label1">
                                                        {{ trans('app.currency') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width: 70px" name="c_check" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->c_check:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class = "card-body-label">{{ trans('app.Vash tekshiruv boyicha') }}</label>
                                                        </div>
                                                    </div>   
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input style="width: 70px" name="c_m_report" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->c_m_report:'' }}" />
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label class = "card-body-label">{{ trans('app.b monthly report') }} </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input style="width: 70px" name="c_execution" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->c_execution:'' }}" />
                                                
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label class = "card-body-label">{{ trans('app.Ijro intizom') }} </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input style="width: 70px" name="c_phone" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->c_phone:'' }}" />
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class="card-body-label">{{ trans('app.c phone') }} </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            
                                        </ul>
                                    </li>
                                    <li>
                                        <div class="card">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="card-body-label">
                                                        <div class="form-group">
                                                            <input style="width: 70px" name="ijro_head" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->ijro_head:'' }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="card-body-label">
                                                        {{ trans('app.ijro') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul>
                                            <li>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input style="width: 70px" name="ijro" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->ijro:'' }}" />
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label >{{ trans('app.ijro') }} </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li> 
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul> 
                        {{-- <div class="row">
                            <div class="col-12 col-md-10" style="margin: auto;">
                                <div class="card">
                                    <div class="card-header">
                                        {{ trans('app.Jami reyting uchun') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-header">
                                                {{ trans('app.inspeksiya') }}
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input name="inspeksiya" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->inspeksiya:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-header">
                                                {{ trans('app.business') }}
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input name="business" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->business:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-header">
                                                {{ trans('app.cash') }}
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input name="cash" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-header">
                                                {{ trans('app.currency') }}
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input name="currency" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->currency:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-header">
                                                {{ trans('app.ijro') }}
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input name="ijro_head" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->ijro_head:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-10" style="margin: auto;">
                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.problem loans') }} </label> 
                                                    <input name="i_out_of" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_out_of:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.liquidity assessment(Deposit and aktive)') }} </label> 
                                                    <input name="i_likvid_active" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_likvid_active:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.liquidity assessment(Deposit and kredit)') }} </label> 
                                                    <input name="i_likvid_credit" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_likvid_credit:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.bank liabilities(Population and total deposits)') }} </label> 
                                                    <input name="i_b_liability" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_b_liability:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.bank liabilities(request and total deposits)') }} </label> 
                                                    <input name="i_b_liability_demand" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_b_liability_demand:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Bank profitability') }} </label> 
                                                    <input name="i_net_profit" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_net_profit:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Liquid assets') }} </label> 
                                                    <input name="i_active_likvid" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_active_likvid:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Expenses and income') }} </label> 
                                                    <input name="i_income_expense" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_income_expense:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Zarar bilan ishlaydigan') }} </label> 
                                                    <input name="i_work_lost" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->i_work_lost:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.Uy joylar') }} </label> 
                                                    <input name="b_home" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_home:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.2-Kontur') }} </label> 
                                                    <input name="b_kontur" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_kontur:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.b guarantee') }} </label> 
                                                    <input name="b_guarantee" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_guarantee:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.b monthly report') }} </label> 
                                                    <input name="b_m_report" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_m_report:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Ijro intizom') }} </label> 
                                                    <input name="b_execution" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_execution:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.b family') }}</label> 
                                                    <input name="b_family" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_family:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Otgan yilga nisbati') }}</label> 
                                                    <input name="b_past" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->b_past:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.cash tushum') }} </label> 
                                                    <input name="cash_tushum" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash_tushum:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.cash qaytish') }} </label> 
                                                    <input name="cash_qaytish" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash_qaytish:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Hisobot topshirilishi') }} </label> 
                                                    <input name="cash_m_report" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash_m_report:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Ijro intizom') }} </label> 
                                                    <input name="cash_execution" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->cash_execution:'' }}" />
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.Vash tekshiruv boyicha') }} </label> 
                                                    <input name="c_check" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->c_check:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.b monthly report') }} </label> 
                                                    <input name="c_m_report" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->c_m_report:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.Ijro intizom') }} </label> 
                                                    <input name="c_execution" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->c_execution:'' }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label>{{ trans('app.c phone') }} </label> 
                                                    <input name="c_phone" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->c_phone:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>{{ trans('app.ijro') }} </label> 
                                                    <input name="ijro" class="form-control" type="text" placeholder="%" value="{{ !(empty($weight))?$weight->ijro:'' }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <label for=""></label>
                                <div class="form-group" style="    padding-left: 15%;
                                padding-top: 2%;">
                                    <label>{{ trans('app.Ozgarish sanasini kiriting') }} </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card">
                            <div class="card-body">
                                <label for=""></label>
                                <div class="form-group">
                                    <input type="hidden" name="id" value="{{ !(empty($weight))?$weight->id:'' }}">
                                    <input readonly="true" name="monthyear" class="form-control datepicker"  type="text" placeholder="" value="{{ !(empty($weight))?$weight->year.'-'.$weight->month:'' }}" />
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="card">
                            <div class="card-body">
                                <label for=""></label>
                                <div class="form-group">
                                    <button class="form-control btn btn-primary">{{ trans('app.save') }} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<script src="{{ URL::asset('/assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $('document').ready(function(){
        $('input.datepicker').datepicker({
            format:'yyyy-mm',
            startView: 'months',
            minViewMode: 'months',
            autoclose:1,
            startView:'1',
            endDate: new Date()
        });
    })
</script>
<style>
.card-header{
    padding: 10px;
    padding-top: 7px;
}
.form-group{
    margin-bottom: 16px!important;
}

h3 {
     text-align: center;
     padding: 0 10px;
}
.tree {
     margin: 18px;
     padding: 0;
}
.tree:not(:empty):before, .tree:not(:empty):after, .tree ul:not(:empty):before, .tree ul:not(:empty):after, .tree li:not(:empty):before, .tree li:not(:empty):after {
     display: block;
     position: absolute;
     content: "";
}
.tree ul, .tree li {
     position: relative;
     margin: 0;
     padding: 0;
}
.tree li {
     list-style: none;
}
.tree li > div {
     background-color: #fff;
     border: 1px solid #ecaf32;
     color: #222;
     /*padding: 5px;*/
     display: inline-block;
}

.tree.horizontal li {
     display: flex;
     align-items: center;
     /*margin-left: 24px;*/
     padding-left: 170px;
}
.tree.horizontal li div {
     margin: 3px 0;
}
.tree.horizontal li:before {
     border-left: 1px solid #ecaf32;
     height: 100%;
     width: 0;
     top: 0;
     left: 100px;
}
.tree.horizontal li:first-child:before {
     height: 50%;
     top: 50%;
}
.tree.horizontal li:last-child:before {
     height: 50%;
     bottom: 50%;
     top: auto;
}

.tree.horizontal li:after, .tree.horizontal li ul:after {
     border-top: 1px solid #ecaf32;
     height: 0;
     width: 70px;
     top: 50%;
     left: 100px;
}

.tree.horizontal li:only-child:before {
     content: none;
}

.tree.horizontal li ul:after {
     left: 0;

}

.tree.horizontal > li:only-child {
     margin-left: 0;
}

.tree.horizontal > li:only-child:before, .tree.horizontal > li:only-child:after {
     content: none;
}
.card-body-label{
    padding-top: 7px;
}
.card-body-label1{
    padding-top: 15px;
}

 
</style>
@endsection