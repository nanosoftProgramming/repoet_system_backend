<?php


namespace Modules\User\DTO;

use Illuminate\Support\Facades\Hash;
class UserDto
{
    public $name;
    public $email;
    public $phone;
    public $password;
    public $identity_number;
    public $username;
    public $national_number;
    public $academy_id;
    public $birth_date;
    public function __construct($request)
    {
      if ($request->get('academy_id'))
            $this->academy_id = $request->get('academy_id');
        if ($request->get('name'))
            $this->name = $request->get('name');
        if ($request->get('email'))
            $this->email = $request->get('email');
        if ($request->get('phone'))
            $this->phone = $request->get('phone');
        if ($request->get('password'))
            $this->password = Hash::make($request->get('password'));
        if ($request->get('identity_number'))
            $this->identity_number = $request->get('identity_number');
        if ($request->get('username'))
            $this->username = $request->get('username');
        if ($request->get('national_number'))
            $this->national_number = $request->get('national_number');
        if ($request->get('birth_date'))
            $this->birth_date = $request->get('birth_date');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->name == null)
            unset($data['name']);
        if ($this->email == null)
            unset($data['email']);
        if ($this->phone == null)
            unset($data['phone']);
        if ($this->password == null)
            unset($data['password']);
        if ($this->identity_number == null)
            unset($data['identity_number']);
        if ($this->username == null)
            unset($data['username']);
        if ($this->national_number == null)
            unset($data['national_number']);
        if ($this->birth_date == null)
            unset($data['birth_date']);
          if ($this->academy_id == null)
            unset($data['academy_id']);
        return $data;
    }
}

