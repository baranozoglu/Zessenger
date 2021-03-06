<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\CouldNotUploadFileException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Repository\FileRepository;
use PHPUnit\Runner\Exception;
use Psr\Http\Message\UploadedFileInterface;

global $fileRepository;
$fileRepository = new FileRepository();

class FileController extends Controller
{
    public function getFile($request, $response)
    {
        try {
            $loggedUser = $this->authUser;
            $data = $request->getQueryParams();
            $data['user_id'] = $loggedUser['id'];
            $file = $this->query($data);
            $response->getBody()->write(json_encode($file));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
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
        global $fileRepository;
        try {
            return $fileRepository->save($data,$filename);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    private function query($data) {
        global $fileRepository;
        try {
            return $fileRepository->getFileByUserId($data['id'], $data['user_id'], $data['messaged_user_id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }

}

