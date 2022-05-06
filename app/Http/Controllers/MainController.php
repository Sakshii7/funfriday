<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Auth;
use stdClass;
use Session;

class MainController extends Controller
{
    
    public function login()
    {
        return view('admin.login');
    }
    
    public function loginpost(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $auth_data = [
            'email' => $email,
            'password' => $password,
        ];
        
        
        // $user = DB::table('users')->where('email', $request->email)->first(); 
        if (Auth::attempt($auth_data)) {
            return redirect('dashboard');
        } else {
            return back()->with('error', 'Invalid email or password!');
        }
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect('login')->with('success','You have been successfully logged out.');
    }
    
    public function employees()
    {
        $employees = DB::table('employees')->get();
        
        return view('admin.employeelist')->with(['employees' => $employees]);
    }
    
    public function add_employee()
    {
        return view('admin.add-employee');
    }
    
    public function employee_addPost(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:50|min:2',
        ]);
        
        $name = $request->name;
        
        $employees = [
            'name'=> $name,
        ];
        $user = DB::table('employees')->insert($employees);
        
        return redirect('/employee-list')->with('success','Your Data added successfully');
    }
    
    public function employee_delete(Request $request,$id)
    {
        $user=DB::table('employees')->where ('id',$id);
        $user->delete();
        return redirect('/employee-list')->with('success','Your Data is Deleted');
    }
    
    public function employee_edit($id)
    {
        $employees=DB::table('employees')->find($id);
        return view('admin.employee-edit')->with('employees',$employees);
    }
    
    public function employee_update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:50|min:2',
        ]);
        
        $name = $request->name;
        
        $employees = [
            'name'=> $name,
        ];
        //  dd($employees);
        DB::table('employees')->where('id', $id)->update($employees);
        
        return redirect('/employee-list')->with('success','Your Data Updated successfully');
    }
    
    public function createteams()
    {
        $employees = DB::table('employees')->get();
        return view('admin.create-teams')->with(['employees' => $employees]);
    }

    public function filtered_employees(Request $request)
    {
        $absent_employees = $request->absent_employees;
        // $absent_employees = explode(',', $absent_employees);
        $present = DB::table('employees')->whereNotIn('id',$absent_employees)->get();
        DB::table('temp_present_employee')->delete();
        
        foreach ($present as $key => $value){
            DB::table('temp_present_employee')->insert(['employee_id' => $value->id,'name'=>$value->name]);
        }
        Session::put(['present'=>$present,'no_of_teams'=>$request->no_of_teams]);
       
        return redirect('/filter_list');
    }
    

    public function filterlist()
    {
        $present=Session::get('present');
        $no_of_teams=Session::get('no_of_teams');
        // $request = Session::get('no_of_teams');
        return view('admin.select-captains')->with(['present'=>$present,'no_of_teams'=>$no_of_teams]);
    }
    
    public function selectCaptains(Request $request)
    {
        $team_name = $request->team_name;
        // $team_names = explode(',', $team_name);
        $teams_count=count($team_name);
        // $absent_employees = $request->absent_employees;
        // $absent_employees = explode(',', $absent_employees);
        $present_shuffled_employees = DB::table('temp_present_employee')->orderByRaw("RAND()")->pluck('employee_id')->toArray();
        
        $count_present_shuffled_employees = count($present_shuffled_employees);
        
        $captain_id=$request->captain_id;
        // $captain_ids = explode(',', $captain_id);
        
        $allCapFilled = [];
        while(count($allCapFilled) < $teams_count){
            foreach($captain_id as $key => $value ){ 
                if(empty($value)){
                    $random_captain = (string)$present_shuffled_employees[rand(1,$count_present_shuffled_employees-1)];
                    if(!in_array($random_captain,$captain_id)){
                        $captain_id[$key]= $random_captain;
                    }
                }else{
                    $allCapFilled[] = 1;
                }
            }
        }
        $present_emp_without_captain = array_diff($present_shuffled_employees,$captain_id);
        //  dd($present_emp_without_captain);
        
        DB::table('team_members')->delete();

        $i = 0;
        $j= 2;
        
        $nextStartAgain =  $teams_count-1;
        
        $present_shuffled_employees= array_merge($captain_id,$present_emp_without_captain);
        
        foreach ($present_shuffled_employees as $key => $value) {
            
            DB::table('team_members')->insert(['employee_id' => $value, 'team_name' => $team_name[$i], 'captain_id' => $captain_id[$i]]);
            
            if($key == $nextStartAgain){
                
                $nextStartAgain =  ($teams_count * $j)-1;
                $i = 0;
                $j++;
            }else{
                $i++;
            }
        }   
        $result = [];
        foreach ($team_name as $key => $value) {
            $teams=DB::table('team_members')->join('employees','team_members.employee_id','=','employees.id')->where('team_members.team_name',$value)->select('team_members.team_name','team_members.employee_id','employees.name','team_members.captain_id')->get();
            
            $team_obj = new stdClass();
            $team_obj->team_name=$value;
            $team_obj->players = $teams;        
            $result[] = $team_obj;
        }   
        //  dd($result);
        Session::put('teams',$result);
        return redirect('/teams');
    }
    
    public function Teams()
    {
        $result = Session::get('teams');
        if(empty($result)){
            return redirect('/create-teams');
        }else{
            //  dd($result);
            return view('admin.teams')->with(['result'=>$result]);
        }
        
    }
}

