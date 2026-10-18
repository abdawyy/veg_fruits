<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\FileUploadController;
use Throwable;

class LivewireUploadController extends Controller
{
    public function handle(Request $request): JsonResponse|array
    {
        if (! $request->hasValidRelativeSignature()) {
            return $this->error('The upload link does not match this site. Refresh the page and try again.');
        }

        // In tests Livewire stores uploads on a fake disk that is created here.
        FileUploadConfiguration::storage();

        $files = $request->file('files');

        if (! is_array($files) || $files === []) {
            return $this->error('The image did not reach the server. It may be larger than the server upload limit.');
        }

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                $code = $file instanceof UploadedFile ? $file->getError() : UPLOAD_ERR_NO_FILE;

                return $this->error($this->phpUploadMessage($code));
            }
        }

        try {
            $paths = app(FileUploadController::class)->validateAndStore(
                array_values($files),
                FileUploadConfiguration::disk(),
            );

            return ['paths' => $paths];
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);

            return $this->error('The image could not be stored. '.$exception->getMessage());
        }
    }

    private function phpUploadMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'This image is larger than the server allows. Raise upload_max_filesize and post_max_size to at least 12M.',
            UPLOAD_ERR_PARTIAL => 'The image was only partly uploaded. Try again.',
            UPLOAD_ERR_NO_FILE => 'No image was received.',
            UPLOAD_ERR_NO_TMP_DIR => 'The server has no folder for temporary uploads. Set upload_tmp_dir in php.ini.',
            UPLOAD_ERR_CANT_WRITE => 'The server could not write the uploaded image.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension blocked this upload.',
            default => 'The image failed to upload.',
        };
    }

    private function error(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => [
                'files.0' => [$message],
            ],
        ], 422);
    }
}
