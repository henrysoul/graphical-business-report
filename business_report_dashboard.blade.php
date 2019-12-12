@extends('layouts.master')

@section ('sub_title') Assessment @endsection

@section ('header')
    @include ('layouts.partials.header')
    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section ('body_classes') hold-transition skin-blue sidebar-mini @endsection

@section ('wrapper_classes') wrapper @endsection

@section ('nav_topbar')
  @include('layouts.partials.nav_topbar')
@endsection

@section ('nav_sidebar')
    @include('layouts.partials.nav_sidebar')
@endsection

@section ('content_classes') content-wrapper @endsection

@section('alerts')
  @include('layouts.partials.alerts')
@stop
@section('content')
	<script type="text/javascript" src="{{asset('plugins/js/tabs.js')}}"></script>
<script>
	$(document).ready(function() {
		$('.nav-tabs > li > a').click(function(event){
		event.preventDefault();//stop browser to take action for clicked anchor
					
		//get displaying tab content jQuery selector
		var active_tab_selector = $('.nav-tabs > li.active > a').attr('href');					
					
		//find actived navigation and remove 'active' css
		var actived_nav = $('.nav-tabs > li.active');
		actived_nav.removeClass('active');
					
		//add 'active' css into clicked navigation
		$(this).parents('li').addClass('active');
					
		//hide displaying tab content
		$(active_tab_selector).removeClass('active');
		$(active_tab_selector).addClass('hide');
					
		//show target tab content
		var target_tab_selector = $(this).attr('href');
		$(target_tab_selector).removeClass('hide');
		$(target_tab_selector).addClass('active');
	     });
	  });
	</script>
		<style>
			/** Start: to style navigation tab **/
			.nav {
			  margin-bottom: 18px;
			  margin-left: 0;
			  list-style: none;
			}

			.nav > li > a {
			  display: block;
			}
			
			.nav-tabs{
			  *zoom: 1;
			}

			.nav-tabs:before,
			.nav-tabs:after {
			  display: table;
			  content: "";
			}

			.nav-tabs:after {
			  clear: both;
			}

			.nav-tabs > li {
			  float: left;
			}

			.nav-tabs > li > a {
			  padding-right: 12px;
			  padding-left: 12px;
			  margin-right: 2px;
			  line-height: 14px;
			}

			.nav-tabs {
			  border-bottom: 1px solid #ddd;
			}

			.nav-tabs > li {
			  margin-bottom: -1px;
			}

			.nav-tabs > li > a {
			  padding-top: 8px;
			  padding-bottom: 8px;
			  line-height: 18px;
			  border: 1px solid transparent;
			  -webkit-border-radius: 4px 4px 0 0;
				 -moz-border-radius: 4px 4px 0 0;
					  border-radius: 4px 4px 0 0;
			}

			.nav-tabs > li > a:hover {
			  border-color: #eeeeee #eeeeee #dddddd;
			}

			.nav-tabs > .active > a,
			.nav-tabs > .active > a:hover {
			  color: #555555;
			  cursor: default;
			  background-color: #ffffff;
			  border: 1px solid #ddd;
			  border-bottom-color: transparent;
			}
			
			li {
			  line-height: 18px;
			}
			
			.tab-content.active{
				display: block;
			}
			
			.tab-content.hide{
				display: none;
			}
			
			
			/** End: to style navigation tab **/
		</style>

              <!-- Content Header (Page header) -->
       <section class="content-header">
     
          <!--<h1>
            Dashboard
            <small>Control panel</small>
          </h1>-->
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Business Report</a></li>
          </ol>

          </section>

 
	<div>
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#dashboard">Dashboard</a>
				</li>
				<li id="business_report_graph">
					<a href="#business_report">Bussiness Report</a>
				</li>
				<li id="building_report_graph">
					<a href="#building_report">Building Report</a>
				</li>
                	<li id="wards_report_graph">
					<a href="#wards_report">Wards Report</a>
				</li>
                	<li id="apartment_report_graph">
					<a href="#apartment_report">Apartment Report</a>
				</li>
                	<li id="land_report_graph">
					<a href="#land_report">Land Report</a>
				</li>
			</ul>	
		</div>

