<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VoterElectionsController extends Controller
{

       public function showPendingElections()
           {
               // Get elections starting in the next 30 days but haven't ended yet
               $elections = Election::with(['candidates' => function($query) {
                       $query->where('approved', true);
                   }])
                   ->where('start_date', '>', now()->subDay()) // Show elections starting from yesterday onward
                   ->where('end_date', '>', now()) // Only elections that haven't ended
                   ->orderBy('start_date')
                   ->get();

               // Add status flags to each election
               $elections->each(function($election) {
                   $election->isActive = now()->between($election->start_date, $election->end_date);
                   $election->isFuture = now()->lt($election->start_date);
               });

               Log::info('Fetched elections:', $elections->toArray());

               return view('voter.pending-elections', [
                   'elections' => $elections,
                   'hasElections' => $elections->isNotEmpty()
               ]);
           }

    public static function getCandidatePhotoUrl(?string $filename): string
    {
        // Define default path with proper directory separators
        $defaultPath = 'storage/candidate_photos/default-candidate.jpg';
        $defaultFullPath = public_path(str_replace('/', DIRECTORY_SEPARATOR, $defaultPath));

        // Verify default image exists
        if (!file_exists($defaultFullPath)) {
            \Log::error('Default candidate image missing at: ' . $defaultFullPath);
            throw new \RuntimeException('Default candidate image not found');
        }

        if (empty($filename)) {
            return asset($defaultPath);
        }

        // Handle filename with proper encoding and path separators
        $encodedFilename = rawurlencode($filename);
        $path = 'storage/candidate_photos/' . $encodedFilename;
        $fullPath = public_path(str_replace('/', DIRECTORY_SEPARATOR, $path));

        // 1. Check exact match first
        if (file_exists($fullPath)) {
            return asset($path);
        }

        // 2. Case-insensitive fallback with directory scan
        $directory = public_path('storage/candidate_photos');
        if (file_exists($directory)) {
            $files = scandir($directory);
            $dbBasename = pathinfo($filename, PATHINFO_FILENAME);

            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;

                $fileBasename = pathinfo($file, PATHINFO_FILENAME);

                // Case-insensitive comparison without extensions
                if (strtolower($fileBasename) === strtolower($dbBasename)) {
                    $foundPath = 'storage/candidate_photos/' . $file;
                    return asset($foundPath);
                }
            }
        }

        // 3. Final fallback with logging
        \Log::warning('Candidate photo not found', [
            'requested_file' => $filename,
            'searched_path' => $fullPath,
            'fallback_to' => $defaultPath
        ]);

        return asset($defaultPath);
    }

}
