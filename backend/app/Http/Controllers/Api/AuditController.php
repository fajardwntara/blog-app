<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;

class AuditController extends Controller
{
    public function postAudits()
    {
        $audits = Audit::where('auditable_type', Post::class)
            ->with('user')
            ->latest()
            ->get();

        return $audits->map(function ($audit) {
            $data = $audit->toArray();

            // Ubah old_values dan new_values jika mengandung category_id
            if (isset($data['old_values']['category_id'])) {
                $category = Category::find($data['old_values']['category_id']);
                $data['old_values']['category_name'] = $category ? $category->name : null;
            }

            if (isset($data['new_values']['category_id'])) {
                $category = Category::find($data['new_values']['category_id']);
                $data['new_values']['category_name'] = $category ? $category->name : null;
            }

            return $data;
        });
    }

    public function categoryAudits()
    {
        return Audit::where('auditable_type', Category::class)
            ->with('user')
            ->latest()
            ->get();
    }

    public function userAudits()
    {
        return Audit::where('auditable_type', User::class)
            ->with('user')
            ->latest()
            ->get();
    }
}
