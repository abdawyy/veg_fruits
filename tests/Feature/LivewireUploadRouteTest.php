<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Livewire\Facades\GenerateSignedUploadUrlFacade;
use Tests\TestCase;

final class LivewireUploadRouteTest extends TestCase
{
    public function test_banner_upload_endpoint_accepts_an_image_on_the_same_origin(): void
    {
        $url = GenerateSignedUploadUrlFacade::forLocal();

        $this->assertStringStartsWith('/livewire-', $url);

        $response = $this->post($url, [
            'files' => [UploadedFile::fake()->image('banner.jpg', 800, 400)],
        ], ['Accept' => 'application/json']);

        $response->assertSuccessful();
        $response->assertJsonStructure(['paths']);
        $this->assertNotSame('', $response->json('paths.0'));
    }
}
