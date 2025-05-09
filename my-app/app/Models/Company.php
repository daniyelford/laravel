<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone', 'email'];

    public function companyUsers()
    {
        return $this->hasMany(CompanyUser::class);
    }

    public function companyRoles()
    {
        return $this->hasMany(CompanyRole::class);
    }

    public function companyUserAccesses()
    {
        return $this->hasMany(CompanyUserAccess::class);
    }

    public function companyMeets()
    {
        return $this->hasMany(CompanyMeet::class);
    }

    public function companyMeetsUsers()
    {
        return $this->hasMany(CompanyMeetUser::class);
    }

    public function companyTasks()
    {
        return $this->hasMany(CompanyTask::class);
    }

    public function companyTaskUsers()
    {
        return $this->hasMany(CompanyTaskUser::class);
    }

    public function companyRoleRequests()
    {
        return $this->hasMany(CompanyRoleRequest::class);
    }

    public function companyCategoryProductPositions()
    {
        return $this->hasMany(CompanyCategoryProductPosition::class);
    }

    public function companyMaps()
    {
        return $this->hasMany(CompanyMap::class);
    }
}
