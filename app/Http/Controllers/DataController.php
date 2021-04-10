<?php

namespace App\Http\Controllers;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Portfolio;

use Illuminate\Http\Request;

use JWTAuth;

class DataController extends Controller
{
    public function open() 
    {
        $data = "This data is open and can be accessed without the client being authenticated";
        return response()->json(compact('data'),200);

    }

    public function closed() 
    {
        $data = "Only authorized users can see this";
        return response()->json(compact('data'),200);
    }

    public function addexperience(Request $request)
    {
        $start_day = 01;
		$end_day = 01;
		$start_month = $request->start_month;
		$start_year = $request->start_year;
		$end_month = $request->end_month;
		$end_year = $request->end_year;

		$start_duration = $start_year . '-' . $start_month . '-' . $start_day;
		$end_duration = $end_year . '-' . $end_month . '-' . $end_day;
	 	$start_DT = date('Y-m-d', strtotime("$start_duration"));
		$end_DT = date('Y-m-d', strtotime("$end_duration"));

		
    //     $roles = json_decode($request->roles, true);
        
    // $extracted_roles = implode(",", $roles);
    // $extracted_roles2 = join(",", $roles);


		
        // $data = [
        //     'start' => $start_DT,
        //     'end' => $end_DT,
        //     'position' => $request->exposition,
        //     'company_name' => $request->company_name,
        //     //'role' => $extracted_roles,
        //     'user_id' => auth()->user()->user_id,
        //     ];

        $experience = Experience::create([
            'user_id' => auth()->user()->user_id,
            'start' => $start_duration,
            'end' => $end_duration,
            'company_name' => $request->company_name,
            'position' => $request->exposition,
            //'roles' => 'dfefrfr',
        ]);

  
        return response()->json(compact('experience', 201));
       
    }

    public function experiences()
    {
        $experiences = Experience::where('user_id', auth()->user()->user_id)->get();
        return response()->json(compact('experiences'));
    }

    public function addeducation(Request $request)
    {
        $start_day = 01;
		$end_day = 01;
		$start_month = $request->start_month;
		$start_year = $request->start_year;
		$end_month = $request->end_month;
		$end_year = $request->end_year;

		$start_duration = $start_year . '-' . $start_month . '-' . $start_day;
		$end_duration = $end_year . '-' . $end_month . '-' . $end_day;
	 	$start_DT = date('Y-m-d', strtotime("$start_duration"));
		$end_DT = date('Y-m-d', strtotime("$end_duration"));

		
    //     $roles = json_decode($request->roles, true);
        
    // $extracted_roles = implode(",", $roles);
    // $extracted_roles2 = join(",", $roles);


		
        // $data = [
        //     'start' => $start_DT,
        //     'end' => $end_DT,
        //     'position' => $request->exposition,
        //     'company_name' => $request->company_name,
        //     //'role' => $extracted_roles,
        //     'user_id' => auth()->user()->user_id,
        //     ];

        $education = Education::create([
            'user_id' => auth()->user()->user_id,
            'start' => $start_duration,
            'end' => $end_duration,
            'school' => $request->school,
            'course' => $request->course,
            'class_of_degree' => $request->class_of_degree,
            //'skills' => 'dfefrfr',
        ]);

  
        return response()->json(compact('education', 201));
       
    }

    public function educations()
    {
        $educations = Education::where('user_id', auth()->user()->user_id)->get();
        if(empty($educations))
        {
            return response()->json(null);
        }
        else
        {
            return response()->json(compact('educations'));
        }
        
    }


    public function addportfolio(Request $request)
    {
        if ($request->hasFile('portfolio')) 
        {
            $portfolio_image_name = time(). $request->portfolio->getClientOriginalName();
            // $fileSize = $request->atm_card_file_name->getClientSize();
            $request->portfolio->move(public_path('uploads'), $portfolio_image_name);

            $portfolio = new Portfolio();
            $portfolio->user_id = auth()->user()->user_id;
            $portfolio->image = $portfolio_image_name;
            $portfolio->save();

            return response()->json([
                "success" => true,
                "message" => "Portfolio successfully uploaded",
                "portfolio_image_name" => $portfolio_image_name,
            ]);
        }
    }
}