<div>
<div>
            <div class="alert alert-success hide"></div>
             <section class="content">
            <!--Start Tab content -->
         
                <!--start  dashboard tab -->
				  <div class="tab-content">
                <div id="dashboard" class="tab-content active">
                   <!-- Admin Users -->
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                      <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                          <div class="inner">
                            <h3>{{ number_format($search_resultcount[0]->total_business) }}</h3>
                            <p>Total Business</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-suitcase"></i>
                          </div>
                          <a href="business_category_report?business_name={{$business_name}}&building_number={{$building_number}}&business_lga={{$business_lga}}&ward={{$ward}}&street={{$street}}&registered_by={{$registered_by}}&registered_on={{$registered_on}}&registered_to={{$registered_to}}"
                          class="small-box-footer">View Business Report <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div><!-- ./col -->
                      <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-green">
                          <div class="inner">
                          <h3>{{ number_format($search_resultcount[0]->total_buildings) }}</h3>
                            <p>Total Building</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-institution"></i>
                          </div>
                          @if( number_format($search_resultcount[0]->total_buildings) <= 0 )
                            <a class="small-box-footer">No Builings Found<a/>
                          @else 
                            <a href="bussiness_result_report_building?business_name={{$business_name}}&building_number={{$building_number}}&business_lga={{$business_lga}}&ward={{$ward}}&street={{$street}}&registered_by={{$registered_by}}&registered_on={{$registered_on}}&registered_to={{$registered_to}}" class="small-box-footer">View Building Report <i class="fa fa-arrow-circle-right"></i></a>
                          @endif 
                          </div>
                      </div><!-- ./col -->
                      <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                          <div class="inner">
                            <h3>{{ number_format($search_resultcount[0]->total_apartments) }}</h3>
                            <p>Total Apartment</p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-building-o"></i>
                          </div>
                          @if(number_format($search_resultcount[0]->total_apartments)==0)
                            <a class="small-box-footer">No Apartments Found<a/>
                          @else 
                              <a href="business_result_report_apartment?business_name={{$business_name}}&building_number={{$building_number}}&business_lga={{$business_lga}}&ward={{$ward}}&street={{$street}}&registered_by={{$registered_by}}&registered_on={{$registered_on}}&registered_to={{$registered_to}}" class="small-box-footer">View Apartment Report <i class="fa fa-arrow-circle-right"></i></a>
                          @endif
                        </div>
                      </div><!-- ./col -->
                      <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                          <div class="inner">
                          <h3>{{ number_format($search_resultcount[0]->total_wards) }}</h3>
                            <p>Total Wards </p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-map-marker"></i>
                          </div>
                          @if( number_format($search_resultcount[0]->total_wards) == 0 )
                            <a class="small-box-footer">No Wards Found<a/>
                          @else
                              <a href="business_result_report_ward?business_name={{$business_name}}&building_number={{$building_number}}&business_lga={{$business_lga}}&ward={{$ward}}&street={{$street}}&registered_by={{$registered_by}}&registered_on={{$registered_on}}&registered_to={{$registered_to}}" class="small-box-footer">View Ward Report <i class="fa fa-arrow-circle-right"></i></a>
                          @endif
                          </div>
                      </div><!-- ./col -->
                    </div><!-- /.row -->
                    <!-- Main row -->
                </div>
				</div>
				
                   
                <!--End  dashboard tab -->
                 <!-- Bussiness tab -->
			
                <div  id="business_report" class="tab-content hide">
                    <fieldset>
                      <div class="row">
                        <div class="col-xs-12">
                    
                          <div class="box">
                            <div class="box-header row">
                               
                            </div><!-- /.box-header -->
                            <div class="row">
                                    <div class="col-md-12">
                                        <div id="chartContainer_business" style="height: 300px; width: 100%;"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3" style="padding:25px 20px 10px 20px;">
                                            <div class="form-group">
                                            <label for="chart_type"><strong>Chart Type</strong></label>
                                            <select class="form-control" id="chartType_business" name="Chart Type">
                                                <option>Select Chart Type</option>
                                                <option value="pie">Pie</option>
                                                <option value="bar">Bar</option>
                                                <option value="column">Column</option>
                                                <option value="line">Line</option>
                                                <option value="scatter">Scatter</option>
                                                <option value="area">Area</option>
                                                <option value="spline">Spline</option>
                                            </select>
                                            </div>
                                    </div>
                                </div>
                            <div class="box-body">
                                
                            <div style="overflow-x:auto;" >
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>BusinessName</th>
                                        <th>BusinessCategory</th>
                                        <th>BusinessSize</th>
                                        <th>BusinessAddress</th>
                                        <th>BusinessOperation</th>
                                        <th>RegisteredOn</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($search_result as $business)
                                            <tr>
                                                <td>{{$business->business_name}}</td>
                                                <td>{{$business->business_category}}</td>
                                                <td>{{$business->business_size}}</td>
                                                <td>{{$business->business_address}}</td>
                                                <td>{{$business->business_operation}}</td>
                                                <td>{{$business->created_at}}</td>
                                                 <td>
                       <div class="btn-group">
                            <button type="button" class="btn btn-info" style="padding: 2px;">Action</button>
                            <button type="button" class="btn btn-info dropdown-toggle" style="padding: 2px;" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                  <li>
                                  <a href="{{ route('businesses.edit', ['id' => $business->id_business]) }}">Edit</a>
                                  </li>
                                 <li>
                                  <a data-target="#businesImage{{$business->business_id}}" data-toggle="modal">View</a>
                                  </li>
                            </ul>
                            </div>
                          </td>
                                           
                                            </tr>
                                            <!--View Business Image-->
                                                <div class="modal fade" id="businesImage{{$business->business_id}}" role="dialog">
                                                    <div class="modal-dialog">
                                                            <!-- Modal content-->
                                                        <div class="modal-content">
                                                                <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Business Image</h4>
                                                                </div>
                                                                <div class="modal-body"> 
                                                                    <div class="box-body">
                                                                        <div>
                                                                            <img src="{{asset('uploads/'.$business->photo_url)}}"/>
                                                                        </div>      
                                                                    </div><!-- /.box-body -->
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!--End View Business Image-->
                                    @endforeach 
                                    </tbody>
                                </table>
                            
                                <div align="right">
                                    {{-- $individuals->links() --}}
                                </div><!-- /.box-body -->
                          </div><!-- /.box -->
                        </div><!-- /.col -->
                      </div><!-- /.row -->
                      </div>
				  </div>
                    </fieldset>
                   
                
				    </div>
				
                <!--End Bussiness tab -->
                <!--start  building_report tab -->
				
                <div id="building_report" class="tab-content hide">
                  <fieldset>
                   <div class="row">
                      <div class="col-xs-12">
                  
                        <div class="box">
                          <div class="box-header row">
                              
                          </div><!-- /.box-header -->
                          <div class="row">
                                  <div class="col-md-12">
                                      <div id="chartContainer_building" style="height: 300px; width: 100%;"></div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-3" style="padding:25px 20px 10px 20px;">
                                          <div class="form-group">
                                          <label for="chart_type"><strong>Chart Type</strong></label>
                                          <select class="form-control" id="chartType_building" name="Chart Type">
                                              <option>Select Chart Type</option>
                                              <option value="pie">Pie</option>
                                              <option value="bar">Bar</option>
                                              <option value="column">Column</option>
                                              <option value="line">Line</option>
                                              <option value="scatter">Scatter</option>
                                              <option value="area">Area</option>
                                              <option value="spline">Spline</option>
                                          </select>
                                          </div>
                                  </div>
                              </div>
                          <div class="box-body">
                              
                          <div style="overflow-x:auto;" >
                              <table id="example1" class="table table-bordered table-striped">
                                  <thead>
                                  <tr>
                                      <th>Street</th>
                                      <th>Building Type</th>
                                      <th>Registered BY</th>
                                      <th>RegisteredOn</th>
                                  </tr>
                                  
                                  </thead>
                                  <tbody>
                                  @foreach($search_result as $business)
                                          <tr>
                                              <td>{{$business->buildings_street}}</td>
                                              <td>{{$business->building_type}}</td>
                                              <td>{{$business->buildings_created_by}}</td>
                                              <td>{{$business->buildings_created_at}}</td>
                                          </tr>
                                          
                                  @endforeach 
                                  </tbody>
                              </table>
                          
                              <div align="right">
                                  {{-- $individuals->links() --}}
                              </div><!-- /.box-body -->
                        </div><!-- /.box -->
                      </div><!-- /.col -->
                    </div><!-- /.row -->
					   </div>
				 </div>
                    </fieldset>
             
				  </div>
                <!--End  building_report tab -->
                <!-- wards_report tab -->
                <div  id="wards_report" class="tab-content hide">
                    <fieldset>
                    <div class="row">
                      <div class="col-xs-12">
                  
                  
                        <div class="box">
                          <div class="box-header row">
                             
                          </div><!-- /.box-header -->
                          <div class="row">
                                  <div class="col-md-12">
                                      <div id="chartContainer_wards" style="height: 300px; width: 100%;"></div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-3" style="padding:25px 20px 10px 20px;">
                                          <div class="form-group">
                                          <label for="chart_type"><strong>Chart Type</strong></label>
                                          <select class="form-control" id="chartType_wards" name="Chart Type">
                                              <option>Select Chart Type</option>
                                              <option value="pie">Pie</option>
                                              <option value="bar">Bar</option>
                                              <option value="column">Column</option>
                                              <option value="line">Line</option>
                                              <option value="scatter">Scatter</option>
                                              <option value="area">Area</option>
                                              <option value="spline">Spline</option>
                                          </select>
                                          </div>
                                  </div>
                              </div>
                          <div class="box-body">
                              
                          <div style="overflow-x:auto;" >
                              <table id="example1" class="table table-bordered table-striped">
                                  <thead>
                                  <tr>
                                      <th>Ward</th>
                                      <th>LGA</th>
                                      {{-- <th>Registered BY</th>
                                      <th>Registered On</th> --}}
                                  </tr>
                                  
                                  </thead>
                                  <tbody>
                                  @foreach($search_result as $business)
                                          <tr>
                                              <td>{{$business->ward}}</td>
                                              <td>{{$business->lga}}</td>
                                            {{-- <td>{{$business->buildings_created_by}}</td>
                                              <td>{{$business->buildings_created_at}}</td> --}}
                                          </tr>
                                  @endforeach 
                                  </tbody>
                              </table>
                          
                              <div align="right">
                                  {{-- $individuals->links() --}}
                              </div><!-- /.box-body -->
                        </div><!-- /.box -->
                      </div><!-- /.col -->
                    </div><!-- /.row -->
					  </div>
					   </div>
                    </fieldset>
                </div>
                <!--End  wards_report tab -->

                <!-- apartment_report tab -->
                <div  id="apartment_report" class="tab-content hide">
                    <fieldset>
                      <div class="row">
                        <div class="col-xs-12">
                    
                          <div class="box">
                            <div class="box-header row">
                            </div><!-- /.box-header -->
                            <div class="row">
                                    <div class="col-md-12">
                                        <div id="chartContainer_apartment" style="height: 300px; width: 100%;"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3" style="padding:25px 20px 10px 20px;">
                                            <div class="form-group">
                                            <label for="chart_type"><strong>Chart Type</strong></label>
                                            <select class="form-control" id="chartType_apartments" name="Chart Type">
                                                <option>Select Chart Type</option>
                                                <option value="pie">Pie</option>
                                                <option value="bar">Bar</option>
                                                <option value="column">Column</option>
                                                <option value="line">Line</option>
                                                <option value="scatter">Scatter</option>
                                                <option value="area">Area</option>
                                                <option value="spline">Spline</option>
                                            </select>
                                            </div>
                                    </div>
                                </div>
                            <div class="box-body">
                                
                            <div style="overflow-x:auto;" >
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Apartment</th>
                                        <th>Registered BY</th>
                                        <th>RegisteredOn</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($search_result as $business)
                                            <tr>
                                                <td>{{$business->aparment}}</td>
                                                <td>{{$business->apartment_created_by}}</td>
                                                <td>{{$business->apartment_created_at}}</td>
                                            </tr>
                                    @endforeach 
                                    </tbody>
                                </table>
                            
                                <div align="right">
                                    {{-- $individuals->links() --}}
                                </div><!-- /.box-body -->
                          </div><!-- /.box -->
                        </div><!-- /.col -->
                      </div><!-- /.row -->
					     </div>
						    </div>
                    </fieldset>
                   
                </div>
                <!--End  apartment_report tab -->
                <!-- Land tab -->
                <div  id="land_report" class="tab-content hide">
                    <fieldset></fieldset>
                   
                </div>
                <!--End Land info tab -->
            </div>
            <!--End Tab content -->
        </div>
        <!--End  -->

    </div>
          
        </section>
