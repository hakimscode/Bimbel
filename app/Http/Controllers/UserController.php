<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function loginUser(Request $request){
        $status = false;
        $message = "";
        $result = array();

        $username = $request['username'];
        $password = $request['password'];

        $row = User::where('username', $username)->first();
        // $row = User::find(8);
        if($row){
            if(Hash::check($password, $row->password)){
                if($row->status == 1){
                    $status = true;
                    $message = "Berhasil Login";
                    $result[0] = $row->toArray();
                }else{
                    $message = "Akun anda belum di aktivasi";   
                }
            }else{
                $message = "Password salah";
            }
        }else{
            $message = "Username tidak dikenali";
        }

        return response()->json(array("status" => $status, "message" => $message, "result" => $result));
    }

    public function aktivasiUser($userid){
        $status = false;
        $message = "";
        
        $row = User::find($userid);
        if($row){
            $aktivasi = $row->update(['status' => 1]);
            if($aktivasi){
                $status = $aktivasi;
                $message = "Aktivasi user berhasil";
            }else{
                $message = "Gagal mengaktivasi user";
            }
        }else{
            $message = "User tidak ada";
        }

        return response()->json(array("status" => $status, "message" => $message));
    }

    public function registerUser(Request $request){
        $status = false;
        $message = "";
        $result = array();

        $insert = User::insertGetId([
            'jenis_user' => $request['jenis_user'],
            'nama_lengkap' => $request['nama_lengkap'],
            'alamat' => $request['alamat'],
            'email' => $request['email'],
            'no_hp' => $request['no_hp'],
            'status' => $request['status'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        if($insert){
            $status = true;
            $message = "Berhasil menambahkan user";
            $result = $insert;
        }

        return response()->json(array("status" => $status, "message" => $message));
    }

    public function listUser(){
        $status = false;
        $message = "";
        $result = array();

        $user = User::whereNotIn('jenis_user', [0])->get();

        if(!$user->isEmpty()){
            $status = true;
            $message = "Berhasil";
            $result = $user;
        }

        return response()->json(array("status" => $status, "message" => $message, "result" => $result));
    }

}