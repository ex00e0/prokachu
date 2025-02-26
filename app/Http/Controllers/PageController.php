<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Application;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Car;

use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class PageController extends Controller
{
    public function login_show () {
        return view('login');
    }
    public function reg_show () {
        return view('reg');
    }
    public function login (Request $request) {
                    $data = [
                        'email'=>$request->email,
                        'password'=>$request->password,
                    ];
                    $rules = [
                        'email'=>'required',
                        'password'=>'required',
                    ];
                    $messages = [
                        'email.required'=>'Не заполнено поле электронной почты',
                        'password.required'=>'Не заполнено поле пароля',];
                    $validate = Validator::make($data, $rules, $messages);
                    if($validate->fails()){
                        return back()
                        ->withErrors($validate)
                        ->withInput();
                    }
                    else{ 
                        $check = User::where('email','=', $request->email)->exists();
                        if($check == true){
                            $user = User::select('id','email','password','role')->where('email', '=', $request->email)->get();
                            foreach($user as $u){
                                $password = $u->password;
                                $id = $u->id;
                                $role = $u->role;
                            }
                            if (Hash::check($request->password, $password)) {
                                Auth::login( User::find($id));
                                if ($role == 'user') {
                                    return redirect()->route('my_appls')->withErrors(['message'=>'Вы вошли в профиль!']);
                                }
                                else if ($role == 'admin') {
                                    return redirect()->route('all_appls')->withErrors(['message'=>'Вы вошли в профиль как админ!']);
                                }
                            } else {
                                return back()->withErrors(['password'=>'Неверный пароль!'])->withInput();
                        }
                       }
                       else{
                            return back()->withErrors(['login'=>'Нет такого пользователя!'])->withInput();
                       }
                    }
    }
    public function logout () {
        Auth::logout();
        return redirect()->route('login_show')->withErrors(['message'=>'Вы вышли из аккаунта']);
    }
    public function reg (Request $request) {
        $data = $request->all();
        $rules = [
            'password'=>'required|min:3|regex:/\d/',
            'fio'=>'required|regex:/^[А-Яа-яA-Za-z- ]+$/u',
            'phone'=>'required|regex:/^\8\d{3}-\d{3}-\d{2}-\d{2}+$/u',
            'email'=>'required|email|unique:users',
            'drive_licence'=>'required',
        ];
        $messages = [
            'password.required'=>'Не заполнено поле пароля',
            'fio.required'=>'Не заполнено поле ФИО',
            'phone.required'=>'Не заполнено поле телефона',
            'email.required'=>'Не заполнено поле электронной почты',
            'drive_licence.required'=>'Не заполнено поле водительского удостоверения',
            'email.unique'=>'Электронная почта занята',
            'password.min'=>'Пароль должен содержать минимум 3 символа',
            'fio.regex'=>'Неверный формат ФИО',
            'phone.regex'=>'Неверный формат телефона',
            'email.email'=>'Неверный формат электронной почты',
            'password.regex' => 'В пароле должна присутствовать хотя бы 1 цифра'];
        $validate = Validator::make($data, $rules, $messages);
        if($validate->fails()){
            return back()
            ->withErrors($validate)
            ->withInput();
        }
        else{
            $user = User::create(['fio'=>$request->fio,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'drive_licence'=>$request->drive_licence,
            'password'=>Hash::make($request->password)]);
            Auth::login($user);
            return redirect()->route('my_appls')->withErrors(['message'=>'Вы вошли в профиль!']);
        }
                 
    }
    public function my_appls(){
            $soon = Application::select('applications.*', 'car.name')->join('cars', 'cars.id', '=', 'applications.car_id')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
            $count = $soon->count();
            return view('my_appls', ['appls'=>$soon, 'count' => $count]);
    }
    public function send_appl (){
        return view('send_appl');
    }
    public function send_appl_db (Request $request) {
$exist = Application:select('*')->where('date', $request->date)->where('car_id', $request->car_id)->get()->count();
            if ($exist != 0) {
                return redirect()->route('my_appls')->withErrors(['message_error'=>'Данный автомобиль на данную дату занят']);
            }
            else {
                $appl = Application::create(['user_id'=>Auth::user()->id,
                'car_id'=>$request->car_id,
                'date'=>$request->date,
                ]);
            return redirect()->route('my_appls')->withErrors(['message'=>'Вы отправили заявку!']);

            }
           
    }

    public function all_appls () {
        $soon = Application::select('applications.*', 'car.name')->join('cars', 'cars.id', '=', 'applications.car_id')->orderBy('created_at', 'DESC')->get();
       
            $count = $soon->count();
            return view('admin/all_appls', ['appls'=>$soon, 'count' => $count]);
    }

    public function decline_status (Request $request) {
       
        Application::where('id', $request->id)->update(['status'=>'отменена']);
        return redirect()->route('all_appls')->withErrors(['message'=>'Вы отклонили заявку!']);
    }

    public function accept_status (Request $request) {
       
        Application::where('id', $request->id)->update(['status'=>'принята']);
        return redirect()->route('all_appls')->withErrors(['message'=>'Вы приняли заявку!']);
    }

}