@endsection

@section ('footer')
    @include('layouts.partials.footer')
    <!--JS CANVAS-->
       <script src="{{asset('js/canvasjs.min.js')}}"></script>
    <!--End CANVAS-->
    <!--sweet alert-->
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!--end sweet alert-->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <!-- SlimScroll -->
    <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{asset('plugins/fastclick/fastclick.min.js')}}"></script>
    <!--start tab-->
    <script type="text/javascript">
      $('.tab-trigger').click(function (e) {
          e.preventDefault();
          $(this).tab('show');
      })
    </script>
    <!--End Tab-->


<script>
  //Business report AJAX call
  $('#business_report_graph').click(function(){
     $.ajax({
                async: false,
                method: "get",
                url: "<?php echo  'business_category_report?business_name='.$business_name.'&building_number='.$building_number.'&business_lga='.$business_lga.'&ward='.$ward.'&street='.$street.'&registered_by='.$registered_by.'&registered_on='.$registered_on.'&registered_to='.$registered_to.'' ?>",
                success: function (data) {
                  var business_chart = new CanvasJS.Chart("chartContainer_business",
                  {
                          animationEnabled: true,
                          theme: "light2",
                          title:{
                              text: data.search_info.name
                          },
                          axisY: {
                              title: data.search_info.axisY
                          },
                          axisX: {
                              title: data.search_info.axisX
                          },
                          data: [
                              {
                                  startAngle: 25,
                                  toolTipContent: "",
                                  showInLegend: "true",
                                  legendText: "{label}",
                                  indexLabelFontSize: 16,
                                  indexLabel: "{label} - {y}",
                                  type: "pie",
                                  dataPoints: data.data_points
                              }
                          ]
                  });
                  business_chart.render();
                  var chartType = document.getElementById('chartType_business');
                  chartType.addEventListener("change",  function(){
                    business_chart.options.data[0].type = chartType.options[chartType.selectedIndex].value;
                    business_chart.render();
                  });
                  
                },
                error: function(err){
                    swal(mssg_title, mssg, mssg_type);
                }

            });   
  });

  // Building Report Ajax Call
    $('#building_report_graph').click(function(){
     $.ajax({
                method: "get",
                url: "<?php echo  'bussiness_result_report_building?business_name='.$business_name.'&building_number='.$building_number.'&business_lga='.$business_lga.'&ward='.$ward.'&street='.$street.'&registered_by='.$registered_by.'&registered_on='.$registered_on.'&registered_to='.$registered_to.'' ?>",
                success: function (data) {
                  var building_chart = new CanvasJS.Chart("chartContainer_building",
                  {
                          animationEnabled: true,
                          theme: "light2",
                          title:{
                              text: data.search_info.name
                          },
                          axisY: {
                              title: data.search_info.axisY
                          },
                          axisX: {
                              title: data.search_info.axisX
                          },
                          data: [
                              {
                                  startAngle: 25,
                                  toolTipContent: "",
                                  showInLegend: "true",
                                  legendText: "{label}",
                                  indexLabelFontSize: 16,
                                  indexLabel: "{label} - {y}",
                                  type: "pie",
                                  dataPoints: data.data_points
                              }
                          ]
                  });
                  building_chart.render();

                  var chartType = document.getElementById('chartType_building');
                  chartType.addEventListener("change",  function(){
                    building_chart.options.data[0].type = chartType.options[chartType.selectedIndex].value;
                    building_chart.render();
                  });
                  
                },
                error: function(err){
                    swal(mssg_title, mssg, mssg_type);
                }

            });   
  });
