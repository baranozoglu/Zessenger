<?php
namespace App\Controllers;

use App\Models\File;
use PHPUnit\Runner\Exception;
use Psr\Http\Message\UploadedFileInterface;

class FileController extends Controller
{
    public function getFile($request, $response)
    {
        try {
            $data = $request->getQueryParams();
            $file = $this->query($data);
            $response->getBody()->write(json_encode($file));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response;
        }
    }

    public function fileUpload($request, $response)
    {
        try {
            $data = $request->getParsedBody();

            $uploadedFiles = $request->getUploadedFiles();
            $uploadedFile = $uploadedFiles['file'];

            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile(__DIR__ . '/../../resources/files', $uploadedFile);
                $savedFile = $this->save($data, $filename);
                $response->getBody()->write(json_encode($savedFile));
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

    private function save($data, $filename) {
        try {
            return File::updateOrCreate(['id' => $data['id']],
                [
                    'filename' => $filename,
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => $data['receiver_id'],
                ]
            );
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while inserting data to database!',500);
        }
    }

    private function query($data) {
        try {
            return File::whereRaw('id = ? and ((sender_id = ? and receiver_id = ? ) or (sender_id = ? and receiver_id = ? ))', [$data['id'], $data['sender_id'], $data['receiver_id'], $data['receiver_id'], $data['sender_id']])
                ->get();
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while getting data from database!',500);
        }
    }

}
