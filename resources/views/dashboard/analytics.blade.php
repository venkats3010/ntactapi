    <?php
    $session = Session::all();
	//print_r($session['username']);//exit;
	$role = $session['auth']['data']['role'];
	$permissions = json_decode($session['auth']['data']['permissions']);
    $today = date('Y-m-d', strtotime(now()));
    //print_r($permissions);
    ?>
<?php
$userCount = $taskCount = $archiveCount = $docCount = 0;
if(isset($summary['status']) && $summary['status'] == 200){
    $userCount = $summary['userCount'];
    $taskCount = $summary['taskCount'];
    $archiveCount = $summary['archiveCount'];
    $docCount = $summary['docCount'];
}
?>

	
	<div class="col-lg-3 <?=($role == 2)?'d-none':''?>">	
		<div class="card">
		    <a href="{{ url('users') }}">
			<div class="card-body d-flex justify-content-between align-items-center pb-4" style="height:150px;">
				<div>
					<h5 class="card-title">Users </h5>
					<h2 class="card-text m-3" id="total_users"><?=$userCount?></h2>
				</div>
				<div class="theme-avtar bg-primary rounded-icon" style="background-color:#beedd4;">
					<span class="mdi mdi-account-multiple"></span>
				</div>
			</div>
			</a>
		</div>

	</div>

	<div class="col-lg-3">	
		<div class="card">
		    <a href="{{ url('profile/edit', ['tpurl' => 'mydesk']) }}">
			<div class="card-body d-flex justify-content-between align-items-center pb-4" style="height:150px;">
			<div>
				<h5 class="card-title">Profile</h5>
				<h2 class="card-text m-3" id="profile"></h2>
			</div>
			<div class="theme-avtar bg-info rounded-icon" style="background-color:#c1e9f7;">
				<span class="mdi mdi-account-multiple"></span>
			</div>
			</div>
			</a>
		</div>
	</div>

	<div class="col-lg-3">	
		<div class="card">
		    <a href="{{ url('/tasks') }}">
			<div class="card-body d-flex justify-content-between align-items-center pb-4" style="height:150px;">
			<div>
				<h5 class="card-title">Tasks</h5>
				<h2 class="card-text m-3" id="my_tasks"><?=$taskCount?></h2>
			</div>
			<div class="theme-avtar bg-danger rounded-icon" style="background-color:#ffe2c7;">
				<span class="mdi mdi-account-multiple"></span>
			</div>
			</div>
			</a>
		</div>
	</div>

	<div class="col-lg-3">	
		<div class="card">
		    <a href="{{ url('/document') }}">
			<div class="card-body d-flex justify-content-between align-items-center pb-4" style="height:150px;">
			<div>
				<h5 class="card-title">Docs</h5>
				<h2 class="card-text m-3" id="docs"><?=$docCount?></h2>
			</div>
			<div class="theme-avtar bg-warning rounded-icon" style="background-color:#ffe2c7;">
				<span class="mdi mdi-file-cloud"></span>
			</div>
			</div>
			</a>
		</div>
	</div>
	
	
	
	
	
					
			
			<div class="col-lg-8">
			<div class="card">
			<div class="card-body">	
				<div id="totalRecordings" style="text-align:center;min-height:400px;"></div>			
			</div>
			</div>
			</div>
			
			
			<div class="col-lg-4">
			<div class="card">
			<div class="card-body">
				<div id="totalRecordingSummary" style="text-align:center;min-height:400px;"></div>			
			</div>
			</div>
			</div>
			
		
	
	
	

		
			<div class="col-lg-12">
			<div class="card">
			<div class="card-body">
						
				<div class="col-lg-12">
					<div id="recordingSummarybyDate" style="text-align:center;min-height:400px;"></div>
				</div>				
			</div>
			</div>
			
			</div>




        
			<div class="col-lg-12">
			<div class="card">
			<div class="card-body">			
				<div class="col-lg-12">
					<div id="linegraphdata" style="text-align:center;min-height:400px;"></div>
				</div>				
			</div>
			</div>			
			</div>
		

        
			<div class="col-lg-6">
                <div class="card">
                    <div class="card-body">				
                        <div id="stackedgraph" style="text-align:center;min-height:400px;"></div>				
                    </div>
                </div>
			</div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">                        
                        <div id="areachart" style="text-align:center;min-height:400px;"></div>                                    
                    </div>
                </div>			
			</div>
		
		
		
		



