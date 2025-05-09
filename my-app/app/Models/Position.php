<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'category_id'];

    public function positionTels()
    {
        return $this->hasMany(PositionTel::class);
    }

    public function positionUsers()
    {
        return $this->hasMany(PositionUser::class);
    }

    public function positionImages()
    {
        return $this->hasMany(PositionImage::class);
    }

    public function positionVideos()
    {
        return $this->hasMany(PositionVideo::class);
    }

    public function positionMaps()
    {
        return $this->hasMany(PositionMap::class);
    }

    public function positionChats()
    {
        return $this->hasMany(PositionChat::class);
    }

    public function positionForms()
    {
        return $this->hasMany(PositionForm::class);
    }

    public function positionFormQuestions()
    {
        return $this->hasMany(PositionFormQuestion::class);
    }

    public function positionFormQuestionAnswers()
    {
        return $this->hasMany(PositionFormQuestionAnswer::class);
    }

    public function positionProductOrders()
    {
        return $this->hasMany(PositionProductOrder::class);
    }
}
