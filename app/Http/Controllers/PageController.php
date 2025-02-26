<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Application;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Tour;

use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class PageController extends Controller
{
    public function index () {
        $soon = Tour::select('*')->get();
        $count = $soon->count();
        return view('index', ['tours'=>$soon, 'count' => $count]);
    }
    public function all_tours () {
        $soon = Tour::select('*')->get();
        $count = $soon->count();
        return view('admin/all_tours', ['tours'=>$soon, 'count' => $count]);
    }
    public function create_tour () {
        return view('admin/create_tour');
    }
    public function create_tour_db (Request $request) {
        $validator = Validator::make($request->all(), [
            "date_start"=>"required",
            "date_end"=>"required|after:date_start",
            'name'=>'required|max:200',
            'image'=>'required|image',
            'price'=>'required|numeric',
            'description'=>'required',
        ],
        $messages = [
            'date_start.required' => 'Не заполнена дата начала',
            'date_end.required' => 'Не заполнена дата окончания',

            'date_end.after' => 'Дата окончания не может быть раньше даты начала',
            'name.required'=>'Не заполнено имя',
           'name.max'=>'Слишком длинное имя',

           'image.required'=>'Не отправлено изображение',
           'image.image'=>'Неверный формат изображения',

           'price.required'=>'Не заполнена цена',
           'price.numeric'=>'Неверный формат цены',

           'description.required'=>'Не заполнено описание',
        ]
        );
        if ($validator->fails()) {
            return back()
            ->withErrors($validator)
            ->withInput();
        }
        else {
            $extention = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path() . '/images', $extention);

            $tour = Tour::create(['name'=>$request->name,
            'description'=>$request->description,
            'date_start'=>$request->date_start,
            'date_end'=>$request->date_end,
            'image'=>$extention,
            'price'=>$request->price,]);

            return redirect()->route('all_tours')->withErrors(['message'=>'Вы успешно создали тур!']);
        }
    }
    public function index_sfs (Request $request) {
        $data = Tour::select('*');
        if ($request->search != null && $request->search != '') {
            $data = $data->where('tours.name', 'LIKE', '%'.$request->search.'%');
         }
         if ($request->filter != null && $request->filter != '') {
            if ($request->filter == '1-5') {
                $data = $data->whereRaw('DATEDIFF(date_end, date_start) BETWEEN ? AND ?', [1, 5]);
                
            }
            else if ($request->filter == '6-9') {
                $data = $data->whereRaw('DATEDIFF(date_end, date_start) BETWEEN ? AND ?', [6, 9]);
            }
            else {
                $data = $data->whereRaw('DATEDIFF(date_end, date_start) BETWEEN ? AND ?', [10, 15]);
            }
         }
         if ($request->sort != null && $request->sort != '') {
             $data = $data->orderBy('price', $request->sort);
         }
         $data = $data->get();
         $count = $data->count();
         return view('index', ['tours'=>$data, 'count' => $count]);
 
    }
    public function login_show () {
        return view('login');
    }
    public function reg_show () {
        return view('reg');
    }
    public function login (Request $request) {
                    $data = [
                        'login'=>$request->login,
                        'password'=>$request->password,
                    ];
                    $rules = [
                        'login'=>'required',
                        'password'=>'required',
                    ];
                    $messages = [
                        'login.required'=>'Не заполнено поле логина',
                        'password.required'=>'Не заполнено поле пароля',];
                    $validate = Validator::make($data, $rules, $messages);
                    if($validate->fails()){
                        return back()
                        ->withErrors($validate)
                        ->withInput();
                    }
                    else{ 
                        $check = User::where('login','=', $request->login)->exists();
                        if($check == true){
                            $user = User::select('id','login','password','role')->where('login', '=', $request->login)->get();
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
            'login'=>'required|unique:users',
            'password'=>'required|min:6',
            'fio'=>'required|regex:/^[А-Яа-я- ]+$/u',
            'phone'=>'required|regex:/^\+7\d{3}-\d{3}-\d{2}-\d{2}+$/u',
            'email'=>'required|email',
        ];
        $messages = [
            'login.required'=>'Не заполнено поле логина',
            'password.required'=>'Не заполнено поле пароля',
            'fio.required'=>'Не заполнено поле ФИО',
            'phone.required'=>'Не заполнено поле телефона',
            'email.required'=>'Не заполнено поле электронной почты',
            'login.unique'=>'Такой логин занят',
            'password.min'=>'Пароль должен содержать минимум 6 символов',
            'fio.regex'=>'Неверный формат ФИО',
            'phone.regex'=>'Неверный формат телефона',
            'email.email'=>'Неверный формат электронной почты'];
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
            'login'=>$request->login,
            'password'=>Hash::make($request->password)]);
            Auth::login($user);
            return redirect()->route('my_appls')->withErrors(['message'=>'Вы вошли в профиль!']);
        }
                 
    }
    public function my_appls(){
            $soon = Application::select('applications.*', 'tours.name as name', 'tours.date_start as date_start', 'tours.date_end as date_end')->join('tours','tours.id', '=', 'applications.tour_id')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
            $count = $soon->count();
            return view('my_appls', ['appls'=>$soon, 'count' => $count]);
    }
    public function send_appl(Tour $id){
        return view('send_appl', ['tour' => $id]);
    }
    public function send_appl_db (Request $request) {
        $data = $request->all();
        $rules = [
            'phone'=>'regex:/^\+7\d{3}-\d{3}-\d{2}-\d{2}+$/u',
        ];
        $messages = [
            'phone.regex'=>'Неверный формат телефона',];
        $validate = Validator::make($data, $rules, $messages);
        if($validate->fails()){
            return back()
            ->withErrors($validate)
            ->withInput();
        }
        else{
           
            if ($request->comment == null) {
                $appl = Application::create(['user_id'=>Auth::user()->id,
                'tour_id'=>$request->id,
                'phone'=>$request->phone,
            ]);
            }
            else {
                $appl = Application::create(['user_id'=>Auth::user()->id,
                'phone'=>$request->phone,
                'comment'=>$request->comment,
                'tour_id'=>$request->id,
            ]);
            }
           
            return redirect()->route('my_appls')->withErrors(['message'=>'Вы отправили заявку!']);
        }

    }

    public function all_appls () {
        $soon = Application::select('applications.*', 'tours.name as name', 'tours.date_start as date_start', 'tours.date_end as date_end', 'users.fio')->join('tours','tours.id', '=', 'applications.tour_id')->join('users', 'users.id', '=', 'applications.user_id')->orderBy('created_at', 'DESC')->get();
       
            $count = $soon->count();
            return view('admin/all_appls', ['appls'=>$soon, 'count' => $count]);
    }

    public function change_status (Request $request) {
        if ($request->status == 'отменена') {
            Application::where('id', $request->id)->update(['status'=>$request->status, 'admin_text'=>$request->admin_text]);
        }
        else {
            Application::where('id', $request->id)->update(['status'=>$request->status,]);

        }
        return redirect()->route('all_appls')->withErrors(['message'=>'Вы изменили статус!']);
    }

}
