@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $position = get_position($user);
@endphp
<div class="content-w height">
    <div class="content-i">
        <div class="content-box">
            <div class="element-wrapper">
                <h6 class="element-header">{{ $title }}</h6>
                <div class="element-box">
                    <div class="row justify-content-md-center">
                        <div class="col-12 col-md-8">
                            <div id="treeWeightofRating" class="chat-container" style="height: 600px"></div>
                        </div>
                    </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $('document').ready(function(){
        var myChart = echarts.init(document.getElementById('treeWeightofRating'));
        myChart.showLoading();
        var data = {
            "name": "{{ trans('app.total') }}",
            "value" :100,
            "children": [
                {
                    "name": "{{ trans('app.cash') }}",
                    "value": {{ $weight->cash }},
                    "children": [
                        {
                            "name": "{{ trans('app.cash tushum report') }}",
                            "value": {{ $weight->cash_tushum }}
                        },
                        {
                            "name": "{{ trans('app.cash qaytish report') }}",
                            "value": {{ $weight->cash_qaytish }}
                        },
                        {
                            "name": "{{ trans('app.cash monthly report') }}",
                            "value": {{ $weight->cash_m_report }}
                        },
                        {
                            "name": "{{ trans('app.cash execution report') }}",
                            "value": {{ $weight->cash_execution }}
                        }
                    ]
                },
                {
                    "name": "{{ trans('app.business') }}",
                    "value": {{ $weight->business }},
                    "children": [
                        {
                            "name": "{{ trans('app.business home report') }}",
                            "value": {{ $weight->b_home }}
                        },
                        {
                            "name": "{{ trans('app.business kontur report') }}",
                            "value": {{ $weight->b_kontur }}
                        },
                        {
                            "name": "{{ trans('app.business family report') }}",
                            "value": {{ $weight->b_family }}
                        },
                        {
                            "name": "{{ trans('app.business guarantee report') }}",
                            "value": {{ $weight->b_guarantee }}
                        },
                        {
                            "name": "{{ trans('app.business past report') }}",
                            "value": {{ $weight->b_past }}
                        },
                        {
                            "name": "{{ trans('app.business execution report') }}",
                            "value": {{ $weight->b_execution }}
                        },
                        {
                            "name": "{{ trans('app.business monthly report') }}",
                            "value": {{ $weight->b_m_report }}
                        }
                    ]
                },
                {
                    "name": "{{ trans('app.inspeksiya') }}",
                    "value": {{ $weight->inspeksiya }},
                    "children": [
                        {
                            "name": "{{ trans('app.inspeksiya outof report') }}",
                            "value": {{ $weight->i_out_of }}
                        },
                        {
                            "name": "{{ trans('app.inspeksiya worklost report') }}",
                            "value": {{ $weight->i_work_lost }}
                        },
                        {
                            "name": "{{ trans('app.inspeksiya likvidactive report') }}",
                            "value": {{ $weight->i_likvid_active }}
                        },
                        {
                            "name": "{{ trans('app.inspeksiya likvidcredit report') }}",
                            "value": {{ $weight->i_likvid_credit }}
                        },
                        {
                            "name": "{{ trans('app.inspeksiya liability report') }}",
                            "value": {{ $weight->i_b_liability }}
                        },
                        {
                            "name": "{{ trans('app.inspeksiya demand report') }}",
                            "value": {{ $weight->i_b_liability_demand }}
                        },
                        {
                            "name": "{{ trans('app.inspeksiya activelikvid report') }}",
                            "value": {{ $weight->i_active_likvid }}
                        },
                        {
                            "name": "{{ trans('app.inspeksiya incomeexpense report') }}",
                            "value": {{ $weight->i_income_expense }}
                        },
                        {
                            "name": "{{ trans('app.inspeksiya netprofit report') }}",
                            "value": {{ $weight->i_net_profit }}
                        },
                        {
                            "name": "{{ trans('app.i others') }}",
                            "value": {{ $weight->i_others }}
                        }
                    ]
                },
                {
                    "name": "{{ trans('app.currency') }}",
                    "value": {{ $weight->currency }},
                    "children": [
                        {
                            "name": "{{ trans('app.currency phone report') }}",
                            "value": {{ $weight->c_phone }}
                        },
                        {
                            "name": "{{ trans('app.currency vash report') }}",
                            "value": {{ $weight->c_check }}
                        },
                        {
                            "name": "{{ trans('app.currency execution report') }}",
                            "value": {{ $weight->c_execution }}
                        },
                        {
                            "name": "{{ trans('app.currency monthly report') }}",
                            "value": {{ $weight->c_m_report }}
                        }
                    ]
                },
                {
                    "name": "{{ trans('app.ijro') }}",
                    "value": {{ $weight->ijro_head }},
                    "children": [
                        {
                            "name": "{{ trans('app.ijro') }}",
                            "value": {{ $weight->ijro }}
                        }
                    ]
                }
            ]
        };
        myChart.hideLoading();

        myChart.setOption(option = {
            tooltip: {
                trigger: 'item',
                triggerOn: 'mousemove',
                formatter: function(params){
                    var name = params.data.name;
                    var value = params.value;
                    return value?name+": ["+value+"%]":name+": [0%]";
                }
            },

            grid: {
                left: 200
            },
            series:[
                {
                    type: 'tree',

                    name: 'tree1',

                    lineStyle: {color: '#ecaf32'},

                    data: [data],

                    top: '5%',
                    left: '12%',
                    bottom: '2%',
                    right: '60%',

                    symbolSize: 7,

                    label: {
                        position: 'left',
                        verticalAlign: 'middle',
                        formatter: function(params){
                            return params.data.value?"["+params.data.value+"%] "+params.data.name:"[0%] "+params.data.name;
                        },
                        fontSize: 16,
                        
                        align: 'right',
                        color: '#000',
                        fontFamily: '"Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                    },
                    itemStyle: {
                        borderColor: '#ecaf32'
                        // #f04942
                    },
                    leaves: {
                        label: {
                            position: 'right',
                            verticalAlign: 'middle',
                            align: 'left',
                            color: '#000',
                            fontFamily: '"Avenir Next W01", "Lato", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
                        }
                    },

                    expandAndCollapse: true,

                    animationDuration: 550,
                    animationDurationUpdate: 750

                }
            ]
        });
    })
</script>
<style type="text/css">
    

</style>
@endsection