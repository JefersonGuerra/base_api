<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PgSql\Lob;

class UserModel extends Model
{

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected const PATH_PREFIX = 'public/users';

    public static function rules($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['bail', 'required', 'max:255'],
                'email' => ['bail', 'required', 'max:255'],
                'password' => ['bail', 'required', 'max:255'],
            ],
            [
                'required' => 'O campo :attribute é obrigatório',
            ],
            [
                'name' => 'Nome',
                'email' => 'E-mail',
                'password' => 'Senha',
                'image' => 'Imagem',
            ]
        );

        if ($validator->fails()) {
            return response(["error" => $validator->errors()->first()], 422);
        }
    }

    public function getImageAttribute($value)
    {
        $disk = Storage::disk($this->file_disk);
        if (!$disk->exists(self::PATH_PREFIX . "/" . $value)) return null;
        return env('APP_URL') . Storage::url(self::PATH_PREFIX . '/' . $value);
    }

    public function setImageAttribute(UploadedFile $file)
    {
        try {
            $filesys = Storage::disk($this->file_disk);
            $filesys->delete(self::PATH_PREFIX . "/" . $this->attributes['image']);
        } catch (\Throwable $th) {
        }
        try {
            $file_name = uniqid('users_') . "-" . now()->format('d-m-Y') . "." . $file->getClientOriginalExtension();
            if (Storage::putFileAs(self::PATH_PREFIX, $file, $file_name))
                $this->attributes['image'] = $file_name;
        } catch (\Throwable $th) {
            report($th);
        }
    }

    public function get($request)
    {
        if ($request->get('page')) {
            return UserModel::query()->paginate(4);
        } else {
            return UserModel::all();
        }
    }

    public function post($request)
    {
        $request['password'] =  Hash::make($request['password']);
        $user = UserModel::create($request->all());
        return $user;
    }

    public function put($request)
    {
        $user = UserModel::find($request['id']);
        if ($request['password']) {
            $request['password'] =  Hash::make($request['password']);
        }
        $user = $user->update(array_filter($request));
        return $user;
    }

    public function deleteUser($request)
    {
        $user = UserModel::find($request['id']);
        $user = $user->delete();
        return $user;
    }
}