// Wards Report Ajax Call
  $('#wards_report_graph').click(function(){
     $.ajax({
                method: "get",
                url: "<?php echo  'business_result_report_ward?business_name='.$business_name.'&building_number='.$building_number.'&business_lga='.$business_lga.'&ward='.$ward.'&street='.$street.'&registered_by='.$registered_by.'&registered_on='.$registered_on.'&registered_to='.$registered_to.'' ?>",
                success: function (data) {
                  var wards_chart = new CanvasJS.Chart("chartContainer_wards",
                  {
                          animationEnabled: true,
                          theme: "light2",
                          title:{
                              text: data.search_info.name
                          },
                          axisY: {
                              title: data.search_info.axisY
                          },
                          axisX: {
                              title: data.search_info.axisX
                          },
                          data: [
                              {
                                  startAngle: 25,
                                  toolTipContent: "",
                                  showInLegend: "true",
                                  legendText: "{label}",
                                  indexLabelFontSize: 16,
                                  indexLabel: "{label} - {y}",
                                  type: "pie",
                                  dataPoints: data.data_points
                              }
                          ]
                  });
                  wards_chart.render();

                  var chartType = document.getElementById('chartType_wards');
                  chartType.addEventListener("change",  function(){
                      wards_chart.options.data[0].type = chartType.options[chartType.selectedIndex].value;
                      wards_chart.render();
                  });                         
                },
                error: function(err){
                    swal(mssg_title, mssg, mssg_type);
                }

            });   
  });

  // Apartments Report Ajax Call
  $('#apartment_report_graph').click(function(){
     $.ajax({
                method: "get",
                url: "<?php echo  'business_result_report_apartment?business_name='.$business_name.'&building_number='.$building_number.'&business_lga='.$business_lga.'&ward='.$ward.'&street='.$street.'&registered_by='.$registered_by.'&registered_on='.$registered_on.'&registered_to='.$registered_to.'' ?>",
                success: function (data) {
                  var apartment_chart = new CanvasJS.Chart("chartContainer_apartment",
                  {
                          animationEnabled: true,
                          theme: "light2",
                          title:{
                              text: data.search_info.name
                          },
                          axisY: {
                              title: data.search_info.axisY
                          },
                          axisX: {
                              title: data.search_info.axisX
                          },
                          data: [
                              {
                                  startAngle: 25,
                                  toolTipContent: "",
                                  showInLegend: "true",
                                  legendText: "{label}",
                                  indexLabelFontSize: 16,
                                  indexLabel: "{label} - {y}",
                                  type: "pie",
                                  dataPoints: data.data_points
                              }
                          ]
                  });

                  console.log(data.data_points);
                 
                  apartment_chart.render();
                  var chartType = document.getElementById('chartType_apartments');
                  chartType.addEventListener("change",  function(){
                    apartment_chart.options.data[0].type = chartType.options[chartType.selectedIndex].value;
                    apartment_chart.render();
                  });
                  
                },
                error: function(err){
                    swal(mssg_title, mssg, mssg_type);
                }

            });   
  });

  // chang chart type    
</script>
<!--End Business Report-->
@endsection