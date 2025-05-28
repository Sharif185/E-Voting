<?php
// app/Http/Controllers/Traits/ElectionTrait.php
namespace App\Http\Controllers\Traits;

use App\Models\Election;

trait ElectionTrait
{
    protected function getRecentElections($limit = null)
    {
        $query = Election::where('start_date', '>', now())
            ->where('is_active', true)
            ->orderBy('start_date', 'asc');

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }
}
