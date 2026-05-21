<?php

namespace App\Support\Imports;

use App\Models\ImportAuditLog;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

final class SpreadsheetImportRunner
{
    /**
     * @param  class-string  $importClass  Must implement row validation via static rules() and applyRow()
     * @return array{ok: int, failed: int, errors: list<array{row: int, messages: list<string>}>}
     */
    public function dryRun(string $importClass, string $path): array
    {
        return $this->process($importClass, $path, persist: false);
    }

    /**
     * @param  class-string  $importClass
     */
    public function commit(string $importClass, string $path, ?int $userId, ?string $filename = null): ImportAuditLog
    {
        $result = $this->process($importClass, $path, persist: true);

        return ImportAuditLog::query()->create([
            'import_type' => class_basename($importClass),
            'filename' => $filename,
            'dry_run' => false,
            'rows_total' => $result['ok'] + $result['failed'],
            'rows_ok' => $result['ok'],
            'rows_failed' => $result['failed'],
            'row_errors' => $result['errors'],
            'user_id' => $userId,
        ]);
    }

    public function logDryRun(string $importClass, string $path, array $result, ?int $userId, ?string $filename = null): ImportAuditLog
    {
        return ImportAuditLog::query()->create([
            'import_type' => class_basename($importClass),
            'filename' => $filename,
            'dry_run' => true,
            'rows_total' => $result['ok'] + $result['failed'],
            'rows_ok' => $result['ok'],
            'rows_failed' => $result['failed'],
            'row_errors' => $result['errors'],
            'user_id' => $userId,
        ]);
    }

    /**
     * @param  class-string  $importClass
     * @return array{ok: int, failed: int, errors: list<array{row: int, messages: list<string>}>}
     */
    private function process(string $importClass, string $path, bool $persist): array
    {
        if (! method_exists($importClass, 'importRules') || ! method_exists($importClass, 'applyRow')) {
            throw new \InvalidArgumentException("{$importClass} must define importRules() and applyRow().");
        }

        $sheets = Excel::toArray(new $importClass, $path);
        $rows = $sheets[0] ?? [];
        if ($rows === []) {
            return ['ok' => 0, 'failed' => 0, 'errors' => []];
        }

        $headers = array_map(fn ($h) => strtolower(trim((string) $h)), array_shift($rows));
        $rules = $importClass::importRules();
        $ok = 0;
        $failed = 0;
        $errors = [];

        foreach ($rows as $index => $rawRow) {
            $rowNumber = $index + 2;
            $assoc = [];
            foreach ($headers as $i => $key) {
                if ($key !== '') {
                    $assoc[$key] = $rawRow[$i] ?? null;
                }
            }

            if ($this->rowIsEmpty($assoc)) {
                continue;
            }

            $validator = Validator::make($assoc, $rules);
            if ($validator->fails()) {
                $failed++;
                $errors[] = [
                    'row' => $rowNumber,
                    'messages' => $validator->errors()->all(),
                ];

                continue;
            }

            try {
                if ($persist) {
                    $importClass::applyRow($assoc);
                }
                $ok++;
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = [
                    'row' => $rowNumber,
                    'messages' => [$e->getMessage()],
                ];
            }
        }

        return ['ok' => $ok, 'failed' => $failed, 'errors' => $errors];
    }

    /** @param  array<string, mixed>  $row */
    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
