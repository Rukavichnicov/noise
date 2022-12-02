<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createAdminUser(): User
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Admin';
        $user->password = Hash::make('password');
        $user->id_role = ADMIN_USER;
        return $user;
    }

    public function createUsualUser(): User
    {
        $user = new User();
        $user->id = 2;
        $user->name = 'Admin';
        $user->password = Hash::make('password');
        $user->id_role = USUAL_USER;
        return $user;
    }

    public function createValidFoundationFile(): UploadedFile
    {
        $path = public_path('foundation1.pdf');
        $name = 'foundation1.pdf';
        $file_pdf = new UploadedFile($path, $name, 'application/pdf', 0, true);
        return $file_pdf;
    }

    public function createValidDataOneNoiseSource(): array
    {
        $file_pdf = $this->createValidFoundationFile();
        $arrayDataOneNoiseSource = [
            "count" => "1",
            "name_1" => "BТест",
            "mark_1" => "МаркаТест",
            "id_type_of_source_1" => "1",
            "distance_1" => "1",
            "la_31_5_1" => "50",
            "la_63_1" => "50",
            "la_125_1" => "50",
            "la_250_1" => "50",
            "la_500_1" => "50",
            "la_1000_1" => "50",
            "la_2000_1" => "50",
            "la_4000_1" => "50",
            "la_8000_1" => "50",
            "la_eq_1" => "80",
            "la_max_1" => "80",
            "remark_1" => "ПримечаниеТест",
            "foundation" => "ОбоснованиеТест",
            "submit" => "Добавить в базу данных",
            "file_name" => $file_pdf,
        ];
        return $arrayDataOneNoiseSource;
    }
}
