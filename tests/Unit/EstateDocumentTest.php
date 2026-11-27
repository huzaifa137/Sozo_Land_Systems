<?php

namespace Tests\Unit;

use App\Models\Estate;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class EstateDocumentTest extends TestCase
{
    private string $dir;
    private bool $dirExisted;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = public_path('estate_pdf');
        $this->dirExisted = is_dir($this->dir);
        File::ensureDirectoryExists($this->dir);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '/unit_*') ?: [] as $f) {
            @unlink($f);
        }
        if (!$this->dirExisted) {
            @rmdir($this->dir);
        }
        parent::tearDown();
    }

    private function make(?string $file): Estate
    {
        $e = new Estate();
        $e->estate_pdf = $file;
        return $e;
    }

    public function test_pdf_on_disk_is_found_and_classified_as_pdf(): void
    {
        file_put_contents($this->dir . '/unit_plan.pdf', '%PDF-1.4');
        $e = $this->make('unit_plan.pdf');

        $this->assertSame(realpath($this->dir . '/unit_plan.pdf'), realpath($e->documentPath()));
        $this->assertSame('pdf', $e->documentKind());
    }

    public function test_image_extensions_are_classified_as_image(): void
    {
        foreach (['png', 'jpg', 'jpeg', 'webp', 'gif'] as $ext) {
            file_put_contents($this->dir . "/unit_pic.$ext", 'x');
            $this->assertSame('image', $this->make("unit_pic.$ext")->documentKind(), $ext);
        }
    }

    public function test_unsupported_extension_has_no_kind(): void
    {
        file_put_contents($this->dir . '/unit_run.php', '<?php echo 1;');
        $this->assertNull($this->make('unit_run.php')->documentKind());
    }

    public function test_missing_empty_or_null_file_gives_no_path(): void
    {
        $this->assertNull($this->make('unit_nope.pdf')->documentPath());
        $this->assertNull($this->make('')->documentPath());
        $this->assertNull($this->make(null)->documentPath());
        $this->assertNull($this->make('..')->documentPath());
    }

    public function test_path_traversal_is_neutralised_by_basename(): void
    {
        // A traversal attempt must never resolve outside the estate folders.
        $e = $this->make('../../.env');
        $path = $e->documentPath();

        $this->assertTrue($path === null || str_starts_with(realpath($path), realpath(public_path())));
        $this->assertNotSame(realpath(base_path('.env')), $path ? realpath($path) : null);
    }

    public function test_urls_are_null_without_a_file_and_versioned_with_one(): void
    {
        $this->assertNull($this->make('unit_nope.pdf')->documentUrl());

        file_put_contents($this->dir . '/unit_v.pdf', '%PDF');
        $e = $this->make('unit_v.pdf');
        $e->id = 5;

        $this->assertStringContainsString('estate-file/5', $e->documentUrl());
        $this->assertStringContainsString('v=', $e->documentUrl());
        $this->assertStringContainsString('download=1', $e->downloadUrl());
    }
}
