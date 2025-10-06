<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldReceipt extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'file_path', 'amount_paid', 'balance'];

}
