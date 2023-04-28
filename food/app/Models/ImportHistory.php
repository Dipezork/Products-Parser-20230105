<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    use HasFactory;

    protected $fillable = ['imported_at', 'status', 'file_name'];

    protected $attributes = [
        'file_name' => ''
    ];

    protected $dates = [
        'imported_at',
        'created_at',
        'updated_at',
    ];

    public $fileName = '';

    protected $table = "import_histories";
}

?>
