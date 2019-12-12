<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Exception;
use App\Models\Business\Businesses;
use App\Models\ManageStreetBusinessType\ManageStreet;

use App\Models\ManageZonesLGAsWards\ManageLGA;
use App\Models\ManageZonesLGAsWards\ManageWards;
use DB;
use App\User;
class BusinessController extends Controller
{
       public function __construct() 
    {
          //$this->middleware("check_user_owner");
               
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mlga=ManageLGA::get();
        $wards = ManageWards::orderBy('ward')->get();
        $street = ManageStreet::where('service_id',serviceId())->orderBy('street')->get();
        $users = User::where('service_id', serviceId())->orderBy('username', 'asc')->get();


        $businesses = Businesses::where('service_id',serviceId())->orderBy('business_rin')->get();
        return view('Report.business_search',compact('mlga','wards', 'street', 'businesses','users'));
        //return view('EnumerationReport.business', compact('businesses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request->all());
        $this->validate($request, [
            'business_rin' => 'required|string',
            'business_type' => 'required|string',
            'business_name' => 'required|string',
            'business_category' => 'required|string',
            'business_sector' => 'required|string'
        ]);
        try{
            $b_type = Businesses::create($request->all());
            return redirect()->back()->with('success', "Business was successfully created.");
        }
        catch (Exception $exception){
         // dd($exception->errorInfo[2]);
          $error_code =  $exception->errorInfo[1];
          if($error_code == 1062){
                return redirect()->back()->with('error', "The Business already exist. Try a new Business.");
            }
                return redirect()->back()->with('error', "Error. Something went wrong... Try again or contact system administrator");

         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      //  dd($request->all());
        $this->validate($request, [
            'business_rin' => 'required|string',
            'business_type' => 'required|string',
            'business_name' => 'required|string',
            'business_category' => 'required|string',
            'business_sector' => 'required|string'
        ]);

        try{
            switch ($request->input('submitmany')) {
                case 'update':
                $b_type = Businesses::find($id);
                $b_type->business_rin = $request->business_rin;
                $b_type->business_type = $request->business_type;
                $b_type->business_name = $request->business_name;
                $b_type->business_category = $request->business_category;
                $b_type->business_sector = $request->business_sector;
                $b_type->save();
                return redirect()->route('business.index')->with('success', "The Business was successfully updated.");
                break;

            case 'delete':
               $b_type =Businesses::find($id);
               $b_type->delete();
               return redirect()->route('business.index')->with('success', "The Business   was successfully deleted.");
                break;

            case 'cancel':
            return redirect()->route('business.index');
                break;
            }
        }
          catch (Exception $exception){
            $error_code =  $exception->errorInfo[1];
             if($error_code == 1062){
                return redirect()->back()->with('error', "The Business already exist. Try a new Business.");
             }
            return redirect()->back()->with('error', "Error. Something went wrong... Try again or contact system administrator");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function general_business_report( Request $request){

     // dd($request->input('ward'));

//$getbussinessname=$_GET['business_name'];

       // $business_name = $request->business_name ? $request->business_name : '%%';
        $business_name = $_GET['business_name'];
        $building_number =$_GET['building_number'];
        $business_lga =$_GET['lga'];
        $ward =$_GET['ward'];
        
        $street =$_GET['street'];
        $registered_by =$_GET['registered_by'];
        $registered_on =$_GET['registered_on'];
        $registered_to = $_GET['registered_to'] ? $request->registered_to: date('Y-m-d');
        $serviceids=serviceId();
        $organisationid=organisationCode();
  //dd($business_name);
        $search_result = DB::table('businesses')
            ->select(
            'businesses.*',
            'wards.*',
            'buildings.street as buildings_street',
            'buildings.building_type',
            'buildings.created_by as  buildings_created_by',
            'buildings.created_at as buildings_created_at',
            '_apartments.aparment',
            '_apartments.registered_on as apartment_created_at',
            '_apartments.registered_by as apartment_created_by',
            DB::raw('count(businesses.business_name) as total_business,count(_apartments.aparment) as total_apartments,
                count(buildings.building) as total_buildings,count(wards.ward) as total_wards')
            )
         
            ->leftJoin('buildings', 'businesses.building_id', '=', 'buildings.building_id')
            ->leftJoin('_apartments', 'businesses.apartment_id', '=', '_apartments.apartment_id')
            ->leftJoin('wards', 'businesses.business_lga', '=', 'wards.lga')
            ->Join('client_service', 'businesses.service_id', '=', 'client_service.service_id')
            
          
          //->where('businesses.service_id', $serviceids)
           ->when(!empty($organisationid) , function ($query) use($organisationid){
                    return $query->where('client_service.organization_code',$organisationid);
                })
            ->when(!empty($business_name) , function ($query) use($business_name){
                    return $query->where('businesses.business_name', 'like', '%' . $business_name. '%');
                })

            ->when(!empty($building_number) , function ($query) use($building_number){
                return $query->Where('buildings.building_number', 'like', '%' . $building_number . '%');
            })
            ->when(!empty($business_lga) , function ($query) use($business_lga){
                return $query->Where('businesses.business_lga', 'like', '%' .$business_lga . '%');
            })

           ->when(!empty($ward) , function ($query) use($ward){
                return $query->Where('wards.ward', 'like', '%' .$ward. '%');
            })   

            ->when(!empty($street) , function ($query) use($street){
                return $query->Where('buildings.street', 'like','%' . $street. '%');
            })      

            ->when(!empty($registered_by) , function ($query) use($registered_by){
                return $query->Where('businesses.created_by', 'like','%' . $registered_by. '%');
            }) 

            ->when(!empty($registered_on) , function ($query) use($registered_on){
                return $query->WhereBetween('businesses.created_at', [$registered_on,$registered_to]);
            }) 
         
            ->groupBy('businesses.business_rin')
            ->get();

        //   dd($search_result);
            $search_resultcount = DB::table('businesses')
            ->select(
                DB::raw('count(businesses.business_name) as total_business,count(_apartments.aparment) as total_apartments,
                    count(buildings.building) as total_buildings,count(wards.ward) as total_wards')
            )
         
            ->leftJoin('buildings', 'businesses.building_id', '=', 'buildings.building_id')
            ->leftJoin('_apartments', 'businesses.apartment_id', '=', '_apartments.apartment_id')
            ->leftJoin('wards', 'businesses.business_lga', '=', 'wards.lga')
            ->Join('client_service', 'businesses.service_id', '=', 'client_service.service_id')
            
          
          //->where('businesses.service_id', $serviceids)
           ->when(!empty($organisationid) , function ($query) use($organisationid){
             return $query->where('client_service.organization_code',$organisationid);
                })
            ->when(!empty($business_name) , function ($query) use($business_name){
             return $query->where('businesses.business_name', 'like', '%' . $business_name. '%');
                })

                   ->when(!empty($building_number) , function ($query) use($building_number){
             return $query->Where('buildings.building_number', 'like', '%' . $building_number . '%');
                })
            ->when(!empty($business_lga) , function ($query) use($business_lga){
             return $query->Where('businesses.business_lga', 'like', '%' .$business_lga . '%');
                })

           ->when(!empty($ward) , function ($query) use($ward){
             return $query->Where('wards.ward', 'like', '%' .$ward. '%');
                })   

                 ->when(!empty($street) , function ($query) use($street){
             return $query->Where('buildings.street', 'like','%' . $street. '%');
                })      

            ->when(!empty($registered_by) , function ($query) use($registered_by){
             return $query->Where('businesses.created_by', 'like','%' . $registered_by. '%');
                }) 

            ->when(!empty($registered_on) , function ($query) use($registered_on){
             return $query->WhereBetween('businesses.created_at', [$registered_on,$registered_to]);
                }) 
            ->get();


        
           if(count($search_result) == 0) {
                return back()->with('info', 'No search record found. Kindly try again !');
            }

            

        return view('Report.business_report_dashboard',compact('search_result','search_resultcount','business_name','building_number','business_lga',
        'ward','street','registered_by','registered_on','registered_to'));
        
    }

    public function business_category_report(Request $request){
        
            $business_name = $request->business_name;
            $building_number = $request->building_number;
            $business_lga = $request->lga;
            $ward = $request->ward;
            $street = $request->street;
            $registered_by = $request->registered_by;
            $registered_on = $request->registered_on;
            $registered_to = $request->registered_to;
            $serviceids=serviceId();
             $organisationid=organisationCode();
  //dd($business_name);
        $search_result = DB::table('businesses')
            ->select(
            'businesses.*',
            'wards.*',
            'buildings.street as buildings_street',
            'buildings.building_type',
            'buildings.created_by as  buildings_created_by',
            'buildings.created_at as buildings_created_at',
            '_apartments.aparment',
            '_apartments.registered_on as apartment_created_at',
            '_apartments.registered_by as apartment_created_by',
            DB::raw('count(businesses.business_name) as total_business,count(_apartments.aparment) as total_apartments,
                count(buildings.building) as total_buildings,count(wards.ward) as total_wards')
            )
         
            ->leftJoin('buildings', 'businesses.building_id', '=', 'buildings.building_id')
            ->leftJoin('_apartments', 'businesses.apartment_id', '=', '_apartments.apartment_id')
            ->leftJoin('wards', 'businesses.business_lga', '=', 'wards.lga')
            ->Join('client_service', 'businesses.service_id', '=', 'client_service.service_id')
            
          
          //->where('businesses.service_id', $serviceids)
           ->when(!empty($organisationid) , function ($query) use($organisationid){
                    return $query->where('client_service.organization_code',$organisationid);
                })
            ->when(!empty($business_name) , function ($query) use($business_name){
                    return $query->where('businesses.business_name', 'like', '%' . $business_name. '%');
                })

            ->when(!empty($building_number) , function ($query) use($building_number){
                return $query->Where('buildings.building_number', 'like', '%' . $building_number . '%');
            })
            ->when(!empty($business_lga) , function ($query) use($business_lga){
                return $query->Where('businesses.business_lga', 'like', '%' .$business_lga . '%');
            })

           ->when(!empty($ward) , function ($query) use($ward){
                return $query->Where('wards.ward', 'like', '%' .$ward. '%');
            })   

            ->when(!empty($street) , function ($query) use($street){
                return $query->Where('buildings.street', 'like','%' . $street. '%');
            })      

            ->when(!empty($registered_by) , function ($query) use($registered_by){
                return $query->Where('businesses.created_by', 'like','%' . $registered_by. '%');
            }) 

            ->when(!empty($registered_on) , function ($query) use($registered_on){
                return $query->WhereBetween('businesses.created_at', [$registered_on,$registered_to]);
            }) 
         
            ->groupBy('businesses.business_rin')
            ->get();
        //    dd($search_result);
       

        $search_group = $search_result->groupBy('business_category');
        // $search_result = $search_result->toArray();
      
        $data_points = array();
        $search_info = ["name" => "Business Report", "axisY" => "Number of Business", "axisX" => "Business Type"];
        foreach ($search_group as $key => $result) {
            $points = array("y" => count($result), "label" => $key);
            array_push($data_points, $points);
        }
         return response()->json(['status'=>true,'search_result'=>$search_result,'business_name'=>$business_name,'data_points'=>$data_points,'search_info'=>$search_info]);
        // return view('EnumerationReport.general_bussiness_report',compact('data_points','search_info','search_result'));
    }

    public function business_result_report_building(Request $request){

        $business_name = $request->business_name;
        $building_number = $request->building_number;
        $business_lga = $request->lga;
        $ward = $request->ward;
        $street = $request->street;
        $registered_by = $request->registered_by;
        $registered_on = $request->registered_on;
        $registered_to = $request->registered_to;
        $serviceids=serviceId();

         $search_result = DB::table('businesses')
            ->select(
            'businesses.*',
            'wards.*',
            'buildings.street as buildings_street',
            'buildings.building_type',
            'buildings.created_by as  buildings_created_by',
            'buildings.created_at as buildings_created_at',
            '_apartments.aparment',
            '_apartments.registered_on as apartment_created_at',
            '_apartments.registered_by as apartment_created_by',
            DB::raw('count(businesses.business_name) as total_business,count(_apartments.aparment) as total_apartments,
                count(buildings.building) as total_buildings,count(wards.ward) as total_wards')
            )
            ->leftJoin('buildings', 'businesses.building_id', '=', 'buildings.building_id')
            ->leftJoin('_apartments', 'businesses.apartment_id', '=', '_apartments.apartment_id')
            ->leftJoin('wards', 'businesses.business_lga', '=', 'wards.lga')
            ->where('businesses.service_id', $serviceids)
            ->where('businesses.business_name', 'like', '%' . $business_name. '%')
            ->Where('buildings.building_number', 'like', '%' . $building_number . '%')
            ->Where('businesses.business_lga', 'like', '%' .$business_lga . '%')
          //  ->Where('wards.ward', 'like', '%' .$ward. '')
            ->Where('buildings.street', 'like','%' . $street. '%')
            ->Where('businesses.created_by', 'like','%' . $registered_by. '%')
            ->WhereBetween('businesses.created_at', [$registered_on,$registered_to])
            ->get();
            
            // dd($search_result);
            $search_group = $search_result->groupBy('building_type');
            $data_points = array();
            $search_info = ["name" => "Building Report", "axisY" => "Number of Buildings", "axisX" => "Building Type"];
            foreach ($search_group as $key => $result) {
                $points = array("y" => count($result), "label" => $key);
                array_push($data_points, $points);
            }
            return response()->json(['status'=>true,'search_result'=>$search_result,'business_name'=>$business_name,'data_points'=>$data_points,'search_info'=>$search_info]);
        // return view('EnumerationReport.bussiness_result_report_building',compact('search_result','data_points','search_info'));
    }

     public function business_result_report_ward(Request $request){
        $business_name = $request->business_name;
        $building_number = $request->building_number;
        $business_lga = $request->lga;
        $ward = $request->ward;
        $street = $request->street;
        $registered_by = $request->registered_by;
        $registered_on = $request->registered_on;
        $registered_to = $request->registered_to;
        $serviceids=serviceId();

         $search_result = DB::table('businesses')
            ->select(
            'businesses.*',
            'wards.*',
            'wards.created_at',
            'wards.created_by',
            'wards.ward as wards_found',
            'buildings.street as buildings_street',
            'buildings.building_type',
            'buildings.created_by as  buildings_created_by',
            'buildings.created_at as buildings_created_at',
            '_apartments.aparment',
            '_apartments.registered_on as apartment_created_at',
            '_apartments.registered_by as apartment_created_by',
            DB::raw('count(businesses.business_name) as total_business,count(_apartments.aparment) as total_apartments,
                count(buildings.building) as total_buildings,count(wards.ward) as total_wards')
            )
            ->leftJoin('buildings', 'businesses.building_id', '=', 'buildings.building_id')
            ->leftJoin('_apartments', 'businesses.apartment_id', '=', '_apartments.apartment_id')
            ->leftJoin('wards', 'businesses.business_lga', '=', 'wards.lga')
            ->where('businesses.service_id', $serviceids)
            ->where('businesses.business_name', 'like', '%' . $business_name. '%')
            ->Where('buildings.building_number', 'like', '%' . $building_number . '%')
            ->Where('businesses.business_lga', 'like', '%' .$business_lga . '%')
           // ->Where('wards.ward', 'like', '%' .$ward. '')
            ->Where('buildings.street', 'like','%' . $street. '%')
            ->Where('businesses.created_by', 'like','%' . $registered_by. '%')
            ->WhereBetween('businesses.created_at', [$registered_on,$registered_to])
            ->get();
        $search_group = $search_result->groupBy('wards_found');
        $data_points = array();
        $search_info = ["name" => "Wards Report", "axisY" => "Number of Buildings", "axisX" => "Buildings"];

        foreach ($search_group as $key => $result) {
            $points = array("y" => count($result), "label" => $key);
             
            array_push($data_points, $points);
        }
        // dd($search_result);

        // return view('EnumerationReport.business_result_report_ward',compact('data_points','search_info','search_result'));
         return response()->json(['status'=>true,'search_result'=>$search_result,'business_name'=>$business_name,'data_points'=>$data_points,'search_info'=>$search_info]);
        
    }

    public function business_result_report_apartment(Request $request){
        $business_name = $request->business_name;
        $building_number = $request->building_number;
        $business_lga = $request->lga;
        $ward = $request->ward;
        $street = $request->street;
        $registered_by = $request->registered_by;
        $registered_on = $request->registered_on;
        $registered_to = $request->registered_to;
        $serviceids=serviceId();

         $search_result = DB::table('businesses')
            ->select(
            'businesses.*',
            'wards.*',
            'buildings.street as buildings_street',
            'buildings.building_type',
            'buildings.created_by as  buildings_created_by',
            'buildings.created_at as buildings_created_at',
            '_apartments.aparment',
            '_apartments.registered_on as apartment_created_at',
            '_apartments.registered_by as apartment_created_by',
            DB::raw('count(businesses.business_name) as total_business,count(_apartments.aparment) as total_apartments,
                count(buildings.building) as total_buildings,count(wards.ward) as total_wards')
            )
            ->leftJoin('buildings', 'businesses.building_id', '=', 'buildings.building_id')
            ->leftJoin('_apartments', 'businesses.apartment_id', '=', '_apartments.apartment_id')
            ->leftJoin('wards', 'businesses.business_lga', '=', 'wards.lga')
            ->where('businesses.service_id', $serviceids)
            ->where('businesses.business_name', 'like', '%' . $business_name. '%')
            ->Where('buildings.building_number', 'like', '%' . $building_number . '%')
            ->Where('businesses.business_lga', 'like', '%' .$business_lga . '%')
          //  ->Where('wards.ward', 'like', '%' .$ward. '')
            ->Where('buildings.street', 'like','%' . $street. '%')
            ->Where('businesses.created_by', 'like','%' . $registered_by. '%')
            ->WhereBetween('businesses.created_at', [$registered_on,$registered_to])
            ->get();
        $search_group = $search_result->groupBy('_apartments.apartment_id');
        //dd($search_result);
        $search_result = $search_result->toArray();
        $data_points = array();
        $search_info = ["name" => "Apartment Report", "axisY" => "Number of Apartments", "axisX" => "Apartments"];

        foreach ($search_group as $key => $result) {
            $points = array("y" => count($result), "label" => $key);
            array_push($data_points, $points);
        }
         return response()->json(['status'=>true,'search_result'=>$search_result,'business_name'=>$business_name,'data_points'=>$data_points,'search_info'=>$search_info]);
        // return view('EnumerationReport.bussiness_result_report_apartment',compact('data_points','search_info','search_result'));
    }
}
