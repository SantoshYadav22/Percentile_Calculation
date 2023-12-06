<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Calculate_Percentile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\Percentile_Result;

class UserController extends Controller
{  
    public function registration(Request $request) {
        // Auth::logout();

        $title = "Registration";
        $registration_otp = "registration_otp";
        return view('frontend.Registration',['title'=>$title,'registration_otp'=>$registration_otp]);
    }

    public function login(Request $request) {
        // Auth::logout();

        $title = "Login";
        $login_otp = "login_otp";
        return view('frontend.Login',['title'=>$title,'login_otp'=>$login_otp]);
    }

    public function index(Request $request) {
        $title = "Add";
        $section = "Calculate Percentile";
        return view('frontend.AddUser',['title'=>$title,'section'=>$section]);
    }

    public function calculate(Request $request){
        // dd($request->all());
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:10', 
            'email' => 'required|unique:users|email|max:255',
            'percentile' => 'required|numeric|min:0|max:100',
        ];
    
        $messages = [
            'percentile.between' => 'The percentile must be between :min and :max.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors(), 'status' => 422]);
        }
    
        $data = [
            'name' => trim($request->name),
            'phone' => trim($request->phone),
            'email' => trim($request->email),
        ];
    
        $user = User::create($data);
        $userId = $user->id;
    
        $percentile = [
            'percentile' => trim($request->percentile),
            'user_id' => $user->id, 
        ];
    
        // $percentile_create = Calculate_Percentile::create($percentile);
        $percentile_create = $this->createPercentile($user, $percentile);
    
        if ($user && $percentile_create) {
            Auth::login($user);
            
            return response()->json(['message' => 'Calculated', 'status' => 200, 'data' => $percentile_create]);
        } else {
            return response()->json(['message' => 'Something Went Wrong', 'status' => 500]);
        }
    }

    protected function createPercentile(User $user, $percentile) {
        return Calculate_Percentile::create($percentile);
    }

    public function calculate_page(Request $request){
        $user = Auth::user()->name;
        $title = "Add";
        $section = "Calculate Percentile";
        return view('frontend.calculate_page',['user'=>$user,'title'=>$title,'section'=>$section]);
    }

    public function login_otp(Request $request){
        $login_submit = "login_submit";
        $mobile = $request->phone;
    
        $user = User::where('users.phone', $mobile)->first();
    
        $FourDigitRandomNumber = mt_rand(1111, 9999);
    
        if (!empty($user)) {
            $update_pass = User::where('phone', $mobile)->where('is_active', 1)->first();
            if ($update_pass) {
                $update_pass->update(['otp' => $FourDigitRandomNumber]); 
                $userId = $update_pass->id; 
    
                $url = "https://sms.mobileadz.in/api/push?apikey=618ded2911ed5&route=trans_dnd&sender=CONTRJ&mobileno=".$mobile."&text=ContractorJi%20Mobile%20Verify%20Code%20".$FourDigitRandomNumber."";
    
                $ch = curl_init();
    
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0
                ));
    
                /* get response */
                $output = curl_exec($ch);
    
                if(curl_errno($ch)) {
                    echo "error";
                }
    
                curl_close($ch);
    
                $response = ["message" => "OTP Sended successfully", "status" => 200, 'user_data' => $update_pass, 'login_otp' => $login_submit];
                return response($response);
            } else {
                $response = ["message" =>'User not exist', "status" => false];
                return response($response);        
            }
        } else {
            $response = ["message" =>'User not exist', "status" => false];
            return response($response);        
        }
    }
    