<script type="text/javascript">








//---- pie chart start--------


Highcharts.chart('totalRecordingSummary', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Total  Users'
    },
    tooltip: {
        valueSuffix: ''
    },
    subtitle: {
        text:
        '<a href="https://brainymed.com/" target="_default"></a>'
    },
    plotOptions: {
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 50
            }, {
                enabled: true,
                distance: -5,
                format: '',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                /* filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                } */
            }]
        }
    },
    series: [
        {
            name: 'Count',
            colorByPoint: true,
            data: 
			[
                {
                    name: 'Water',
                    y: 55.02
                },
                {
                    name: 'Fat',
                    sliced: true,
                    selected: true,
                    y: 26.71
                },
                {
                    name: 'Carbohydrates',
                    y: 1.09
                },
                {
                    name: 'Protein',
                    y: 15.5
                },
                {
                    name: 'Ash',
                    y: 1.68
                }
            ]
        }
    ]
});





//---- pie chart End--------









Highcharts.chart('recordingSummarybyDate', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Corn vs wheat estimated production for 2020',
        align: 'left'
    },
    subtitle: {
        text:
            'Source: <a target="_blank" ' +
            'href="https://www.indexmundi.com/agriculture/?commodity=corn">indexmundi</a>',
        align: 'left'
    },
    xAxis: {
        categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
        crosshair: true,
        accessibility: {
            description: 'Countries'
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: '1000 metric tons (MT)'
        }
    },
    tooltip: {
        valueSuffix: ' (1000 MT)'
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [
        {
            name: 'Corn',
            data: [406292, 260000, 107000, 68300, 27500, 14500]
        },
        {
            name: 'Wheat',
            data: [51086, 136000, 5500, 141000, 107180, 77000]
        }
    ]
});










//---------Bar chart start--Recordings by Date--------------



Highcharts.chart('totalRecordings', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'World\'s largest cities per 2021'
    },
    subtitle: {
        text: 'Source: <a href="https://worldpopulationreview.com/world-cities" target="_blank">World Population Review</a>'
    },
    xAxis: {
        type: 'category',
        labels: {
            autoRotation: [-45, -90],
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Population (millions)'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Population in 2021: <b>{point.y:.1f} millions</b>'
    },
    series: [{
        name: 'Population',
        colors: [
            '#9b20d9', '#9215ac', '#861ec9', '#7a17e6', '#7010f9', '#691af3',
            '#6225ed', '#5b30e7', '#533be1', '#4c46db', '#4551d5', '#3e5ccf',
            '#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',
            '#03c69b',  '#00f194'
        ],
        colorByPoint: true,
        groupPadding: 0,
        data: [
            ['Tokyo', 37.33],
            ['Delhi', 31.18],
            ['Shanghai', 27.79],
            ['Sao Paulo', 22.23],
            ['Mexico City', 21.91],
            ['Dhaka', 21.74],
            ['Cairo', 21.32],
            ['Beijing', 20.89],
            ['Mumbai', 20.67],
            ['Osaka', 19.11],
            ['Karachi', 16.45],
            ['Chongqing', 16.38],
            ['Istanbul', 15.41],
            ['Buenos Aires', 15.25],
            ['Kolkata', 14.974],
            ['Kinshasa', 14.970],
            ['Lagos', 14.86],
            ['Manila', 14.16],
            ['Tianjin', 13.79],
            ['Guangzhou', 13.64]
        ],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            inside: true,
            verticalAlign: 'top',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});


// Line graph start--------------------
Highcharts.chart('linegraphdata', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Monthly Average Temperature'
    },
    subtitle: {
        text: 'Source: ' +
            '<a href="https://en.wikipedia.org/wiki/List_of_cities_by_average_temperature" ' +
            'target="_blank">Wikipedia.com</a>'
    },
    xAxis: {
        categories: [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
            'Oct', 'Nov', 'Dec'
        ]
    },
    yAxis: {
        title: {
            text: 'Temperature (°C)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Reggane',
        data: [
            16.0, 18.2, 23.1, 27.9, 32.2, 36.4, 39.8, 38.4, 35.5, 29.2,
            22.0, 17.8
        ]
    }, {
        name: 'Tallinn',
        data: [
            -2.9, -3.6, -0.6, 4.8, 10.2, 14.5, 17.6, 16.5, 12.0, 6.5,
            2.0, -0.9
        ]
    }]
});


