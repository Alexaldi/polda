<?php

namespace App\Services;

use App\Interfaces\ReportJourneyRepositoryInterface;
use App\Models\ReportEvidence;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
=======
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
>>>>>>> 02a3e64 (test: verify journey multi-upload success)
=======
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552

class ReportJourneyService
{
    public function __construct(
        protected ReportJourneyRepositoryInterface $repository
    ) {
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function store(array $data, array $files = [])
=======
    public function store(array $data, array $files = []): array
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552
    {
        DB::beginTransaction();

        try {
            $journey = $this->repository->store($data);

            foreach ($files as $file) {
<<<<<<< HEAD
=======
    public function store(array $data, array $files = []): array
    {
        DB::beginTransaction();

        try {
            $journey = $this->repository->store($data);

            foreach ($files as $file) {
=======
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552
                if (! $file instanceof UploadedFile) {
                    continue;
                }

<<<<<<< HEAD
>>>>>>> 02a3e64 (test: verify journey multi-upload success)
=======
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552
                $storedPath = $file->store('evidences', 'public');

                ReportEvidence::create([
                    'report_journey_id' => $journey->id,
                    'report_id' => $journey->report_id,
                    'file_url' => Storage::url($storedPath),
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }

<<<<<<< HEAD
<<<<<<< HEAD
            return $journey;
        });
=======
=======
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552
            DB::commit();

            return [
                'status' => true,
                'message' => 'Tahapan penanganan berhasil ditambahkan.',
                'data' => $journey->load('evidences'),
            ];
        } catch (Throwable $throwable) {
            DB::rollBack();

            report($throwable);

            return [
                'status' => false,
                'message' => 'Gagal menambahkan tahapan penanganan.',
            ];
        }
<<<<<<< HEAD
>>>>>>> 02a3e64 (test: verify journey multi-upload success)
=======
>>>>>>> 3d57bc4bd70e3aac3b06ee5b357fcda2414ab552
    }
}
