<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://laravel.com/img/logomark.min.svg" width="100" alt="Laravel Logo">
  </a>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <a href="https://postman.com" target="_blank">
    <img src="https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/postman-icon.svg" width="60" alt="Postman Logo">
  </a>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <a href="https://en.wikipedia.org/wiki/File_format" target="_blank">
    <img src="https://uxwing.com/wp-content/themes/uxwing/download/file-and-folder/file-icon.svg" width="60" alt="File Upload Logo">
  </a>
</p>

<h1 align="center">📌 Laravel Build a File Upload Endpoint API</h1>

<p align="center">
  A RESTful API built with Laravel for managing tasks, including image/file uploads. Tested using Postman and powered by MySQL.
</p>

---

## 📋 Table of Contents

- [Step 1: Install and Create Laravel Application](#step-1-install-and-create-laravel-application)
- [Step 2: Database Configuration using `.env` File](#step-2-database-configuration-using-env-file)
- [Step 3: Make Model and Migration File](#step-3-make-model-and-migration-file)
- [Step 4: Make Controller](#step-4-make-controller)
- [Step 5: Create Routes](#step-5-create-routes)
- [Step 6: Create Folder for Uploads](#step-6-create-folder-for-uploads)
- [Step 7: Run Application](#step-7-run-application)
- [Step 8: Upload Images/File using Postman](#step-8-upload-imagesfile-using-postman)
- [Step 9: View Complete Code](#step-9-view-complete-code)

---

## ✅ Step 1: Install and Create Laravel Application


    composer create-project laravel/laravel laravel_api_file_upload_endpoint
    cd laravel_api_file_upload_endpoint

## ✅ Step 2: Database Configuration using .env File

Open .env and configure your MySQL database:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=task_manager
    DB_USERNAME=root
    DB_PASSWORD=

## ✅ Step 3: Make Model and Migration File

    php artisan make:model File -m

then open the migration file and add code and run

    php artisan migrate

Update the File model app/Models/File.php: 
    
    <?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class File extends Model
    {
        use HasFactory;

        protected $fillable = ['file'];

    }


## ✅ Step 4: Make Controller

     php artisan make:controller FileController

and make the function  

    public function fileUpload(Request $request) {
        $fileObj = new File;

        if($request->hasFile('file')) {
            $filename = $request->file('file')->getClientOriginalName();
            $getfilenamewitoutext = pathinfo($filename, PATHINFO_FILENAME);
            $getfileExtension = $request->file('file')->getClientOriginalExtension();
            $createnewFileName = time().'_'.str_replace(' ','_', $getfilenamewitoutext).'.'.$getfileExtension;
            $img_path = $request->file('file')->storeAs('public/post_file', $createnewFileName);
            $fileObj->file = $createnewFileName;
        }

        if($fileObj->save()) {
            return ['status' => true, 'message' => "file uploded successfully"];
        }
        else {
            return ['status' => false, 'message' => "Error : Image not uploded successfully"];

        }
    }
    
## ✅ Step 5: Create Routes

    use App\http\controllers\FileController;

    Route::post('fileupload', [FileController::class, 'fileUpload']);

## ✅ Step 6: Create Folder for Uploads
    php artisan storage:link
Uploaded images will be stored in storage/app/public/uploads.

## ✅ Step 7: Run Application
    php artisan serve

Server will run at: http://localhost:8000

## ✅ Step 8: Upload Images/File using Postman

- Method: POST

- URL: http://localhost:8000/api/fileupload

- Body Type: form-data

    - Key → file

    - image → Upload a JPG/PNG image

Make sure to set the Content-Type to multipart/form-data in Postman.

## 🛠 Tools & Technologies

- [Laravel](https://laravel.com/)
- [PHP](https://www.php.net/) 8.2 or higher
- [Composer](https://getcomposer.org/)
- [XAMPP](https://www.apachefriends.org/) / [MySQL](https://www.mysql.com/)
- [Postman](https://www.postman.com/)

---


📂 Sample Folder Structure


    app/
    └── Http/
        └── Controllers/
            └── FileController.php

    └── Models/
        └── File.php

    routes/
    └── api.php


    storage/
    └── app/
        └── public/
            └── uploads/