// Line graph Ends--------------------


// stacked graph Starts--------------------
Highcharts.chart('stackedgraph', {

chart: {
    type: 'column'
},

title: {
    text: 'Olympic Games all-time medal table, grouped by continent',
    align: 'left'
},

xAxis: {
    categories: ['Gold', 'Silver', 'Bronze']
},

yAxis: {
    allowDecimals: false,
    min: 0,
    title: {
        text: 'Count medals'
    }
},

tooltip: {
    format: '<b>{key}</b><br/>{series.name}: {y}<br/>' +
        'Total: {point.stackTotal}'
},

plotOptions: {
    column: {
        stacking: 'normal'
    }
},

series: [{
    name: 'Norway',
    data: [148, 133, 124],
    stack: 'Europe'
}, {
    name: 'Germany',
    data: [102, 98, 65],
    stack: 'Europe'
}, {
    name: 'United States',
    data: [113, 122, 95],
    stack: 'North America'
}, {
    name: 'Canada',
    data: [77, 72, 80],
    stack: 'North America'
}, {
    name: 'London',
    data: [65, 80, 48],
    stack: 'UK'
}, {
    name: 'Paris',
    data: [75, 88, 78],
    stack: 'UK'
}]
});

// stacked graph Ends--------------------


// Area chat starts------------------------

Highcharts.chart('areachart', {
    chart: {
        type: 'area'
    },
    title: {
        useHTML: true,
        text: 'Countries/regions with highest Gt CO<sub>2</sub>-emissions',
        align: 'left'
    },
    subtitle: {
        text: 'Source: ' +
            '<a href="https://energiogklima.no/klimavakten/land-med-hoyest-utslipp/"' +
            'target="_blank">Energi og Klima</a>',
        align: 'left'
    },
    accessibility: {
        point: {
            valueDescriptionFormat: '{index}. {point.category}, {point.y:,' +
                '.1f} billions, {point.percentage:.1f}%.'
        }
    },
    yAxis: {
        labels: {
            format: '{value}%'
        },
        title: {
            enabled: false
        }
    },
    tooltip: {
        pointFormat: '<span style="color:{series.color}">{series.name}</span>' +
            ': <b>{point.percentage:.1f}%</b> ({point.y:,.1f} billion Gt)<br/>',
        split: true
    },
    plotOptions: {
        series: {
            pointStart: 1990
        },
        area: {
            stacking: 'percent',
            marker: {
                enabled: false
            }
        }
    },
    series: [{
        name: 'China',
        data: [
            2.5, 2.6, 2.7, 2.9, 3.1, 3.4, 3.5, 3.5, 3.4, 3.4, 3.4,
            3.5, 3.9, 4.5, 5.2, 5.9, 6.5, 7, 7.5, 7.9, 8.6, 9.5, 9.8,
            10, 10, 9.8, 9.7, 9.9, 10.3, 10.5, 10.7, 10.9
        ]
    }, {
        name: 'USA',
        data: [
            5.1, 5.1, 5.2, 5.3, 5.4, 5.4, 5.6, 5.7, 5.7, 5.8, 6, 5.9,
            5.9, 6, 6.1, 6.1, 6.1, 6.1, 5.9, 5.5, 5.7, 5.5, 5.3, 5.5,
            5.5, 5.4, 5.2, 5.2, 5.4, 5.3, 4.7, 5
        ]
    }, {
        name: 'EU',
        data: [
            3.9, 3.8, 3.7, 3.6, 3.6, 3.6, 3.7, 3.7, 3.6, 3.6, 3.6, 3.7,
            3.7, 3.7, 3.8, 3.7, 3.7, 3.7, 3.6, 3.3, 3.4, 3.3, 3.3, 3.2, 3,
            3.1, 3.1, 3.1, 3, 2.9, 2.6, 2.7
        ]
    }, {
        name: 'India',
        data: [
            0.6, 0.6, 0.7, 0.7, 0.7, 0.8, 0.8, 0.9, 0.9, 1, 1, 1,
            1, 1.1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 2, 2,
            2.2, 2.3, 2.4, 2.4, 2.6, 2.6, 2.4, 2.7
        ]
    }]
});
// Area chat Ends---------------------------------------

</script>
		