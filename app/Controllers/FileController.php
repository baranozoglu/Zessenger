<?php
namespace App\Controllers;

use App\Models\File;
use PHPUnit\Runner\Exception;
use Psr\Http\Message\UploadedFileInterface;

class FileController extends Controller
{
    public function fileUpload($request, $response)
    {
        try {
            $uploadedFiles = $request->getUploadedFiles();
            $uploadedFile = $uploadedFiles['file'];

            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile(__DIR__ . '/../../resources/files', $uploadedFile);
                $this->save($filename);
                $response->getBody()->write('Uploaded: ' . $filename . '<br/>');
            } else {
                throw new Exception('Could not upload file!', 400);
            }

            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: ' . $ex->getMessage()));
            return $response;
        }
    }

    private function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    private function save($filename) {
        try {
            return File::updateOrCreate(['id' => $data['id']],
                [
                    'filename' => $filename,
                ]
            );
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while inserting data to database!',500);
        }
    }


}

