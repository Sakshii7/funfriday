<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Hash;
use stdClass;


class ApiController extends Controller
{
    public function employees()
    {
        $employees = DB::table('employees')->get();
        
        return response()->json( $employees);
    }
    
    public function add_employee(Request $request)
    {
        {
            $validator = Validator::make($request->all(),[
                'name' => 'required|max:50|min:2',
            ]);
            
            if($validator->fails())
            {
                return json_encode($validator->errors());
                
            }else{
                
                $name = $request->name;
                
                $employees = [
                    'name'=> $name,
                ];
                $user = DB::table('employees')->insert($employees);
                
                return response()->json( ['data'=>$user,'message'=>'added successfully','status_code'=>200]);
            }
        }
    }
    
    public function employee_delete(Request $request,$id)
    {
        $user=DB::table('employees')->where ('id',$id);
        $user->delete();
        return response()->json(['data'=>'','message'=>'deleted successfully','status_code'=>200]);
    }
    
    public function employee_update(Request $request, $id)
    {
        {
            $validator = Validator::make($request->all(),[
                'name' => 'required|max:50|min:2',
            ]);
            
            if($validator->fails())
            {
                return json_encode($validator->errors());
                
            }else{
                $name = $request->name;
                
                $employees = [
                    'name'=> $name,
                ];
                //  dd($employees);
                DB::table('employees')->where('id', $id)->update($employees);
                return response()->json(['data'=>$employees,'message'=>'updated successfully','status_code'=>200]);
            }
        }
    }
    
    public function login(Request $req)
    {
        $validator = Validator::make($req->all(),[
            
            'email' => 'required|max:255|regex:/(.+)@(.+)\.(.+)/i',
            'password'=>'required| min:6',
        ]);
        if($validator->fails())
        {
            return json_encode($validator->errors());
        }else{
            
            $user=DB::table('users')->where('email',$req->email)->first();
            
            if(!$user || !Hash::check($req->password,$user->password))
            {
                
                return response()->json(['data'=>'','message'=>'Email or password is incorrect','status_code'=>204]);
                
            }else{
                
                $data=DB::table('users')->where('email',$req->email)->first(['id','name','email','role']);
                $employees = DB::table('employees')->get();
                
                return response()->json(['data'=>['logged User'=>$data,'Employees'=>$employees],'message'=>'Login Successful','status_code'=>200]);
            }
        }
    }
    
    public function filtered_employees(Request $request)
    {
        $absent_employees = $request->absent_employees;
        $absent_employees = explode(',', $absent_employees);
        $present = DB::table('employees')->whereNotIn('id',$absent_employees)->get();
        
        DB::table('temp_present_employee')->delete();
        
        foreach ($present as $key => $value){
            DB::table('temp_present_employee')->insert(['employee_id' => $value->id,'name'=>$value->name]);
        }

        return response()->json(['data'=>$present,'message'=>'','status_code'=>200]);
    }
    
    
    public function create_teams(Request $request)
    {
        
        $team_name = $request->team_name;
        $team_names = explode(',', $team_name);
        $teams_count=count($team_names);
        
        // $absent_employees = $request->absent_employees;
        // $absent_employees = explode(',', $absent_employees);
        $present_shuffled_employees = DB::table('temp_present_employee')->orderByRaw("RAND()")->pluck('employee_id')->toArray();
        
        $count_present_shuffled_employees = count($present_shuffled_employees);
        
        $captain_id=$request->captain_id;
        $captain_ids = explode(',', $captain_id);
        
        $allCapFilled = [];
        while(count($allCapFilled) < $teams_count){
            foreach($captain_ids as $key => $value ){ 
                if(empty($value)){
                    $random_captain = (string)$present_shuffled_employees[rand(1,$count_present_shuffled_employees-1)];
                    if(!in_array($random_captain,$captain_ids)){
                        $captain_ids[$key]= $random_captain;
                    }
                }else{
                    $allCapFilled[] = 1;
                }
            }
        }
        
        $present_emp_without_captain = array_diff($present_shuffled_employees,$captain_ids);
        //  dd($present_emp_without_captain);
        
        DB::table('team_members')->delete();
        
        $i = 0;
        $j= 2;
        
        $nextStartAgain =  $teams_count-1;
        
        $present_shuffled_employees= array_merge($captain_ids,$present_emp_without_captain);
        
        
        foreach ($present_shuffled_employees as $key => $value) {
            
            DB::table('team_members')->insert(['employee_id' => $value, 'team_name' => $team_names[$i], 'captain_id' => $captain_ids[$i]]);
            
            if($key == $nextStartAgain){
                
                $nextStartAgain =  ($teams_count * $j)-1;
                $i = 0;
                $j++;
            }else{
                $i++;
            }
        }
        $result = [];
        foreach ($team_names as $key => $value) {
            $teams=DB::table('team_members')->join('employees','team_members.employee_id','=','employees.id')->where('team_members.team_name',$value)->select('team_members.team_name','team_members.employee_id','employees.name','team_members.captain_id')->get();
            
            $team_obj = new stdClass();
            $team_obj->team_name=$value;
            $team_obj->players = $teams;        
            $result[] = $team_obj;
        }
        return response()->json(['data'=>$result,'message'=>'Team created successfully','status_code'=>200]);    
    }
}

