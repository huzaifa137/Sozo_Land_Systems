<?php

namespace Tests\Support;

/**
 * Upload handlers write into the real public/ and storage/ folders. Snapshot them before each
 * test and remove anything a test created so runs never leave files behind.
 */
trait CleansUploads
{
    private array $uploadSnapshot = [];

    private function uploadDirs(): array
    {
        return [
            public_path('public/national_id'),
            public_path('profile_pic'),
            public_path('storage/receipts'),
            public_path('storage/pdf_receipts'),
            storage_path('app/public/receipts'),
            storage_path('app/public/pdf_receipts'),
            storage_path('app/public/agreements'),
            public_path('agreements'),
        ];
    }

    protected function snapshotUploads(): void
    {
        foreach ($this->uploadDirs() as $dir) {
            $this->uploadSnapshot[$dir] = is_dir($dir) ? array_diff(scandir($dir), ['.', '..']) : null;
        }
    }

    protected function cleanUploads(): void
    {
        foreach ($this->uploadSnapshot as $dir => $before) {
            if (!is_dir($dir)) {
                continue;
            }
            foreach (array_diff(scandir($dir), ['.', '..']) as $f) {
                if ($before === null || !in_array($f, $before, true)) {
                    @unlink($dir . '/' . $f);
                }
            }
            if ($before === null) {
                @rmdir($dir);
            }
        }
    }

    protected function filesCreatedIn(string $dir): array
    {
        $before = $this->uploadSnapshot[$dir] ?? null;
        $now = is_dir($dir) ? array_diff(scandir($dir), ['.', '..']) : [];

        return array_values(array_diff($now, $before ?? []));
    }
}
