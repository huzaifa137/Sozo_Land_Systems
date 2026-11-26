<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estate extends Model
{
    use HasFactory;

    protected $fillable = ['estate_name','location','number_of_plots'];

    /**
     * Folders an uploaded estate file may live in, depending on how the site is served.
     */
    public static function documentFolders(): array
    {
        return [
            public_path('estate_pdf'),
            public_path('public/estate_pdf'),
            base_path('estate_pdf'),
        ];
    }

    /** Absolute path of this estate's uploaded PDF or image, or null if there is none on disk. */
    public function documentPath(): ?string
    {
        $name = basename((string) $this->estate_pdf);
        if ($name === '' || $name === '.' || $name === '..') {
            return null;
        }
        foreach (self::documentFolders() as $folder) {
            $path = $folder . DIRECTORY_SEPARATOR . $name;
            if (is_file($path)) {
                return $path;
            }
        }
        return null;
    }

    /** 'pdf', 'image' or null (no usable file). */
    public function documentKind(): ?string
    {
        $path = $this->documentPath();
        if (!$path) {
            return null;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            return 'pdf';
        }
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) ? 'image' : null;
    }

    /** Public URL for the file. The version changes when a new file is uploaded, so browsers refresh it. */
    public function documentUrl(): ?string
    {
        $path = $this->documentPath();
        return $path ? route('estate-file', ['id' => $this->id, 'v' => filemtime($path)]) : null;
    }

    /** Same file, but served as a download instead of being shown in the browser. */
    public function downloadUrl(): ?string
    {
        $path = $this->documentPath();
        return $path ? route('estate-file', ['id' => $this->id, 'download' => 1, 'v' => filemtime($path)]) : null;
    }
}