public function login_submit(Request $request){
    $phone = $request->phone;
    $validator = Validator::make($request->all(), [

        'phone' => 'required|max:14',
        'input_otp_1' => 'required|min:1',
        'input_otp_2' => 'required|min:1',
        'input_otp_3' => 'required|min:1',
        'input_otp_4' => 'required|min:1',

    ]);

    if ($validator->fails()){
        return response(['errors'=>$validator->errors()->all()], 422);
    }

    $otp = '';

    for ($i = 1; $i <= 4; $i++) {
        $input_name = 'input_otp_' . $i;
        $otp .= request($input_name);
    }

    $user = User::where('phone', $phone)->first();

    if ($user->otp != '' && $user->otp == $otp) {

        $auth = Auth::loginUsingId($user->id);
        $response = ['User' => 'You are Logged in','user'=>$user,'status'=>200,'auth'=>$auth,'otp'=>$otp];
        return response($response, 200);    

    } else {

        $response = ["message" =>'User not exist'];
        return response($response, 422);
    }
}

    function registration_otp(Request $request){

        // dd($request->all());    
        $mobile = $request->phone;
        $registration_submit = "registration_submit";
        $FourDigitRandomNumber = mt_rand(1111,9999);
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:10', 
            'email' => 'required|unique:users|email|max:255',
        ]);
    
        if ($validator->fails()) {
            return redirect('/login')->withErrors($validator)->withInput();
        }
        
        $user = User::where('phone', $mobile)->first();
       
        if ($user) {
            $response = ["message" => "Mobile Already Exist"];
            return response($response, 422); 
        } else {
    
            // $data = $request->input();
            try {
                if(isset($request->action) && $request->action == 'add') {
                    $user = new User;
                    $user->name = $request->name;                        
                    $user->phone = $mobile;
                    $user->email = $request->email;   
                    $user->otp = $FourDigitRandomNumber;                                                
                    $user->remember_token = Session::get('_token');
                    $user->save();
    
                    if($user && $user->otp == $FourDigitRandomNumber ) {
                        $response = ["message" => "OTP Sended successfully","status" => 200,'user'=>$user,'registration_otp'=>$registration_submit];
                        $url = "https://sms.mobileadz.in/api/push?apikey=618ded2911ed5&route=trans_dnd&sender=CONTRJ&mobileno=".$mobile."&text=ContractorJi%20Mobile%20Verify%20Code%20".$FourDigitRandomNumber."";
    
                        $ch = curl_init();
    
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_SSL_VERIFYHOST => 0,
                            CURLOPT_SSL_VERIFYPEER => 0
                        ));
    
                        /* get response */
                        $output = curl_exec($ch);
    
                        if(curl_errno($ch)) {
                            echo "error";
                        }
    
                        curl_close($ch);
    
                        Auth::loginUsingId($user->id);
                        return response($response,"200");
                    }
                } else {
                    return response()->json(array('status'=>'error','message'=>'Action Type Not Defined'));
                }
            } catch(Exception $e){
                return response('insert')->with('failed',"operation failed");
            }
        }
    }

public function registration_submit(Request $request){
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255|min:3',
        'phone' => 'required|max:10',
        'email' => 'required',
        'input_otp_1' => 'required|min:1',
        'input_otp_2' => 'required|min:1',
        'input_otp_3' => 'required|min:1',
        'input_otp_4' => 'required|min:1',
       
    ]);

    if ($validator->fails()) {
        return redirect('/login')->withErrors($validator)->withInput();
    }
    $otp = '';

    for ($i = 1; $i <= 4; $i++) {
        $input_name = 'input_otp_' . $i;
        $otp .= request($input_name);
    }
    $otp_verification = User::where('users.otp', $otp)->where('phone', $request->phone)->first();

    if ($otp_verification->otp !=  $otp) {
        $response = ["message" => "OTP Not Match"];
        return response($response, 422); 
    } else {
        try{
            if(isset($request->action) && $request->action == 'add' && $otp_verification->otp ==$otp) {                         

                // $user = User::where('otp', $otp)
                //     ->update(['is_active' => 1]);  
                    
                    $user = User::where('otp', $otp)->where('phone', $request->phone)->first(); 
                    if ($user) {
                        $user->update(['is_active' => 1]); 
                        $userId = $user->id; 
                        
                    } else {
                        // Handle the case where the user with the given OTP is not found
                    }
                
                $response = ["message" => "Registration successfully","status" => 200,'user'=>$user,'otp'=>$otp];

                Auth::loginUsingId($userId);

                return response($response,"200");
            } else {
                return response()->json(array('status'=>'error','message'=>'Action Type Not Defined'));
            }
        } catch(Exception $e){
            return response('insert')->with('failed',"operation failed");
        }
    }
}

function calculate_percentile(Request $request){

    $G4 = $request->marks;
    if ($request->slot == 1) {
        $marks = $G4 * 1.031;
    } 
    elseif ($request->slot == 2) {
        $marks = $G4 * 0.982;
    }
    elseif ($request->slot == 3) {
        $marks = $G4 * 1.008;
    }  
    else {
        $marks = 'Slot is not equal to 1';
    }
    $result =  intval($marks);
    $percentile = Percentile_Result::where('marks',$result)->first();
    $data = [
        'slot' => trim($request->slot),
        'marks' => $result,
        'percentile' => $percentile->percentile,
        'user_id'=>Auth::id(),
    ];
    // dd($data);

     Calculate_Percentile::create($data);
    // $userId = $user->id;

    $response = ["message" => "Calculated successfully","status" => 200,'result'=>$percentile->percentile];
    return response($response);
}


    
}
