<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\CouldNotUploadFileException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Models\File;
use PHPUnit\Runner\Exception;
use Psr\Http\Message\UploadedFileInterface;

class FileController extends Controller
{
    public function getFile($request, $response)
    {
        try {
            $loggedUser = Auth::user();
            $data = $request->getQueryParams();
            $data['user_id'] = $loggedUser['id'];
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
                throw new CouldNotUploadFileException();
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
            throw new InsertDatabaseException();
        }
    }

    private function query($data) {
        try {
            return File::whereRaw('id = ? and ((sender_id = ? and receiver_id = ? ) or (sender_id = ? and receiver_id = ? ))', [$data['id'], $data['user_id'], $data['messaged_user_id'], $data['messaged_user_id'], $data['user_id']])
                ->get();
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }

}

